<?php
/**
 * Printer
 *
 * This content is released under the Apache License Version 2.0, January 2004
 * https://www.apache.org/licenses/LICENSE-2.0
 *
 * Copyright (c) 2016, Didit Velliz
 *
 * @author	Didit Velliz
 * @since	Version 1.0.0
 *
 */

namespace controller;

use plugins\auth\AnywhereAuthenticator;
use plugins\controller\AnywhereView;
use satframework\auth\Session;

/**
 * Class guide
 * @package controller
 *
 * #Master master.html
 * #Value PageTitle Guide
 */
class guide extends AnywhereView
{

    /**
     * #Value PageTitle Manual
     * @throws \Exception
     */
    public function main()
    {
        if (Session::Is()) {
            $data['IsSessionBlock'] = Session::Get(AnywhereAuthenticator::Instance())->GetLoginData();
        } else {
            $data['IsLoginBlock'] = array(
                'login' => false
            );
        }
        return $data;
    }

    /**
     * #Value PageTitle SAT Template Engine
     */
    public function pte()
    {
    }
}