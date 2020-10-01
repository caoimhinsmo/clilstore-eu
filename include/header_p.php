<?php 

function cabecera ($idioma,$about,$help,$idioma_texto) {
    $serverhome = ( empty($_SERVER["HTTPS"]) ? "http" : "https" ) . "://" . $_SERVER["SERVER_NAME"] ."/";
    $header=<<<END_HEADER
<nav class="navbar navbar-expand-lg fondo_menu navbar-dark" id="mainNav">
  <div class="container">
    <a class="navbar-brand text-white" href="https://www.languages.dk/" target="_blank"><img src="../lonelogo/logo.png" width="150px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse align-items-center" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item">
          <a class="nav-link" href="$serverhome">Home</a>
        </li>
         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          $about
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Clilstore</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Wordlink</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Multidict</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          $help
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Clilstore</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Wordlink</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Multidict</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link">$idioma_texto: $idioma</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
END_HEADER;    
   return $header;
}
?>