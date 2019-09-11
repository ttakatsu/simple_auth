<?php


class Controller_Auth extends Controller
{
	public function action_register()
	{
		if (Input::method() == 'POST') {
			// AuthのCreate_User
			try {
				$created = Auth::create_user();
			} catch (SimpleUserUpdateException $e) {
				$e->getCode();
			}
		}

		return View::forge('auth/registration');
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