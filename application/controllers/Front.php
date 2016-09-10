<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {

	/**
	 *	API controller
	 *----------------	 
	 *	Responsible for queries processing
	 *	
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model("crime_model");
		$this->load->model("app_model");
		$this->load->model("error_model");
		$this->load->model("feedback_model");
	}
	public function index()
	{
		$this->load->view("front/header");
		$this->load->view("front/index");
		$this->load->view("front/footer");
	}
}
