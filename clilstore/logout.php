<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");

  $T = new SM_T('clilstore/logout');
  $T_Wait_a_moment = $T->h('Wait_a_moment');
  $T_You_have_been_logged_out = $T->h('You_have_been_logged_out');

  $menu   = SM_clilHeadFoot::cabecera();
  $footer = SM_clilHeadFoot::pie($EUlogo, $T_Disclaimer, $T_Disclaimer_EuropeanCom);

  $returnTo = $_REQUEST['returnTo'] ?? '/clilstore/';
  try {
    $myCLIL = SM_myCLIL::singleton();
    $myCLIL::logout();
    $servername = SM_myCLIL::servername();
    $serverhome = SM_myCLIL::serverhome();

    if      (!empty($_REQUEST['returnTo']))    { $refreshURL = $_REQUEST['returnTo'];
                                                 if (substr($refreshURL,0,4)<>'http') { $refreshURL = $serverhome . $refreshURL; }
                                               }
     elseif (!empty($_SERVER['HTTP_REFERER'])) { $refreshURL = $_SERVER['HTTP_REFERER']; }
     else                                      { $refreshURL = "$serverhome/clilstore/"; }
    $refreshURL .= ( parse_url($refreshURL,PHP_URL_QUERY) ? '&' : '?' ) . 'refresh=' . time(); //Add a cache-busting dummy parameter

    echo <<<EOD1
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout from Clilstore</title>
    <meta http-equiv="refresh" content="1; url=$refreshURL">
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/scripts.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link rel="icon" type="image/png" href="/favicons/clilstore.png">
</head>
<body>
$menu
<div class="container h100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-lg-12 text-center">
            <h4 class="text-white text-center"><img src="loader-icon.gif" height="30" width="30"> $T_Wait_a_moment... $T_You_have_been_logged_out</h4>
        </div>
        <div class="col-lg-12">
            $footer
        </div>
    </div>
</div>
</body>
</html>
EOD1;

  } catch (Exception $e) { echo $e; }

?>
