<?php $this->load->layout('straight/_layout_another')?>

<h2>Default</h2>
<h3>same skin, layout</h3>
<pre class="brush: php">
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
<h3>none skin, layout</h3>
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

<h2>set skin</h2>
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
