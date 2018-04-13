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
		$this->config->load('straight', TRUE, FALSE);
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

	public function combine( $file = '' )
	{
		$config = $this->config->item('straight');
		
		$key = substr($file, 0, strrpos($file, '.'));
		if( empty($key) 
		|| ! $config['asset_combine']['combine']
		|| ! $this->load->driver('cache', $config['asset_combine']['adapter'] )
		|| ! $cache = $this->cache->get($key) )
		{
			show_404();
		}

		$this->load->driver('straight');
		$this->straight->layout->header( $file );
		
		$cache = @json_decode($cache, TRUE);
		foreach( $cache AS $hash => $file )
		{
			$file = VIEWPATH.$file;
			switch( pathinfo( $file, PATHINFO_EXTENSION ) ){
				case( 'js' ):
					if( class_exists('MatthiasMullie\\Minify\\JS') )
					{
						$minifier = new Minify\JS( $file );
						echo $minifier->minify();
					}else{
						echo $this->straight->layout->asset( $file );
					}
				break;
				case( 'css' ):
					if( class_exists('MatthiasMullie\\Minify\\CSS') )
					{
						$minifier = new Minify\CSS( $file );
						echo $minifier->minify();
					}else{
						echo $this->straight->layout->asset( $file );
					}
				break;
			}
		}
	}
}

/**
 * End of File application/controllers/Asset.php
 */
