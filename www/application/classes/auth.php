<?php defined('SYSPATH') or die('No direct script access.');

class Auth extends Base_Auth {

    public function authorize(&$user)
    {
        Language::set(Model_Language::find($user->language_id));
        parent::authorize($user);
    }

    public function is_admin()
    {
        return $this->has_role('admin');
    }

    public function has_role($role_name)
    {
        if ( ! $this->logged_in())
            return FALSE;

        try
        {
            $role = Model_User_Role::find(array(
                'name' => $role_name,
            ));
            $role_id = $role->id;
        }
        catch (Exception $e)
        {
            return FALSE;
        }

        if ($this->current_user()->role_id == $role_id)
            return TRUE;

        return FALSE;
    }

}
