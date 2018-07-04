<?php

/**
 * Created by PhpStorm.
 * User: uncaose
 * Date: 2016. 6. 15.
 * Time: 23:13.
 */
class Straight extends CI_Driver_Library
{
    public $config = [];

    public $valid_drivers;

    public function __construct()
    {
        get_instance()->config->load('straight', true, false);
        $this->config = get_instance()->config->config['straight'];
        $this->valid_drivers = $this->config['modules'];

        $this->_init();
    }

    public function _init()
    {
        if (!defined('EXT')) {
            define('EXT', '.php');
        }	// ci2 EXT redefine
        if (!defined('VIEWPATH')) {
            define('VIEWPATH', get_instance()->load->_ci_view_path);
        } // ci3 style
        get_instance()->load->driver('cache', $this->config['adapter']);
    }
}
