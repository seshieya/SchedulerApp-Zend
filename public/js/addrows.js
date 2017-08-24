$(function(){

	var rowNum = 1;
	var rowTotal = 1;

	function initializeRow() {
		var rowEntry = '<div class="sc-row-entry">\
              <!-- Strata buildings only: Optional Unit # or area such as common hallway -->\
              <div class="col-md-12">\
                <label for="sc-row' + rowNum + '-unitnum">Unit Number or Area: <br />\
                <span class="sc-unit-label">(Strata/Commercial Buildings Only)</span></label>\
                <input  type="text"\
                        name="sc-row' + rowNum + '-unitnum"\
                        id="sc-row' + rowNum + '-unitnum"\
                        class="sc-rows-unitnum"\
                        placeholder="" />\
              </div>\
              <div class="col-md-4">\
                <label for="sc-row' + rowNum + '-type">Type of Work:</label>\
                <input  type="text"\
                        name="sc-row' + rowNum + '-type"\
                        id="sc-row' + rowNum + '-type"\
                        class="sc-rows-type"\
                        placeholder="" />\
              </div>\
              <div class="col-md-4">\
                <label for="sc-row' + rowNum + '-trade">Trade Name:</label>\
                <input  type="text"\
                        name="sc-row' + rowNum + '-trade"\
                        id="sc-row' + rowNum + '-trade"\
                        class="sc-rows-trade"\
                        placeholder="" />\
              </div>\
              <div class="col-md-4">\
                <label for="sc-row' + rowNum + '-trade-email">Trade Email:</label>\
                <input  type="text"\
                        name="sc-row' + rowNum + '-trade-email"\
                        id="sc-row' + rowNum + '-trade-email"\
                        class="sc-rows-trade-email"\
                        placeholder="" />\
              </div>\
              <div class="col-md-4">\
                <label for="sc-row' + rowNum + '-days">Number of Days Needed:</label>\
                <input  type="text"\
                        name="sc-row' + rowNum + '-days"\
                        id="sc-row' + rowNum + '-days"\
                        class="sc-rows-days"\
                        placeholder="" />\
              </div>\
              <div class="col-md-8">\
                <label for="sc-row' + rowNum + '-comments">Comments:</label>\
                <textarea name="sc-row' + rowNum + '-comments"\
                          id="sc-row' + rowNum + '-comments"\
                          class="sc-rows-comments"></textarea>\
              </div>\
            </div>';


      console.log("row number: " + rowNum);
      console.log("row total: " + rowTotal);

      rowNum++;
      rowTotal += 1;

      

      return rowEntry;
	}


	//initialize first row in html document
	$("#sc-rows-container").append(initializeRow());

	//add rows in html document
	$("#sc-addrows").click(function() {
		$('#sc-rows-container').append(initializeRow());
	});

	//remove rows in html document
    $("#sc-removerows").click(function() {
        $(".sc-row-entry:last-of-type").remove();
        if(rowNum > 1) {
            rowNum--;
            rowTotal -= 1;
		}

	});


	// $(document).on("click", "#sc-addrows", function() {
	// 	$("#sc-rows-container").append(initializeRow());
	//	});



			// <div class="sc-row-entry">\
   //            <div class="col-md-12">\
   //              <label for="sc-row' + rowNum + '-unitnum">Strata/Commercial Buildings Only - Unit Number or Area:</label>\
   //              <input  type="text"\
   //                      name="sc-row' + rowNum + '-unitnum"\
   //                      id="sc-row' + rowNum + '-unitnum"\
   //                      placeholder="" />\
   //            </div>\
   //            <div class="col-md-4">\
   //              <label for="sc-row' + rowNum + '-type">Type of Work:</label>\
   //              <input  type="text"\
   //                      name="sc-row' + rowNum + '-type"\
   //                      id="sc-row' + rowNum + '-type"\
   //                      class="sc-rows-type"\
   //                      placeholder="" />\
   //            </div>\
   //            <div class="col-md-4">\
   //              <label for="sc-row' + rowNum + '-trade">Trade Name:</label>\
   //              <input  type="text"\
   //                      name="sc-row' + rowNum + '-trade"\
   //                      id="sc-row' + rowNum + '-trade"\
   //                      class="sc-rows-trade"\
   //                      placeholder="" />\
   //            </div>\
   //            <div class="col-md-4">\
   //              <label for="sc-row' + rowNum + '-trade-email">Trade Email:</label>\
   //              <input  type="text"\
   //                      name="sc-row' + rowNum + '-trade-email"\
   //                      id="sc-row' + rowNum + '-trade-email"\
   //                      placeholder="" />\
   //            </div>\
   //            <div class="col-md-4">\
   //              <label for="sc-row' + rowNum + '-days">Number of Days Needed:</label>\
   //              <input  type="text"\
   //                      name="sc-row' + rowNum + '-days"\
   //                      id="sc-row' + rowNum + '-days"\
   //                      placeholder="" />\
   //            </div>\
   //            <div class="col-md-8">\
   //              <label for="sc-row' + rowNum + '-comments">Comments: \
   //                <textarea name="sc-row' + rowNum + '-comments"\
   //                          id="sc-row' + rowNum + '-comments"\
   //                          placeholder="testing"></textarea>\
   //              </label>\
   //            </div>\
   //          </div>

		
		//rowNum++;
		//rowTotal += 1;
		//rowTotal = rowTotal + 1;

		//console.log(rowTotal);

		

		//$('[id$="-type"]').css("border", "5px solid red");


	



	/*FORM VALIDATOR */


	// $("#sc-form").validate({
 //      rules: {
 //      	"sc-job-number": {
 //          	required: true,
 //          	digits: true
 //        	}, 
 //        	"sc-job-address": "required",
 //        	"sc-coordinator": "required",
 //        	"sc-coord-phone": {
 //        		required: true,
 //        		phoneUS: true
 //        	},
 //        	"sc-coord-email": {
 //        		required: true,
 //        		email: true
 //        	},
 //        	"sc-job-access": "required",
 //        	"sc-startdate": {
 //        		required: true,
 //        		date: true
 //        	}
 //        	//"sc-row' + rowNum + '-unitnum"
        	
 //        	// for(let i = 1; i <= rowTotal; i++) {
 //        	// 	"sc-row" + i + "-type": "required",
 //        		// "sc-rows-trade": "required",
 //        		// "sc-rows-trade-email": {
 //        		// 	required: true,
 //        		// 	email: true,
 //        		// },
 //        		// "sc-rows-days": {
 //        		// 	required: true,
 //        		// 	digits: true,
 //        		// }
 //        		//"sc-row' + rowNum + '-comments"
 //        	//}

 //      }
 //    });



	// jQuery.validator.addClassRules({
	// 	//"sc-row' + rowNum + '-unitnum"
	// 	"sc-rows-type": {
	// 		required: true
	// 	},
	// 	"sc-rows-trade": {
	// 		required: true
	// 	},
 //  		"sc-rows-trade-email": {
 //  			required: true,
 //  			email: true
 //  		},
 //  		"sc-rows-days": {
 //  			required: true,
 //  			digits: true,
//				positiveNumber: true
 //  		}
 //  		//"sc-row' + rowNum + '-comments"
	// });


	/*FORM VALIDATOR ENDS HERE */


		// $('[id$="-type"], [id$="-trade"]').each(function() {
		// 	$(this).rules("add", {
		// 		required: true
		// 	});
		// });

		// $('[id$="-type"]').rules("add", {
		// 	required: true
		// });


		// jQuery.validator.addClassRules({
		// 	//"sc-row' + rowNum + '-unitnum"
		// 	"sc-rows-type": "required",
		// 	"sc-rows-trade": "required",
  //    		"sc-rows-trade-email": {
  //    			required: true,
  //    			email: true
  //    		},
  //    		"sc-rows-days": {
  //    			required: true,
  //    			digits: true
  //    		}
  //    		//"sc-row' + rowNum + '-comments"
		// });







	

	

	/**
	 * Matches US phone number format
	 *
	 * where the area code may not start with 1 and the prefix may not start with 1
	 * allows '-' or ' ' as a separator and allows parens around area code
	 * some people may want to put a '1' in front of their number
	 *
	 * 1(212)-999-2345 or
	 * 212 999 2344 or
	 * 212-999-0983
	 *
	 * but not
	 * 111-123-5434
	 * and not
	 * 212 123 4567
	 */
	$.validator.addMethod( "phoneUS", function( phone_number, element ) {
		phone_number = phone_number.replace( /\s+/g, "" );
		return this.optional( element ) || phone_number.length > 9 &&
			phone_number.match( /^(\+?1-?)?(\([2-9]([02-9]\d|1[02-9])\)|[2-9]([02-9]\d|1[02-9]))-?[2-9]([02-9]\d|1[02-9])-?\d{4}$/ );
	}, "Please specify a valid phone number" );

    $.validator.addMethod("positiveNumber",
        function (value) {
            return Number(value) > 0;
        }, 'Enter a positive number.');







});




