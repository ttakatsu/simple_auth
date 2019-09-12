<?php


class Controller_Auth extends Controller_Template
{
	public $template = 'admin/template';

	public function before()
	{
		parent::before();

		$this->current_user = Model_Crud::forge();
//		$this->current_user->username = Auth::get_screen_name();
		$this->current_user->username = 'hoge';

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

		$this->template->title = 'Register';
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
}