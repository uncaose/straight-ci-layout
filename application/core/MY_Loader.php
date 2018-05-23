<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * @author    : uncaose@gmail.com
 * @url     : https://github.com/uncaose/straight-ci-layout
 */
class MY_Loader extends CI_Loader
{
    public $_ci_view_path = '';     // ci3
    public $_views = [];    // view lists
    public $_skin = '_skin';    // skin
    public $_layout = '_layout';    // layout
    public $_css = [];  // css list
    public $_js = [];   // js list
    
    // Extend
    public function view( $view, $vars = [], $return = FALSE, $unshift = FALSE )
    {
        if( $unshift )
        {
            array_unshift( $this->_views, substr($view, strpos($view, '/')===0?1:0) ); // store viewname
        } else {
            array_push( $this->_views, substr($view, strpos($view, '/')===0?1:0) ); // store viewname
        }
        return parent::view($view, $vars, $return);
    }

    // view alias return $this
    public function _view($view, $vars = [])
    {
        $this->view($view, $vars);
        return $this;
    }
    
    public function getView( $unique=FALSE )
    {
        if( $unique === TRUE )
        {
            return array_unique( $this->_views, SORT_STRING );
        }
        return $this->_views;
    }
    
    public function skin( $name = '' )
    {
        $this->_skin = $name;
        return $this;
    }

    public function layout( $name = '' )
    {
        $this->_layout = $name;
        return $this;
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
        if( $unique === TRUE )
        {
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
        if( $unique === TRUE )
        {
            return array_unique( $this->_js, SORT_REGULAR );
        }
        
        return $this->_js;
    }

}

/**
 * End Of File application/core/MY_Loader.php
 */
