<?php

require_once './app/controllers/ApiController.php';
require_once './app/models/UserModel.php';
require_once './app/helpers/AuthApiHelper.php';

class UserApiController extends ApiController{

    private $model;
    private $authApiHelper;

    function __construct(){
        parent::__construct();
        $this->model = new UserModel();
        $this->authApiHelper = new AuthApiHelper();
    }

    function getToken($params = []){
        $basic = $this->authApiHelper->getAuthHeaders(); //nos brinda el header authorization

        if(empty($basic)){
            $this->view->response('No envio encabezados de autenticacion', 401);//falta en la apiview el 401
            return;
        }

        $basic = explode(" ", $basic); //esto va a hacer un arreglo ["Basic", "base64 (usr:pass)"]

        if($basic[0]!="Basic"){
            $this->view->response('Los encabezados de autenticacion son incorrectos', 401);//falta en la apiview el 401
            return;
        }

        $userpass = base64_decode($basic[1]); // usr:pass
        $userpass = explode(":", $userpass); // ["usr", "pass"]

        $username = $userpass[0];
        $password = $userpass[1];

        $user = $this->model->getUserByUsername($username);

        if($user && password_verify($password, $user->password)){
            $token = $this->authApiHelper->createToken($user);
            $this->view->response($token, 200);
        }else {
            $this->view->response('El nombre de usuario o contraseña son incorrectos', 401);//falta en la apiview el 401
        }
    }

}
