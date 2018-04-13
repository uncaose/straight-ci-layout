<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{
	public function index()
	{
		$this->load->view('straight/welcome');
	}

	public function depth()
	{
		$this->load->layout('straight/_layout')->view('straight/depth/depth');
	}
}
