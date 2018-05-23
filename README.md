# straight-ci-layout #
- Using MY_Controller.php

## Apply CI version ##
- <s>Codeigniter 2.2.6</s> : [CI3/CI2 straight-ci-layout v1.1](https://github.com/uncaose/straight-ci-layout/releases/tag/v1.1)
- Codeigniter 3.1.7
- Codeigniter 3.1.8

## Method ##
```php
$this->load->css('any.css'); // chainning
$this->load->js('any.js');  // chainning
$this->load->view('any', []);   // CI3 default
$this->load->view('any', [], TRUE);   // CI3 default, non chain
$this->load->css()->js()->view()->view()->css()->js()->view();    // usage
```

## Config ##
```php
$config['modules'] = ['layout'];
$config['asset_controller'] = 'asset';
$config['asset_hashkey'] = 'md5';
$config['asset_nocache_uri'] = TRUE;   // TRUE : /asset/css/style.css?_=abc...1234, FALSE : /asset/cas/style.css
$config['asset_combine'] = TRUE;    // TRUE|FALSE css, js combine, TRUE: use cache if possible or query
$config['asset_minify_js'] = TRUE;  // require composer minify lib
$config['asset_minify_css'] = TRUE; // require composer minify lib

$config['adapter'] =  ['adapter' => 'dummy', 'backup' => 'file'];  // CI cache lib, default dummy
$config['ttl'] = 2592000;	// 30 day

$config['view_skin'] = '_skin';
$config['view_layout'] = '_layout';
$config['view_minify'] = TRUE;
```

## Ex Controller ##
```php
Class Welcome extends MY_Controller
{
    public function index() {
        $this->load->css('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css')
                ->js([
                    ['src'=>'https://code.jquery.com/jquery-3.2.1.slim.min.js', 'integrity'=>'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN','crossorigin'=>'anonymous'],
                    'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
                ])->js([
                    'src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" crossorigin="anonymous"'
                ])->_view('welcome_head')
                ->_view('welcome_message')
                ->_view('welcome_foot');
    }

    // Ex
    public function depth()
    {
        $this->load->view('celcome_message');
    }
}
```

## Ex Output ##

```html
<html>
<head>
    ...
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" />
    <link rel='stylesheet' type='text/css' href='/asset/css/_layout.css' />
    <link rel='stylesheet' type='text/css' href='/asset/css/_skin.css' />
    <link rel='stylesheet' type='text/css' href='/asset/css/welcome_head.css' />
    <link rel='stylesheet' type='text/css' href='/asset/css/welcome_message.css' />
</head>
<body>
    
    <_layout.php>
        <_skin.php>
            <welcome_head.php>
            <welcome_message.php>
            <welcome_foot.php>
        </_skin.php>
    </_layout.php>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script type='text/javascript' src='/asset/js/_layout.js'></script>
    <script type='text/javascript' src='/asset/js/_skin.js'></script>
    <script type='text/javascript' src='/asset/js/welcome_message.js'></script>
    <script type='text/javascript' src='/asset/js/welcome_foot.js'></script>
</body>
</html>
```
