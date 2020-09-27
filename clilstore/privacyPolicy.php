<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
    header("Cache-Control:no-cache,must-revalidate");

  try {
    $myCLIL = SM_myCLIL::singleton();
    
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

    echo <<<EOD_barr
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login to Clilstore</title>
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/scripts.js"></script>
    <link href="../lone.css" rel="stylesheet">         
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">       
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">       
    <script src="../js/bootstrap.bundle.min.js"></script>    
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/login.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/favicons/clilstore.png">        
</head>
<body>
$menu
   
 <div class="container h100">
    <div class="row h-100 justify-content-center align-items-center">
        <div class="col-lg-12">
            <h3 class="text-white">Clilstore privacy policy</h3>

            <p class="text-white">Any user data collected by Clilstore and multidict.net is solely for use within the project and for the benefit of users.</p>

            <p class="text-white">Clilstore and multidict.net will not sell or give personal data to any third parties.</p>

            <p class="text-white">Clilstore and multidict.net do not use any third-party cookies.  We use only our own cookies, to make the system work, and these contain no personal information.</p> 

            <a class="btn btn-outline-light text-uppercase mr-1" href="GDPR_policy.pdf" target="_blank"><i class="fa fa-file"></i> GDPR policy statement</a> 
            
            <p class="text-center mt-3"><i class="fa fa-arrow-left fa-fw"></i><a class="text-white" href="index.php">Volver</a></p>
        </div>    
     <div class="col-lg-12">    
        $footer  
     </div> 
   </div>
</div>

</body>
</html>
EOD_barr;
} catch (Exception $e) { echo $e; }