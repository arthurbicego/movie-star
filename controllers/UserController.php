<?php

$__ROOT__ = dirname(__DIR__);

require_once($__ROOT__ . "/globals.php");
require_once($__ROOT__ . "/database.php");
require_once($__ROOT__ . "/models/User.php");
require_once($__ROOT__ . "/models/Message.php");
require_once($__ROOT__ . "/models/dao/UserDAO.php");
require_once($__ROOT__ . "/controllers/ImageController.php");

class UserController
{

  private $type;

  private $user;
  private $userDao;
  private $userData;
  private $id;
  private $name;
  private $lastname;
  private $email;
  private $bio;

  private $message;

  private $password;
  private $password_confirmation;
  private $finalPassword;

  private $imageController;
  private $passwordController;

  private $stringType;

  public function __construct($conn, $BASE_URL, $userType, $name, $lastname, $email, $bio, $password, $password_confirmation)
  {

    $this->user = new User();
    $this->userDao = new UserDAO($conn, $BASE_URL);
    $this->userData = $this->userDao->verifyToken();
    $this->id = $this->userData->id;
    $this->type = $userType;
    $this->name = $name;
    $this->lastname = $lastname;
    $this->email = $email;
    $this->bio = $bio;
    $this->password = $password;
    $this->password_confirmation = $password_confirmation;

    $this->message = new Message($BASE_URL);
    $this->finalPassword;

    $this->imageController = new ImageController($_FILES["image"], $BASE_URL);
    $this->passwordController = new PasswordController($conn, $BASE_URL);

    $this->stringType = "user";
  }

  public function verifyFormsType()
  {
    if ($this->type === "update") {
      $this->updateUserData();
      $this->userData->image = $this->imageController->verifyImageUpload($this->stringType);
      $this->userDao->update($this->userData);
    } else if ($this->type === "changepassword") {
      if ($this->password === $this->password_confirmation) {
        $this->finalPassword = $this->passwordController->generatePassword($this->password);
        $this->user->password = $this->finalPassword;
        $this->user->id = $this->id;
        $this->userDao->changePassword($this->user);
      } else {
        $this->message->setMessage("As senhas não são iguais!", "error", "back");
      }
    } else {
      $this->message->setMessage("Informações inválidas!", "error", "index.php");
    }
  }

  private function updateUserData()
  {
    $this->userData->name = $this->name;
    $this->userData->lastname = $this->lastname;
    $this->userData->email = $this->email;
    $this->userData->bio = $this->bio;
  }
}
