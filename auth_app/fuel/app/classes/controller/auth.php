<?php

/**
 * Class Controller_Auth
 */
class Controller_Auth extends Controller_Template
{
	public $template = 'admin/template';

	public function before()
	{
		parent::before();

		$this->current_user = Model_Crud::forge();
		$this->current_user->username = Auth::get_screen_name();

		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
	}

	public function action_register()
	{
		$val = Validation::forge();

		if (Input::method() === 'POST') {
			$val->add('username')->add_rule('required');
			$val->add('password')->set_label('パスワード')->add_rule('required');
			$val->add('email');

			if ($val->run()) {

				// AuthのCreate_User
				try {
					$created = Auth::create_user(Input::post('username'), Input::post('password'), Input::post('email'));

					// ユーザーが正常に作成された
					if ($created) {
						Session::set_flash('success', e('Register success.'));
//						Response::redirect_back('admin');
						Response::redirect('admin');
					} else {
						$this->template->set_global('register_error', 'Create user failed');
					}

				} catch (SimpleUserUpdateException $e) {
					if ($e->getCode() === 2) {
						// メールアドレスが重複
						$this->template->set_global('register_error', $e->getMessage());
					} else if ($e->getCode() === 3) {
						// ユーザー名が重複
						$this->template->set_global('register_error', $e->getMessage());
					} else {
						// 起こり得ないがそれ以外のエラー
						$this->template->set_global('register_error', $e->getMessage());
					}
				}
			}
		}

		$this->template->title = 'Auth/Register';
		$this->template->content = View::forge('auth/register')->set('val', $val, false);
	}

	/* create_user サンプル
			// create the administrator account if needed, and assign it the superadmin group so it has all access
			$result = \DB::select('id')->from($table)->where('username', '=', 'admin')->execute($connection);
			if (count($result) == 0)
			{
				\Auth::instance()->create_user('admin', 'admin', 'admin@example.org', $group_id_admin, array('fullname' => 'System administrator'));
			}
	 */

	/**
	 * index action
	 */
	public function action_index()
	{
		$auth = [
			'is_login' => Auth::check(),
		];

		$instance_id = Auth::get_id();
		$auth['instance_id'] = $instance_id;

		// ログインしているユーザーID
		$id_info = Auth::get_user_id();
		$auth['user_id'] = var_export($id_info, true);

		// Session
		$session_data = Session::get();
		$auth['session_data'] = $session_data;


		$this->template->title = 'Auth';
		$this->template->content = View::forge('auth/index')->set('auth', $auth);
	}

	public function action_session()
	{



		$this->template->title = 'Auth/session';
		$this->template->content = 'Session start!';
	}
}