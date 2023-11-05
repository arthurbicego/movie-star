<?php
  require_once("globals.php");
  require_once("database.php");
  require_once("models/User.php");
  require_once("models/Message.php");
  require_once("dao/UserDAO.php");

class AuthController {

  private $type;
  private $name;
  private $lastname;
  private $email;
  private $password;
  private $password_confirmation;
  private $message;
  private $userDao;

  public function __construct($conn, $BASE_URL) {
    $this->type = filter_input(INPUT_POST, "type");
    $this->name = filter_input(INPUT_POST, "name");
    $this->lastname = filter_input(INPUT_POST, "lastname");
    $this->email = filter_input(INPUT_POST, "email");
    $this->password = filter_input(INPUT_POST, "password");
    $this->password_confirmation = filter_input(INPUT_POST, "password_confirmation");
    $this->message = new Message($BASE_URL);
    $this->userDao = new UserDAO($conn, $BASE_URL);
  }

  public function verifyFormsType() {
    if($this->type === "register") {
      $this->verifyInput();

    } else if ($this->type === "login") {
      if($this->userDao->authenticateUser($this->email, $this->password)) {
        $this->message->setMessage("Seja bem-vindo!", "success", "editprofile.php");
      } else {
        $this->message->setMessage("Usuário e/ou senha incorretos.", "error", "back");
      }

    } else {
      $this->message->setMessage("Informações inválidas!", "error", "index.php");
    }
  }

  private function verifyInput() {
    if ($this->name && $this->lastname && $this->email && $this->password) {
    $this->verifyPassword();
    } else {
      $this->message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
  }

  private function verifyPassword() {
    if ($this->password === $this->password_confirmation) {
      $this->verifyEmailIsRegistered();
    } else {
      $this->message->setMessage("As senhas não são iguais.", "error", "back");
    }
  }

  private function verifyEmailIsRegistered(){
    if ($this->userDao->findByEmail($this->email) === false) {
      $user = new User();

      $userToken = $user->generateToken();
      $finalPassword = $user->generatePassword($this->password);

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