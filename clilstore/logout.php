<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");
  
  //Load Menu and Footer
  include ("../include/header_p.php");
  include ("../include/footer.php");
  
  $T = new SM_T('clilstore/index');
  $T_Disclaimer             = $T->h('Disclaimer');
  $T_Disclaimer_EuropeanCom = $T->h('Disclaimer_EuropeanCom');
  $T_Help                   = $T->h('Cobhair');
  $T_About                  = $T->h('About');
  $T_Language               = $T->h('Language');
  
  $EUlogo = '/EUlogos/' . SM_T::hl0() . '.png';
    
  $hlSelect = SM_mdNavbar::hlSelect();
  
  $menu = cabecera($hlSelect,$T_Help,$T_About,$T_Language);
  $footer = pie($EUlogo, $T_Disclaimer, $T_Disclaimer_EuropeanCom);

  try {
    $myCLIL = SM_myCLIL::singleton();
    $myCLIL::logout();
    $servername = SM_myCLIL::servername();
    $serverhome = SM_myCLIL::serverhome();
    
    

    echo <<<EOD1
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout from Clilstore</title>
    <meta http-equiv="refresh" content="2; url=$serverhome/clilstore/">
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
            <h4 class="text-white text-center"><img src="loader-icon.gif" height="30" width="30"> Wait a moment... You have been logged out from Clilstore</h4>
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
