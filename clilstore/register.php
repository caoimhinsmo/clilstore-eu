<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");

  $T = new SM_T('clilstore/register');

  $menu   = SM_clilHeadFoot::cabecera();
  $footer = SM_clilHeadFoot::pie();


  function validEmail($email) {
  // Returns true if the email address has the email address format and the domain exists. From http://www.linuxjournal.com/article/9585
     $isValid = true;
     $atIndex = strrpos($email, "@");
     if (is_bool($atIndex) && !$atIndex) {
        $isValid = false;
     } else {
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64) {
           $isValid = false;  //local part length exceeded
        } elseif ($domainLen < 1 || $domainLen > 255) {
           $isValid = false;  //domain part length exceeded
        } elseif ($local[0] == '.' || $local[$localLen-1] == '.') {
           $isValid = false;  //local part starts or ends with '.'
        } elseif (preg_match('/\\.\\./', $local)) {
           $isValid = false;  //local part has two consecutive dots
        } elseif (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
           $isValid = false;  //character not valid in domain part
        } elseif (preg_match('/\\.\\./', $domain)) {
           $isValid = false;  //domain part has two consecutive dots
        } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
           // character not valid in local part unless local part is quoted
           if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) { $isValid = false; }
        }
        if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
           $isValid = false;  //domain not found in DNS
        }
     }
     return $isValid;
  }

  try {
    echo <<<EOD1
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register a new userid on clilstore</title>
    <link rel="icon" type="image/png" href="/favicons/clilstore.png">
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/scripts.js"></script>
    <link href="../lone.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/login.css" rel="stylesheet">
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
EOD1;

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $formRequired = 1;
    $user   = $fullname   = $email   = $password   = $errorMessage = '';
    $userSC = $fullnameSC = $emailSC = $passwordSC = $password2SC  = '';
    if (!empty($_POST['user'])) {
        $user      = trim($_POST['user']);      $userSC      = htmlspecialchars($user);
        $fullname  = trim($_POST['fullname']);  $fullnameSC  = htmlspecialchars($fullname);
        $email     = trim($_POST['email']);     $emailSC     = htmlspecialchars($email);
        $password  = trim($_POST['password']);  $passwordSC  = htmlspecialchars($password);
        $password2 = trim($_POST['password2']); $password2SC = htmlspecialchars($password2);

        $stmtEmail = $DbMultidict->prepare('SELECT user FROM users WHERE email=:email');
        $stmtEmail->bindParam(':email',$email);
        $stmtUser = $DbMultidict->prepare('SELECT user FROM users WHERE user=:user');
        $stmtUser->bindParam(':user',$user);

        if (empty($fullname) || strlen($fullname)<8) {
            $errorMessage = 'You have not given your full name';
        } elseif (empty($email)) {
            $errorMessage = 'You have not given your e-mail address';
        } elseif (validEmail($email)==0) {
            $errorMessage = 'This is not a valid e-mail address';
        } elseif ($stmtEmail->execute() && $stmtEmail->bindColumn(1,$prevUser) && $stmtEmail->fetch()) {
            $errorMessage = "You already have a Clilstore userid “<b>$prevUser</b>” registered for this e-mail address.";
            $errorMessage .= "<br>You should <a href=\"login.php?user=$prevUser\" style=\"border:1px solid;border-radius:3px;padding:0 3px\">login</a> as $prevUser.";
            $errorMessage .= "<br><br><br>Or you can continue and register another Clilstore userid against a <i>different</i> e-mail address if you have one. But this is generally not recommended.";
        } elseif ($stmtUser->execute() && $stmtUser->fetch()) {
            $errorMessage = "Sorry, the userid “<b>$userSC</b>” is already taken.  You’ll have to choose something else.";
        } elseif (strlen($user)<3) {
            $errorMessage = 'Userids must be at least 3 characters long.  You’ll have to choose something longer.';
        } elseif (strlen($user)>16) {
            $errorMessage = 'Userids cannot be over 16 characters long.  You’ll have to choose something shorter.';
        } elseif ($password<>$password2) {
            $errorMessage = 'The retyped password does not match. Try again.';
        } elseif (strlen($password)<8) {  //Change sometime to more sophisticed check using PHP function crack_check.  Tried, but “pecl install crack” failed.
            $errorMessage = 'This password is too short and insecure';
        } else {
            $utime = time();
            $passwordCrypt = crypt($password,'$2a$07$rudeiginLanLanAmaideach');

            $stmtInsert = $DbMultidict->prepare('INSERT INTO users(user,fullname,email,joined,password) VALUES (:user,:fullname,:email,:joined,:password)');
            $stmtInsert->bindParam(':user',    $user);
            $stmtInsert->bindParam(':fullname',$fullname);
            $stmtInsert->bindParam(':email',   $email);
            $stmtInsert->bindParam(':joined',  $utime);
            $stmtInsert->bindParam(':password',$passwordCrypt);
            if (!$stmtInsert->execute()) { throw new SM_MDexception('Failed to insert user record'); }
            echo <<<ENDsuccess
<h2 class="text-white">User ID <b>$user</b> has been successfully registered. You may now </h2><a class="btn btn-outline-light text-uppercase ml-2" href="login.php">Login</a>
ENDsuccess;
            $formRequired = 0;
        }
    }

    if ($formRequired) { echo <<<ENDform
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-lg col-sm">
		<div class="card">
			<div class="card-header bg-primary text-center">
				<h4>Register to Clilstore</h4>
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
								<label>User ID</label>
                                                                <p style="font-size:xx-small">At least three characters long, preferably more (but not more that 16)</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
									</div>
									<input type="text" class="form-control" name="user" value="$userSC" required pattern=".{3,16}" autofocus placeholder="Choose a unique userid">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
                                        <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Full Name</label>
                                                                <p style="font-size:xx-small">Your real name - This will be visible to other users</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
									</div>
									<input type="text" class="form-control" name="fullname" value="$fullnameSC" required pattern=".{8,}" placeholder="Your full name" style="width:22em">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
                                        <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Email</label>
                                                                <p style="font-size:xx-small">This is kept private</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-envelope" aria-hidden="true"></i></span>
									</div>
									<input class="form-control" type="email" name="email" value="$emailSC" style="width:22em">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>Password</label>
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
								<label>Re-type Password</label>
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
							<input type="hidden" name="redirect" value="index.php">
							<input type="submit" class="btn btn-primary btn-lg btn-block mb-2" value="Register" name="submit">
						</div>
					</div>
				</form>
				<div class="clear">
				</div>
				<i class="fa fa-user fa-fw"></i> ¿Ya tienes una cuenta? <a href="login.php">Login</a><br>
				<i class="fa fa-file fa-fw"></i> Nuestra politica de privacidad <a href="privacyPolicy.php">Click aquí</a><br>
                                <p class="text-center mt-2"><i class="fa fa-arrow-left fa-fw"></i><a href="index.php">Volver</a></p>
			</div>
		</div>
	</div>
</div>
ENDform;
    }

    echo <<<EOD2

        <div class="col-lg-12">
            $footer
        </div>
    </div>
</div>

</body>
</html>
EOD2;

  } catch (Exception $e) { echo $e; }

?>
