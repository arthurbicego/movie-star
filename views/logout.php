<?php

$__ROOT__ = dirname(__DIR__);
  require_once($__ROOT__ . "/views/templates/header.php");

  if($userDao) {
    $userDao->destroyToken();
  }