<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13
 */
class Straight_layout Extends CI_Driver
{
    private function skin( $output='' )
    {
        if( get_instance()->load->_skin != $this->config['view_skin'] )
        {
            $path = substr(get_instance()->load->_views[0], 0, strrpos(get_instance()->load->_views[0], '/'));
            $skin = $path.'/'.str_replace(EXT, '', get_instance()->load->_skin);

            if( file_exists(VIEWPATH.$skin.EXT) )
            {
                get_instance()->load->_skin = $skin;
            }
        }

        if( file_exists(VIEWPATH.get_instance()->load->_skin.EXT) )
        {
            $output = get_instance()->load->view( get_instance()->load->_skin, Array('skin'=>$output), TRUE, TRUE );
        }
        return $output;
    }

    private function layout( $output='' )
    {
        if( get_instance()->load->_layout != $this->config['view_layout'] )
        {
            $path = substr(get_instance()->load->_views[0], 0, strrpos(get_instance()->load->_views[0], '/'));
            $layout = $path.'/'.str_replace(EXT, '', get_instance()->load->_layout);
    
            if( file_exists(VIEWPATH.$layout.EXT) )
            {
                get_instance()->load->_layout = $layout;
            }
        }

        if( file_exists(VIEWPATH.get_instance()->load->_layout.EXT) )
        {
            $output = get_instance()->load->view( get_instance()->load->_layout, Array('layout'=>$output), TRUE, TRUE );
        }
        else
        {
            $output = '<!DOCTYPE html><html lang="en"><head></head><body>'.$output.'</body></html>';
        }
        return $output;
    }
    
    
    private function view2asset( $output='' )
    {
        $asset_path = $this->config['asset_controller'];
        $nocache_uri = $this->config['asset_nocache_uri'];
        $views = array_unique( get_instance()->load->_views, SORT_STRING );

        foreach( $views AS $v )
        {
            $js = VIEWPATH.$v.'.js';
            $css = VIEWPATH.$v.'.css';

            // view js
            if( file_exists( $js ) )
            {
                $js = str_replace(VIEWPATH, $asset_path.'/js/', $js).($nocache_uri?'?_='.hash($this->config['asset_hashkey'], $js ):'');
                get_instance()->load->js("/{$js}");
            }

            // view css
            if( file_exists( $css ) )
            {
                $css = str_replace(VIEWPATH, $asset_path.'/css/', $css).($nocache_uri?'?_='.hash($this->config['asset_hashkey'], $css ):'');
                get_instance()->load->css("/{$css}");
            }
        }
        return $output;
    }

    private function view2combineAsset( $output )
    {
        $asset_path = $this->config['asset_controller'];
        $nocache_uri = $this->config['asset_nocache_uri'];
        $views = array_unique( get_instance()->load->_views, SORT_STRING ); 

        $_js = [];
        $_css = [];
        foreach( $views AS $v )
        {
            $js = $v.'.js';
            $css = $v.'.css';

            // view js
            if( file_exists( VIEWPATH.$js ) )
            {
                $hash = hash_file($this->config['asset_hashkey'], VIEWPATH.$js);
                $_js[$hash] = $js;
            }

            // view css
            if( file_exists( VIEWPATH.$css ) )
            {
                $hash = hash_file($this->config['asset_hashkey'], VIEWPATH.$css);
                $_css[$hash] = $css;
            }
        }

        if( sizeof($_js) )
        {
            $content = json_encode($_js);
            $hash = hash($this->config['asset_hashkey'], $content);
            if( ! $cache = get_instance()->cache->get($hash) )
            {
                get_instance()->cache->save($hash, $content, $this->config['ttl']);
            }

            get_instance()->load->js("/{$asset_path}/combine/{$hash}.js?l=".urlencode(join(',', $_js)));
        }

        if( sizeof($_css) )
        {
            $content = json_encode($_css);
            $hash = hash($this->config['asset_hashkey'], $content);
            if( ! $cache = get_instance()->cache->get($hash) )
            {
                get_instance()->cache->save($hash, $content, $this->config['ttl']);
            }

            get_instance()->load->css("/{$asset_path}/combine/{$hash}.css?l=".urlencode(join(',', $_css)));
        }

        return $output;
    }
    
    public function css( $output = '' )
    {
        $_css = get_instance()->load->_css;

        foreach( $_css AS $key => $href )
        {
            if( is_array($href) )
            {
                $href = isset($href[0])?
                        "href='{$href[0]}'":
                        join(' ', array_map(function($key, $val){ return "{$key}='{$val}'";    }, array_keys($href), array_values($href)));
            }
            else
            {
                $href = preg_match('#href=#i', $href)?$href:"href='{$href}'";
            }

            $_css[$key] = "<link rel='stylesheet' type='text/css' {$href} />";
        }
        return str_replace('</head>', "\t".join("\n\t", $_css)."\n</head>", $output);
    }

    public function js( $output = '' )
    {
        $_js = get_instance()->load->_js;

        foreach( $_js AS $key => $src )
        {
            if( is_array($src) )
            {
                $src = isset($src[0])?
                    "src='{$src[0]}'":
                    join(' ', array_map(function($key, $val){ return "{$key}='{$val}'";    }, array_keys($src), array_values($src)));
            }
            else
            {
                $src = preg_match('#src=#i', $src)?$src:"src='{$src}'";
            }

            $_js[$key] = "<script type='text/javascript' {$src}></script>";
        }
        return str_replace('</body>', "\n\t".join("\n\t", $_js)."\n</body>", $output);
    }

    public function output( $output = '' )
    {
        $output = $this->skin( $output );
        $output = $this->layout( $output );
        $output = $this->config['asset_combine']?$this->view2combineAsset( $output ):$this->view2asset( $output );
        $output = $this->css( $output );
        $output = $this->js( $output );

        if( $this->config['view_minify'] === TRUE )
        {
            $hash = hash($this->config['asset_hashkey'], $output );
            if( ! $cache = get_instance()->cache->get($hash) )
            {
                $cache = $this->sanitize_output( $output );
                get_instance()->cache->save($hash, $cache, $this->config['ttl']);
                log_message('info', __FUNCTION__.':'.__LINE__.' view_minify cache hash : '.$hash);
            }
            return $cache;
        }
        return $output;
    }

    // https://stackoverflow.com/questions/27878158/php-bufffer-output-minify-not-textarea-pre
    private function sanitize_output($buffer)
    {
        // Searching textarea and pre
        $cnt = preg_match_all('#\<(textarea|pre|script).*\>.*\<\/(textarea|pre|script)\>#Uis', $buffer, $found);

        if( $cnt && $cnt > sizeof(get_instance()->load->_js) )
        {
            $buffer = str_replace($found[0], array_map(function($el){ return '<textarea>'.$el.'</textarea>'; }, array_keys($found[0])), $buffer); // replacing both with <textarea>$index</textarea>
            $buffer = preg_replace([ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--[^\\[<>].*?(?<!!)-->/' ],[ '>', '<', '\\1', '' ], $buffer);
            $buffer = str_replace(array_map(function($el){ return '<textarea>'.$el.'</textarea>'; }, array_keys($found[0])), $found[0], $buffer); // Replacing back with content
        }
        else
        {
            $buffer = preg_replace([ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/<!--[^\\[<>].*?(?<!!)-->/' ],[ '>', '<', '\\1', '' ], $buffer);
        }

        return $buffer;
    }
}


/**
 * End of File drivers/Straight_layout.php
 */