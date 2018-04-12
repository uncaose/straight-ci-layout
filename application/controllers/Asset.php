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
		$this->straight->layout->webCache( $file );

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
		$this->straight->layout->webCache( $file );

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
		$this->config->load('straight', FALSE, TRUE);
		$config = $this->config->item('asset_combine');

		$key = substr($file, 0, strrpos($file, '.'));
		if( empty($key) 
			|| ! $config['combine']
			|| ! $this->load->driver('cache', $config['adapter'] )
			|| ! $cache = $this->cache->get($key) )
		{
			show_404();
		}

		$ext = pathinfo( $file, PATHINFO_EXTENSION );
		$cache = json_decode($cache, TRUE);
		// $this->load->driver('straight');
		// $this->straight->layout->header( $file );
		switch( $ext ){
			case( 'js' ):
				header('Content-Type: text/javascript');
			break;
			case( 'css' ):
				header('Content-Type: text/css');
			break;
		}

		foreach( $cache AS $hash => $file )
		{
			$file = VIEWPATH.$file;
			switch( $ext ){
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
