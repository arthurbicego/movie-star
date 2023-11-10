<?php

class ImageController
{

  private $image;
  private $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
  private $jpgArray = ["image/jpeg", "image/jpg"];
  private $imageFile;
  private $message;

  public function __construct($image, $BASE_URL)
  {
    $this->image = $image;
    $this->message = new Message($BASE_URL);
  }

  public function verifyImageUpload()
  {
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
      var_dump($this->image);
      return $this->verifyImageType();
    }
  }

  private function verifyImageType()
  {
    if (in_array($this->image["type"], $this->imageTypes)) {

      if (in_array($this->image["type"], $this->jpgArray)) {
        $this->imageFile = imagecreatefromjpeg($this->image["tmp_name"]);
      } else {
        $this->imageFile = imagecreatefrompng($this->image["tmp_name"]);
      }
      return $this->generateImageName();
    } else {
      return $this->message->setMessage("Tipo inválido de imagem, insira .png ou .jpg!", "error", "back");
    }
  }

  private function generateImageName()
  {
    $imageName = bin2hex(random_bytes(60)) . ".jpg";

    imagejpeg($this->imageFile, "./resources/users/" . $imageName, 100);
    return $imageName;
    // if ($stringType === "user") {
    // } else if ($stringType === "movie") {
    //   imagejpeg($this->imageFile, "./resources/movies/" . $imageName, 100);
    //   return $imageName;
    // }
  }
}
