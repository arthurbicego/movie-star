<?php

$__ROOT__ = dirname(__DIR__);

require_once($__ROOT__ . "/views/templates/header.php");
require($__ROOT__ . "/controllers/AuthController.php");
require($__ROOT__ . "/controllers/MovieController.php");
require($__ROOT__ . "/controllers/UserController.php");
require($__ROOT__ . "/controllers/ReviewController.php");

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

// UserController
$userType = filter_input(INPUT_POST, "userType");
// $name = filter_input(INPUT_POST, "name");
// $lastname = filter_input(INPUT_POST, "lastname");
// $email = filter_input(INPUT_POST, "email");
$bio = filter_input(INPUT_POST, "bio");
// $password = filter_input(INPUT_POST, "password");
// $password_confirmation = filter_input(INPUT_POST, "password_confirmation");

// ReviewController
$reviewType = filter_input(INPUT_POST, "reviewType");
$rating = filter_input(INPUT_POST, "rating");
$review = filter_input(INPUT_POST, "review");
$movies_id = filter_input(INPUT_POST, "movies_id");

if ($authType) {
    $authController = new AuthController($conn, $BASE_URL, $authType, $name, $lastname, $email, $password, $password_confirmation);
    $authController->verifyFormsType();
    $authType = false;
} else if ($movieType) {
    $movieController = new MovieController($conn, $BASE_URL, $id, $movieType, $title, $description, $trailer, $category, $length);
    $movieController->verifyFormsType();
    $movieType = false;
} else if ($userType) {
    $userController = new UserController($conn, $BASE_URL, $userType, $name, $lastname, $email, $bio, $password, $password_confirmation);
    $userController->verifyFormsType();
    $userType = false;
} else if ($reviewType) {
    $reviewController = new ReviewController($conn, $BASE_URL, $url, $reviewType, $rating, $rating, $movies_id);
    $reviewController->verifyFormsType();
    $reviewType = false;
}
