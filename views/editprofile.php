<?php

$__ROOT__ = dirname(__DIR__);
require_once($__ROOT__ . "/views/templates/header.php");

require_once($__ROOT__ . "/models/dao/UserDAO.php");
require_once($__ROOT__ . "/controllers/FormController.php");

$user = new User();
$userDao = new UserDao($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);

$fullName = $user->getFullName($userData);

if ($userData->image == "") {
  $userData->image = "user.png";
}

?>
<div id="main-container" class="container-fluid edit-profile-page">
  <div class="col-md-12">
    <form action="<?php echo $BASE_URL ?>controllers/FormController.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="userType" value="update">
      <div class="row">
        <div class="col-md-4">
          <h1><?php echo $fullName ?></h1>
          <p class="page-description">Altere seus dados no formulário abaixo:</p>
          <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Digite o seu nome" value="<?php echo $userData->name ?>">
          </div>
          <div class="form-group">
            <label for="lastname">Sobrenome:</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite o seu nome" value="<?php echo $userData->lastname ?>">
          </div>
          <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="text" readonly class="form-control disabled" id="email" name="email" placeholder="Digite o seu nome" value="<?php echo $userData->email ?>">
          </div>
          <input type="submit" class="btn card-btn" value="Alterar">
        </div>
        <div class="col-md-4">
          <div id="profile-image-container" style="background-image: url('<?php echo $BASE_URL ?>resources/users/<?php echo $userData->image ?>')"></div>
          <div class="form-group">
            <label for="image">Foto:</label>
            <input type="file" class="form-control-file" name="image">
          </div>
          <div class="form-group">
            <label for="bio">Sobre você:</label>
            <textarea class="form-control" name="bio" id="bio" rows="5" placeholder="Conte quem você é, o que faz e onde trabalha..."><?php echo $userData->bio ?></textarea>
          </div>
        </div>
      </div>
    </form>
    <div class="row" id="change-password-container">
      <div class="col-md-4">
        <h2>Alterar a senha:</h2>
        <p class="page-description">Digite a nova senha e confirme, para alterar sua senha:</p>
        <form action="<?php echo $BASE_URL ?>controllers/FormController.php" method="POST">
          <input type="hidden" name="userType" value="changepassword">
          <div class="form-group">
            <label for="password">Senha:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Digite a sua nova senha">
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirmação de senha:</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirme a sua nova senha">
          </div>
          <input type="submit" class="btn card-btn" value="Alterar Senha">
        </form>
      </div>
    </div>
  </div>
</div>
<?php
require_once($__ROOT__ . "/views/templates/footer.php");
?>