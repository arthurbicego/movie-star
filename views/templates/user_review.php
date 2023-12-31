<?php

$__ROOT__ = dirname(dirname(__DIR__));

require_once($__ROOT__ . "/models/User.php");
$userModel = new User();

require_once($__ROOT__ . "/views/templates/header.php");
require_once($__ROOT__ . "/models/DAO/ReviewDAO.php");

// DAO dos filmes
$userDao = new UserDAO($conn, $BASE_URL);

$fullName = $userModel->getFullName($review->user);

// Checar se o filme tem imagem
if ($review->user->image == "") {
  $review->user->image = "user.png";
}

?>
<div class="col-md-12 review">
  <div class="row">
    <div class="col-md-1">
      <div class="profile-image-container review-image" style="background-image: url('<?php echo $BASE_URL ?>resources/users/<?php echo $review->user->image ?>')"></div>
    </div>
    <div class="col-md-9 author-details-container">
      <h4 class="author-name">
        <a href="<?php echo $BASE_URL ?>views/profile.php?id=<?php echo $review->user->id ?>"><?php echo $fullName ?></a>
      </h4>
      <p><i class="fas fa-star"></i> <?php echo $review->rating ?></p>
    </div>
    <div class="col-md-12">
      <p class="comment-title">Comentário:</p>
      <p><?php echo $review->review ?></p>
    </div>
  </div>
</div>