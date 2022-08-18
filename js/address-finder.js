(function() {
    var widget, initAddressFinder = function() {
        widget = new AddressFinder.Widget(
            document.getElementsByName('input_16')[0],
            'C63FLUJN48PT9AVY7BDK',
            'NZ', {
            "address_params": {},
            "empty_content": "No addresses were found. This could be a new address, or you may need to check the spelling. Learn more", "manual_style": true
        }
        );

    widget.on('address:select', function(fullAddress, metaData) {
        console.log(metaData);
    document.getElementsByName('input_16')[0].value = fullAddress;
    document.getElementsByName('input_26')[0].value = metaData.rural;
    document.getElementsByName('input_30')[0].value = metaData.postcode;
    console.log("mdru", metaData.rural);
    if (metaData.rural) {
        console.log("metaData.postal_line_2", metaData.postal_line_2);
    var rd = metaData.postal_line_2;
    console.log("rd", rd);
    var rdval = rd.substring(0, 2);
    console.log("rdval", rdval);
    if (rdval === "RD") {
        document.getElementsByName("input_26")[0].value = metaData.postal_line_2 // RD
    }
      }
    /***************************************
       Unit/Flat Apartment process by Francis  
      ****************************************/

    // Alpha
    var annoying_alpha = metaData.alpha != null ? metaData.alpha : "";
    console.log("annoying_alpha", annoying_alpha);
    // Address in apartment or high rise case
    // eg)
    // Suite 1 Level 3, 53 Cuba Street, Te Aro, Wellington 6011
    // Suite C Floor 1, 2 Marine Parade, Mount Maunganui 3116
    //
    var floor_with_unit =
    metaData.unit_type != null && metaData.floor != null
    ? metaData.unit_type +
    " " +
    metaData.unit_identifier +
    " " +
    metaData.floor +
    ", " +
    metaData.number +
    annoying_alpha +
    " " +
    metaData.street
    : "";

    console.log("floor_with_unit ", floor_with_unit);

    // Same as above without unit (if whole floor is just one unit)
    // eg)
    // Floor 2, 124 Vincent Street, Auckland Central, Auckland 1010
    //
    var floor_only =
    metaData.floor != null
    ? metaData.floor +
    ", " +
    metaData.number +
    annoying_alpha +
    " " +
    metaData.street
    : "";

    console.log("floor_only ", floor_only);

    // Address with unit_type case
    // Flat/Unit/Suite/Room/Villa/Apartment all gets dropped and only show unit_identifier with /
    //
    var unit_only =
    metaData.unit_type != null
    ? metaData.unit_identifier +
    "/" +
    metaData.number +
    annoying_alpha +
    " " +
    metaData.street
    : "";

    console.log("unit_only ", unit_only);

    // Address with box_type case
    // CMB => no suburb (box_type number, city postcode)
    // Counter Delivery => (box_type, lobby_name, city postcode)
    // PO Box => (box_type number, lobby_name, city postcode)
    // Private Bag => (box_type number, lobby_name, city postcode)
    //
    var box_numbers =
    metaData.box_type != null
    ? metaData.box_type + " " + metaData.number
    : "";

    console.log("box_numbers ", box_numbers);

    // Simple address
    //
    var simple_address =
    metaData.number != null
    ? metaData.number + annoying_alpha + " " + metaData.street
    : "";

    console.log("simple_address ", simple_address);

    // Address assigning logic
    //

    if (metaData.floor != null) {
        document.getElementsByName("input_27")[0].value = metaData.unit_type != null ? floor_with_unit : floor_only;
      } else {
        if (metaData.unit_type != null) {
        document.getElementsByName("input_27")[0].value = unit_only;
        } else if (metaData.box_type != null) {
        // all box_type cases
        document.getElementsByName("input_27")[0].value = box_numbers;
        } else {
        // simple address case
        document.getElementsByName("input_27")[0].value = simple_address;
        }
      }
    if (metaData.rd_number != null) {
        document.getElementsByName("input_26")[0].value = "RD " + metaData.rd_number;
      } else {
        document.getElementsByName("input_26")[0].value = "";
      }

    var normal_suburb = metaData.suburb != null ? metaData.suburb : "";
    var postal_suburb =
    metaData.post_suburb != null ? metaData.post_suburb : normal_suburb;
    var correct_suburb = postal_suburb; // all non-box addresses
    if (metaData.box_type != null) {
        correct_suburb =
        metaData.lobby_name != null && metaData.lobby_name != metaData.city
            ? metaData.lobby_name
            : "";
      }
    document.getElementsByName("input_28")[0].value = correct_suburb;
    var towncity =
    metaData.mailtown != null ? metaData.mailtown : metaData.city;
    document.getElementsByName("input_31")[0].value = towncity;
        });
    };

    function downloadAddressFinder() {
        var script = document.createElement('script');
    script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
    script.async = true;
    script.onload = initAddressFinder;
    document.body.appendChild(script);
    };

    document.addEventListener('DOMContentLoaded', downloadAddressFinder);
})();