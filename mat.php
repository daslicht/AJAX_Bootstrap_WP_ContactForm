<?php

class DaslichtEmailForm{



	function displayEmailSentMessage() {

	}

	function html_form_code() {

		?>

			<?php if(isset($_SESSION['emailSent'])):?>
				<div class="alert alert-success" role="success">
				  <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
				  Email Sent !
				</div>
				<?php
					//session_destroy();
					echo $_SESSION['emailSent'];
					$_SESSION['emailSent']= false;
					echo $_SESSION['emailSent'];
				?>
			<?php else:?>

			<?php endif;?>




		<?php
		// echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
		// echo '<p>';
		// echo 'Your Name (required) <br/>';
		// echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
		// echo '</p>';
		// echo '<p>';
		// echo 'Your Email (required) <br/>';
		// echo '<input type="email" name="cf-email" value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
		// echo '</p>';
		// echo '<p>';
		// echo 'Subject (required) <br/>';
		// echo '<input type="text" name="cf-subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : '' ) . '" size="40" />';
		// echo '</p>';
		// echo '<p>';
		// echo 'Your Message (required) <br/>';
		// echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
		// echo '</p>';
		// echo '<p><input type="submit" name="cf-submitted" value="Send"></p>';
		// echo '</form>';
	}
		
	function myStartSession() {
	    if(!session_id()) {
	        session_start();
	    }
	}

	function __construct($Object) {
		$test = add_shortcode( 'sitepoint_contact_form', array( $Object, 'cf_shortcode' ) );
		//add_action('init', 'myStartSession', 1);
		 if(!session_id()) {
	        session_start();
	    }
	}



}

class EmailFormShortcodeHandler 
{
	function token(){
		$uid = uniqid();
		$_SESSION['emailToken'] = $uid;
		return $uid;
	}

	function displayEmailForm() {
		?>
				<form id="daslicht-email-form" data-toggle="validator" role="form" action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="POST">
					<input type="hidden" name="token" value="<?php echo $this->token(); ?>">
					<div class="form-group">
					    <label for="name" class="control-label">Name</label>
					    <input  type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" class="form-control" id="name" placeholder="Name" value="<?php isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : ''  ?>">
				  	</div>
					<div class="form-group">
						<label for="email">eMail</label>
						<input  type="email" name="cf-email" class="form-control" id="email" placeholder="Email" value="<?php  isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ?>">
					</div>
					<div class="form-group">
						<label for="subject">Subject</label>
						<input  type="text" name="cf-subject"  pattern="[a-zA-Z ]+" class="form-control" id="subject" placeholder="Subject" value="<?php  isset( $_POST["cf-subject"] ) ? esc_attr( $_POST["cf-subject"] ) : ''  ?>">
					</div>
					<div class="form-group">
						<textarea class="form-control" rows="10" cols="35" name="cf-message"><?php  isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ?></textarea>
					</div>

				  <button type="submit" name="cf-submitted" class="btn btn-default pull-right">Submit</button>
				</form>
		<?php
	}

	function displaySuccessMessage() {
		?>
		<div class="alert alert-success" role="success">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			Email Sent !
		</div>
		<?php
	}

	function deliver_mail() {


		if ( isset( $_POST['cf-submitted'] ) ) {

			$path = rtrim("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'/'); // the URL the form is called from

			// sanitize form values
			$name    = sanitize_text_field( $_POST["cf-name"] );
			$email   = sanitize_email( $_POST["cf-email"] );
			$subject = sanitize_text_field( $_POST["cf-subject"] );
			$message = esc_textarea( $_POST["cf-message"] );

			// get the blog administrator's email address
			$to = get_option( 'admin_email' );

			$headers = "From: $name <$email>" . "\r\n";

			// If email has been process for sending, display a success message
			if ( wp_mail( $to, $subject, $message, $headers ) ) {
				//echo '<div class="text-center">';
				//echo '<p>Thanks for contacting me, expect a response soon.</p>';
				//echo '</div>';
				$_SESSION['emailSent'] = true;
				ChromePhp::log('sent!: ',$_SESSION['emailSent']);
				
				header('Location: '. $path);
				
			} else {
				//$_SESSION['emailSent'] = false;
			//	echo 'An unexpected error occurred';
			}
		}
	
	}

	public function cf_shortcode() {


		

		// if("55fb3b7f44ca2" === 0){
		// 	ChromePhp::log("true");
			
		// }else{
		// 	ChromePhp::log("false");
		// }
		
	//	ob_start();
	//	
		// Check if the incoming Request is a POST request which we get if the eMail Form has been sent
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			//ChromePhp::log('redirect POST: ',"http://" . $_SERVER[HTTP_HOST]);

			
			ChromePhp::log('POST session: ',$_SESSION);

			ChromePhp::log('_POST token: ', $_POST['token'] );

		//ChromePhp::log('POST token: ', $_POST['token'] );

			//$_SESSION['emailToken']=null ;

			//Check if the email token match//strcmp ( 
			if( isset($_SESSION['emailToken']) && isset($_POST["token"]) ) 
			{
				ChromePhp::log('_session: ',$_SESSION);

				ChromePhp::log(' _POST: ', $_POST );
				if($_SESSION['emailToken'] != 0) {
					ChromePhp::log('emailToken NOT null', $_SESSION['emailToken']);
				}
				if( $_SESSION['emailToken'] == $_POST["token"]  && $_SESSION['emailToken'] != 0   ) {
					ChromePhp::log('token is valid and match');
					ChromePhp::log('- session emailToken: ', $_SESSION['emailToken'] );
					ChromePhp::log('- post token: ', $_POST["token"] );
					$this->deliver_mail(); //deliver email in case its a Post Request 
					$_SESSION['emailToken'] = 0;
					$_POST = array();
					ChromePhp::log('-1 session emailToken: ', $_SESSION['emailToken'] );
					ChromePhp::log('-1 post token: ', $_POST["token"] );
					$this->displaySuccessMessage();
				}else{
					ChromePhp::log('token is INVALID');
					//header('Location: '. "http://" . $_SERVER['HTTP_HOST'] );

				}
			}else{
				ChromePhp::log('token is INVALID 2');
				//header('Location: '. "http://" . $_SERVER['HTTP_HOST'] );
			}
			
			if(isset($_SESSION['emailSent'])) { // check if session exists
				if($_SESSION['emailSent'] === true) { // if sessikon exists and its value is true =then show SUCCESS message
					echo "EMAIL SENT!";
					ChromePhp::log('session: ',$_SESSION['emailSent']);
					$_SESSION['emailSent'] = false;
					ChromePhp::log('session: ',$_SESSION['emailSent']);
				}else{
					//$path = rtrim("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'/');
					//header('Location: '. "http://" . $_SERVER[HTTP_HOST] );
					//
				}
			} 

		}else{
	
			$this->displayEmailForm();
			ChromePhp::log('after displayEmailFormGET: ',$_SESSION);
						
		}	

		

		

			
		// }
		//displayEmailForm();
		//

		
		//html_form_code();

		//return ob_get_clean();
	}
}

$e  = new EmailFormShortcodeHandler();

new DaslichtEmailForm($e);


?>