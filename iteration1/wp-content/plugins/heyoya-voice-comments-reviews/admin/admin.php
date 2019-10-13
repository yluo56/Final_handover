<?php
/*
 * This class is responsible for creating the Heyoya Admin panel
 * Login, signup and admin will be preformed in this class.
 */
class AdminOptionsPage{

	/*
	 * Class constructor. in it we're attaching the hooks that will render the application 
	 */
	public function __construct(){		
		add_action('admin_menu', array($this, 'heyoya_menu') );		
		add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts') );
		add_action('admin_init', array($this, 'heyoya_admin_init') );
		add_action('admin_head', array($this, 'heyoya_admin_head') );
		add_action( 'wp_ajax_logout', array($this, 'heyoya_logout') );
	}
	
	/*
	 * Adding heyoya to the comments menu
	 */
	function heyoya_menu() {
		add_comments_page( 'Heyoya Options', 'Heyoya', 'manage_options', 'heyoya-options', array($this, 'set_heyoya_options') );
	}
	
	/*
	 * Loading client scripts for login and logged in modes.
	 * - report script will report usage statistics to Heyoya servers for bug tracking.
	 * - loggedout script will handle the login and signup modes + validations.
	 * - loggedin script will load the Heyoya admin (in an iframe)
	 * - messaging script will provide the communication layer between the heyoya admin and wordpress.     
	 */
	function load_admin_scripts($hook){
		if ( 'comments_page_heyoya-options' != $hook )
			return;			

		wp_register_script( 'report_script', plugins_url( '/js/report.js', __FILE__ ) );
		wp_enqueue_script( 'report_script', plugins_url( '/js/report.js', __FILE__ ), array( 'jQuery') );
		
		wp_register_script( 'messaging_script', plugins_url( '/js/messaging.js', __FILE__ ) );
		wp_enqueue_script( 'messaging_script', plugins_url( '/js/messaging.js', __FILE__ ) );	
		
		if (!is_heyoya_installed()){
			wp_register_script( 'loggedout_script', plugins_url( '/js/loggedout.js', __FILE__ ) );
			wp_enqueue_script( 'loggedout_script', plugins_url( '/js/loggedout.js', __FILE__ ), array( 'jQuery') );			
		} else {
			wp_register_script( 'loggedin_script', plugins_url( '/js/loggedin.js', __FILE__ ) );
			wp_enqueue_script( 'loggedin_script', plugins_url( '/js/loggedin.js', __FILE__ ), array( 'jQuery') );
		}
	}		
	
	/*
	 * Logically registering the forms and inputs
	 */
	function heyoya_admin_init(){
		$options = get_option('heyoya_options', null);
		if ($options == null){
			$options = array();
			add_option("heyoya_options", $options);
		}
		
		register_setting( 'heyoya-options', 'heyoya-options', array($this, 'heyoya_options_validate' ));
		add_settings_section('heyoya_login', '', array($this, 'heyoya_section_text'), 'admin-login');
		add_settings_section('heyoya_signup', '', array($this, 'heyoya_section_text'), 'admin-signup');
		
		add_settings_field('login_email', 'Email', array($this, 'admin_email_string'), 'admin-login', 'heyoya_login');
		add_settings_field('login_password', 'Password', array($this, 'admin_password_string'), 'admin-login', 'heyoya_login');

		add_settings_field('signup_fullname', 'Full Name', array($this, 'admin_fullname_string'), 'admin-signup', 'heyoya_signup');
		add_settings_field('signup_email', 'Email', array($this, 'admin_email_string'), 'admin-signup', 'heyoya_signup');
		add_settings_field('signup_password', 'Password', array($this, 'admin_password_string'), 'admin-signup', 'heyoya_signup');		
		
		/*add_settings_field('signup_hsi', '_hsi', array($this, 'admin_hsi_string'), 'admin-signup', 'heyoya_signup');		
		add_settings_field('signup_hssi', '_hssi', array($this, 'admin_hssi_string'), 'admin-signup', 'heyoya_signup');		
		add_settings_field('signup_hci', '_hci', array($this, 'admin_hci_string'), 'admin-signup', 'heyoya_signup');		
		add_settings_field('signup_hcsi', '_hcsi', array($this, 'admin_hcsi_string'), 'admin-signup', 'heyoya_signup');		
		add_settings_field('signup_hclki', '_hclki', array($this, 'admin_hclki_string'), 'admin-signup', 'heyoya_signup');		
		add_settings_field('signup_hcpmi', '_hcpmi', array($this, 'admin_hcpmi_string'), 'admin-signup', 'heyoya_signup');		
		add_settings_field('signup_hrt', '_hrt', array($this, 'admin_hrt_string'), 'admin-signup', 'heyoya_signup');		
		*/
	}	
	
