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
 * redux_auth_model: BENS NOTES:
 * Redux is pretty much the only native CI extended Model. We dont use Doctrine (yet)
 * to handle redux operations, and so we need to ensure that all standard CI Model functions
 * and libs (i.e. the db lib) are accessible to this code.
 * 
 */
class redux_auth_model extends Model
{
	/**
	 * Holds an array of tables used in
	 * redux.
	 *
	 * @var string
	 **/
	public $tables = array();

	/**
	 * activation code
	 *
	 * @var string
	 **/
	public $activation_code;

	/**
	 * forgotten password key
	 *
	 * @var string
	 **/
	public $forgotten_password_code;

	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;

	public function __construct()
	{
		parent::__construct();
		$this->load->config('redux_auth');
		$this->tables  = $this->config->item('tables');
		$this->columns = $this->config->item('columns');
	}

	/**
	 * Misc functions
	 *
	 * Hash password : Hashes the password to be stored in the database.
	 * Hash password db : This function takes a password and validates it
	 * against an entry in the users table.
	 * Salt : Generates a random salt value.
	 *
	 * @author Mathew
	 */

	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password($password = false)
	{
		$salt_length = $this->config->item('salt_length');
	  
		if ($password === false)
		{
			return false;
		}
	  
		$salt = $this->salt();

		$password = $salt . substr(sha1($salt . $password), 0, -$salt_length);

		return $password;
	}

	/**
	 * This function takes a password and validates it
	 * against an entry in the users table.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password_db($identity = false, $password = false)
	{
		$identity_column   = $this->config->item('identity');
		$users_table       = $this->tables['users'];
		$salt_length       = $this->config->item('salt_length');
	  
		if ($identity === false || $password === false)
		{
			return false;
		}
	  
		$query  = $this->db->select('password')
		->where($identity_column, $identity)
		->limit(1)
		->get($users_table);

		$result = $query->row();

		if ($query->num_rows() !== 1)
		{
			return false;
		}
	  
		$salt = substr($result->password, 0, $salt_length);

		$password = $salt . substr(sha1($salt . $password), 0, -$salt_length);

		return $password;
	}

	/**
	 * Generates a random salt value.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function salt()
	{
		return substr(md5(uniqid(rand(), true)), 0, $this->config->item('salt_length'));
	}

	/**
	 * Activation functions
	 *
	 * Activate : Validates and removes activation code.
	 * Deactivae : Updates a users row with an activation code.
	 *
	 * @author Mathew
	 */

	/**
	 * activate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function activate($code = false)
	{
		$identity_column = $this->config->item('identity');
		$users_table     = $this->tables['users'];
	  
		if ($code === false)
		{
			return false;
		}
		 
		$query = $this->db->select($identity_column)
		->where('activation_code', $code)
		->limit(1)
		->get($users_table);
		 
		$result = $query->row();

		if ($query->num_rows() !== 1)
		{
			return false;
		}
	  
		$identity = $result->{$identity_column};

		$data = array('activation_code' => '');

		$this->db->update($users_table, $data, array($identity_column => $identity));

		return ($this->db->affected_rows() == 1) ? true : false;
	}
	
	public function user_is_active($identity)
	{
		$p = $this->profile($identity);		
		return is_null($p->activation_code) || empty($p->activation_code);
	}

	/**
	 * Deactivate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($email = false)
	{
		$users_table = $this->tables['users'];
	  
		if ($email === false)
		{
			return false;
		}
	  
		$activation_code = sha1(md5(microtime()));
		$this->activation_code = $activation_code;

		$data = array('activation_code' => $activation_code);

		$this->db->update($users_table, $data, array('email' => $email));

		return ($this->db->affected_rows() == 1) ? true : false;
	}

	/**
	 * change password
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function change_password($identity = false, $old = false, $new = false)
	{
		$identity_column   = $this->config->item('identity');
		$users_table       = $this->tables['users'];
	  
		if ($identity === false || $old === false || $new === false)
		{
			return false;
		}
	  
		$query  = $this->db->select('password')
		->where($identity_column, $identity)
		->limit(1)
		->get($users_table);

		$result = $query->row();

		$db_password = $result->password;
		$old         = $this->hash_password_db($identity, $old);
		$new         = $this->hash_password($new);

		if ($db_password === $old)
		{
			$data = array('password' => $new);
			 
			$this->db->update($users_table, $data, array($identity_column => $identity));
			 
			return ($this->db->affected_rows() == 1) ? true : false;
		}
	  
		return false;
	}

	
	/**
	 * Checks email.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function email_check($email = false)
	{
		$users_table = $this->tables['users'];
	  
		if ($email === false)
		{
			return false;
		}
	  
		$query = $this->db->select('id')
		->where('email', $email)
		->limit(1)
		->get($users_table);

		if ($query->num_rows() == 1)
		{
			return true;
		}

		return false;
	}

	/**
	 * Identity check
	 *
	 * @return void
	 * @author Mathew
	 **/
	protected function identity_check($identity = false)
	{
		$identity_column = $this->config->item('identity');
		$users_table     = $this->tables['users'];
	  
		if ($identity === false)
		{
			return false;
		}
	  
		$query = $this->db->select('id')
		->where($identity_column, $identity)
		->limit(1)
		->get($users_table);

		if ($query->num_rows() == 1)
		{
			return true;
		}

		return false;
	}

