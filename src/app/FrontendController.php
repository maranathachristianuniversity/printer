<?php
/**
 * Created by PhpStorm.
 * User: Didit Velliz
 * Date: 7/13/2016
 * Time: 8:41 AM
 */

namespace anywhere\app;


use anywhere\engine\AnywhereController;

class FrontendController extends AnywhereController
{

    public function main()
    {
        $this->view('main', array('nama' => 'didit'));
    }

    public function login(){
        echo 'login';
    }
}