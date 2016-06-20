# straight-ci-layout #
- Using MY_Controller.php

## Apply CI version ##
- Codeigniter 2.2.6
- Codeigniter 3.0.6
- Version 2 will all be higher.

## Controller ##
<pre>
// application/core/MY_Controller Extends
Class Welcome extends MY_Controller {
    public function index()
    {
        // default skin, layout setting
        // $this->load->setSkin('_skin')->setLayout('_layout');
        $this->load->view('welcome_message');
    }
    public function today()
    {
        $this->load->view('welcome/today');
    }
}
</pre>

## Source File ##

<pre>
application/views/
    _layout.php                // default layout auto wrap
    _skin.php                // default skin auto wrap
    welcome_message.css        // isset {view}.css auto asset
    welcome_message.js        // isset {view}.js auto asset
    welcome_message.php
</pre>

## Output ##

<pre>
&lt;html>
&lt;head>
    ...
    &lt;link rel='stylesheet' type='text/css' href='/asset/welcome_message.css?_=b7ea82de66456fa21a534be873010e57' />
&lt;/head>
&lt;body>
    ...
    &lt;script type='text/javascript' src='/asset/welcome_message.js?_=0adebfced0f5c2cfece1dbcbaaaa53b0'>&lt;/script>
&lt;/body>
&lt;/html>
</pre>