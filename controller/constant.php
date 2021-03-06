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

use Exception;
use model\ConstantaModel;
use plugins\auth\AnywhereAuthenticator;
use plugins\controller\AnywhereView;
use plugins\model\constanta;
use satframework\auth\Session;
use satframework\Framework;
use satframework\Request;

/**
 * #ClearOutput false
 * #Master master.html
 * #Value PageTitle Constant Editor
 */
class constant extends AnywhereView
{

    /**
     * @throws \Exception
     */
    public function manage()
    {
        $session = Session::Get(AnywhereAuthenticator::Instance())->GetLoginData();
        if (!isset($session['ID'])) {
            $this->RedirectTo(Framework::$factory->getBase());
        }

        $data = [];

        switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
            case 'POST':

                $id = Request::Post('constID', '');
                $key = Request::Post('key', '');

                $val = Request::Post('val', '');
                if ($val === '') {
                    throw new Exception('val required!');
                }
                $action = Request::Post('action', '');

                if ($action === 'Save') {
                    if ($key === '') {
                        throw new Exception('key required!');
                    }

                    $exists = ConstantaModel::IsKeyExists($session['ID'], $key);
                    if ($exists) {
                        throw new Exception("Global variable dengan nama {$key} sudah diinput sebelumnya.");
                    }
                    $constanta = new constanta();
                    $constanta->userID = $session['ID'];
                    $constanta->uniquekey = $key;
                    $constanta->constantaval = $val;
                    $constanta->save();
                }
                if ($action === 'Update') {
                    if ($id === '') {
                        throw new Exception('id required!');
                    }

                    $constanta = new constanta($id);
                    $constanta->userID = $session['ID'];
                    $constanta->uniquekey = $key;
                    $constanta->constantaval = $val;
                    $constanta->modify();
                }
                /*
                if ($action === 'Delete') {

                }
                */

                break;
            case 'GET':

                break;
            default:
                break;
        }

        $data['constanta'] = ConstantaModel::GetCollection($session['ID']);
        $data['total'] = sizeof($data['constanta']);

        return $data;
    }

}