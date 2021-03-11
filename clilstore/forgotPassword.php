<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
  header("Cache-Control:no-cache,must-revalidate");

  try {
    $myCLIL = SM_myCLIL::singleton();

    $T = new SM_T('clilstore/forgotPassword');

    $T_Email           = $T->h('E-mail');
    $T_UserID          = $T->h('UserID');
    $T_Send_reset_link = $T->h('Send_reset_link');
    $T_Return          = $T->h('Return');
    $T_No_such_user    = $T->h('No_such_user');

    $T_Forgotten_your_password           = $T->h('Forgotten_your_password');
    $T_forgotPassword_email_request_info = $T->h('forgotPassword_email_request_info');
    $T_forgotPassword_junkmail_reminder  = $T->h('forgotPassword_junkmail_reminder');
    $T_email_registered_with_Clilstore   = $T->h('email_registered_with_Clilstore');
    $T_or_your_userid                    = $T->h('or_your_userid');
    $T_Link_sent_confirmation            = $T->h('Link_sent_confirmation');

    $menu   = SM_clilHeadFoot::cabecera();
    $footer = SM_clilHeadFoot::pie();

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
            $errorMessage = $T_No_such_user;
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
                    <h5 class="text-white">$T_Link_sent_confirmation</h5><br>
                    <p class="text-center mt-3"><i class="fa fa-arrow-left fa-fw"></i><a class="text-white" href="index.php">$T_Return</a></p>
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
				<h3>$T_Forgotten_your_password</h3>
                                <h6>$T_forgotPassword_email_request_info<br>$T_forgotPassword_junkmail_reminder</h6>
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
								<label>$T_Email / $T_UserID</label>
                                                                <p style="font-size:xx-small">$T_email_registered_with_Clilstore <span style="font-style:italic">($T_or_your_userid)</span></p>
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
							<input type="submit" class="btn btn-primary btn-lg btn-block mb-2 text-uppercase" name="sendLink" value="$T_Send_reset_link" name="submit">
						</div>
					</div>
				</form>
				<div class="clear">
				</div>
                                <p class="text-center"><i class="fa fa-arrow-left fa-fw"></i><a href="index.php">$T_Return</a></p>
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
