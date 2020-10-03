<?php
  try {
    if (!include('autoload.inc.php')) { throw new Exception('Failed to find autoload.inc.php'); }
    header('Cache-Control:max-age=0');

    $T = new SM_T('clilstore/token');

    $menu   = SM_clilHeadFoot::cabecera();
    $footer = SM_clilHeadFoot::pie();

    if (empty($_GET['token'])) { throw new Exception('This page requires a token= parameter'); }
    $token = $_GET['token'];

    $refresh = '';
    $serverhome = SM_myCLIL::serverhome();
    $nextpage = "$serverhome/clilstore/options.php";

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $utime = time();
    $DbMultidict->prepare("DELETE FROM tokens WHERE expires<$utime")->execute();
    $stmt = $DbMultidict->prepare('SELECT * FROM tokens WHERE token=:token');
    $stmt->execute([':token'=>$token]);
    if (!($row = $stmt->fetch(PDO::FETCH_ASSOC))) { throw new Exception('The token is invalid or has expired'); }
    extract($row);

    if ($purpose=='verifyEmail') {
        $email = $data;
        $emailEnc = htmlspecialchars($email);
        $stmtUPD = $DbMultidict->prepare('UPDATE users SET emailVerUtime=:utime WHERE user=:user AND email=:email');
        $stmtUPD->execute([':utime'=>$utime,':user'=>$user,':email'=>$email]);
        if ($stmtUPD->rowCount()==0) { throw new Exception("Error while verifying email address: $emailEnc"); }
        $tick = "<span style='color:green;font-weight:bold'>✔</span>";
        $HTML = "<div class='col-lg-12 text-center'><h4 class='text-white text-center'><i class='fa fa-check'></i> Successfully verified email address:&nbsp; $emailEnc</h4></div>";
        $refresh = "<meta http-equiv='refresh' content='2;URL=$nextpage?user=$user'>";
    } else {
        throw new Exception('This token has an unrecognised purpose: '.htmlspecialchars($purpose));
    }

  } catch (Exception $e) {
    $exceptionMessage = $e->getMessage();
    $HTML = "<div class='col-lg-12 text-center'><h4 class='text-red text-center'><i class='fa fa-close'></i> $exceptionMessage</h4></div>";
  }

  echo <<<EOD_PAGE
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> $refresh
    <title>Clilstore: Act on a token</title>
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

$HTML

        <div class="col-lg-12">
            $footer
        </div>
    </div>
</div>

</body>
</html>
EOD_PAGE;

?>
