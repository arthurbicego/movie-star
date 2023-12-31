<?php

$__ROOT__ = dirname(dirname(__DIR__));

require_once($__ROOT__ . "/globals.php");

?>

<footer id="footer">
  <div id="social-container">
    <ul>
      <li>
        <a href="#"><i class="fab fa-facebook-square"></i></a>
      </li>
      <li>
        <a href="#"><i class="fab fa-instagram"></i></a>
      </li>
      <li>
        <a href="#"><i class="fab fa-youtube"></i></a>
      </li>
    </ul>
  </div>
  <div id="footer-links-container">
    <ul>
      <li><a href="<?php echo $BASE_URL ?>views/newmovie.php">Adicionar filme</a></li>
      <li><a href="<?php echo $BASE_URL ?>views/dashboard.php">Meu filmes</a></li>
      <li><a href="<?php echo $BASE_URL ?>views/auth.php">Entrar / Registrar</a></li>
    </ul>
  </div>
  <p>&copy; 2023-<?php echo date("Y") ?> | <a class="text-decoration-none" href="https://arthurbicego.com/">Arthur Bicego Quintaneira</a></p>
</footer>
<!-- BOOTSTRAP JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.3/js/bootstrap.js" integrity="sha512-KCgUnRzizZDFYoNEYmnqlo0PRE6rQkek9dE/oyIiCExStQ72O7GwIFfmPdkzk4OvZ/sbHKSLVeR4Gl3s7s679g==" crossorigin="anonymous"></script>
</body>

</html>