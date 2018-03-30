<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use MatthiasMullie\Minify;

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class Asset extends CI_Controller
{
	public function __construct() {
		parent::__construct();
	}

	public function js()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->load->driver('straight');
		$this->straight->layout->header( $file );

		if( class_exists('MatthiasMullie\\Minify\\JS') )
		{
			$minifier = new Minify\JS( $file );
			echo $minifier->minify();
		}else{
			echo $this->straight->layout->asset( $file );
		}
	}
	
	public function css()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->load->driver('straight');
		$this->straight->layout->header( $file );

		if( class_exists('MatthiasMullie\\Minify\\CSS') )
		{
			$minifier = new Minify\CSS( $file );
			echo $minifier->minify();
		}else{
			echo $this->straight->layout->asset( $file );
		}
	}
}

/**
 * End of File application/controllers/Asset.php
 */
