<?php

$__ROOT__ = dirname(__DIR__);

require_once($__ROOT__ . "/globals.php");
require_once($__ROOT__ . "/database.php");
require_once($__ROOT__ . "/models/Movie.php");
require_once($__ROOT__ . "/models/Message.php");
require_once($__ROOT__ . "/models/dao/UserDAO.php");
require_once($__ROOT__ . "/models/dao/MovieDAO.php");
require_once($__ROOT__ . "/controllers/ImageController.php");

class MovieController
{

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

  private $stringType;

  private $dump;

  public function __construct($conn, $BASE_URL, $id, $movieType, $title, $description, $trailer, $category, $length)
  {
    $this->message = new Message($BASE_URL);
    $this->imageController = new ImageController($BASE_URL);

    $this->movie = new Movie();
    $this->movieDao = new MovieDAO($conn, $BASE_URL);

    $this->userDao = new UserDAO($conn, $BASE_URL);
    $this->userData = $this->userDao->verifyToken();

    $this->id = $id;
    $this->type = $movieType;
    $this->title = $title;
    $this->description = $description;
    $this->trailer = $trailer;
    $this->category = $category;
    $this->length = $length;
    $this->movie->users_id = $this->userData->id;

    $this->stringType = "movie";
  }


  public function verifyFormsType()
  {
    if ($this->type === "create" && $this->verifyInput()) {
      $this->movie->image = $this->imageController->verifyImageUpload($this->stringType);
      $this->movieDao->create($this->movie);
    } else if ($this->type === "delete") {
      if ($this->verifyMovieFound() && $this->verifyMovieUser()) {
        $this->movieDao->destroy($this->id);
      } else {
        $this->message->setMessage("Informações inválidas!", "error", "index.php");
      }
    } else if ($this->type === "update" && $this->verifyInput()) {
      if ($this->verifyMovieFound() && $this->verifyMovieUser()) {
        if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
          $this->movie->image = $this->imageController->verifyImageUpload($this->stringType);
        } else {
          $this->movie->image = $this->movieDao->findById($this->id)->image;
        }
        $this->movieDao->update($this->movie);
      } else {
        $this->message->setMessage("Informações inválidas!", "error", "index.php");
      }
    }
  }

  // ---------------------------------------------------------------------------------------

  private function verifyInput()
  {
    if (!empty($this->title) && !empty($this->description) && !empty($this->category)) {
      $this->movie->id = $this->id;
      $this->movie->title = $this->title;
      $this->movie->description = $this->description;
      $this->movie->trailer = $this->trailer;
      $this->movie->category = $this->category;
      $this->movie->length = $this->length;
      $this->movie->users_id = $this->userData->id;
      return true;
    } else {
      $this->message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
      return false;
    }
  }

  // ---------------------------------------------------------------------------------------

  private function verifyMovieFound()
  {
    if ($this->movieDao->findById($this->id)) {
      $this->dump = $this->movieDao->findById($this->id);
      return true;
    } else {
      return false;
    }
  }

  private function verifyMovieUser()
  {
    if ($this->dump->users_id === $this->userData->id) {
      return true;
    } else {
      return false;
    }
  }
}
