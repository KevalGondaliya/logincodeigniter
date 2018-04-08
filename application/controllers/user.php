<?php 

class User extends CI_Controller {

	public function __construct(){

        parent::__construct();
  			$this->load->helper('url');
 			$this->load->model('user_model');
        $this->load->library('session');
 		$this->load->library('form_validation');

	}

	public function index()
	{
		$this->load->view("register");
	}

	public function register_user(){

	  // $this->form_validation->set_error_delimiters('<div class="alert alert-danger">', '</div>');	
	 $this->form_validation->set_rules('user_name','Username', 'required');
	 $this->form_validation->set_rules('user_email','email', 'required|valid_email');
	 $this->form_validation->set_rules('user_password','Password', 'required');
	 $this->form_validation->set_rules('user_age','Age', 'required');
	 $this->form_validation->set_rules('user_mobile','Mobile', 'required');

      $user=array(
      'user_name'=>$this->input->post('user_name'),
      'user_email'=>$this->input->post('user_email'),
      'user_password'=>md5($this->input->post('user_password')),
      'user_age'=>$this->input->post('user_age'),
      'user_mobile'=>$this->input->post('user_mobile')
        );
    
		if ($this->form_validation->run() == FALSE)
        {
            $this->load->view("register");
        }
        else
        {
          	$this->user_model->register_user($user);
			$this->session->set_flashdata('success_msg', 'Registered successfully.Now login to your account.');
			redirect('user/login_view');

        }	

	}

	public function login_view(){

		$this->load->view("login");

	}

	function login_user(){
	  $user_login=array(

	  'user_email'=>$this->input->post('user_email'),
	  'user_password'=>md5($this->input->post('user_password'))

	    );

	    $data=$this->user_model->login_user($user_login['user_email'],$user_login['user_password']);
	      if($data)
	      {
	        $this->session->set_userdata('user_id',$data['user_id']);
	        $this->session->set_userdata('user_email',$data['user_email']);
	        $this->session->set_userdata('user_name',$data['user_name']);
	        $this->session->set_userdata('user_age',$data['user_age']);
	        $this->session->set_userdata('user_mobile',$data['user_mobile']);

	        $this->load->view('user_profile');

	      }
	      else{
	        $this->session->set_flashdata('error_msg', 'Error occured,Try again.');
	        $this->load->view("login");

	      }

	}

	function user_profile(){

		$this->load->view('user_profile');

	}
	public function user_logout(){

	  $this->session->sess_destroy();

	  redirect('user/login_view', 'refresh');
	}

}
