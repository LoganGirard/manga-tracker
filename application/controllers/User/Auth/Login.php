<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends No_Auth_Controller {
	public function __construct() {
		parent::__construct();

		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
	}

	public function index() {
		$this->header_data['title'] = "Login";
		$this->header_data['page']  = "login";

		$this->form_validation->set_rules('identity', 'Identity',  'required', array('required' => 'Please enter your username or email.'));
		$this->form_validation->set_rules('password', 'Password',  'required', array('required' => 'Please enter your password.'));

		if ($isValid = $this->form_validation->run() === TRUE) {
			//form is valid

			//check if identity is email, if not then attempt to use grab from DB
			$identity = $this->User->find_email_from_identity($this->input->post('identity'));

			$remember = (bool) $this->input->post('remember');

			if($identity && $this->ion_auth->login($identity, $this->input->post('password'), $remember)) {
				//login is successful

				//Add some extra session data
				$this->session->set_userdata('username', $this->ion_auth->user()->row()->username);

				//Errors
				$this->session->set_flashdata('notices', $this->ion_auth->messages());

				//redirect to main page, or previous URL
				$this->session->keep_flashdata('referred_from');
				if($prevURL = $this->session->flashdata('referred_from')) {
					redirect($prevURL);
				} else { //@codeCoverageIgnore
					redirect('/'); //TODO (CHECK): Should this be refresh?
				} //@codeCoverageIgnore
			} else {
				//login was unsuccessful

				$this->session->set_flashdata('notices', $this->ion_auth->errors());

				$isValid = FALSE;
			}
		}

		//login wasn't valid, failed, or this is a fresh login attempt
		if(!$isValid) {
			$this->body_data['notices'] = validation_errors() ? validation_errors() : $this->session->flashdata('notices');
			//$errors = $this->form_validation->error_array();

			$this->body_data['form_create'] = array (
				'action' => 'user/login',

				'role'   => 'form'
			);
			$this->body_data['form_identity'] = array(
				'name'        => 'identity',
				'id'          => 'identity',
				'type'        => 'text',

				'class'       => 'form-control input-lg',

				'placeholder' => 'Username or Email Address',
				'value'       => $this->form_validation->set_value('identity'),

				'required'    => ''
			);
			$this->body_data['form_password'] = array(
				'name'        => 'password',
				'id'          => 'password',
				'type'        => 'password',

				'class'       => 'form-control input-lg',

				'placeholder' => 'Password',

				'required'    => '',
			);
			$this->body_data['form_remember'] = array(
				'name'    => 'remember',
				'id'      => 'remember',
				'type'    => 'checkbox',

				'class'   => 'hidden',

				'checked' => 'checked',
				'value'   => 'remember' //CI is stupid, so we need to pass a value so CI can see it's checked :\
			);
			$this->body_data['form_submit'] = array(
				'name' => 'submit',
				'type' => 'submit',

				'class' => 'btn btn-lg btn-success btn-block',

				'value' => 'Login'
			);

			$this->session->keep_flashdata('referred_from');
			$this->_render_page('User/Login');
		}
	}

	//TODO: Steam Login
	//TODO: Google Login
	//TODO (Research): PSN/XBL
}