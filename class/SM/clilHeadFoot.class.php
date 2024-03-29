<?php
class SM_clilHeadFoot {

  public static function cabecera () {
    $T = new SM_T('clilstore/serverhome');
    $T_Home     = $T->h('Home');
    $T_Help     = $T->h('Cobhair');
    $T_About    = $T->h('About');
    $T_Language  = $T->h('Language');

    $hlSelect   = SM_mdNavbar::hlSelect();
    $hlSelectJs = SM_mdNavbar::hlSelectJs();
    $hl = SM_T::hl0();
    $hlHelp = ( in_array($hl,['da','es','ga','it']) ? $hl : 'en' );

    $header=<<<END_HEADER
<nav class="navbar navbar-expand-lg fondo_menu navbar-dark" id="mainNav">
  <div class="container">
    <a class="navbar-brand text-white" href="https://languages.dk/" target="_blank"><img src="../lonelogo/logo.png" width="150px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse align-items-center" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="/clilstore/">$T_Home</a></li>
        <li class="nav-item"><a class="nav-link" href="https://languages.dk/help/$hlHelp/page2.html">$T_Help</a></li>
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
<script>
$hlSelectJs
</script>
        <li class="nav-item"><a class="nav-link">$T_Language: $hlSelect</a></li>
      </ul>
    </div>
  </div>
</nav>
END_HEADER;
   return $header;
  }


  public static function cabecera0 ($usuario, $mode) {
    $T = new SM_T('clilstore/serverhome');
    $T_Home     = $T->h('Home');
    $T_Help     = $T->h('Cobhair');
    $T_About    = $T->h('About');
    $T_Language = $T->h('Language');
    $T_Login    = $T->h('Log_air');
    $T_Logout   = $T->h('Logout');
    $T_My_units = $T->h('My_units');
    $T_Register = ucfirst($T->h('Register'));
    $T_Options       = $T->h('Options');
    $T_Create_a_unit = $T->h('Create_a_unit');
    $T_Vocabulary    = $T->h('Vocabulary');
    $T_Portfolios    = $T->h('Portfolios');

    $hlSelect   = SM_mdNavbar::hlSelect();
    $hlSelectJs = SM_mdNavbar::hlSelectJs();

    $hl = SM_T::hl0();
    $hlHelp = ( in_array($hl,['da','es','ga','it']) ? $hl : 'en' );
    $scriptName = $_SERVER['SCRIPT_NAME'];
    if ($scriptName=='/clilstore/edit.php') { $helpFile = 'create_unit.html';      }
      elseif (!empty($usuario) && $mode>=2) { $helpFile = 'teacher_loggedin.html'; }
      else                                  { $helpFile = 'page2.html';            }
    $homeLink = ( $scriptName=='/clilstore/index.php' ? '/' : '/clilstore/' );

    if (empty($usuario)) { //Not logged in yet
      $headerUsuario = <<<END_headerLogin
    <a class="btn btn-outline-light ml-2 mr-2" href="login.php">$T_Login</a>
    <a class="btn btn-outline-light" href="register.php">$T_Register</a>
END_headerLogin;
    } else {
        if ($mode<=1) { //Student mode
            $myItems = <<<END_myItemsStudent
        <a href="voc.php?user=$usuario" class="dropdown-item">$T_Vocabulary</a>
        <a href="portfolio.php" class="dropdown-item">$T_Portfolios</a>
END_myItemsStudent;
        } else { //Teacher mode
            $myItems = <<<END_myItemsTeacher
        <a href="./?owner=$usuario" class="dropdown-item">$T_My_units</a>
        <a href="edit.php?id=0" class="dropdown-item">$T_Create_a_unit</a>
        <a href="portfolios.php" class="dropdown-item">$T_Portfolios</a>
END_myItemsTeacher;
        }
        $headerUsuario = <<<END_headerUsuario
  <div class="dropdown" id="dmenu">
    <button type="button" class="btn btn-outline-light dropdown-toggle ml-2" data-toggle="dropdown" id="navbarDropdown"><i class="fa fa-user"></i> $usuario</button>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a href="options.php?user=$usuario" class="dropdown-item">$T_Options</a>
$myItems
        <div class="dropdown-divider"></div>
        <a href="logout.php" class="dropdown-item">$T_Logout</a>
     </div>
   </div>
END_headerUsuario;
    }

$scriptName = $_SERVER['SCRIPT_NAME'];
    $header = <<<END_HEADER0
<nav class="navbar navbar-expand-lg fondo_menu navbar-dark" id="mainNav">
  <div class="container">
    <img src="../lonelogo/logo.png" width="150px">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse align-items-center" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-center list-unstyled">
        <li class="nav-item"><a class="nav-link" href="$homeLink">$T_Home</a></li>
        <li class="nav-item"><a class="nav-link" href="https://languages.dk/help/$hlHelp/$helpFile">$T_Help</a></li>
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
<script>
$hlSelectJs
</script>
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
    $hl0 = SM_T::hl0();
    $EUlogos = $_SERVER['DOCUMENT_ROOT'] . '/EUlogos';
    if      (file_exists("$EUlogos/$hl0.png")) { $EUlogo = "/EUlogos/$hl0.png"; }
     elseif (file_exists("$EUlogos/$hl0.jpg")) { $EUlogo = "/EUlogos/$hl0.jpg"; }
     else                                      { $EUlogo = '/EUlogos/en.png';   }
    $EUlangs = explode(',', 'bg,cs,da,de,en,el,es,et,fi,fr,hr,ga,hr,it,lt,lv,mt,nl,pl,pt,ro,sk,sl,sv');
    $hlEU = ( in_array($hl0,$EUlangs) ? $hl0 : 'en' ); //EU official language, otherwise English

    $footer = <<<END_FOOTER
  <footer class="mt-5 mb-3">
    <div class="container mt-5 align-items-center">
      <div class="row">
        <div class="col-xs-12 col-md-6 text-center">
            <a href="//www.eacea.ec.europa.eu/grants_$hlEU"><img src="$EUlogo" width="281" height="60"></a>
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
