<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" :
 * <thepixeldeveloper@googlemail.com> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Mathew Davies
 * ----------------------------------------------------------------------------
 */
 
/**
* Redux Authentication 2
*/
class redux_auth
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;

	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * __construct
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function __construct()
	{
		$this->ci =& get_instance();
		$email = $this->ci->config->item('email');
		$this->ci->load->library('email', $email);
		
		//
	    // BENS EDIT When calling from a view - the model needs to be constructed
	    //
	    if(is_null($this->ci->redux_auth_model)) $this->ci->redux_auth_model = new redux_auth_model();
	}
	
	/**
	 * Activate user.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function activate($code)
	{
		return $this->ci->redux_auth_model->activate($code);
	}
	
	/**
	 * Deactivate user.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($identity)
	{
	    return $this->ci->redux_auth_model->deactivate($code);
	}
	
	/**
	 * Change password.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function change_password($identity, $old, $new)
	{
        return $this->ci->redux_auth_model->change_password($identity, $old, $new);
	}

	/**
	 * forgotten password feature
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password($email)
	{
		$forgotten_password = $this->ci->redux_auth_model->forgotten_password($email);
		
		if ($forgotten_password)
		{
			// Get user information.
			$profile = $this->ci->redux_auth_model->profile($email);

			$data = array('identity'                => $profile->{$this->ci->config->item('identity')},
    			          'forgotten_password_code' => $this->ci->redux_auth_model->forgotten_password_code);
			
			log_info("Sending Forgotten Password Verification to user(".$profile->email.")");
			return send_template_email('Email Verification (Forgotten Password)', $profile->email, "forgotten_password", $data);			
		}
		else
		{
			log_info( "Couldn't find forgotten_password_code in table for user(".$email.")");
			return false;
		}
	}
	
	
	
	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code)
	{
	    $identity                 = $this->ci->config->item('identity');
	    $profile                  = $this->ci->redux_auth_model->profile($code);
		$forgotten_password_complete = $this->ci->redux_auth_model->forgotten_password_complete($code);

		if ($forgotten_password_complete)
		{
			$data = array('identity'    => $profile->{$identity},
				         'new_password' => $this->ci->redux_auth_model->new_password,
							'profile' => $profile);
         	   
			return send_template_email("New Password", $profile->email, "new_password", $data);			
		}
		else
		{
			return false;
		}
	}

	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function register($password, $email, $organisation)
	{
	    $email_activation = $this->ci->config->item('email_activation');	    

		if (!$email_activation)
		{
			$success = $this->ci->redux_auth_model->register($password, $email, $organisation);
			return $success;
		}
		else
		{
			$register = $this->ci->redux_auth_model->register($password, $email, $organisation);
            
			if (!$register) { return false; }

			$deactivate = $this->ci->redux_auth_model->deactivate($email);

			if (!$deactivate) 
			{
				return false; 
			}

			$activation_code = $this->ci->redux_auth_model->activation_code;

			$data = array('password'   => $password,
        				'email'      => $email,
        				'activation' => $activation_code);
            
			
			return send_template_email('Email Activation (Registration)', $email, "activation", $data);			
		}
	}
	
	
	
	/**
	 * login
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function login($identity, $password)
	{
		return $this->ci->redux_auth_model->login($identity, $password);
	}
	
	/**
	 * logout
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logout()
	{
	    $identity = $this->ci->config->item('identity');
	    $this->ci->session->unset_userdata($identity);
		$this->ci->session->sess_destroy();
	}
	
	/**
	 * logged_in
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function logged_in()
	{
	    $identity = $this->ci->config->item('identity');
	    
	    //
	    // Bens edit - also check to see if there is a respondent logged in
	    //
		return ($this->ci->session->userdata($identity) || $this->ci->session->userdata("current_respondent")  ) ? true : false;
	}
	
	/**
	 * Profile
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function profile($return_doctrine_entity = false)
	{
	    $session  = $this->ci->config->item('identity');
	    $identity = $this->ci->session->userdata($session);
	    $resp_id = $this->ci->session->userdata("current_respondent");
	    
	    if($identity && $return_doctrine_entity == false) return $this->ci->redux_auth_model->profile($identity);
	    if($identity && $return_doctrine_entity == true)
	    {
	    	$result = user_finder()->findByEmail($identity);
	    	$user = $result[0];
	    	return $user;	
	    }
	    if($resp_id) return respondent_finder()->find($resp_id);
	}
}
