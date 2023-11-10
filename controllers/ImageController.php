<?php

class ImageController
{

  private $image;
  private $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
  private $jpgArray = ["image/jpeg", "image/jpg"];
  private $imageFile;
  private $message;

  public function __construct($BASE_URL)
  {
    $this->image = $_FILES["image"];
    $this->message = new Message($BASE_URL);
  }

  public function verifyImageUpload($stringType)
  {
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
      var_dump($this->image);
      return $this->verifyImageType($stringType);
    }
  }

  private function verifyImageType($stringType)
  {
    if (in_array($this->image["type"], $this->imageTypes)) {

      if (in_array($this->image["type"], $this->jpgArray)) {
        $this->imageFile = imagecreatefromjpeg($this->image["tmp_name"]);
      } else {
        $this->imageFile = imagecreatefrompng($this->image["tmp_name"]);
      }
      return $this->generateImageName($stringType);
    } else {
      return $this->message->setMessage("Tipo inválido de imagem, insira .png ou .jpg!", "error", "back");
    }
  }

  private function generateImageName($stringType)
  {
    $imageName = bin2hex(random_bytes(60)) . ".jpg";

    if ($stringType === "user") {
      imagejpeg($this->imageFile, "../resources/users/" . $imageName, 100);
      return $imageName;
    } else if ($stringType === "movie") {
      imagejpeg($this->imageFile, "../resources/movies/" . $imageName, 100);
      return $imageName;
    }
  }
}
