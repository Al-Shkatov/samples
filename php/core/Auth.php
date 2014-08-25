<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author Administrator
 */
class Auth
{

    private static $user;
    public static $_admins = array('admin', 'moderator');

    public static function setUser($user)
    {
        self::$user = $user;
    }

    public static function getUser()
    {
        $session = Session::getInstance('user');

        if ($session->has('id') && empty(self::$user)) {
            $userModel = Loader::loadModel('modules/user/model/User');
            self::setUser($userModel->getUser($session->id));
        } elseif (!$session->has('id')) {
            $guest = new stdClass();
            $guest->role = 'guest';
            $guest->status = '1';
            self::setUser($guest);
        }

        return self::$user;
    }

    public static function getRole()
    {
        return self::getUser()->role;
    }

    public static function login($user)
    {
        self::setUser($user);
        if (isset($user->id)) {
            Session::getInstance('user')->id = $user->id;
            Session::getInstance('user')->role = $user->role;
        }
    }

    public static function logout()
    {
        Session::getInstance('user')->id = null;
        self::setUser(null);
    }

    public static function isAdmin()
    {
        $user = self::getUser();
        if (!empty($user) && $user->status) {
            return in_array($user->role, self::$_admins);
        } else {
            self::logout();
            return false;
        }
    }

    public static function isLogined()
    {
        $user = self::getUser();
        if (!empty($user) && $user->status && $user->role != 'guest') {
            return true;
        } else {
            self::logout();
            return false;
        }
    }

}

?>
