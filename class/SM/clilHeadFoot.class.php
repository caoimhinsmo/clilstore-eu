<?php
class SM_clilHeadFoot {

  public static function cabecera () {
    $T = new SM_T('clilstore/serverhome');
    $T_Home     = $T->h('Home');
    $T_Help     = $T->h('Cobhair');
    $T_About    = $T->h('About');
    $T_Language  = $T->h('Language');

    $hlSelect = SM_mdNavbar::hlSelect();
    $hl = SM_T::hl0();
    $hlHelp = ( in_array($hl,['da','es','ga','it']) ? $hl : 'en' );

    $serverhome = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' ) . '://' . $_SERVER['SERVER_NAME'] .'/';
    $header=<<<END_HEADER
<nav class="navbar navbar-expand-lg fondo_menu navbar-dark" id="mainNav">
  <div class="container">
    <a class="navbar-brand text-white" href="https://www.languages.dk/" target="_blank"><img src="../lonelogo/logo.png" width="150px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse align-items-center" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="$serverhome">$T_Home</a></li>
        <li class="nav-item"><a class="nav-link" href="https://languages.dk/help/$hlHelp/">$T_Help</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">$T_About</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Clilstore</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Wordlink</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Multidict</a>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link">$T_Language: $hlSelect</a></li>
      </ul>
    </div>
  </div>
</nav>
END_HEADER;
   return $header;
  }


  public static function cabecera0 ($usuario, $modo) {
    $T = new SM_T('clilstore/serverhome');
    $T_Home     = $T->h('Home');
    $T_Help     = $T->h('Cobhair');
    $T_About    = $T->h('About');
    $T_Language = $T->h('Language');
    $T_Login    = $T->h('Log_air');
    $T_Logout   = $T->h('Logout');
    $T_My_units = $T->h('My_units');
    $T_Register = ucfirst($T->h('Register'));
    $T_My_options    = $T->h('My_options');
    $T_Create_a_unit = $T->h('Create_a_unit');
    $T_My_vocabulary = $T->h('My_vocabulary');

    $hlSelect = SM_mdNavbar::hlSelect();

    $serverhome = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' ) . '://' . $_SERVER['SERVER_NAME'] .'/';

    if (empty($usuario)) { //Not logged in yet
      $headerUsuario = <<<END_headerLogin
    <a class="btn btn-outline-light ml-2 mr-2" href="login.php">$T_Login</a>
    <a class="btn btn-outline-light" href="register.php">$T_Register</a>
END_headerLogin;
    } else {
        if ($modo<=1) { //Student mode
            $myItems = <<<END_myItemsStudent
        <a href="voc.php?user=$usuario" class="dropdown-item">$T_My_vocabulary</a>
END_myItemsStudent;
        } else { //Teacher mode
            $myItems = <<<END_myItemsTeacher
        <a href="./?owner=$usuario" class="dropdown-item">$T_My_units</a>
        <a href="edit.php?id=0" class="dropdown-item">$T_Create_a_unit</a>
END_myItemsTeacher;
        }
        $headerUsuario = <<<END_headerUsuario
  <div class="dropdown" id="dmenu">
    <button type="button" class="btn btn-outline-light dropdown-toggle ml-2" data-toggle="dropdown" id="navbarDropdown"><i class="fa fa-user"></i>$usuario</button>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a href="options.php?user=$usuario" class="dropdown-item">$T_My_options</a>
$myItems
        <div class="dropdown-divider"></div>
        <a href="logout.php" class="dropdown-item">$T_Logout</a>
     </div>
   </div>
END_headerUsuario;
    }

    $header = <<<END_HEADER0
<nav class="navbar navbar-expand-lg fondo_menu navbar-dark" id="mainNav">
  <div class="container">
    <a class="navbar-brand text-white" href="https://www.languages.dk/" target="_blank"><img src="../lonelogo/logo.png" width="150px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse align-items-center" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-center list-unstyled">
        <li class="nav-item"><a class="nav-link" href="$serverhome">$T_Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">$T_Help</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Clilstore</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Wordlink</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Multidict</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">$T_About</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Clilstore</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Wordlink</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Multidict</a>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link">$T_Language: $hlSelect</a></li>
    </div>
   </ul>
$headerUsuario
  </div>
</nav>;
END_HEADER0;

    return $header;
  }


  public static function pie () {
    $T = new SM_T('clilstore/serverhome');
    $T_Disclaimer               = $T->h('Disclaimer');
    $T_Disclaimer_EuropeanCom   = $T->h('Disclaimer_EuropeanCom');
    $EUlogo = '/EUlogos/' . SM_T::hl0() . '.jpg';
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $EUlogo)) { $EUlogo = '/EUlogos/en.png'; }

    $footer = <<<END_FOOTER
  <footer class="mt-5 mb-3">
    <div class="container mt-5 align-items-center">
      <div class="row">
        <div class="col-xs-12 col-md-6 text-center">
            <a href="http://eacea.ec.europa.eu/llp/index_en.php"><img src="$EUlogo" width="281" height="60"></a>
        </div>
        <div class="col-xs-12 col-md-6 text-center">
            <p style="font-size:x-small" class="text-white"><i>$T_Disclaimer:</i> $T_Disclaimer_EuropeanCom</p>
        </div>
      </div>
    </div>
  </footer>
END_FOOTER;
    return $footer;
  }

}
?>
