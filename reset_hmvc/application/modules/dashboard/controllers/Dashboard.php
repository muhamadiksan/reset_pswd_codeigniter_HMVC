<?php
 
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
// buat class sesuai dengan nama file dan diawali dengan huruf besar
class Dashboard extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }
 
    // method dengan nama index akan dipanggil otomatis jika url hanya mendefinisikan controller saja
    public function index(){
        $this->load->view('template'); // kode ini akan memanggil view dengan nama template
       
    }
 

    }