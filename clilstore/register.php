<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");

  $T = new SM_T('clilstore/register');

  $T_Password   = $T->h('Password');
  $T_Email      = $T->h('E-mail');
  $T_Fullname   = $T->h('Fullname');
  $T_Login      = $T->h('Log_air');
  $T_Click_here = $T->h('Click_here');
  $T_Return     = $T->h('Return');
  $T_UserID     = $T->h('UserID');
  $T_Register   = $T->h('Register');
  $T_Retype_password         = $T->h('Retype_password');
  $T_UserID_advice           = $T->h('UserID_advice');
  $T_Fullname_advice         = $T->h('Fullname_advice');
  $T_Email_advice            = $T->h('Email_advice');
  $T_Password_advice         = $T->h('Password_advice');
  $T_Retype_pw_advice        = $T->h('Retype_pw_advice');
  $T_Register_on_Clilstore   = $T->h('Register_on_Clilstore');
  $T_Already_have_an_account = $T->h('Already_have_an_account');
  $T_Choose_unique_userid    = $T->h('Choose_unique_userid');
  $T_Not_a_valid_email       = $T->h('Not_a_valid_email');
  $T_User_exists_for_email_1 = $T->h('User_exists_for_email_1');
  $T_User_exists_for_email_2 = $T->h('User_exists_for_email_2');
  $T_User_exists_for_email_3 = $T->h('User_exists_for_email_3');
  $T_User_already_taken      = $T->h('User_already_taken');
  $T_Retyped_pw_mismatch     = $T->h('Retyped_pw_mismatch');

  $T_Our_privacy_policy = $T->h('Our_privacy_policy');

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

        if ( $user <> strtr($user,'^!£$%^&"*()=+{}[]\\/|:;\'@~#<>,?',
                                  '________________________________') ) {
        } elseif (empty($fullname) || strlen($fullname)<8) {
            $errorMessage = 'You have not given your full name';
        } elseif (empty($email)) {
            $errorMessage = 'You have not given your e-mail address';
        } elseif (validEmail($email)==0) {
            $errorMessage = $T_Not_a_valid_email;
        } elseif ($stmtEmail->execute() && $stmtEmail->bindColumn(1,$prevUser) && $stmtEmail->fetch()) {
            $User_exists_for_email_1 = strtr( $T_User_exists_for_email_1, ['[xxxxxx]' => "<b>$prevUser</b>"] );
            $User_exists_for_email_2 = strtr( $T_User_exists_for_email_2,
                                             ['[xxxxxx]' => $prevUser,
                                              '{' => "<a href=\"login.php?user=$prevUser\" style='border:1px solid;border-radius:3px;padding:0 3px'>",
                                              '}' => '</a>'] );
            $User_exists_for_email_3 = strtr( $T_User_exists_for_email_3, ['{'=>'<i>','}'=>'</i>'] );
            $errorMessage = "$User_exists_for_email_1<br>$User_exists_for_email_2<br><br><br>$User_exists_for_email_3";
        } elseif ($stmtUser->execute() && $stmtUser->fetch()) {
            $errorMessage = strtr( $T_User_already_taken, ['[xxxxxx]' => "<b>$userSC</b>"] );
        } elseif (strlen($user)<3) {
            $errorMessage = 'Userids must be at least 3 characters long.  You’ll have to choose something longer.';
        } elseif (strlen($user)>16) {
            $errorMessage = 'Userids cannot be over 16 characters long.  You’ll have to choose something shorter.';
        } elseif ($password<>$password2) {
            $errorMessage = $T_Retyped_pw_mismatch;
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
<h2 class="text-white">User ID <b>$user</b> has been successfully registered. You may now </h2><a class="btn btn-outline-light text-uppercase ml-2" href="login.php">$T_Login</a>
ENDsuccess;
            $formRequired = 0;
        }
    }

    if ($formRequired) { echo <<<ENDform
<div class="row h-100 justify-content-center align-items-center">
	<div class="col-lg col-sm">
		<div class="card">
			<div class="card-header bg-primary text-center">
				<h4>$T_Register_on_Clilstore</h4>
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
								<label>$T_UserID</label>
                                                                <p style="font-size:xx-small">$T_UserID_advice</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
									</div>
									<input type="text" class="form-control" name="user" value="$userSC" required pattern="[^!£$%^&amp;&quot;*()=+{}\[\]\\\/|:;'@~#<>,?]{3,16}" autofocus placeholder="$T_Choose_unique_userid">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
                                        <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>$T_Fullname</label>
                                                                <p style="font-size:xx-small">$T_Fullname_advice</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
									</div>
									<input type="text" class="form-control" name="fullname" value="$fullnameSC" required pattern=".{8,}" style="width:22em">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
                                        <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>$T_Email</label>
                                                                <p style="font-size:xx-small">$T_Email_advice</p>
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
								<label>$T_Password</label>
                                                                <p style="font-size:xx-small">$T_Password_advice</p>
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
								<label>$T_Retype_password</label>
                                                                <p style="font-size:xx-small">$T_Retype_pw_advice</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
									</div>
									<input class="form-control" type="password" name="password2" value="$password2SC" required pattern=".{8,}">
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<input type="hidden" name="redirect" value="index.php">
							<input type="submit" class="btn btn-primary btn-lg btn-block mb-2" value="$T_Register" name="submit">
						</div>
					</div>
				</form>
				<div class="clear">
				</div>
				<i class="fa fa-user fa-fw"></i> $T_Already_have_an_account <a href="login.php">$T_Login</a><br>
				<i class="fa fa-file fa-fw"></i> $T_Our_privacy_policy <a href="privacyPolicy.php">$T_Click_here</a><br>
                                <p class="text-center mt-2"><i class="fa fa-arrow-left fa-fw"></i><a href="index.php">$T_Return</a></p>
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
