<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

	function __construct(){
		parent::__construct();
		// cek login
		if($this->session->userdata('status') != "login"){
			redirect(base_url().'welcome?pesan=belumlogin');
		}
	}

	function index(){
		$this->load->view('admin/header');
		$this->load->view('admin/index');
		$this->load->view('admin/footer');
	}	

	function logout(){$this->session->sess_destroy();
		redirect(base_url().'login/welcome?pesan=logout');
	}

	function ganti_password(){
		$this->load->view('admin/header');
		$this->load->view('admin/ganti_password');
		$this->load->view('admin/footer');
}
	function ganti_password_act(){
		$pass_baru = $this->input->post('pass_baru');$ulang_pass = $this->input->post('ulang_pass');
					 $this->form_validation->set_rules('pass_baru','Password Baru','required|matches[ulang_pass]');
					 $this->form_validation->set_rules('ulang_pass','Ulangi Password Baru','required');
	if($this->form_validation->run() != false){
		$data = array('admin_password' => md5($pass_baru));
		$w = array('admin_id' => $this->session->userdata('id')
);
	$this->m_rental->update_data($w,$data,'admin');
	redirect(base_url().'admin/ganti_password?pesan=berhasil');
		}else{$this->load->view('admin/header');
				$this->load->view('admin/ganti_password');
				$this->load->view('admin/footer');
}
}
}

