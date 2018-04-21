# straight-ci-layout #
- Using MY_Controller.php

## Apply CI version ##
- Codeigniter 2.2.6
- Codeigniter 3.1.7
- Codeigniter 3.1.8
- Version 2 will all be higher.

## Ex Controller ##
```php
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
```

## Ex View File ##
```
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
```

## Ex Output ##

```html
<html>
<head>
    ...
    <link rel='stylesheet' type='text/css' href='/asset/css/_layout.css' />
    <link rel='stylesheet' type='text/css' href='/asset/css/_skin.css' />
    <link rel='stylesheet' type='text/css' href='/asset/css/welcome_message.css' />
</head>
<body>
    
    <_layout.php>
        <_skin.php>
            ... welcome_message.php contents ...
        </_skin.php>
    </_layout.php>

    <script type='text/javascript' src='/asset/js/_layout.js'></script>
    <script type='text/javascript' src='/asset/js/_skin.js'></script>
    <script type='text/javascript' src='/asset/js/welcome_message.js'></script>
</body>
</html>
```

## Config ##
```php
$config['modules'] = CI_VERSION>="3.0.0"?array('layout'):array('straight_layout');

$config['asset_controller'] = 'asset';
$config['asset_hashkey'] = 'md5';
$config['asset_nocache_uri'] = TRUE;   // TRUE : /asset/css/style.css?_=abc...1234, FALSE : /asset/cas/style.css
$config['asset_combine'] = TRUE;    // combine : welcome_message.js(css), _skin.js(css), _layout.js(css)
$config['asset_minify_js'] = TRUE;  // require composer minify lib
$config['asset_minify_css'] = TRUE; // require composer minify lib

$config['adapter'] = ['adapter' => 'apc', 'backup' => 'file'];  // CI cache lib
$config['ttl'] = 2592000;	// 30 day

$config['view_skin'] = '_skin';
$config['view_layout'] = '_layout';
$config['view_minify'] = TRUE;
```

## minify, combine Output ##

```html
<html lang="en"><head><meta charset="utf-8"><title>Welcome to CodeIgniter</title><link rel='stylesheet' type='text/css' href='/asset/combine/c93667b961c1da6c29ac55d8e6b51e61.css' /></head><body> <div id="container"> <h1>Welcome to CodeIgniter!</h1><div id="body"><p>The page you are looking at is being generated dynamically by CodeIgniter.</p><p>If you would like to edit this page you'll find it located at:</p><code> application/views/welcome_message.css<br/> application/views/welcome_message.js<br/> application/views/welcome_message.php </code><p>The corresponding controller for this page is found at:</p><code>application/controllers/Welcome.php</code><p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p><p>CI_VERSION : 3.1.8 / ENVIRONMENT : development</p><p><a href="https://github.com/uncaose/straight-ci-layout" target="_blank">https://github.com/uncaose/straight-ci-layout</a></p><ul><li><a href="/straight/welcome">/straight/welcome</a></li><li><a href="/straight/welcome/depth">/straight/welcome/depth</a></li></ul></div><p class="footer">Page rendered in <strong>0.0206</strong> seconds. CodeIgniter Version <strong>3.1.8</strong></p></div><pre style="white-space: pre-wrap;">
Straight-layout
</pre><textarea name="desc">
Straight-layout
</textarea><script type="text/javascript>
// Straight-layout
console.log( 'Straight-layout' );
</script><script type='text/javascript' src='/asset/combine/8a982f02f422b7e90425eaf2b4a5a852.js'></script></body></html>
```