<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header("Cache-Control:no-cache,must-revalidate");

  try {
    $myCLIL = SM_myCLIL::singleton();
    $csSess = SM_csSess::singleton();
    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $servername = SM_myCLIL::servername();
    $serverhome = SM_myCLIL::serverhome();

    $T = new SM_T('clilstore/login');
    $T_Email    = $T->h('E-mail');
    $T_UserID   = $T->h('UserID');
    $T_Password = $T->h('Password');
    $T_Login    = $T->h('Log_air');
    $T_Register = $T->h('Register');
    $T_Return   = $T->h('Return');
    $T_No_account_yet          = $T->h('No_account_yet');
    $T_Forgotten_your_password = $T->h('Forgotten_your_password');
    $T_Recover_it              = $T->h('Recover_it');
    $T_Login_to_Clilstore      = $T->h('Login_to_Clilstore');
    $T_Successfully_logged_in  = $T->h('Successfully_logged_in');
    $T_Userid_or_pw_incorrect  = $T->h('Userid_or_pw_incorrect');
    $T_Wait_a_moment           = $T->h('Wait_a_moment');

    $formRequired = TRUE;
    $successMessage = $failureMessage = $refreshHeader = $formHtml = '';
    $userAsTyped = $passwordAsTyped = $userAutofocus = $passwordAutofocus = '';

    if      (!empty($_REQUEST['returnTo']))    { $refreshURL = $_REQUEST['returnTo'];
                                                 if (substr($refreshURL,0,4)<>'http') { $refreshURL = $serverhome . $refreshURL; }
                                               }
     elseif (!empty($_SERVER['HTTP_REFERER'])) { $refreshURL = $_SERVER['HTTP_REFERER']; }
     else                                      { $refreshURL = "$serverhome/clilstore/"; }
    $refreshURL .= ( parse_url($refreshURL,PHP_URL_QUERY) ? '&' : '?' ) . 'refresh=' . time(); //Add a cache-busting dummy parameter

    $menu   = SM_clilHeadFoot::cabecera();
    $footer = SM_clilHeadFoot::pie();

    $returnTo = $_REQUEST['returnTo'] ?? '/clilstore/';
    if (!empty($csSess->getCsSession()->user)) { $userAsTyped = $csSess->getCsSession()->user; }
    if (!empty($_REQUEST['user'])) {
        $userAsTyped     = trim($_REQUEST['user']);
        $passwordAsTyped = $_POST['password'] ?? '';
        $stmt1 = $DbMultidict->prepare('SELECT user,password,csid FROM users WHERE user=:user OR email=:email');
        $stmt1->bindParam(':user',$userAsTyped);
        $stmt1->bindParam(':email',$userAsTyped);
        $stmt1->bindColumn(1,$user);
        $stmt1->bindColumn(2,$password);
        $stmt1->bindColumn(3,$csid);
        if  ($stmt1->execute()
          && $stmt1->fetch()
          && (crypt($passwordAsTyped,$password)==$password || $password=='')) {
           //Copy filter parameters from the most recent previous Clilstore session (if any) for this user. Remember the new csid.
            $newCsid = $_COOKIE['csSessionId'];
            if (!empty($csid)) {
                $stmt2 = $DbMultidict->prepare('REPLACE INTO csFilter(csid,fd,m0,m1,m2,m3,sortpri,sortord,val1,val2)'
                                                        . ' SELECT :newCsid,fd,m0,m1,m2,m3,sortpri,sortord,val1,val2 FROM csFilter WHERE csid=:csid');
                $stmt2->execute([':newCsid'=>$newCsid,':csid'=>$csid]);
                $stmt3 = $DbMultidict->prepare('SELECT mode from csSession WHERE csid=:csid');
                $stmt3->execute([':csid'=>$csid]);
                $oldMode = $stmt3->fetchColumn();
                $csSess->setmode($oldMode);
            }
            $stmt4 = $DbMultidict->prepare('UPDATE users SET csid=:newCsid WHERE user=:user');
            $stmt4->execute([':newCsid'=>$newCsid,':user'=>$user]);
           //Create cookie
            $myCLIL::cuirCookie('myCLIL_authentication',$user,0,108000); //Cookie expires at session end, or max 30 hours
            $csSess->setUser($user);  //Remember $user, to make the next login easier
            SM_csSess::logWrite($user,'login','clilstore.eu');
            $successMessage = <<<ENDsuccess
<div class="col-lg-12 text-center">
            <h4 class="text-white text-center"><img src="loader-icon.gif" height="30" width="30"> $T_Wait_a_moment... $T_Successfully_logged_in</h4>
</div>
ENDsuccess;
            $formRequired = FALSE;
            $refreshHeader =  "<meta http-equiv=refresh content='1; url=$refreshURL'>";
        } elseif (!isset($_GET['user'])) {
            $failureMessage = <<<ENDfailure
$T_Userid_or_pw_incorrect
ENDfailure;
        }
    }

    if ($formRequired) {
        $userSC     = htmlspecialchars($userAsTyped);
        $passwordSC = htmlspecialchars($passwordAsTyped);
        if (empty($userSC)) { $userAutofocus = 'autofocus'; } else { $passwordAutofocus = 'autofocus'; }
        $formHtml = <<<ENDform

<div class="row h-100 justify-content-center align-items-center">
	<div class="col-lg col-sm">
		<div class="card">
			<div class="card-header bg-primary text-center">
				<h4>$T_Login_to_Clilstore</h4>
			</div>
			<div class="card-body">
				<div class="alert text-center" role="alert">
					<h5><span class="badge badge-danger">$failureMessage</span></h5>
				</div>
				<form data-toggle="validator" role="form" method="post" action="/clilstore/login.php?returnTo=$refreshURL">
					<input type="hidden" class="hide" id="csrf_token" name="csrf_token" value="C8nPqbqTxzcML7Hw0jLRu41ry5b9a10a0e2bc2">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>$T_Email / $T_UserID</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
									</div>
									<input type="text" class="form-control" name="user" value="$userSC" required $userAutofocus>
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>$T_Password</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-unlock" aria-hidden="true"></i></span>
									</div>
									<input type="password" name="password" value="$passwordSC" required $passwordAutofocus class="form-control" pattern=".{4,}" title="Cuatro(4) o mas caracteres">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" name="redirect" value="index.php">
							<input type="submit" class="btn btn-primary btn-lg btn-block mb-2" value="$T_Login" name="submit">
						</div>
					</div>
				</form>
				<div class="clear">
				</div>
				<i class="fa fa-user fa-fw"></i> $T_No_account_yet <a href="register.php?returnTo=$returnTo">$T_Register</a><br>
				<i class="fa fa-undo fa-fw"></i> $T_Forgotten_your_password <a href="forgotPassword.php">$T_Recover_it</a>
                                <p class="text-center"><i class="fa fa-arrow-left fa-fw"></i><a href="index.php">$T_Return</a></p>
			</div>
		</div>
	</div>
</div>



ENDform;
    }

   echo <<<EOD
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$T_Login_to_Clilstore</title>
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
        span.info { color:green; font-size:70%; font-style:italic; }

        .card .card-header {
        color: #ffffff;
        }

        .bg-primary {
           background-color: #35a4bf !important;
        }

        p {
           margin-bottom: 0.3rem;
           margin-bottom: 0.0rem;
        }
    </style>
$refreshHeader
</head>
<body>
$menu
<div class="container h100">
    <div class="row h-100 justify-content-center align-items-center">
        $successMessage
        $formHtml
    </div>
    <div class="col-lg-12">
        $footer
    </div>
  </div>
</div>
</body>
</html>
EOD;

  } catch (Exception $e) { echo $e; }

?>
