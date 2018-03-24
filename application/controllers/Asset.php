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

	public function _remap( $method, $params = array() ) {

		$file = VIEWPATH.join('/', $params);
		
		$this->load->driver('straight');
		$this->straight->layout->header( $file );

		if( class_exists('MatthiasMullie\\Minify\\JS') )
		{
			if( $method == 'js' ){
				$minifier = new Minify\JS( $file );
			}else{
				$minifier = new Minify\CSS( $file );
			}
			echo $minifier->minify();
		}else{
			$this->straight->layout->asset( $file );
		}

	}
}

/**
 * End of File application/controllers/Asset.php
 */
