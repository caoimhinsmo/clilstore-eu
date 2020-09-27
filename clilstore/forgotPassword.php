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
    <style>
       .card .card-header {                
        color: #ffffff;
        } 
    
        .bg-primary {
           background-color: #35a4bf !important;
        }    
      
      
        label {    
           margin-bottom: 0.0rem;
        }    
   </style>    
</head>
<body>
$menu
<div class="container h100">
    <div class="row h-100 justify-content-center align-items-center">
EOD_barr;

    $formRequired = TRUE;
    $errorMessage = '';
    $findme = ( isset($_REQUEST['findme']) ? trim($_REQUEST['findme']) : ''); 
    $findmeSC = htmlspecialchars($findme);
    if (isset($_POST['sendLink']) && !empty($findme)) {
        $DbMultidict = SM_DbMultidictPDO::singleton('r');
        $stmt = $DbMultidict->prepare('SELECT user,email FROM users WHERE (user=:user OR email=:email) AND email IS NOT NULL');
        $stmt->execute(array('user'=>$findme,'email'=>$findme));
        if (!($row=$stmt->fetch())) {
            $errorMessage = 'There’s no user with this e-mail address or userid';
        } else {
            $user = $row['user'];
            $email= $row['email'];
            $utime = time();
            $md5 = md5("moSgudal$user$utime");
            $servername = SM_myCLIL::servername();
            $link = "http://$servername/clilstore/changePassword.php?user=$user&t=$utime&md5=$md5";
            mail($email,'Clilstore - link to reset password (valid for 24 hours)',$link,"From:no-reply@multidict.net\r\n");
            echo <<<END_mess
            <div class="row">
		<div class="col-md-12">							
                    <h5 class="text-white"> A link allowing you to reset your password has been sent to your e-mail address. This will be valid for 24 hours.</h5><br>
                    <p class="text-center mt-3"><i class="fa fa-arrow-left fa-fw"></i><a class="text-white" href="index.php">Volver</a></p>
		</div>
            </div>
            
            
END_mess;
            $formRequired = FALSE;
        }
    }

    if ($formRequired) {
        $userSC     = htmlspecialchars($userAsTyped);
        echo <<<ENDform
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-lg col-sm">
		<div class="card">
			<div class="card-header bg-primary text-center">
				<h3>Forgotten your password?</h3>
                                <h6>You can ask for a link to be e-mailed to you which will allow you to reset your password</h6>
			</div>
			<div class="card-body">
				<div class="alert text-center" role="alert">
					<h5><span class="badge badge-danger">$errorMessage</span></h5>
				</div>
				<form data-toggle="validator" role="form" method="post" action="#">
					<input type="hidden" class="hide" id="csrf_token" name="csrf_token" value="C8nPqbqTxzcML7Hw0jLRu41ry5b9a10a0e2bc2">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Email / Usuario</label>
                                                                <p style="font-size:xx-small">The e-mail address you registered with Clilstore <span style="font-style:italic">(or your userid is also acceptable)</span></p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
									</div>
									<input type="text" class="form-control" name="findme" value="$findmeSC" required utofocus>
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" name="redirect" value="index.php">
							<input type="submit" class="btn btn-primary btn-lg btn-block mb-2 text-uppercase" name="sendLink" value="Send reset link" name="submit">
						</div>
					</div>
				</form>
				<div class="clear">
				</div>				
                                <p class="text-center"><i class="fa fa-arrow-left fa-fw"></i><a href="index.php">Volver</a></p>
			</div>
		</div>
	</div>
</div>         

ENDform;
    }

  } catch (Exception $e) { echo $e; }

  echo <<<EOD_bonn
     <div class="col-lg-12">    
            $footer  
     </div> 
   </div>
</div>

</body>
</html>
EOD_bonn;
?>
