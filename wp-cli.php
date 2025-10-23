<?php

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

class WP_Mediafiles_Repair_Command {

    /**
     * Repairs incomplete WP-Stateless (sm_cloud) metadata.
     *
     * ## OPTIONS
     *
     * [--dry-run]
     * : Only show what would be changed; do not update the database.
     *
     * ## EXAMPLES
     *
     *     wp mediafiles repair
     *     wp mediafiles repair --dry-run
     */
    public function repair($args, $assoc_args) {
        $dry_run = isset($assoc_args['dry-run']);

        $stateless = function_exists('ud_get_stateless_media') ? ud_get_stateless_media() : null;
        if (!$stateless) {
            WP_CLI::error('WP-Stateless plugin not detected or not active.');
        }

        $bucket = $stateless->get('sm.bucket');
        WP_CLI::log('Using bucket: ' . $bucket);
        $baseurl = untrailingslashit($stateless->get_gs_host());
        WP_CLI::log('Using base URL: ' . $baseurl);

        $fixed = 0;
        $not_needed = 0;
        $paged = 1;
        $per_page = 30;

        do {
            $query = new WP_Query([
                'post_type' => 'attachment',
                'posts_per_page' => $per_page,
                'paged' => $paged,
                'post_status' => 'any',
                'fields' => 'ids',
            ]);

            if (!$query->have_posts()) {
                break;
            }

            foreach ($query->posts as $id) {
                $sm_cloud = get_post_meta( $id, 'sm_cloud', true );

                $file_path = get_post_meta($id, '_wp_attached_file', true);

                if (empty($file_path)) {
                    WP_CLI::log('Attachment #' .$id . ' has no _wp_attached_file meta. Skipping.');
                    continue;
                }

                $needs_fix =
                    empty($sm_cloud) ||
                    empty($sm_cloud['name']) ||
                    empty($sm_cloud['bucket']) ||
                    empty($sm_cloud['fileLink']);

                if (!$needs_fix) {
                    WP_CLI::log('Attachment #' .$id . ' is already complete. Skipping.');
                    $not_needed++;
                    continue;
                }

                $file_path = ltrim($file_path, '/');

                // Check if it already includes year/month structure
                if (!preg_match('#^\d{4}/\d{2}/#', $file_path)) {
                    // No year/month structure → reconstruct from attachment post_date.
                    $post = get_post($id);
                    if ($post && !empty($post->post_date)) {
                        $year = date('Y', strtotime($post->post_date));
                        $month = date('m', strtotime($post->post_date));
                        $file_path = "$year/$month/$file_path";
                    }
                }

                $filelink = trailingslashit($baseurl) . $file_path;

                if (empty($sm_cloud)) {
                    $sm_cloud = [];
                }

                $sm_cloud['name'] = $file_path;
                $sm_cloud['bucket'] = $bucket;
                $sm_cloud['fileLink'] = $filelink;

                if ($dry_run) {
                    WP_CLI::log('Would fix #' .$id . ' → ' .$filelink);
                } else {
                    update_post_meta($id, 'sm_cloud', $sm_cloud);
                    WP_CLI::log('✅ Fixed attachment #' . $id . ' → ' . $filelink);
                }
                $fixed++;
            }
            $paged++;
            wp_reset_postdata();
        } while ($query->found_posts > ($paged - 1) * $per_page);

        if ($dry_run) {
            WP_CLI::success('Dry run finished. No changes made. ' .
                            $fixed . ' attachment(s) would be updated. ' .
                            $not_needed . ' attachment(s) would not need changes.');
        } else {
            WP_CLI::success('Repair complete. ' .
                            $fixed . ' attachment(s) updated. ' .
                            $not_needed . ' attachment(s) did not need changes.');
        }
    }
}

WP_CLI::add_command('mediafiles', 'WP_Mediafiles_Repair_Command');