	function heyoya_section_text() {
		echo '';
	} 

	/*
	 * Rendering the inputs
	 */
	function admin_email_string() {
		$options = get_option('heyoya_options');
		echo "<input class='login_email' name='heyoya_options[login_email]' size='30' type='text' value='" . (isset($options["login_email"])?$options["login_email"]:"") . "' />";
	}

	/*
	 * Rendering the inputs
	 */	
	function admin_fullname_string() {
		$options = get_option('heyoya_options');
		echo "<input class='signup_fullname' name='heyoya_options[signup_fullname]' size='30' type='text' value='" . (isset($options["signup_fullname"])?$options["signup_fullname"]:"") . "' />";
	}
	
	/*
	 * Rendering the inputs
	 */	
	function admin_password_string() {
		$options = get_option('heyoya_options');
		echo "<input class='login_password' name='heyoya_options[login_password]' size='30' type='password' value='" . (isset($options["login_password"])?$options["login_password"]:"") . "' />";
	}

	/*
	 * Rendering the inputs
	 */	
	function admin_hsi_string() {
		$options = get_option('heyoya_options');		
		echo "<input name='heyoya_options[signup_hsi]' id='heyoya_options[signup_hsi]' type='hidden' value=''/>";
	}

	/*
	 * Rendering the inputs
	 */	
	function admin_hssi_string() {
		$options = get_option('heyoya_options');		
		echo "<input name='heyoya_options[signup_hssi]' id='heyoya_options[signup_hssi]' type='hidden' value=''/>";
	}

	
	/*
	 * Rendering the inputs
	 */	
	function admin_hci_string() {
		$options = get_option('heyoya_options');		
		echo "<input name='heyoya_options[signup_hci]' id='heyoya_options[signup_hci]' type='hidden' value=''/>";
	}

	/*
	 * Rendering the inputs
	 */	
	function admin_hcsi_string() {
		$options = get_option('heyoya_options');		
		echo "<input name='heyoya_options[signup_hcsi]' id='heyoya_options[signup_hcsi]' type='hidden' value=''/>";
	}

	/*
	 * Rendering the inputs
	 */	
	function admin_hclki_string() {
		$options = get_option('heyoya_options');		
		echo "<input name='heyoya_options[signup_hclki]' id='heyoya_options[signup_hclki]' type='hidden' value=''/>";
	}

	/*
	 * Rendering the inputs
	 */	
	function admin_hcpmi_string() {
		$options = get_option('heyoya_options');		
		echo "<input name='heyoya_options[signup_hcpmi]' id='heyoya_options[signup_hcpmi]' type='hidden' value=''/>";
	}

