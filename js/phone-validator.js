var debug = false;

// Build array with all URL Params for later use
var params = {};
location.search
    .substr(1)
    .split("&")
    .forEach(function (item) {
        params[item.split("=")[0]] = item.split("=")[1];
    });

// Debug URL Param
if (decodeURIComponent(params["d"]) == "1") {
    debug = true;
}

if (debug) {
    console.log("💚 Debug Flag Enabled");
}
// We will use this later to pass into function
let country = "NZ";

function validationErrors(element, type, customErrorMessage) {
    if (type == "phoneLength") {
        if (customErrorMessage == "ok") {
            phoneOK = true;
            $("input[class='gform_button button']").attr("disabled", false);
            $("input[name='input_22']").css("border-color", "#6f7376");
            $("div[class='gfield_description validation_message gfield_validation_message phone']")
                .text("")
                .hide()
            console.log("green");
        } else {
            element.attr("data-validation-message", customErrorMessage);
            phoneOK = false;
            $("input[class='gform_button button']").attr("disabled", true);
            $("input[name='input_22']").css("border-color", "red");
            $("div[class='gfield_description validation_message gfield_validation_message phone']")
                .text(customErrorMessage)
                .show()
            console.log("red");

        }
    }
}

const cleanData = function (value) {
    let cleanValue = value.replace("&", "and").replace(/[!@#$%^*()_+=<>]/g, "");
    return cleanValue;
};

// On submit Phone Validation function
const formatPhoneNumber = function (input) {
    const phone = {
        phone_number: "",
        transformed_phone: "",
        mobile: "",
        landline: "",
    };
    try {
        const phoneNumber = new libphonenumber.parsePhoneNumber(input, country);
        if (typeof phoneNumber == "undefined") {
            if (debug) {
                console.log("phoneNumber is undefined");
            }
            return phone;
        } else {
            if (phoneNumber.isValid() && phoneNumber.country === "NZ") {
                const numberType = phoneNumber.getType(); //Used to Determine if number is mobile or landline
                const cleanNumber = cleanData(phoneNumber.number); //Strip + and other symbols
                switch (numberType) {
                    case "MOBILE":
                        phone.phone_number = phoneNumber.number; //User typed number
                        phone.mobile = phone.transformed_phone = cleanNumber; //Set both Mobile & Transformed_phone
                        break;
                    case "FIXED_LINE":
                    case "UAN":
                        phone.phone_number = phoneNumber.number; //User typed number
                        phone.landline = phone.transformed_phone = cleanNumber; //Set both landline & transformed_phone
                        break;
                    default:
                        if (debug) {
                            console.log(`No match for: ${phoneNumber.number}`);
                        }
                        break;
                }

                //DEBUG
                if (debug) {
                    console.log(`phone_number = ${phone.phone_number}`);
                    console.log(`transformed_phone = ${phone.transformed_phone}`);
                    console.log(`phone.mobile = ${phone.mobile}`);
                    console.log(`phone.landline = ${phone.landline}`);
                    console.log(phoneNumber.country);
                    console.log(phoneNumber.isValid());
                }

                return phone; //Returns phone Object
            } else {
                // Store user input phone even if not a NZ number
                phone.phone_number = phoneNumber.number;
                if (debug) {
                    console.log("Phone Number not a valid NZ Number");
                }
                return phone;
            }
        }
    } catch (error) {
        if (debug) {
            console.log(`Debug: Phone Function Error: ${error}`);
        }
        return phone;
    }
};

// Add div bellow phone input for Alert message
$("input[name=input_22]").after('<div class="gfield_description validation_message gfield_validation_message phone" style="display:none"></div>');
// AsYouType PhoneNumber validation
const inputPhoneValidator = function (input) {
    // let inputNumber = $(this).val(); // get input value
    let asYouType = new libphonenumber.AsYouType(country); //country var as defined by selector
    asYouType.input(input);
    if (debug) {
        console.log(`inputnumber = ${input}`);
    }
    if (input != "" && asYouType.isValid() == false) {
        validationErrors(
            $("input[name=input_22]"),
            "phoneLength",
            "This is not a valid " + country + " number."
        );
    } else {
        if (debug) {
            console.log("Phone Ok");
        }
        validationErrors($("input[name=input_22]"), "phoneLength", "ok");
    }

    if (debug) {
        console.log(`Selected Country = ${asYouType.country}`);
        console.log(`Valid Phone = ${asYouType.isValid()}`);
    }
};

// On Input field change run asYouType phone validator
//document
//.getElementsByName("input_22")
document
    .getElementsByName("input_22")[0]
    .addEventListener("input", function (event) {
        let inputNumber = $(this).val(); // get input value
        if (debug) {
            console.log(inputNumber);
        }
        inputPhoneValidator(inputNumber);
    });

// Get Country Dropdown slection and update var for use in asYouType function
document
    .getElementsByName("input_21")[0]
    .addEventListener("input", function (event) {
        country = event.target.value;
        if (debug) {
            console.log(event.target.value);
        }
        let inputNumber = document
            .getElementsByName("input_21")[0].value; // get input value
        inputPhoneValidator(inputNumber); // run asyoutype input validator to update country check
    });

document.addEventListener("DOMContentLoaded", (event) => {
    // Get all Supported Countries
    const countryCodes = new libphonenumber.getCountries();
    // Get DropDown Element
    let countrySelectEl = document.getElementsByName("input_21")[0];

    // Populate Dropdown with all supported Country Codes
    for (var i = 0; i < countryCodes.length; i++) {
        var opt = countryCodes[i];
        var el = document.createElement("option");
        el.textContent = opt;
        el.value = opt;
        countrySelectEl.appendChild(el);
    }
    if (debug) {
        console.log(countryCodes);
    }
});

document.addEventListener("submit", ()=>{
    var phoneInput = document.getElementsByName("input_22")[0].value;
    var formattedPhone = formatPhoneNumber(phoneInput);
    document.getElementsByName("input_42")[0].value = formattedPhone.mobile;
    document.getElementsByName("input_43")[0].value = formattedPhone.landline;
    document.getElementsByName("input_44")[0].value = formattedPhone.transformed_phone;
});
