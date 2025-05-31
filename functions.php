<?php
/* added child plugins in composer-local.json */


// add_action( 'wp_head', 'gpnz_enqueue_script', 11 );
// function gpnz_enqueue_script() {
// 	global $post;
	
// 	wp_enqueue_script( 'phone-format-js', get_stylesheet_directory_uri().'/phoneFormat.js', false );
// 	wp_enqueue_script( 'jquery-inputmask-js', get_stylesheet_directory_uri().'/jquery.inputmask.bundle.min.js', false );
	
	
// }

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99);
function enqueue_child_styles() {
	$css_creation = filectime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
    wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri().'/js/custom.js', [], '1.0.1',  array(
        'strategy' => 'defer',
    ));
}

add_action( 'gform_post_payment_completed', function ( $entry, $action ) {
     
    // Do something here.
 	echo "gform_post_payment_completed entry<br/>";
	echo "<pre>";
	print_r($entry);
	echo "</pre>";


	echo "========================== action<br/>";
	echo "<pre>";
	print_r($action);
	echo "</pre>";

	// get details

	// add details
 
}, 10, 2 );




add_filter( 'gform_stripe_customer_id', function ( $customer_id, $feed, $entry, $form ) {
 
    $feed_name = rgars( $feed, 'meta/feedName' );
 
    gf_stripe()->log_debug( 'gform_stripe_customer_id: Running for Feed: ' . $feed_name );
 
    // Define the names of the feeds you want this code to run.
    $feed_names = array( 'Donation' );
 
    // Abort if the entry was processed by a different feed or it's not a product and services feed.
    if ( rgars( $feed, 'meta/transactionType' ) !== 'product' && ! in_array( $feed_name, $feed_names ) ) {
        return $customer_id;
    }
 
    if ( empty( $customer_id ) ) {
        $response = gf_stripe()->get_stripe_js_response();
        if ( ! empty( $response->id ) && substr( $response->id, 0, 3 ) === 'pi_' ) {
            try {
                $intent = \Stripe\PaymentIntent::retrieve( $response->id );
                if ( ! empty( $intent->customer ) ) {
                    gf_stripe()->log_debug( 'gform_stripe_customer_id: PaymentIntent already has customer; ' . print_r( $intent->customer, true ) );
 
                    return is_object( $intent->customer ) ? $intent->customer->id : $intent->customer;
                }
            } catch ( \Exception $e ) {
                gf_stripe()->log_debug( 'gform_stripe_customer_id: unable to get PaymentIntent; ' . $e->getMessage() );
            }
        }
 
        $customer_params = array();
 
        // $email_field = rgars( $feed, 'meta/receipt_field' );
        // if ( ! empty( $email_field ) && strtolower( $email_field ) !== 'do not send receipt' ) {
        //     $customer_params['email'] = gf_stripe()->get_field_value( $form, $entry, $email_field );
        // }
 
        // // Optional - Customer name using a Name field that has id 8. Update the id number or remove the following line.
        // $customer_params['name'] = rgar( $entry, '8.3' ) . ' ' . rgar( $entry, '8.6' );
        // gf_stripe()->log_debug( __METHOD__ . '(): Name: ' . $customer_params['name'] );
 
        // // Optional - Customer phone using a Phone field that has id 5. Update the id number or remove the following line.
        // $customer_params['phone'] = rgar( $entry, '5' );
        // gf_stripe()->log_debug( __METHOD__ . '(): Phone: ' . $customer_params['phone'] );
 
        $customer    = gf_stripe()->create_customer( $customer_params, $feed, $entry, $form );
        $customer_id = $customer->id;
        gf_stripe()->log_debug( 'gform_stripe_customer_id: Returning Customer ID: ' . $customer_id );
    }
 
    return $customer_id;
 
}, 10, 4 );


// function add_card_details($order_id) {
//     $order = wc_get_order($order_id);

//     if (!$order) {
//         error_log('Failed to get order');
//         return;
//     }

//     $payment_gateway = wc_get_payment_gateway_by_order($order);

//     if (!$payment_gateway || $payment_gateway->id !== 'stripe') {
//         return;
//     }

//     $card_details = get_card_details($order_id);

//     if ($card_details !== false) {
//         $order->update_meta_data('_card_details', $card_details);
//         $order->save();
//     } else {
//         error_log('Failed to get card details from Stripe');
//     }
// }

// function get_card_details($order_id) {
//     $order = wc_get_order($order_id);
//     $payment_intent_id = $order->get_meta('_stripe_intent_id');

//     if (!$payment_intent_id) {
//         return false;
//     }
//     \Stripe\Stripe::setApiKey(SK_LIVE_KEY);
//     try {
//         // Retrieve the PaymentIntent from Stripe
//         $payment_intent = \Stripe\PaymentIntent::retrieve($payment_intent_id);

//         // Check if the PaymentIntent has a PaymentMethod
//         if ($payment_intent->payment_method) {
//             // Retrieve the PaymentMethod details
//             $payment_method = \Stripe\PaymentMethod::retrieve($payment_intent->payment_method);

//             // Check if the PaymentMethod has card details
//             if ($payment_method->card) {
//                 $card_details = [
//                     'last_4_digits' => $payment_method->card->last4,
//                     'expiry_date' => $payment_method->card->exp_month . '/' . $payment_method->card->exp_year,
//                     'brand' => $payment_method->card->brand,
//                 ];

//                 return $card_details;
//             }
//         }

//         return false;
//     } catch (\Stripe\Exception\ApiErrorException $e) {
//         error_log('Stripe API Error: ' . $e->getMessage());
//         return false;
//     }
// }

// add_action('woocommerce_payment_complete', 'add_card_details');


add_action('admin_bar_menu', function ($wp_admin_bar) {
    if (!defined('WP_STATELESS_MEDIA_ROOT_DIR')) {
        return;
    }

    $timestamp = current_time('timestamp');
    $replacements = [
        '%date_year%'   => date('Y', $timestamp),
        '%date_month%'  => date('m', $timestamp),
        '%date_day%'    => date('d', $timestamp),
        '%timestamp%'   => $timestamp,
        '%username%'    => is_user_logged_in() ? wp_get_current_user()->user_login : 'anonymous',
        '%user_id%'     => get_current_user_id() ?: 0,
    ];

    $resolved_value = strtr(WP_STATELESS_MEDIA_ROOT_DIR, $replacements);
    error_log("[Stateless] MEDIA_ROOT_DIR raw:     " . WP_STATELESS_MEDIA_ROOT_DIR);
    error_log("[Stateless] MEDIA_ROOT_DIR resolved: " . $resolved_value);

    $wp_admin_bar->add_node([
        'id'    => 'stateless-root-dir',
        'title' => 'Stateless Path: ' . $resolved_value,
        'href'  => false,
    ]);
}, 100);
