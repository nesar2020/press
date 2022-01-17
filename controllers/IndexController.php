<?php 

/**
 * Index Page Controller
 * @category  Controller
 */
class IndexController extends BaseController{
	/**
     * Index Action 
     * @return View
     */
	function index(){
		$this->view->render("index/index.php" , null , "main_layout.php");
	}
	
	private function login_user($username , $password_text, $rememberme = false){
		$db = $this->GetModel();
		
		$db->where("username", $username)->orWhere("email", $username);
		$user = $db->getOne('users');

		if(!empty($user)){
			
			//Verify User Password Text With DB Password Hash Value.
			//Uses PHP password_verify() function with default options
			$password_hash = $user['password'];
			if(password_verify($password_text,$password_hash)){

				
        		unset($user['password']); //Remove user password as it's not needed.

				set_session('user_data',$user); // Set Active User Data in A Sessions

				//if Remeber Me, Set Cookie
				if($rememberme==true){
					$sessionkey=time().random_str(20);// Generate a Session Key for the User
					//Update User Detail in Database with the session key
					$db->where('id' , $user['id']);
					$res = $db -> update(array("login_session_key"=>hash_value($sessionkey)));
					if(!empty($res)){
						set_cookie("login_session_key",$sessionkey);// save user login_session_key in a Cookie
					}
				}
				else{
					clear_cookie("login_session_key");// Clear any Previous Set Cookie
				}

				$redirect_url=get_session("login_redirect_url");// Redirect to user active page
				if(!empty($redirect_url)){
					redirect_to_page($redirect_url);
					clear_session("login_redirect_url");
				}
				else{
					redirect_to_page(HOME_PAGE);
				}
			}
			else{
				$this->view->page_error = get_lang('prompt_username_password_incorrect');
				$this->view->render("index/login.php" ,null,"main_layout.php");
			}
		}
		else{
			$this->view->page_error = get_lang('prompt_username_password_incorrect');
			$this->view->render("index/login.php" ,null,"main_layout.php");
		}
	}
	
	
	/**
     * Login Action
     * If Not $_POST Request, Display Login Form View
     * @return View
     */
	function login(){
		if(is_post_request()){
			
			$form_collection=$_POST;
			$username = trim($form_collection['username']);
			$password = $form_collection['password'];
			$rememberme = (!empty($form_collection['rememberme']) ? $form_collection['rememberme'] : false);
			$this->login_user($username , $password, $rememberme = false);
		}
		else{
			$this->view->page_error = get_lang('prompt_invalid_request');
			$this->view->render("index/login.php" ,null,"main_layout.php");
		}
	}
	
	
	/**
     * Register User Action 
     * If Not $_POST Request, Display Register Form View
     * @return View
     */
	function register(){
		if(is_post_request()){
			$modeldata = transform_request_data($_POST);
			
			
			
			$rules_array = array(
				
				'name' => 'required',
				'email' => 'required|valid_email',
				'password' => 'required',
				'username' => 'required',
				'role' => 'required',
			);
			
			$is_valid = GUMP::is_valid($modeldata, $rules_array);
			
			if( $is_valid !== true) {
				if(is_array($is_valid)){
					foreach($is_valid as  $error_msg){
						$this->view->page_error[] = $error_msg;
					}
				}
				else{
					$this->view->page_error[] = $is_valid;
				}
			}

			
			$cpassword = $modeldata['confirm_password'];
			$password = $modeldata['password'];
			if($cpassword != $password){
				$this->view->page_error[] = get_lang('prompt_password_not_confirm');
			}
			unset($modeldata['confirm_password']);
			
			$password_text = $modeldata['password'];
			$modeldata['password'] = password_hash($password_text , PASSWORD_DEFAULT);

			$db = $this->GetModel();

			//Check if Duplicate Record Already Exit In The Database
			$db->where('email',$modeldata['email']);
			if($db->has('Users')){
				$this->view->page_error[] = $modeldata['email'].get_lang('link_delete_account');
			}
			//Check if Duplicate Record Already Exit In The Database
			$db->where('username',$modeldata['username']);
			if($db->has('Users')){
				$this->view->page_error[] = $modeldata['username'].get_lang('link_delete_account');
			}
			
			if( empty($this->view->page_error) ){
				$rec_id = $db->insert('Users',$modeldata);
				if(!empty($rec_id)){
					
					$this->login_user($modeldata['email'] , $password_text);
					
					return;
				}
				else{
					if($db->getLastError()){
						$this->view->page_error = $db->getLastError();
					}
					else{
						$this->view->page_error = get_lang('prompt_error_registering_user');
					}
				}
			}
		}
		
		$this->view->page_title =get_lang('txt_add_page_title');
		
		$this->view->render('index/register.php' , null ,"main_layout.php");
	}

	
	/**
     * Logout Action
     * Destroy All Sessions And Cookies
     * @return View
     */
	function logout($arg=null){
		
		session_destroy();
		clear_cookie("login_session_key");
		redirect_to_page("");
	}
	
	
	
	
	/**
     * Change User Language
     * @return null
     */
	function change_language($lang){
		set_session('lang', $lang);
		redirect_to_page(DEFAULT_PAGE);
	}

}