	/*
	 * Rendering the inputs
	 */	
	function admin_hrt_string() {
		$options = get_option('heyoya_options');		
		echo "<input name='heyoya_options[signup_hrt]' id='heyoya_options[signup_hrt]' type='hidden' value=''/>";
	}
	
	
	/*
	 * Main login function, will send a POST login request to the server and pass the response to the login_signup_handle_response method. 
	 */
	function login_user($options, $email, $password){
		if ( $email == null || trim($email) == "" || !is_email($email) || ( ( $password == null || trim($password) == "" ) && ( $options == null || $options["apikey"] == null ) ) )
			return;

		$time = time();		
		$email = trim($email);
		$url = 'https://admin.heyoya.com/client-admin/lwak.heyoya';
		
		if ($password != null){
			$password = trim($password);			
			
			$args = array ("body" => array ("e" => $email,"p" => $password,"t" => $time), "sslverify" => false, "timeout" => 60);
			
		} else			
			$args = array('body' => array('e' =>  $email,'ak' => $options["apikey"],'t' =>  $time), "sslverify" => false, "timeout" => 60);		
		
		$response = wp_remote_post( $url, $args );	
		
		$options["last_method"] = "login";
		update_option("heyoya_options", $options);
		
		$this->login_signup_handle_response($options, $response, $email, $time);
	}

	/*
	 * Main signup function, will send a POST signup request to the server and pass the response to the login_signup_handle_response method. 
	 */
	function signup_user($options, $fullname, $email, $password, $_hsi, $_hssi, $_hclki, $_hcpmi, $_hrt, $_hci, $_hcsi){
		if ( $email == null || trim($email) == "" || !is_email($email) || $fullname == null || trim($fullname) == "" || $password == null || trim($password) == "" )
			return;							

		$email = trim($email);
		$password = trim($password);			
		$fullname = trim($fullname);
		$time = time(); 		
		$_hsi = trim($_hsi);
		$_hssi = trim($_hssi);
		$_hclki = trim($_hclki);
		$_hcpmi = trim($_hcpmi);
		$_hrt = trim($_hrt);
		$_hci = trim($_hci);
		$_hcsi = trim($_hcsi);
		if (trim($_hci) == "")
			$_hci = "wordpress";
		else  if (trim($_hcsi) == "")
			$_hcsi = "wordpress";

		$url = 'https://admin.heyoya.com/client-admin/rwak.heyoya';
		$args = array('body' => array('e' => $email,'p' => $password,'n' => $fullname, 'hsi' => $_hsi, 'hssi' => $_hssi, 'hclki' => $_hclki, 'hcpmi' => $_hcpmi, 'hrt' => $_hrt, 'hci' => $_hci, 'hcsi' => $_hcsi, 't' => $time), "sslverify" => false, "timeout" => 60);
		
		$response = wp_remote_post( $url, $args );

		$options["last_method"] = "signup";
		
		$this->login_signup_handle_response($options, $response, $email, $time);
	}


	/*
	 * Login/signup response method.
	 * Check the response code and body for errors and associate the user if the resposne is valid.
	 */	
	function login_signup_handle_response($options, $response, $email, $last_login_time){
		if ($response == null || $email == null || !is_email($email) || $last_login_time == null){
			$options["error_raised"] = true;
			$options["error_code"] = -1;
			update_option("heyoya_options", $options);				
			return;
		}	
		
		$response_code = wp_remote_retrieve_response_code($response);
		if ($response_code == "" || $response_code != 200){
			$options["error_raised"] = true;
			$options["error_code"] = -1;
			update_option("heyoya_options", $options);
			return;
		}
		
		$body = wp_remote_retrieve_body( $response );
		if ($body == null || trim($body) == "" || preg_match('/^-[0-9]{1}$/i', trim($body))){			
			
			$options["error_raised"] = true;			
			
			if ($body == null || trim($body) == "")
				$options["error_code"] = -1;
			else  	
				$options["error_code"] = intval(trim($body));
						
			update_option("heyoya_options", $options);
			return;
		}		

		$body_response = json_decode($body, true);
		
		if (isset($body_response["ai"])){
			if (isset($options["last_method"]) &&  $options["last_method"] == "login"){
				$options = get_option('heyoya_options', array());
			}
				
			$options["affiliate_id"] = $body_response["ai"];
		}
		
		if (isset($body_response["ak"]))
			$options["apikey"] = $body_response["ak"];
		
		$options["login_email"] = $email;
		$options["last_login_time"] = $last_login_time;				
		
		update_option("heyoya_options", $options);
	}	
	
