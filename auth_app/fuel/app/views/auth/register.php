<?php
/** @var $val Validation */
?>
<div class="row">
	<div class="col-md-3">
		<?= Form::open() ?>

		<?php if (isset($register_error)): ?>
			<div class="alert alert-danger" role="alert"><?php echo $register_error; ?></div>
		<?php endif; ?>

		<div class="form-group <?= ! $val->error('username') ?: 'has-error' ?>">
			<label for="username">Username:</label>
			<?= Form::input('username', Input::post('username'), ['class' => 'form-control', 'placeholder' => 'Username', 'autofocus']) ?>

			<?php if ($val->error('username')) : ?>
				<span class="control-label"><?= $val->error('username')->get_message('Y') ?></span>
			<?php endif; ?>
		</div>

		<div class="form-group <?= ! $val->error('password') ?: 'has-error' ?>">
			<label for="username">Password:</label>
			<?= Form::input('password', Input::post('password'), ['class' => 'form-control', 'placeholder' => 'Password']) ?>

			<?php if ($val->error('password')) : ?>
				<span class="control-label"><?= $val->error('password')->get_message(':label cannot be blank') ?></span>
			<?php endif; ?>
		</div>

		<div class="form-group">
			<label for="username">Email:</label>
			<?= Form::input('email', Input::post('email'), ['class' => 'form-control', 'placeholder' => 'Email address']) ?>

			<?php if ($val->error('email')) : ?>
				<span class="control-label"><?= $val->error('email')->get_message('P') ?></span>
			<?php endif; ?>
		</div>

		<div class="actions">
			<?= Form::submit(['value' => 'Register', 'name' => 'submit', 'class' => 'btn btn-lg btn-primary btn-block']) ?>
		</div>

		<?= Form::close() ?>
	</div>
</div>