	/**
	 * Insert a forgotten password key.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password($email = false)
	{
		$users_table = $this->tables['users'];
	  
		if ($email === false)
		{
			return false;
		}
	  
		$query = $this->db->select('forgotten_password_code')
		->where('email', $email)
		->limit(1)
		->get($users_table);

		$result = $query->row();
		$code = $result->forgotten_password_code;

		if (empty($code))
		{
			$key = $this->hash_password(microtime().$email);
				
			$this->forgotten_password_code = $key;

			$data = array('forgotten_password_code' => $key);
				
			$this->db->update($users_table, $data, array('email' => $email));
				
			return ($this->db->affected_rows() == 1) ? true : false;
		}
		else
		{
			//
			// BENS NOTE: This should return true, as a code has already been generated - the user might
			// be trying to send another verification email
			//
			$this->forgotten_password_code = $code;
			return true;
		}
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code = false)
	{
		$users_table = $this->tables['users'];
		$identity_column = $this->config->item('identity');
	  
		if ($code === false)
		{
			return false;
		}
	  
		$query = $this->db->select('id')
		->where('forgotten_password_code', $code)
		->limit(1)
		->get($users_table);

		$result = $query->row();

		if ($query->num_rows() > 0)
		{
			$salt       = $this->salt();
			$password   = $this->hash_password($salt);

			$this->new_password = $salt;

			$data = array('password'                => $password,
                          'forgotten_password_code' => '0');

			$this->db->update($users_table, $data, array('forgotten_password_code' => $code));

			return true;
		}

		return false;
	}

	/**
	 * profile
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function profile($identity = false)
	{
		$users_table     = $this->tables['users'];
		$groups_table    = $this->tables['groups'];
		$user_profiles_table      = $this->tables['user_profiles'];
		$user_profiles_join       = $this->config->item('join');
		$identity_column = $this->config->item('identity');
	  
		if ($identity === false)
		{			
			return false;
		}
	  
		$this->db->select($users_table.'.id, '.		
		$users_table.'.password, '.
		$users_table.'.email, '.
		$users_table.'.activation_code, '.
		$users_table.'.forgotten_password_code , '.
		$users_table.'.ip_address, '.
		$groups_table.'.name AS `group`');

		if (!empty($this->columns))
		{
			foreach ($this->columns as $value)
			{				
				$this->db->select($user_profiles_table.'.'.$value);
			}
		}

		$this->db->from($users_table);
		$this->db->join($user_profiles_table, $users_table.'.id = '.$user_profiles_table.'.'.$user_profiles_join, 'left');
		$this->db->join($groups_table, $users_table.'.group_id = '.$groups_table.'.id', 'left');

		if (strlen($identity) === 40)
		{
			$this->db->where($users_table.'.forgotten_password_code', $identity);
		}
		else
		{
			$this->db->where($users_table.'.'.$identity_column, $identity);
		}
	  
		$this->db->limit(1);
		$i = $this->db->get();
		
		return ($i->num_rows <= 0) ?  false : $i->row();		
	}

	
	/**
	 * Basic functionality
	 *
	 * Register
	 * Login
	 *
	 * @author Mathew
	 */

	/**
	 * register
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function register($password = false, $email = false, $organisation = false)
	{
		$users_table        = $this->tables['users'];
		$user_profiles_table         = $this->tables['user_profiles'];
		$groups_table       = $this->tables['groups'];
		$user_profiles_join          = $this->config->item('join');
		$additional_columns = $this->config->item('columns');

		if ($password === false || $email === false)
		{		
			return false;
		}
	  
		// Group ID
		$query    = $this->db->select('id')->where('name', $this->config->item('default_group'))->get($groups_table);
		$result   = $query->row();
		$group_id = $result->id;

		// IP Address
		$ip_address = $this->input->ip_address();
	  
		// Password
		$password = $this->hash_password($password);
		
		// Users table.
		$data = array('password' => $password, 
					  'email'    => $email,
					  'group_id' => $group_id,
					  'ip_address' => $ip_address);
				
		$this->db->insert($users_table, $data);

		// Meta table.
		$id = $this->db->insert_id();

		$data = array($user_profiles_join => $id);

		if (!empty($additional_columns))
		{
			foreach ($additional_columns as $input)
			{
				$data[$input] = $this->input->post($input);
			}
		}

		$this->db->insert($user_profiles_table, $data);
		$rowsAffected = $this->db->affected_rows();
		
		return ($rowsAffected > 0) ? true : false;
	}

	/**
	 * login
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function login($identity = false, $password = false)
	{
		$identity_column = $this->config->item('identity');
		$users_table     = $this->tables['users'];
	  
		if ($identity === false || $password === false || $this->identity_check($identity) == false)
		{
			return false;
		}
	  
		$query = $this->db->select($identity_column.', password, activation_code')
		->where($identity_column, $identity)
		->limit(1)
		->get($users_table);
	  
		$result = $query->row();

		if ($query->num_rows() == 1)
		{
			$password = $this->hash_password_db($identity, $password);

			if (!empty($result->activation_code)) { return "inactive"; }

			if ($result->password === $password)
			{
				$this->session->set_userdata($identity_column,  $result->{$identity_column});
				return true;
			}
		}

		return false;
	}
}
