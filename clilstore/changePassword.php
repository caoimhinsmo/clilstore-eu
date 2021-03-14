<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header('Cache-Control:max-age=0');

  $T = new SM_T('clilstore/changePassword');
  $T_Return          = $T->h('Return');
  $T_Change_password = $T->h('Change_password');
  $T_Old_password    = $T->h('Old_password');
  $T_New_password    = $T->h('New_password');
  $T_Password_advice = $T->h('Password_advice');
  $T_Retype_password = $T->h('Retype_password');
  $T_Retype_new_password      = $T->h('Retype_new_password');
  $T_Retype_to_confirm        = $T->h('Retype_to_confirm');
  $T_Change_password_for_user = $T->h('Change_password_for_user_');
  $T_Retyped_pw_mismatch      = $T->h('Retyped_pw_mismatch');
  $T_Old_password_missing     = $T->h('Old_password_missing');
  $T_Old_password_incorrect   = $T->h('Old_password_incorrect');
  $T_No_new_password          = $T->h('No_new_password');
  $T_No_retyped_new_password  = $T->h('No_retyped_new_password');

  try {

    $menu   = SM_clilHeadFoot::cabecera();
    $footer = SM_clilHeadFoot::pie();

    echo <<<EOD_barr
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$T_Change_password</title>
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
            $errorMessage = $T_Old_password_missing;
        } elseif (!$vialink && (crypt($oldpass,$row['password']) <> $row['password'])) {
            $errorMessage = $T_Old_password_incorrect;
        } elseif (empty($password)) {
            $errorMessage = $T_No_new_password;
        } elseif (empty($password2)) {
            $errorMessage = $T_No_retyped_new_password;
        } elseif ($password<>$password2) {
            $errorMessage = $T_Retyped_pw_mismatch;
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
            $oldpassRow = <<<END_oldpassRow
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>$T_Old_password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    </div>
                                    <input class="form-control" type="password" name="oldpass" value="$oldpassSC" required">
                                </div>
                                <div class="help-block with-errors text-danger">
                                </div>
                            </div>
                        </div>
                    </div>
END_oldpassRow;
        }
        $passwordSC  = htmlspecialchars($password);
        $password2SC = htmlspecialchars($password2);
        echo <<<ENDform
<div class="row h-100 justify-content-center align-items-center">
    <div class="col-lg col-sm">
        <div class="card">
            <div class="card-header bg-primary text-center">
                <h4>$T_Change_password_for_user $user</h4>
            </div>
            <div class="card-body">
                <div class="alert text-center" role="alert">
                    <h5><span class="badge badge-danger">$errorMessage</span></h5>
                </div>
                <form data-toggle="validator" role="form" method="post" action="#">
                    <input type="hidden" class="hide" id="csrf_token" name="csrf_token" value="C8nPqbqTxzcML7Hw0jLRu41ry5b9a10a0e2bc2">
$oldpassRow
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>$T_New_password</label>
                                <p style="font-size:xx-small">$T_Password_advice</p>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    </div>
                                    <input class="form-control" type="password" name="password" value="$passwordSC" required pattern=".{8,}">
                                </div>
                                <div class="help-block with-errors text-danger">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>$T_Retype_new_password</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                    </div>
                                    <input class="form-control" type="password" name="password2" value="$password2SC" required pattern=".{8,}" placeholder="$T_Retype_to_confirm">
                                </div>
                                <div class="help-block with-errors text-danger">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="user" value="$userSC">
                            <input type="submit" class="btn btn-primary btn-lg btn-block mb-2" value="$T_Change_password" name="change">
                        </div>
                    </div>
                </form>
                <div class="clear">
                </div>
                <p class="text-center mt-2"><i class="fa fa-arrow-left fa-fw"></i><a href="/clilstore/">$T_Return</a></p>
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
