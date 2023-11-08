<?php

$__ROOT__ = dirname(__DIR__);

require_once($__ROOT__ . "globals.php");
require_once($__ROOT__ . "db.php");
require_once($__ROOT__ . "models/Movie.php");
require_once($__ROOT__ . "models/Review.php");
require_once($__ROOT__ . "models/Message.php");
require_once($__ROOT__ . "models/dao/UserDAO.php");
require_once($__ROOT__ . "models/dao/MovieDAO.php");
require_once($__ROOT__ . "models/dao/ReviewDAO.php");

class ReviewController
{

  private $id;
  private $message;
  private $userDao;
  private $movieDao;
  private $reviewDao;
  private $reviewObject;
  private $type;
  private $userData;
  private $rating;
  private $review;
  private $movies_id;
  private $users_id;


  public function __construct($conn, $BASE_URL, $url, $reviewType, $rating, $review, $movies_id)
  {
    $this->message = new Message($url);
    $this->userDao = new UserDAO($conn, $BASE_URL);
    $this->movieDao = new MovieDAO($conn, $BASE_URL);
    $this->reviewDao = new ReviewDAO($conn, $BASE_URL);
    $this->reviewObject = new Review();
    $this->userData = $this->userDao->verifyToken();
    $this->type = $reviewType;
    $this->rating = $rating;
    $this->review = $review;
    $this->movies_id = $movies_id;
    $this->users_id = $this->userData->id;
  }

  public function verifyFormsType()
  {
    if ($this->type === "create") {
      if ($this->verifyMovieFound()) {
        $this->verifyInput();
        $this->reviewDao->create($this->reviewObject);
      } else {
        $this->message->setMessage("Informações inválidas!", "error", "index.php");
      }
    } else {
      $this->message->setMessage("Informações inválidas!", "error", "index.php");
    }
  }

  private function verifyInput()
  {
    if (!empty($this->rating) && !empty($this->review) && !empty($this->movies_id)) {
      $this->reviewObject->rating = $this->rating;
      $this->reviewObject->review = $this->review;
      $this->reviewObject->movies_id = $this->movies_id;
      $this->reviewObject->users_id = $this->users_id;
    }
  }


  private function verifyMovieFound()
  {
    if ($this->movieDao->findById($this->movies_id)) {
      return true;
    } else {
      return false;
    }
  }
}