	/*
	 * Main validation function.
	 * Server validation for the login and signup forms 
	 */
	function heyoya_options_validate() {
		$input = $_POST["heyoya_options"];
		$options = get_option('heyoya_options');			
		
		if ($input == null || !isset($input["login_email"]) || !is_email(trim($input["login_email"])) || ( ( !isset($input["signup_fullname"]) || trim($input["signup_fullname"]) == "" ) && ( !isset($input["login_password"]) || trim($input["login_password"]) == "" ) ) ){
			update_option("heyoya_options", $options);
			return;				
		}
		
		if ( !isset($input["signup_fullname"]) || trim($input["signup_fullname"]) == "" ){
			$this->login_user( $options, trim($input["login_email"]), trim($input["login_password"]) );			
			return;
		} 			
		
		$this->signup_user($options, trim($input["signup_fullname"]), trim($input["login_email"]), trim($input["login_password"]), trim($input["_hsi"]), trim($input["_hssi"]), trim($input["_hclki"]), trim($input["_hcpmi"]), trim($input["_hrt"]), trim($input["_hci"]), trim($input["_hcsi"]));		
		return;		
	}
	
	/*
	 * Heyoya logout callback listener
	 * Once executed, will empty the Heyoya options object. 
	 */
	function heyoya_logout(){
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}		
		
		echo "1";
		
		$options = get_option('heyoya_options', null);
		if ($options == null){
			exit();
			return;		
		}
		
		if (isset($options["apikey"]))
			$options["apikey"] = null;
		
		if (isset($options["login_email"]))
			$options["login_email"] = null;
		
		if (isset($options["last_login_time"]))
			$options["last_login_time"] = null;
		
		if (isset($options["last_method"]))
			$options["last_method"] = null;
		
		if (isset($options["affiliate_id"]))
			$options["affiliate_id"] = null;

		update_option("heyoya_options", $options);

