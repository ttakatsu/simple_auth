<?php


class Controller_Admin extends Controller_Template
{
	public $template = 'admin/template';

	/**
	 * from Controller Base
	 */
	public function before()
	{
		parent::before();

		$this->current_user = null;

//		foreach (Auth::verified() as $driver)
//		{
//			if (($id = $driver->get_user_id()) !== false)
//			{
//				$this->current_user = Model_User::find($id[1]);
//			}
//			Log::debug('id: '.$id, __METHOD__);
//			break;
//		}
//		Log::debug('count verified:'.count(Auth::verified()), __METHOD__);

		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
	}

	/**
	 * auth_app/fuel/packages/oil/views/admin/crud/controllers/admin.php
	 */
	public function action_login()
	{
		// Already logged in
		//Auth::check() and Response::redirect('admin');

		$val = Validation::forge();

		if (Input::method() === 'POST') {
			$val->add('email', 'Email or Username')
				->add_rule('required');
			$val->add('password', 'Password')
				->add_rule('required');

			if ($val->run()) {
				if ( ! Auth::check()) {
					if (Auth::login(Input::post('email'), Input::post('password'))) {
						// assign the user id that lasted updated this record
						foreach (Auth::verified() as $driver)
						{
							if (($id = $driver->get_user_id()) !== false)
							{
								// credentials ok, go right in
//								$current_user = Model_User::find($id[1]);
								$user_name = Auth::get_screen_name();
								Session::set_flash('success', e('Welcome, '.$user_name));
								Response::redirect('admin');
							}
						}
					}
					$this->template->set_global('login_error', 'Login failed');
				} else {
					$this->template->set_global('login_error', 'Already logged in!');
				}
			}
		}

		$this->template->title = 'Login';
		$this->template->content = View::forge('admin/login', ['val' => $val], false);
	}

	/**
	 * The logout action.
	 */
	public function action_logout()
	{
		Auth::logout();
		Session::set_flash('success', 'Logout');
		Response::redirect('admin');
	}

	/**
	 * The index action.
	 */
	public function action_index()
	{
		$this->template->title = 'Dashboard';
		$this->template->content = View::forge('admin/dashboard');
	}

	public function action_info()
	{
		$this->template = '';
		phpinfo();
	}
}