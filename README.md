# straight-ci-layout #
- Using MY_Controller.php

## Apply CI version ##
- Codeigniter 2.2.6
- Codeigniter 3.1.7
- Version 2 will all be higher.

## Ex Controller ##
<h2>controllers/Welcome.php</h2>
<pre>
Class Welcome extends MY_Controller
{
    public function index() {
        // default skin, layout setting
        // $this->load->skin('_skin')->layout('_layout');
        $this->load->view('welcome_message');
    }

    public function today() {
        $this->load->view('welcome/today');
    }
}
</pre>
<h2>controllers/straight/Welcome.php</h2>
<pre>
class Welcome extends MY_Controller
{
    public function index()
    {
        $this->load->view('straight/welcome');
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

## layout, skin ##
<pre>
views/
    _layout.php
    _skin.php
views/straight/
        _layout.php
        _skin.php
        welcome.php

load : straight/welcome.php
load : straight/_skin.php
load : straight/_layout.php
</pre>

<pre>
views/
    _layout.php
    _skin.php
views/straight/
        welcome.php

load : straight/welcome.php
load : _skin.php
load : _layout.php
</pre>

<pre>
views/
    _layout.php
    _skin.php
views/straight/
        _layout_another.php
        _layout.php
        _skin.php
        welcome.php
            &lt;?php $this->load->skin('_skin')->set('straight/_layout_another'); ?&gt;

load : straight/welcome.php
load : _skin.php
load : straight/_layout_another.php
</pre>