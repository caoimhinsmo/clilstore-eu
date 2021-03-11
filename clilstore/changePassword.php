<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header('Cache-Control:max-age=0');

  try {
    $T = new SM_T('clilstore/changePassword');
    $menu   = SM_clilHeadFoot::cabecera();
    $footer = SM_clilHeadFoot::pie();

    echo <<<EOD_barr
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change password on Clilstore</title>
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

        p {
           margin-bottom: 0.2rem;
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

    $myCLIL = SM_myCLIL::singleton();
    if (isset($_REQUEST['user'])) { $user = $_REQUEST['user']; }
     else { throw new SM_MDexception('This page has been called without the required ‘user=’ parameter'); }
    $userSC = htmlspecialchars($user);
    if (isset($_GET['md5'])) {
        $vialink = TRUE;
        $md5       = $_GET['md5'];
        $timestamp = $_GET['t'];
        if (md5("moSgudal$user$timestamp")<>$md5) { throw new SM_MDexception('Corrupt link.  Not authorised.'); }
        if (time()-$timestamp > 86400) { throw new SM_MDexception('The link is stale (over one day old).  You’ll have to go to Clilstore and request another password reset link.'); }
    } elseif (!empty($myCLIL)) {
        $vialink = FALSE;
        $loggedinUser = $myCLIL->id;
        if ($loggedinUser<>$user && $loggedinUser<>'admin') { throw new SM_MDexception('Attempt to change another user’s password<br>'
                                     . "You are logged in as $loggedinUser and have attempted to change the password of $userSC"); }
    } else {
        throw new SM_MDexception('You are not logged in and have no authorisation');
    }

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $errorMessage = $oldpass = $password = $password2 = '';
    $formRequired = 1;

    $stmt = $DbMultidict->prepare('SELECT password FROM users WHERE user=:user');
    $stmt->execute(array('user'=>$user));
    if (!($row = $stmt->fetch())) { throw new SM_MDexception("User $user does not exist"); }
    $stmt = null;

    if (!empty($_POST['change'])) {
        $oldpass   = @$_POST['oldpass'];
        $password  = @$_POST['password'];
        $password2 = @$_POST['password2'];

        if (!$vialink && empty($oldpass)) {
            $errorMessage = 'You have not given your old password';
        } elseif (!$vialink && (crypt($oldpass,$row['password']) <> $row['password'])) {
            $errorMessage = 'Old password incorrect';
        } elseif (empty($password)) {
            $errorMessage = 'You have not specified a new password';
        } elseif (empty($password2)) {
            $errorMessage = 'You have not retyped your new password';
        } elseif ($password<>$password2) {
            $errorMessage = 'Retyped password does not match';
        } else {
            $passwordCrypt = crypt($password,'$2a$07$rudeiginLanLanAmaideach');
            $stmt = $DbMultidict->prepare('UPDATE users SET password =:pw WHERE user=:user');
            if (!$stmt->execute(array('pw'=>$passwordCrypt,'user'=>$user))) { throw new SM_MDexception('Failed to update password'); }
            echo <<<ENDsuccess
            <h2 class="text-white">Your password has been changed. You may now</h2><a class="btn btn-outline-light text-uppercase ml-2" href="login.php">Login</a>
ENDsuccess;
            $formRequired = 0;
        }
    }


    if ($formRequired) {
        if ($vialink) {
            $oldpassRow = '';
        } else {
            $oldpassSC   = htmlspecialchars($oldpass);
            $oldpassRow = "<tr><td>Old password:</td><td><input type=password name=oldpass value=\"$oldpassSC\" required</td></tr>";
        }
        $passwordSC  = htmlspecialchars($password);
        $password2SC = htmlspecialchars($password2);
        echo <<<ENDform
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-lg col-sm">
		<div class="card">
			<div class="card-header bg-primary text-center">
				<h4>Change password for user $user</h4>
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
								<label>New Password</label>
                                                                <p style="font-size:xx-small">Set a password (at least 8 characters long)</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
									</div>
									<input class="form-control" type="password" name="password"  value="$passwordSC"  required pattern=".{8,}">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
                                        <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Re-type New Password</label>
                                                                <p style="font-size:xx-small">Reenter the password</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
									</div>
									<input class="form-control" type="password" name="password2" value="$password2SC" required pattern=".{8,}" placeholder="Retype to confirm">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" name="user" value="$userSC">
							<input type="submit" class="btn btn-primary btn-lg btn-block mb-2" value="Change Password" name="change">
						</div>
					</div>
				</form>
				<div class="clear">
				</div>
                                <p class="text-center mt-2"><i class="fa fa-arrow-left fa-fw"></i><a href="index.php">Volver</a></p>
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
