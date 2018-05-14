	<h1>Welcome to CodeIgniter!</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code>
        application/views/welcome_message.css<br/>
        application/views/welcome_message.js<br/>
        application/views/welcome_message.php
        </code>

		<p>The corresponding controller for this page is found at:</p>
		<code>application/controllers/Welcome.php</code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>

		<p>
			CI_VERSION : <?php echo CI_VERSION?> / ENVIRONMENT : <?php echo ENVIRONMENT?>
		</p>
		<p>
			<a  class="btn btn-sm btn-primary" href="https://github.com/uncaose/straight-ci-layout" target="_blank">https://github.com/uncaose/straight-ci-layout</a>
		</p>

		<ul>
			<li><a href="/straight/welcome">/straight/welcome</a></li>
			<li><a href="/straight/welcome/depth">/straight/welcome/depth</a></li>
		</ul>
	</div>
	
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>

    <?php $this->load
                ->css('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css')
                ->js([
                    ['src'=>'https://code.jquery.com/jquery-3.2.1.slim.min.js', 'integrity'=>'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN','crossorigin'=>'anonymous'],
                    'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js',
                ])->js([
                    'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js'
                ]); ?>