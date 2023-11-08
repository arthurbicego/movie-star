<?php

$__ROOT__ = dirname(__DIR__);

  require_once($__ROOT__ . "/globals.php");
  require_once($__ROOT__ . "/database.php");
  require_once($__ROOT__ . "/models/Movie.php");
  require_once($__ROOT__ . "/models/Message.php");
  require_once($__ROOT__ . "/models/dao/UserDAO.php");
  require_once($__ROOT__ . "/models/dao/MovieDAO.php");
  require_once($__ROOT__ . "/controllers/ImageController.php");

class MovieController {

  private $id;
  private $type;
  private $title;
  private $description;
  private $trailer;
  private $category;
  private $length;

  private $message;
  private $userDao;
  private $userData;

  private $movie;
  private $movieDao;

  private $imageController;

  public function __construct($conn, $BASE_URL, $id, $movieType, $title, $description, $trailer, $category, $length) {
    $this->id = $id;
    $this->type = $movieType;
    $this->title = $title;
    $this->description = $description;
    $this->trailer = $trailer;
    $this->category = $category;
    $this->length = $length;

    $this->movie = new Movie();
    $this->message = new Message($BASE_URL);

    $this->userDao = new UserDAO($conn, $BASE_URL);
    $this->userData = $this->userDao->verifyToken();
    $this->movieDao = new MovieDAO($conn, $BASE_URL);
    
    $this->imageController = new ImageController($_FILES["image"], $BASE_URL);
  }


  public function verifyFormsType() {
    if($this->type === "create") {
      $this->verifyInput();
      $this->imageController->verifyImageUpload();
      $this->movieDao->create($this->movie);

    } else if ($this->type === "delete") {
      if ($this->verifyMovieFound() && $this->verifyMovieUser()) {
        $this->movieDao->destroy($this->movie->id);
      } else {
        $this->message->setMessage("Informações inválidas!", "error", "index.php");
      }

    } else if ($this->type === "update") {
      if ($this->verifyMovieFound() && $this->verifyMovieUser()) {
        $this->verifyInput();
        $this->imageController->verifyImageUpload();
        $this->movieDao->update($this->movie);
      } else {
        $this->message->setMessage("Informações inválidas!", "error", "index.php");
      }
    }
  }

  // ---------------------------------------------------------------------------------------

  private function verifyInput(){
    if(!empty($this->title) && !empty($this->description) && !empty($this->category)) {
      $this->movie->title = $this->title;
      $this->movie->description = $this->description;
      $this->movie->trailer = $this->trailer;
      $this->movie->category = $this->category;
      $this->movie->length = $this->length;
      // I need to understand where this userData id comes from
      $this->movie->users_id = $this->userData->id;

    } else {
      $this->message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
    }
  }

  // ---------------------------------------------------------------------------------------
  
  private function verifyMovieFound() {
    if ($this->movieDao->findById($this->id)) {
      return true;
    } else {
      return false;
    }
  }
  
  private function verifyMovieUser(){
    if($this->movie->users_id === $this->userData->id) {
      return true;
      
    } else {
      return false;
    }
  }

}