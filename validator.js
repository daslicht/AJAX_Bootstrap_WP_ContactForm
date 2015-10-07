$(document).ready( function() {


    var form = $("#daslicht-email-form");

    $("#daslicht-email-form").validator();
    function resetEmailForm(){
        $('#email_form').css('opacity','1');
        $('#email_sent').css('opacity','0');
        $('#email_sent').css('display','none');
    }
//console.log(form);
//
    function initFormSubmit() { 
         console.log('init submit',$("#daslicht-email-form"));
        $("#daslicht-email-form").on('submit',  function(e) { //readd button after ahax nav!
            console.log('submit');
            
        	e.preventDefault();
        	//form.validator('validate');
    		$('#send_button_text').hide();
        	//$('.spinner').css("opacity","1");
    		$('.spinner').show();
            var postData = $(this).serializeArray();
    		console.log(postData);
            var data = {
                action: 'serversidefunction',
                token: postData[0].value,
                name: postData[1].value,
                email: postData[2].value,
                subject: postData[3].value,
                message: postData[4].value
            }
            var url = php_vars.url;

            $.ajax({
                type:'POST',
                dataType:'json',
                url: url,
                data: data,
                success : function(data, textStatus, XMLHttpRequest){
    				console.log('Client Result: ',data );

    				if(data.success){
    					$('.spinner').css("opacity","0");
    					$('#daslicht-email-form').css("opacity","0");
    					$('#daslicht-email-sent-message').css('display','block')
    					$('#daslicht-email-sent-message').animate({
    						"opacity":"1"
    					});
    				}else{
                        alert('error');
    					$('.spinner').hide();
    					$('#send_button_text').show();
    				}
                }
            });
    
        });
    };
    $(document).on('hijax', initFormSubmit );
    
    initFormSubmit();


});