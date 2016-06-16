<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class Asset extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function _remap($method, $params = array())
	{
		echo $this->straight->layout->asset( $method, $params );
	}
}

/**
 * End of File application/controllers/Asset.php
 */
