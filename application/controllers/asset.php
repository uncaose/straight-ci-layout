<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use MatthiasMullie\Minify;
// ini_set('display_errors', 0);   // passing of Call to a member function item() on array in Output.php on line 374

/**
 * Asset controller
 * Caution Error on system/core/Output.php on line 374
 * 
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class Asset extends CI_Controller
{
	public $cofnig = [];
	public $isCache = FALSE;

    public function __construct() 
    {
		parent::__construct();
		$this->config->load('straight', TRUE, FALSE);
		$this->config = $this->config->item('straight');
        $this->load->driver('cache', $this->config['adapter'] );
		
		$this->isCache = $this->cache->{$this->config['adapter']['adapter']}->is_supported()
				|| $this->cache->{$this->config['adapter']['backup']}->is_supported();
	}

	public function js()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->load->driver('straight');
		$this->header( $file );
        echo $this->_js( $file ); exit;
	}
	
	public function css()
	{
		$file = VIEWPATH.join('/', func_get_args());

		$this->load->driver('straight');
		$this->header( $file );
        echo $this->_css( $file ); exit;
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

		if( $this->isCache && $content = $this->cache->get( $file ) ) // 캐시
		{
			if( isset($content['minify']) && $content['minify'] == $this->config['asset_minify_'.$ext] )
			{
				echo $content['body'];
				exit;
            }

        }
        else if( $this->isCache && $cache = $this->cache->get($key) )
        {
            if( is_array($cache) ) $cache = $cache[0];
            $cache = @json_decode($cache, TRUE);
        }
        else if( strlen($l) ) // 파일목록
        {
            $cache = explode(',', $l );
            if( ! sizeof($cache) )
            {
                show_404();
            }
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

        if( $this->isCache )
        {
            $this->cache->save($file, ['minify'=>$this->config['asset_minify_'.$ext], 'body'=>$content], $this->config['ttl'] );
        }

            echo $content; exit;
	}

	private function _js( $file = '' )
	{
        $rs = '';
		if( $this->config['asset_minify_js'] === TRUE && class_exists('MatthiasMullie\\Minify\\JS') )
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
		if( $this->config['asset_minify_css'] === TRUE && class_exists('MatthiasMullie\\Minify\\CSS') )
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

		$lastModifTime = '';
		if( file_exists($file) )
		{
			$lastModifTime = filemtime($file);
			$Etag = hash_file($this->config['asset_hashkey'], $file);
		}else{
			$Etag = hash($this->config['asset_hashkey'], $file);
		}

		// checkt last time & Etag
		if ( ( isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModifTime  )
			|| ( ! empty($Etag) && isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $Etag) )
		{
			// Not Modify
			header("HTTP/1.1 304 Not Modified");
			exit;
		}

		// set Cache header
		header('Vary: Accept-Encoding');
		header("Expires: ".gmdate("D, d M Y H:i:s", time()+$time)." GMT");
		if( ! empty($lastModifTime) ) header("Last-Modified: ".gmdate("D, d M Y H:i:s", $lastModifTime)." GMT");
		header("Etag: {$Etag}");
		header('Pragma: cache');
		header('Cache-Control: public');
		header("Cache-Control: max-age=".$time);
    }
}
/**
 * End of File application/controllers/asset.php
 */
