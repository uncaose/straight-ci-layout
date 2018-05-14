<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  CI_Controller > MY_Controller
 */
class Welcome extends MY_Controller
{
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index() {
		$this->load
            ->css('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css')
            ->js([
                ['src'=>'https://code.jquery.com/jquery-3.2.1.slim.min.js', 'integrity'=>'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN','crossorigin'=>'anonymous'],
                'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
            ])->js([
                'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" crossorigin="anonymous"'
            ]);
        $this->load->view('welcome_head');
		$this->load->view('welcome_message');
        $this->load->view('welcome_foot');
	}
}
