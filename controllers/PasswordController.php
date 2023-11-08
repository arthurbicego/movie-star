<?php

class PasswordController
{

  public function generatePassword($password)
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }
}
