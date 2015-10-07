<?php
/*
Plugin Name: Simple Bootstrap AJAX eMail Form
Plugin URI: http://marcwensauer.de
Description: Simple Bootstrap AJAX eMail Form
Version: 1.0
Author: Marc Wensauer
Author URI: http://marcwensauer.de
Text Domain: daslicht-contactform
Domain Path: /languages
*/

/**
 * 
 */
class DaslichtEmailForm 
{




	/**
	 * [token description]
	 * @return [type] [description]
	 */
	function token(){
		$uid = uniqid();
		$_SESSION['token'] = $uid;
		//ChromePhp::log('token set: ',$_SESSION['token'] );
		return $uid;
	}


	/**
	 * [displayEmailForm description]
	 * @return [type] [description]
	 */
	function displayEmailForm() { 
		?>
				<!-- SUCCESS MESSAGE -->
				<div id="daslicht-email-sent-message" style="opacity:0;display: none" class="alert alert-success text-center" role="success">
					<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
					Email Sent !
				</div>

				<!-- eMAIL FORM -->
				<form id="daslicht-email-form" data-toggle="validator" role="form"  method="POST">
					<input type="hidden" name="token" value="<?php echo $this->token(); ?>">
					<div class="form-group">
					    <label for="contact_name" class="control-label">Name</label>
					    <input  type="text" name="contact_name" pattern="[a-zA-Z0-9 ]+" class="form-control" id="name" placeholder="Name" >
				  	</div>
					<div class="form-group">
						<label for="contact_email" class="control-label">eMail</label>
<!--  -->			<input  type="email" name="contact_email" class="form-control" id="email" placeholder="Email" >
					</div>
					<div class="form-group">
						<label for="contact_subject" class="control-label"><?php _e( 'Subject',"daslicht-contactform" ); ?></label>
						<input  type="text" name="contact_subject"  pattern="[a-zA-Z ]+" class="form-control" id="subject" placeholder="<?php _e( 'Subject',"daslicht-contactform" ); ?>" >
					</div>
					<div class="form-group">
						<textarea  class="form-control" rows="10" cols="35" name="contact_message"></textarea>
					</div>

				  <button type="submit" name="contact_submit" class="btn btn-primary pull-right" style="width:100px;height:37px">
				  	<span class="spinner glyphicon glyphicon-refresh spinning"></span> <div id="send_button_text">Send</div>
				  </button>
				</form>
		<?php
	}


	/**
	 * [displaySuccessMessage description]
	 * @return [type] [description]
	 */
	function displaySuccessMessage() {
		?>
		<div class="alert alert-success" role="success">
			<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
			Email Sent !
		</div>
		<?php
	}


	/**
	 * [resetState description]
	 * @return [type] [description]
	 */
	function resetState() {
		$_SESSION['token'] = 0;
		unset($_POST); 
	}


	/**
	 * [sendemail description]
	 * @return [type] [description]
	 */
	function sendemail() {
		$data = $_POST;

		$result = array(
			"success" => false
		);
		//ChromePhp::log('is email',is_email($data["email"]));

		//Check Token
		//if( $_SESSION['token'] === $_POST["token"]  && $_SESSION['token'] != 0   ) {
		if( true ) { //2do: fix ajax token
		//if(true ) {	
		
			//Check if eMail exists
			//if($this->verifyEmail($data['email'], 'daslicht@ansolas.de') == 'valid') {
			if(is_email($data["email"]) != false){//
			//	ChromePhp::log('cehck');

				// sanitize form values
				$name    = sanitize_text_field( $data["name"] );
				$email   = sanitize_email( $data["email"] );
				$subject = sanitize_text_field( $data["subject"] );
				$message = esc_textarea( $data["message"] );
				
			 	// get the blog administrator's email address
				$to = get_option( 'admin_email' );
				$headers = "From: $name <$email>" . "\r\n";

				// Send eMail and return result to the client
				if ( wp_mail( $to, $subject, $message, $headers ) ) {
						//echo json_encode($result);die;
					$this->resetState();
						
						$result["success"] = true; 
				 		echo json_encode($result);die;
					//return true;
				} else {
					//ChromePhp::log('email error :(' );
					$this->resetState();
					//$result["success"] = true; 
					echo json_encode($result);die;
				}

			}else{
				//ChromePhp::log('email invalid :(' );
				$this->resetState();
				echo json_encode($result);die;
			}

		}else{
			//header('Location: '. "http://" . $_SERVER['HTTP_HOST'] );
			echo json_encode($result);die;;
		}
		// 	$path = rtrim("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'/'); // the URL the form is called from
	
	}


	/**
	 * [daslicht_emailform description]
	 * @return [type] [description]
	 */
	public function add_daslicht_emailform_shortcode() {
		//if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$this->displayEmailForm();
		//}
	}

 	/**
 	 * [daslicht_contactform_addvalidator description]
 	 * @return [type] [description]
 	 */
	public function daslicht_contactform_addvalidator() {
	    	//ChromePhp::log('test', plugins_url(). "/daslicht-contactform/validator.js"  );
	        wp_enqueue_script( 'daslicht-bootstrap-validator',plugins_url(). "/daslicht-contactform/bootstrap-validator/dist/validator.min.js" );    
	        wp_enqueue_script( 'daslicht-validator' ,plugins_url(). "/daslicht-contactform/validator.js"  );   //,'','',true

	        $dataToBePassed = array(
			    'url' => admin_url( 'admin-ajax.php' )
			);
			//ChromePhp::log('phpvars ',$dataToBePassed );
			wp_localize_script( 'daslicht-validator', 'php_vars', $dataToBePassed );

	    //$path = rtrim("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'/'); // the URL the form is called from
	}

 	/**
 	 * [daslicht_contactform_addvalidator description]
 	 * @return [type] [description]
 	 */
	public function my_plugin_load_plugin_textdomain() {
		//function my_plugin_load_plugin_textdomain() {
    	load_plugin_textdomain( 'daslicht-contactform', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    	//ChromePhp::log('lok: ',dirname( plugin_basename( __FILE__ ) ) . '/languages/'  );
	}

	/**
	 * [__construct description]
	 */
	function __construct( ) {
		$Object = $this;
		add_shortcode( 'daslicht_emailform', array( $Object, 'add_daslicht_emailform_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $Object, 'daslicht_contactform_addvalidator' ) );
		//$i =	add_action( 'wp_ajax_nopriv_serversidefunction', array( $Object, 'serversidefunction' ) );
		add_action( 'wp_ajax_serversidefunction', array( $Object, 'sendemail' ) );

		
		//}
		add_action( 'plugins_loaded', array( $Object, 'my_plugin_load_plugin_textdomain' ));


		 if(!session_id()) {
	        session_start();
	    }
	}

}

$e  = new DaslichtEmailForm();


?>