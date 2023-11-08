<?php

$__ROOT__ = __DIR__;

require_once($__ROOT__ . "/views/templates/header.php");
require($__ROOT__ . "/controllers/AuthController.php");

$type = filter_input(INPUT_POST, "type");
$name = filter_input(INPUT_POST, "name");
$lastname = filter_input(INPUT_POST, "lastname");
$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");
$password_confirmation = filter_input(INPUT_POST, "password_confirmation");

$authController = new AuthController($conn, $BASE_URL, $type, $name, $lastname, $email, $password, $password_confirmation);
$authController->verifyFormsType();