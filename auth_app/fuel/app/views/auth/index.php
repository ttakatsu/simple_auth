<div>
	Auth<br>
	is_login: <?= $auth['is_login'] ? 'true':'false' ?><br>
	instance_id: <?= $auth['instance_id'] ?><br>
	user_id: <?= $auth['user_id'] ?><br>

	<div>
	<?php Debug::dump(Auth::instance()); ?>
	</div>
	<div>
		<?php Debug::dump($auth['session_data']); ?>
	</div>

</div>