		exit();
	}


	/*
	 * Main UI rendering method.
	 * Will check if the user is logged in.
	 * If so, will load the Heyoya admin panel
	 * Otherwise will render login and signup dialogs. 
	 */	
	function set_heyoya_options() {
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}		
		
		$session_valid = false;
		$error_raised = false;
		$error_code = 0;
		$last_method = "login";
		$options = get_option('heyoya_options', null);
		
		if ($options != null && isset($options["error_raised"]) && trim($options["error_raised"]) != "" && isset($options["last_method"])){
			
				
			$error_raised = true;
			$last_method = $options["last_method"];
			$error_code = $options["error_code"];

			$options["error_raised"] = false;
			$options["last_method"] = null;
			$options["error_code"] = null;
			
			update_option("heyoya_options", $options);			
		}
		
		if (!$error_raised && ($options != null && isset($options["last_login_time"]) && is_numeric($options["last_login_time"]))){
			$last_login_time = intval($options["last_login_time"], 10);
			if ( (time() - $last_login_time) < 10800 )
				$session_valid = true;
		}	
		
		$is_heyoya_installed = is_heyoya_installed();
		
		if (!$error_raised && $is_heyoya_installed && !$session_valid){						
			$session_valid = true;
			$this->login_user($options, $options["login_email"], null);			
			$options = get_option('heyoya_options', null);					
			
			if ($options != null && isset($options["error_raised"])){
				if (isset($options["apikey"]))
					$options["apikey"] = null; 
				
				
				$session_valid = false;
 				$error_raised = true;
				$last_method = "login";
 				
				$error_code = -1000 + intval(trim($options["error_code"]));
					
				$options["error_raised"] = false;
				$options["last_method"] = null;
				$options["error_code"] = null;
				
				update_option("heyoya_options", $options);
			}

			$is_heyoya_installed = is_heyoya_installed();
		}

		
		if (!$is_heyoya_installed || $error_raised || !$session_valid){
			//echo '<pre>'; print_r($options); echo '</pre>';
		?>
		<div id="heyoyaAdmin">
			<div id="heyoyaSignUpDiv" class="<?php echo $last_method != "login"?"":"invisible" ?>">
				<h2>Create Heyoya Account</h2>				
				<div class="updated <?php echo $error_raised && $error_code != 0?"":"invisible" ?>">
					<p>
						<span class="invisible email_invalid">Email address is <strong>invalid</strong></span>
						<span class="invisible email_missing">Email address is <strong>required</strong></span>
						<span class="invisible name_missing">Name is <strong>required</strong></span>
						<span class="invisible password_missing">Password is <strong>required</strong></span>
						<span class="<?php echo ($error_raised && ($error_code == -1 || $error_code == -4))?"":"invisible" ?> general_error">An error has occurred, please try again in a few seconds</span>
						<span class="<?php echo ($error_raised && $error_code == -2)?"":"invisible" ?> general_error">Please make sure to fill the fields below</span>
						<span class="<?php echo ($error_raised && $error_code == -3)?"":"invisible" ?> general_error">Email already exists</span>						
					</p>																
				</div>
				<form action="options.php" method="post">
				<?php settings_fields('heyoya-options'); ?>
				<?php do_settings_sections('admin-signup'); ?>
		 
				<input class="button-primary button" name="Submit" type="submit" value="<?php esc_attr_e('Create account'); ?>"  original_value="<?php esc_attr_e('Create account'); ?>" /><span class="alternate">Already registered?&nbsp;&nbsp;<a id="login">Log in!</a></span>
				<br /><br />
				By creating an account I accept the <a target="_blank" href="https://www.heyoya.com/termsOfUse.html">Terms of Use</a> and recognize that a 'Powered by Heyoya' link will appear on the bottom of my Heyoya widget.				
				</form>
			</div> 
			<div id="heyoyaLoginDiv" class="<?php echo $last_method == "login"?"":"invisible" ?>">
				<h2>Login with your Heyoya account</h2>			
				<div class="updated <?php echo $error_raised && $error_code != 0?"":"invisible" ?>">
					<p>
						<span class="invisible email_invalid">Email address is <strong>invalid</strong></span>
						<span class="invisible email_missing">Email address is <strong>required</strong></span>
						<span class="invisible password_missing">password is <strong>required</strong></span>
						<span class="<?php echo ($error_raised && ($error_code == -1 || $error_code == -4))?"":"invisible" ?> general_error">Email or password are incorrect</span>
						<span class="<?php echo ($error_raised && $error_code == -2)?"":"invisible" ?> general_error">Please make sure to fill the fields below</span>
						<span class="<?php echo ($error_raised && $error_code == -5)?"":"invisible" ?> general_error">An error has occurred, please try again in a few seconds</span>
					</p>
				</div>
				<form action="options.php" method="post">
				<?php settings_fields('heyoya-options'); ?>
				<?php do_settings_sections('admin-login'); ?>
				<input class="button-primary button" name="Submit" type="submit" value="<?php esc_attr_e('Log in'); ?>" original_value="<?php esc_attr_e('Log in'); ?>" /><span class="alternate">No account?&nbsp;&nbsp;<a id="createAccount">Sign up!</a></span>				
				</form>
			</div> 
		</div>
		
		<script type="text/javascript">
			var heyoyaErrorCode = <?php echo $error_code ?>; 
		</script>
		
		<?php } else { 
		//	echo '<pre>'; print_r($options); echo '</pre>';
			?>
			<div id="heyoyaContainer" aa="<?php echo $options["apikey"] ?>"></div>
		<?php }
	}
	
	/*
	 * Adding the Heyoya css file to the page head node.  
	 */	
	function heyoya_admin_head(){
		if (isset($_GET['page']) && $_GET['page'] == 'heyoya-options') {?>
		<link rel='stylesheet' href='<?php echo esc_url( plugins_url( 'css/admin.css', __FILE__ ) ); ?>' type='text/css' />
	<?php }
	}

}
?>
