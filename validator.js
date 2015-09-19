$(document).ready( function() {

	var form = $("#daslicht-email-form");

	$("#daslicht-email-form").validator();
	function resetEmailForm(){
        $('#email_form').css('opacity','1');
        $('#email_sent').css('opacity','0');
        $('#email_sent').css('display','none');
	}

    $(document).on('submit', form, function(e) {
    	e.preventDefault();
    	//form.validator('validate');
		$('#send_button_text').hide();
    	//$('.spinner').css("opacity","1");
		$('.spinner').show();
		
        var data = {
        	action: 'serversidefunction',
        	token: e.data[0][0].value,
            name: e.data[0][1].value,
            email: e.data[0][2].value,
            subject: e.data[0][3].value,
            message: e.data[0][4].value
        }
        var url = php_vars.url;

        $.ajax({
            type:'POST',
            dataType:'json',
            url: url,
            data: data,
            success : function(data, textStatus, XMLHttpRequest){
				//console.log('Client Result: ',data );
				if(data.success){
					$('.spinner').css("opacity","0");
					$('#daslicht-email-form').css("opacity","0");
					$('#daslicht-email-sent-message').css('display','block')
					$('#daslicht-email-sent-message').animate({
						"opacity":"1"
					});
				}else{
					$('.spinner').hide();
					$('#send_button_text').show();
				}
            }
        });
    });


});