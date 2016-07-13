<?php declare(strict_types=1); defined('BASEPATH') or exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
	private $user_tables;

	public function __construct() {
		parent::__construct();
		log_message('debug', "MY_Form_validation Class Initialized");

		$this->CI =& get_instance();
		$this->CI->config->load('ion_auth', TRUE);
		$this->user_tables = $this->CI->config->item('tables', 'ion_auth');
	}

	/*** User Validation ***/
	/**
	 * @param string $username
	 * @return bool
	 */
	public function valid_username(string $username) : bool {
		if(!($isValid = (bool) preg_match('/^[a-zA-Z0-9_-]{4,15}$/', $username))) {
			$this->set_message('valid_username', 'Username is invalid format.');
		}
		return $isValid;
	}
	/**
	 * @param string $password
	 * @return bool
	 */
	public function valid_password(string $password) : bool {
		if(!($isValid = $this->min_length($password, $this->CI->config->item('min_password_length', 'ion_auth')))) {
			$this->set_message('valid_password', 'The password is too short!');
		}
		elseif(!($isValid = $this->max_length($password, $this->CI->config->item('max_password_length', 'ion_auth')))) {
			$this->set_message('valid_password', 'The password is too long!');
		}
		return $isValid;
	}
	/**
	 * @param string $username
	 * @return bool
	 */
	public function is_unique_username(string $username) : bool {
		if(!($isValid = $this->is_unique($username, "{$this->user_tables['users']}.username"))) {
			$this->set_message('is_unique_username', 'The username already exists in our database.');
		}
		return $isValid;
	}
	/**
	 * @param string $email
	 * @return bool
	 */
	public function is_unique_email(string $email) : bool {
		if(!($isValid = $this->is_unique($email, "{$this->user_tables['users']}.email"))) {
			$this->set_message('is_unique_email', 'The email already exists in our database.');
		}
		return $isValid;
	}

	/** MISC FUNCTIONS **/
	/**
	 * @param string $ruleName
	 * @return bool
	 */
	public function isRuleValid(string $ruleName) : bool {
		$isValid = FALSE;
		if(is_string($ruleName) && $this->has_rule($ruleName)){
			$isValid = !in_array($ruleName, array_keys($this->error_array()), TRUE);
		}
		return $isValid;
	}
}