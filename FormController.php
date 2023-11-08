<?php

$__ROOT__ = __DIR__;

require_once($__ROOT__ . "/views/templates/header.php");
require($__ROOT__ . "/controllers/AuthController.php");
require($__ROOT__ . "/controllers/MovieController.php");

// AuthController
$authType = filter_input(INPUT_POST, "authType");
$name = filter_input(INPUT_POST, "name");
$lastname = filter_input(INPUT_POST, "lastname");
$email = filter_input(INPUT_POST, "email");
$password = filter_input(INPUT_POST, "password");
$password_confirmation = filter_input(INPUT_POST, "password_confirmation");

// MovieController
$movieType = filter_input(INPUT_POST, "movieType");
$title = filter_input(INPUT_POST, "title");
$description = filter_input(INPUT_POST, "description");
$trailer = filter_input(INPUT_POST, "trailer");
$category = filter_input(INPUT_POST, "category");
$length = filter_input(INPUT_POST, "length");
$id = filter_input(INPUT_POST, "id");

if($authType) {

    $authController = new AuthController($conn, $BASE_URL, $authType, $name, $lastname, $email, $password, $password_confirmation);
    $authController->verifyFormsType();

} else if ($movieType) {

    $movieController = new MovieController($conn, $BASE_URL, $id, $movieType, $title, $description, $trailer, $category, $length);
    $movieController->verifyFormsType();
}