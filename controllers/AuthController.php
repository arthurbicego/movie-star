<?php

$__ROOT__ = dirname(__DIR__);

require_once($__ROOT__ . "/globals.php");
require_once($__ROOT__ . "/database.php");
require_once($__ROOT__ . "/models/User.php");
require_once($__ROOT__ . "/models/Message.php");
require_once($__ROOT__ . "/models/dao/UserDAO.php");
require_once($__ROOT__ . "/controllers/PasswordController.php");

class AuthController
{
  private $authType;
  private $name;
  private $lastname;
  private $email;
  private $password;
  private $password_confirmation;

  private $message;
  private $userDao;
  private $passwordController;

  public function __construct($conn, $BASE_URL, $authType, $name, $lastname, $email, $password, $password_confirmation)
  {
    $this->authType = $authType;
    $this->name = $name;
    $this->lastname = $lastname;
    $this->email = $email;
    $this->password = $password;
    $this->password_confirmation = $password_confirmation;

    $this->userDao = new UserDAO($conn, $BASE_URL);
    $this->message = new Message($BASE_URL);
    $this->passwordController = new PasswordController();
  }

  public function verifyFormsType()
  {
    if ($this->authType === "register") {
      $this->verifyInput();
    } else if ($this->authType === "login") {
      if ($this->userDao->authenticateUser($this->email, $this->password)) {
        $this->message->setMessage("Seja bem-vindo!", "success", "views/editprofile.php");
      } else {
        $this->message->setMessage("Usuário e/ou senha incorretos.", "error", "back");
      }
    } else {
      $this->message->setMessage("Informações inválidas!", "error", "index.php");
    }
  }

  private function verifyInput()
  {
    if ($this->name && $this->lastname && $this->email && $this->password) {
      $this->verifyPassword();
    } else {
      $this->message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
  }

  private function verifyPassword()
  {
    if ($this->password === $this->password_confirmation) {
      $this->verifyEmailIsRegistered();
    } else {
      $this->message->setMessage("As senhas não são iguais.", "error", "back");
    }
  }

  private function verifyEmailIsRegistered()
  {
    if ($this->userDao->findByEmail($this->email) === false) {
      $user = new User();

      $userToken = bin2hex(random_bytes(50));
      $finalPassword = $this->passwordController->generatePassword($this->password);

      $user->name = $this->name;
      $user->lastname = $this->lastname;
      $user->email = $this->email;
      $user->password = $finalPassword;
      $user->token = $userToken;

      $auth = true;

      $this->userDao->create($user, $auth);
    } else {
      $this->message->setMessage("Usuário já cadastrado, tente outro e-mail.", "error", "back");
    }
  }
}
