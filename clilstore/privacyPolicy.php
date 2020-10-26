<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");
    header("Cache-Control:no-cache,must-revalidate");

  try {
    $myCLIL = SM_myCLIL::singleton();

    $T = new SM_T('clilstore/privacyPolicy');
    $T_Return = $T->h('Return');
    $T_Privacy_policy        = $T->h('Privacy_policy');
    $T_privacy_policy_msg1   = $T->h('privacy_policy_msg1');
    $T_privacy_policy_msg2   = $T->h('privacy_policy_msg2');
    $T_privacy_policy_msg3   = $T->h('privacy_policy_msg3');
    $T_GDPR_policy_statement = $T->h('GDPR_policy_statement');

    $menu   = SM_clilHeadFoot::cabecera();
    $footer = SM_clilHeadFoot::pie();

    $GDPR_link_template = "/clilstore/GDPR_statements/GDPR_COOL_{hl}.pdf";
    $hl = SM_T::hl0();
    $GDPR_link = str_replace('{hl}',$hl,$GDPR_link_template);
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $GDPR_link)) {
        $GDPR_link = str_replace('{hl}','en',$GDPR_link_template); //Default to English if no local translation
    }

    echo <<<EOD_barr
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$T_Privacy_policy</title>
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
            <h3 class="text-white">$T_Privacy_policy</h3>

            <p class="text-white">$T_privacy_policy_msg1</p>
            <p class="text-white">$T_privacy_policy_msg2</p>
            <p class="text-white">$T_privacy_policy_msg3</p>

            <a class="btn btn-outline-light text-uppercase mr-1" href="$GDPR_link" target="_blank"><i class="fa fa-file"></i> $T_GDPR_policy_statement</a>

            <p class="text-center mt-3"><i class="fa fa-arrow-left fa-fw"></i><a class="text-white" href="index.php">$T_Return</a></p>
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