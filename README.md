# straight-ci-layout #
- Using MY_Controller.php

## Apply CI version ##
- Codeigniter 2.2.6
- Codeigniter 3.1.7
- Codeigniter 3.1.8
- Version 2 will all be higher.

## Ex Controller ##
<pre>
Class Welcome extends MY_Controller
{
    public function index() {
        $this->load->view('welcome_message');
    }
    public function depth()
    {
        $this->load->layout('straight/_layout')->view('straight/depth/depth');
    }
}
</pre>

## Ex View File ##
<pre>
views/
    _layout.css
    _layout.js
    _layout.php                // default layout auto wrap
    _skin.css
    _skin.js
    _skin.php                  // default skin auto wrap
    welcome_message.css        // isset {view}.css auto asset
    welcome_message.js         // isset {view}.js auto asset
    welcome_message.php
views/straight/
        _layout_another.php
        _layout.php
        _skin.php
        welcome.php
views/straight/depth
            _skin.php
            _skin.css
            depth.php
</pre>

## Ex Output ##

<pre>
&lt;html>
&lt;head>
    ...
    &lt;link rel='stylesheet' type='text/css' href='/asset/css/_layout.css' />
    &lt;link rel='stylesheet' type='text/css' href='/asset/css/_skin.css' />
    &lt;link rel='stylesheet' type='text/css' href='/asset/css/welcome_message.css' />
&lt;/head>
&lt;body>
    
    &lt;_layout.php>
        &lt;_skin.php>
            ... welcome_message.php contents ...
        &lt;/_skin.php>
    &lt;/_layout.php>

    &lt;script type='text/javascript' src='/asset/js/_layout.js'>&lt;/script>
    &lt;script type='text/javascript' src='/asset/js/_skin.js'>&lt;/script>
    &lt;script type='text/javascript' src='/asset/js/welcome_message.js'>&lt;/script>
&lt;/body>
&lt;/html>
</pre>

## Config ##
<pre>
$config['modules'] = CI_VERSION>="3.0.0"?array('layout'):array('straight_layout');

$config['asset_controller'] = 'asset';
$config['asset_hashkey'] = 'md5';
$config['asset_nocache_uri'] = TRUE;   // TRUE : /asset/css/style.css?_=abc...1234, FALSE : /asset/cas/style.css
$config['asset_combine'] = TRUE;    // combine : welcome_message.js(css), _skin.js(css), _layout.js(css)
$config['asset_minify_js'] = TRUE;  // require composer minify lib
$config['asset_minify_css'] = TRUE; // require composer minify lib

$config['adapter'] = ['adapter' => 'apc', 'backup' => 'file'];
$config['ttl'] = 2592000;	// 30 day

$config['view_skin'] = '_skin';
$config['view_layout'] = '_layout';
$config['view_minify'] = TRUE;
</pre>

## minify, combine Output ##

<pre>
&lt;html lang="en">&lt;head>&lt;meta charset="utf-8">&lt;title>Welcome to CodeIgniter&lt;/title>&lt;link rel='stylesheet' type='text/css' href='/asset/combine/c93667b961c1da6c29ac55d8e6b51e61.css' />&lt;/head>&lt;body> &lt;div id="container"> &lt;h1>Welcome to CodeIgniter!&lt;/h1>&lt;div id="body">&lt;p>The page you are looking at is being generated dynamically by CodeIgniter.&lt;/p>&lt;p>If you would like to edit this page you'll find it located at:&lt;/p>&lt;code> application/views/welcome_message.css&lt;br/> application/views/welcome_message.js&lt;br/> application/views/welcome_message.php &lt;/code>&lt;p>The corresponding controller for this page is found at:&lt;/p>&lt;code>application/controllers/Welcome.php&lt;/code>&lt;p>If you are exploring CodeIgniter for the very first time, you should start by reading the &lt;a href="user_guide/">User Guide&lt;/a>.&lt;/p>&lt;p>CI_VERSION : 3.1.8 / ENVIRONMENT : development&lt;/p>&lt;p>&lt;a href="https://github.com/uncaose/straight-ci-layout" target="_blank">https://github.com/uncaose/straight-ci-layout&lt;/a>&lt;/p>&lt;ul>&lt;li>&lt;a href="/straight/welcome">/straight/welcome&lt;/a>&lt;/li>&lt;li>&lt;a href="/straight/welcome/depth">/straight/welcome/depth&lt;/a>&lt;/li>&lt;/ul>&lt;/div>&lt;p class="footer">Page rendered in &lt;strong>0.0206&lt;/strong> seconds. CodeIgniter Version &lt;strong>3.1.8&lt;/strong>&lt;/p>&lt;/div>&lt;pre style="white-space: pre-wrap;">
Straight-layout
&lt;/pre>&lt;textarea name="desc">
Straight-layout
&lt;/textarea>&lt;script type="text/javascript>
// Straight-layout
console.log( 'Straight-layout' );
&lt;/script>&lt;script type='text/javascript' src='/asset/combine/8a982f02f422b7e90425eaf2b4a5a852.js'>&lt;/script>&lt;/body>&lt;/html>
</pre>