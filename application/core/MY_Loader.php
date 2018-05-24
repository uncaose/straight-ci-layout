<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * @author	: uncaose@gmail.com
 * @url 	: https://github.com/uncaose/straight-ci-layout
 */
class MY_Loader extends CI_Loader
{
	public $_ci_view_path = '';     // ci3
	public $_views = Array();	// view lists
	public $_skin = '_skin';	// skin
    public $_layout = '_layout';	// layout
    public $_css = [];  // css list
    public $_js = [];   // js list
	
	public function __construct() {
		parent::__construct();
		if( empty($this->_ci_view_path) && ! empty($this->_ci_view_paths) ) { // ci2 path setting
			list($this->_ci_view_path) = array_keys($this->_ci_view_paths);
		}

		$this->_composer_autoload();	
	}

	private function _composer_autoload()
	{
		if ($composer_autoload = config_item('composer_autoload'))
		{
			if ($composer_autoload === TRUE)
			{
				file_exists(APPPATH.'vendor/autoload.php')
					? require_once(APPPATH.'vendor/autoload.php')
					: log_message('error', '$config[\'composer_autoload\'] is set to TRUE but '.APPPATH.'vendor/autoload.php was not found.');
			}
			elseif (file_exists($composer_autoload))
			{
				require_once($composer_autoload);
			}
			else
			{
				log_message('error', 'Could not find the specified $config[\'composer_autoload\'] path: '.$composer_autoload);
			}
		}
	}
	
	// Extend
	public function view( $view, $vars = array(), $return = FALSE, $unshift = FALSE ) {
		if( $unshift ) {
			array_unshift( $this->_views, substr($view, strpos($view, '/')===0?1:0) ); // store viewname
		} else {
			array_push( $this->_views, substr($view, strpos($view, '/')===0?1:0) ); // store viewname
        }
        
        $rs = parent::view($view, $vars, $return);
        return $return ? $rs : $this;
    }
    
	public function getView( $unique=FALSE ) {
		if( $unique === TRUE ) {
			return array_unique( $this->_views, SORT_STRING );
		}
		return $this->_views;
	}
	
	public function skin( $name = '' ) {
		$this->_skin = $name;
		return $this;
	}

	public function getSkin() {
		return $this->_skin;
	}

	public function layout( $name = '' ) {
		$this->_layout = $name;
		return $this;
	}

	public function getLayout() {
		return $this->_layout;
	}

    public function css( $href = NULL )
    {
        if( ! is_array($href) )
        {
            if( empty($href) ) return $this;
            $href = [$href];
        }

        $this->_css = array_merge( $this->_css, $href );

        return $this;
    }

    public function getCss( $unique = TRUE )
    {
        if( $unique === TRUE ) {
			return array_unique( $this->_css, SORT_REGULAR );
        }

        return $this->_css;
    }

    public function js( $src = NULL )
    {
        if( ! is_array($src) )
        {
            if( empty($src) ) return $this;
            $src = [$src];
        }

        $this->_js = array_merge( $this->_js, $src );

        return $this;
    }

    public function getJs( $unique = TRUE )
    {
        if( $unique === TRUE ) {
			return array_unique( $this->_js, SORT_REGULAR );
        }
        
        return $this->_js;
    }
}

/**
 * End Of File application/core/MY_Loader.php
 */
