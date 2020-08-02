<?php

namespace plugins\auth;

use model\UserModel;
use satframework\auth\Auth;
use satframework\auth\SatAuth;

/**
 * Class AnywhereAuthenticator
 * @package plugins\auth
 */
class AnywhereAuthenticator implements Auth
{

    /**
     * @var AnywhereAuthenticator
     */
    static $authenticator;

    public static function Instance()
    {
        if (!self::$authenticator instanceof AnywhereAuthenticator) {
            self::$authenticator = new AnywhereAuthenticator();
        }
        return self::$authenticator;
    }

    public function Login($username, $password)
    {
        $loginResult = UserModel::GetUser($username, $password);
        $uid = (isset($loginResult[0]['ID'])) ? $loginResult[0]['ID'] : null;

        return new SatAuth($uid, array());
    }

    public function Logout()
    {
    }

    public function GetLoginData($id, $permission)
    {
        return UserModel::GetUserById($id)[0];
    }
}