<?php

  require_once("globals.php");
  require_once("database.php");
  require_once("models/Movie.php");
  require_once("models/Message.php");
  require_once("models/dao/UserDAO.php");
  require_once("models/dao/MovieDAO.php");

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

  private $image;
  private $imageTypes;
  private $jpgArray;
  private $imageFile;

  public function __construct($conn, $BASE_URL) {
    $this->id = filter_input(INPUT_POST, "id");
    $this->type = filter_input(INPUT_POST, "type");
    $this->title = filter_input(INPUT_POST, "title");
    $this->description = filter_input(INPUT_POST, "description");
    $this->trailer = filter_input(INPUT_POST, "trailer");
    $this->category = filter_input(INPUT_POST, "category");
    $this->length = filter_input(INPUT_POST, "length");

    $this->movie = new Movie();
    $this->message = new Message($BASE_URL);

    $this->userDao = new UserDAO($conn, $BASE_URL);
    $this->userData = $this->userDao->verifyToken();
    $this->movieDao = new MovieDAO($conn, $BASE_URL);
    
    $this->image = $_FILES["image"];
    $this->imageTypes = ["image/jpeg", "image/jpg", "image/png"];
    $this->jpgArray = ["image/jpeg", "image/jpg"];
  }


  public function verifyFormsType() {
    if($this->type === "create") {
      $this->verifyInput();
      $this->verifyImageUpload();
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
        $this->verifyImageUpload();
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

  private function verifyImageUpload() {
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
      $this->verifyImageType();
    } 
  }

  private function verifyImageType(){
    if(in_array($this->image["type"], $this->imageTypes)) {

      if(in_array($this->image["type"], $this->jpgArray)) {
        $this->imageFile = imagecreatefromjpeg($this->image["tmp_name"]);
      } else {
        $this->imageFile = imagecreatefrompng($this->image["tmp_name"]);
      }

      $this->generateImageName();
    } else {
      $this->message->setMessage("Tipo inválido de imagem, insira .png ou .jpg!", "error", "back");
    } 
  }

  private function generateImageName() {
    $imageName = $this->movie->imageGenerateName();

    imagejpeg($this->imageFile, "./resources/img/movies/" . $imageName, 100);

    $this->movie->image = $imageName;
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