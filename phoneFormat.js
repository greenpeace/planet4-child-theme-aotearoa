jQuery(document).ready(function(){
	(function($) {
			var countrycode = 64;
			var prefix = '';
			var format = '';
			var phone_raw_input = $("#en__field_supporter_NOT_TAGGED_4");
			$('.en__field__input--checkbox').attr("checked", "checked");
			
			/* validation messages */
			function phoneLength(val){
			    console.log("val.length ", val.length);
			    if(val.length<9){
				    console.log("less than 9 digits ");
				    $(phone_raw_input).addClass('warning').removeClass('valid');
			    } else {
				    console.log("more than 8 digits ");
				    $(phone_raw_input).addClass('valid').removeClass('warning');
				    return true;
			    }
			}
		    
		    /* add input mask */
		    $(phone_raw_input).inputmask({"mask": "(999) 999-9999999", "placeholder": " "});
		    
		    $('#en__field_supporter_NOT_TAGGED_4').keyup(function(e){
			    var numberinput = this.value;
			    numberinput = numberinput.replace(/\D/g,'');
			    console.log("phoneLength ", phoneLength(numberinput) );
			});
		    
		    /* process input */
		    $(phone_raw_input).keyup(function(){
				var current = phone_raw_input.val();
				console.log("current ", current);
				current = current.replace(/\D/g,''); // replace all non numeric characters
				console.log("current strip non numeric chars: ", current);
				
				var n1 = current.substr(0, 1);
				
				if(n1!='0'){
			        console.log("1st char not zero ");
			        $(phone_raw_input).val('0'+current); // add leading 0
			        current = '0'+current; // add leading 0
			    }
	
				var n1 = current.substr(0, 1);
				var n2 = current.substr(1, 1);
				var n3 = current.substr(2, 1);
				console.log("1st char: ", n1);
				console.log("2nd char: ", n2);
				console.log("3rd char: ", n3);
				
				var digits = 0;
				
				var i = 0; // calculate number of digits
			    while ( i < current.length) {
			        var step = current.substring(i,i+1);
			        if ( isNaN(step) || step==" "){
			            // do nothing
			        } else {
			            digits++;
			        }
			      i++;
			    }
			    console.log("digits: ", digits);
			    
			    /* if starts with 64 NZ prefix (from autofill) then remove */
			    if(n1==6){
				    if( n2==4){
			            console.log("64 found ", current);
			            current = current.substring(3); // remove 1st 3 characters
			            console.log("remove 1st 3 characters ", current);
			            n2 = current.charAt(0);
						console.log("n2 ", n2);
					}
			    }
			    
			/* process based on type (mobile or landline) */
		    switch(n2){
		        case '2': /* is mobile */
		            console.log("mobile ");
		            $(phone_raw_input).inputmask({"mask": "(999) 999-9999999", "placeholder": " "});
		            if ( digits > 8){
			            // is valid min length
		                if( digits < 12 ) {
		                    // validationErrors("phoneLength","ok");
		                    var format = current.replace(/\D/g,''); // replace all non numeric characters
		                    var prefix = countrycode+format.substring(1); // remove leading 0
		                    console.log("prefix: ", prefix);
		                } else if ( digits > 11){
		                    $(this).val($(this).val().substr(0, 16));
		                    
		                    var format = current.replace(/\D/g,''); // replace all non numeric characters
		                    var prefix = countrycode+format.substring(1); // remove leading 0
		                    
		                    console.log("digits > 11 prefix: ", prefix);
		                } else {
			                console.log("else prefix: ", prefix);
		                    // validationErrors("phoneLength","Must be 6 to 9 digits after prefix");
		                }
		            }
		        break;
		        default: /* is landline */
		        	console.log("landline ");
		        	/* update input prefix mask to 2 digits */
		            $(phone_raw_input).inputmask({"mask": "(99) 999-9999999", "placeholder": " "});
		            if ( digits > 8 ) {
			            // is valid min length
		                $(this).val($(this).val().substr(0, 13)); // disallow more characters
		                // validationErrors("phoneLength","ok");
		                    var format = $(this).val().substr(0, 13).replace(/\D/g,''); // replace all non numeric characters
		                    var prefix = countrycode+format.substring(1); // remove leading 0
		                    console.log("prefix: ", prefix);
		            } else if(digits > 1){
		                console.log("landline ");
		                // validationErrors("phoneLength","Must be exactly 7 digits after prefix");
		            } else {
		                console.log("exception ");
		            }
			    } // end switch
			    
			    
			    console.log("prefix ", prefix);
			    $('#en__field_supporter_phoneNumber').val(prefix);
		    }); // end process input
	})( jQuery );
});