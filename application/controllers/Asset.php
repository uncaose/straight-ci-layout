<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use MatthiasMullie\Minify;

/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class Asset extends CI_Controller
{
	public $env = [];

	public function __construct() {
        parent::__construct();
		$this->config->load('straight', TRUE, FALSE);
        $this->env = $this->config->item('straight');
        $this->load->driver('cache', $this->env['adapter']);
	}

	public function js()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->header( $file );
		echo $this->_js( $file );
	}
	
	public function css()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->header( $file );
		echo $this->_css( $file );
	}

	public function combine( $file = '' )
	{
        $l = $this->input->get('l');    // query list
        $key = substr($file, 0, strrpos($file, '.'));
        $ext = pathinfo( $file, PATHINFO_EXTENSION );

		if( empty($key) )
		{
			show_404();
        }

		$this->header( $file );

		if( $content = $this->cache->get( $file ) ) // md5.ext key
		{
			if( isset($content['minify']) && $content['minify'] == $this->env['asset_minify_'.$ext] )
			{
				echo $content['body'];
				exit;
            }
        }
        else if( $cache = $this->cache->get($key) ) // md5 key
        {
            if( is_array($cache) ) $cache = $cache[0];
            $cache = @json_decode($cache, TRUE);
        }
        else if( strlen($l) )   // file list
        {
            $cache = explode(',', urldecode($l) );
            if( ! sizeof($cache) )
            {
                show_404();
            }
        }
        else
        {
            show_404();
        }

		$content = [];
		foreach( $cache AS $h => $f )
		{
			$f = VIEWPATH.$f;
            switch( $ext )
            {
				case( 'js' ):
					$content[] = $this->_js( $f );
				break;
				case( 'css' ):
					$content[] = $this->_css( $f );
				break;
				default:
					show_404();
				break;
			}
        }
        $content = join($ext=='js'?";\n":"\n", $content);

        $this->cache->save($file, ['minify'=>$this->env['asset_minify_'.$ext], 'body'=>$content], $this->env['ttl'] );
		echo $content;
	}

	private function _js( $file = '' )
	{
        $rs = '';
		if( $this->env['asset_minify_js'] === TRUE && class_exists('MatthiasMullie\\Minify\\JS') )
		{
			$minifier = new Minify\JS( $file );
			$rs = $minifier->minify();
		}else{
			$rs = $this->asset( $file );
        }
        return (ENVIRONMENT!=='production'?'/*'.str_replace(VIEWPATH,'', $file).'*/':'').$rs;
	}

	private function _css( $file = '' )
	{
        $rs = '';
		if( $this->env['asset_minify_css'] === TRUE && class_exists('MatthiasMullie\\Minify\\CSS') )
		{
            $minifier = new Minify\CSS( $file );
            $rs = $minifier->minify();
		}else{
			$rs = $this->asset( $file );
        }
        return (ENVIRONMENT!=='production'?'/*'.str_replace(VIEWPATH,'', $file).'*/':'').$rs;
    }
    
    private function header( $file = '' )
	{
		switch( pathinfo( $file, PATHINFO_EXTENSION ) )
		{
			case( 'js' ):
				header('Content-Type: text/javascript');
				break;
			case( 'css' ):
				header('Content-Type: text/css');
				break;
			default:
				header('HTTP/1.0 404 Not Found'); exit;
				break;
		}
		$this->webCache( $file );
    }
    
    private function asset( $file='' )
	{
		if( empty($file) || ! file_exists($file) )    // existst file
		{
			header('HTTP/1.0 404 Not Found');
			exit;
		}

		$this->load->helper('file');
		$this->output->set_content_type( get_mime_by_extension($file) );
		ob_start();
		readfile( $file );
		$content = ob_get_clean();

		return $content;
	}

	// 30758400 : 1 year
	private function webCache( $file='', $time=30758400 )
	{
		if( empty($file) )    // existst file
		{
			header('HTTP/1.0 404 Not Found');
			exit;
		}

		$Etag = file_exists($file)?hash_file($this->env['asset_hashkey'], $file):hash($this->env['asset_hashkey'], $file);
        $this->load->cache( $time, $Etag );
	}
}

/**
 * End of File application/controllers/Asset.php
 */
