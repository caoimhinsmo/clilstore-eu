<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");

  $T = new SM_T('clilstore/options');
  $T_Language   = $T->h('Language');
  $T_Vocabulary = $T->h('Vocabulary');
  $T_Fullname   = $T->h('Fullname');
  $T_Email      = $T->h('E-mail');
  $T_Verified   = $T->h('Verified');
  $T_Reverify   = $T->h('Reverify');
  $T_Unverified = $T->h('Unverified');
  $T_Verify_now = $T->h('Verify_now');
  $T_Return     = $T->h('Return');
  $T_Options_for_user = $T->h('Options_for_user');
  $T_My_vocabulary    = $T->h('My_vocabulary');
  $T_Change_password  = $T->h('Change_password');
  $T_Fullname_advice  = $T->h('Fullname_advice');
  $T_Email_advice     = $T->h('Email_advice');
  $T_Add_words_to_vocabulary     = $T->h('Add_words_to_vocabulary');
  $T_Add_words_to_vocabulary_No  = $T->h('Add_words_to_vocabulary:No');
  $T_Add_words_to_vocabulary_Yes = $T->h('Add_words_to_vocabulary:Yes');
  $T_Default_language_for_units  = $T->h('Default_language_for_units');

  $menu   = SM_clilHeadFoot::cabecera();
  $footer = SM_clilHeadFoot::pie();

  try {
      $myCLIL = SM_myCLIL::singleton();
      if (!$myCLIL->cead('{logged-in}')) { $myCLIL->diultadh(''); }
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  echo <<<EOD_BARR
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$T_Options_for_user</title>
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

        span.change { opacity:0; color:white; }
        span.change.changed { color:green; animation:appearFade 3s; }
        @keyframes appearFade { from { opacity:1; background-color:yellow; } 20% { opacity:0.8; background-color:white; } to { opacity:0; } }
        .h-divider{
            margin-top:5px;
            margin-bottom:10px;
            height:1px;
            width:100%;
            border-top:1px solid gray;
       }

    </style>
    <script>
        function changeUserOption(user,option,value) {
            if (option=='fullname' && value.length<8) { alert('Not changed. Invalid: Not long enough'); return; }
            if (option=='email' && !/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(value)) { alert('Not changed. This is not a valid email address'); return; }
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onload = function() {
                if (this.status!=200 || this.responseText.substring(0,2)!='OK') { alert('Error in changeUserOption: '+this.responseText); return; }
                if (this.responseText=='OK-null') { return; }
                if (option=='email') {
                    vmEl = document.getElementById('verMess');
                    newEl = document.createElement('span');
                    newEl.appendChild(document.createTextNode("Unverified"));
                    newEl.style.color = 'red';
                    vmEl.parentNode.replaceChild(newEl,vmEl);
                    alert('Your have changed your email address.  You will need to reverify it.');
                    location.reload();
                }
                var el = document.getElementById(option+'Changed');
                el.classList.remove('changed'); //Remove the class (if required) and add again after a tiny delay, to restart the animation
                setTimeout(function(){el.classList.add('changed');},50);
            }
            xmlhttp.open('GET', 'ajax/changeUserOption.php?user=' + user + '&option=' + option + '&value=' + value);
            xmlhttp.send();
        }
        function verifyEmail(email) {
            var emailEnc = encodeURIComponent(email);
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onload = function() {
                if (this.status!=200 || this.responseText!='OK') { alert('Error in verifyEmail: '+this.responseText); return; }
            }
            xmlhttp.open('GET', 'ajax/verifyEmail.php?email=' + emailEnc);
            xmlhttp.send();
            alert('An email has been sent to ' + email + ' to request confirmation of the address.  Remember to check for it in your spam folder too.');
        }
    </script>
</head>
<body>
$menu
<div class="container h100">
    <div class="row h-100 justify-content-center align-items-center">
EOD_BARR;

  try {
    $myCLIL->dearbhaich();
    $loggedinUser = $myCLIL->id;

    $user = @$_REQUEST['user'] ?:null;
    $userSC = htmlspecialchars($user);
    if (empty($user)) { throw new SM_MDexception('Parameter ‘user=’ is missing'); }
    if ($loggedinUser<>$user && $loggedinUser<>'admin') { throw new SM_MDexception('sgrios|bog|Attempt to change another user’s options<br>'
                                 . "You are logged in as $loggedinUser and have attempted to change the options for $userSC"); }

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $errorMessage = $successMessage = $transferHtml = '';

    if (!empty($_REQUEST['trResponse'])) {
        if (!empty($_REQUEST['trId'])) { $trId = $_REQUEST['trId']; } else { throw new SM_MDexception('Missid parameter trId'); }
        $trResponse = $_REQUEST['trResponse'];
        $trId       = $_REQUEST['trId'];
        if ($trResponse=='Reject') {
            $stmt = $DbMultidict->prepare('UPDATE clilstore SET offerTime=0 WHERE id=:id AND offer=:offer');
            $stmt->execute(array(':id'=>$trId,':offer'=>$user));
            SM_csSess::logWrite($user,'offerReject',"User $user rejected the offer of unit $trId");
        } elseif ($trResponse=='Accept') {
            $stmt = $DbMultidict->prepare('UPDATE clilstore SET owner=:owner, offer=null, offerTime=0 WHERE id=:id AND offer=:offer');
            $stmt->execute(array(':owner'=>$user,':id'=>$trId,':offer'=>$user));
            SM_csSess::logWrite($user,'offerAccept',"User $user accepted the offer of unit $trId");
        }
    }

    $stmt = $DbMultidict->prepare('SELECT id,owner,title FROM clilstore WHERE offer=:offer AND offerTime<>0 ORDER BY id');
    $stmt->execute(array(':offer'=>$user));
    $transfers = $stmt->fetchAll(PDO::FETCH_OBJ);
    $ntransfers = sizeof($transfers);
    foreach ($transfers as $tr) {
        $trId    = $tr->id;
        $trOwner = $tr->owner;
        $trTitle = $tr->title;
        $transferHtml .= "<tr>"
                       . "<td><a href='/cs/$trId'>$trId</a></td>"
                       . "<td><a href='userinfo.php?user=$trOwner'>$trOwner</a></td>"
                       . "<td><a href='/cs/$trId'>$trTitle</a></td>"
                       . "<td><form method='POST' class='transfer'>"
                          . " <input type='hidden' name='trId' value='$trId'>"
                          . " <input type='hidden' name='user' value='$user'>"
                          . " <input type='submit' name='trResponse' value='Accept' title='Accept ownership of unit $trId'>"
                          . " <input type='submit' name='trResponse' value='Reject' title='Reject the offer'>"
                          . "</form></td>"
                       . "</tr>\n";
    }
    if (!empty($transferHtml)) {
        $legend = ( $ntransfers==1 ? 'The following unit is on offer to you' : 'The following units are on offer to you' );
        $transferHtml = <<<EODtransferHtml
<fieldset class="opts">
<legend>Ownership transfer - $legend</legend>
<table id="transferTable">
<tr style="font-weight:bold;background-color:#eee"><td>unit</td><td>owner</td><td>title</td><td></td></tr>
$transferHtml
</table>
<span class="info">If you accept a unit, you accept responsibility for ensuring it does not infringe copyright</span>
</fieldset>
EODtransferHtml;
    }

    $stmt = $DbMultidict->prepare('SELECT fullname,email,emailVerUtime,unitLang,highlightRow,record FROM users WHERE user=:user');
    $stmt->execute(array('user'=>$user));
    if (!($row = $stmt->fetch(PDO::FETCH_ASSOC))) { throw new SM_MDexception("Failed to fetch information on user $userSC"); }
    extract($row);
    $fullnameSC = htmlspecialchars($fullname);
    $emailSC    = htmlspecialchars($email);
    if ($emailVerUtime==0) {
        $verMessage = "<a class='btn btn-danger btn-lg btn-block mb-2 disabled'><i class='fa fa-close'></i> $T_Unverified</a>";
        $verLink = $T_Verify_now;
    } else {
        date_default_timezone_set('UTC');
        $verTimeObj = new DateTime("@$emailVerUtime");
        $verDateTime = date_format($verTimeObj, 'Y-m-d H:i:s');
        $verMessage = "<a title='Verified at $verDateTime UT' class='btn btn-success btn-lg btn-block mb-2 disabled'><i class='fa fa-check'></i> $T_Verified</a>";
        $verLink = $T_Reverify;
    }
    $verLink = "<a class='btn btn-primary btn-lg btn-block mb-2 text-white' onclick=verifyEmail('$email')>$verLink</a>";
    $verMessage = $verMessage;

    function optionsHtml($valueOptArr,$selectedValue) {
     //Creates the options html for a select in a form, based on value=>text array and the value to be selected
        $htmlArr = array();
        foreach ($valueOptArr as $value=>$option) {
            $selected = ( $value==$selectedValue ? ' selected=selected' : '' );
            $htmlArr[] = "<option value='$value'$selected>$option</option>\n";
        }
        return implode("\r",$htmlArr);
    }

    $langArr[''] = '';
    $stmt3 = $DbMultidict->prepare("SELECT id,endonym FROM lang WHERE id<>'¤' AND id<>'x' ORDER BY endonym,id");
    $stmt3->execute();
    $stmt3->bindColumn(1,$id);
    $stmt3->bindColumn(2,$endonym);
    while ($stmt3->fetch()) { $langArr[$id] = "$endonym ($id)"; }
    $unitLangHtml = optionsHtml($langArr,$unitLang);

    $highlightRowArr = array(
        '-1' => 'Never',
         '0' => 'Only in “Author page - more options”',
         '1' => 'Always');
    $highlightRowHtml = optionsHtml($highlightRowArr,$highlightRow);
    $recordArr = array(
         '0' => $T_Add_words_to_vocabulary_No,
         '1' => $T_Add_words_to_vocabulary_Yes);
    $recordHtml = optionsHtml($recordArr,$record);

    $errorMessage   = ( empty($errorMessage)   ? '' : '<div class="message" style="color:red">' . $errorMessage   . '<br>No changes saved</div>' );
    $successMessage = ( empty($successMessage) ? '' : '<div class="message" style="color:green"><span style="font-size:200%">✔</span> ' . $successMessage . '</div>' );

    echo <<<ENDform

    <div class="row h-100 justify-content-center align-items-center">
	<div class="col-lg col-sm">
		<div class="card">
			<div class="card-header bg-primary text-center">
				<h4>$T_Options_for_user <span style="color:brown">$userSC</span></h4>
			</div>
			<div class="card-body">
				<div class="alert text-center" role="alert">
					<h5><span class="badge badge-danger">$errorMessage</span></h5>
				</div>
                                <div class="row">
						<div class="col-md-6">
							<a class="btn btn-primary btn-lg btn-block mb-2" href="changePassword.php?user=$user">$T_Change_password</a>
						</div>
                                                <div class="col-md-6">
						</div>
				</div>
                                <div class="h-divider"></div>
				<form data-toggle="validator" role="form" method="post" action="#">
					<input type="hidden" class="hide" id="csrf_token" name="csrf_token" value="C8nPqbqTxzcML7Hw0jLRu41ry5b9a10a0e2bc2">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>$T_Language</label>
                                                                <p style="font-size:xx-small">$T_Default_language_for_units</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-language" aria-hidden="true"></i></span>
									</div>
									<select class="form-control selectpicker" name="unitLang" onchange="changeUserOption('$user','unitLang',this.value)">
                                                                        $unitLangHtml
                                                                        </select>
                                                                        <span id=unitLangChanged class="change"><img src="check.gif" height="38" width="48"></img><span>
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>
                                        <div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>$T_Vocabulary</label>
                                                                <p style="font-size:xx-small">$T_Add_words_to_vocabulary</p>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fa fa-globe" aria-hidden="true"></i></span>
									</div>
									<select class="form-control selectpicker" name="record" onchange="changeUserOption('$user','record',this.value)">
                                                                        $recordHtml
                                                                        </select>
                                                                        <span id=recordChanged class="change"><img src="check.gif" height="38" width="48"></img><span>
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
									<input type="text" class="form-control" name="fullname" value="$fullnameSC" required pattern=".{8,}" placeholder="Your full name" style="width:22em" onchange="changeUserOption('$user','fullname',this.value)">
                                                                        <span id=fullnameChanged class="change"><img src="check.gif" height="38" width="48"></img><span>
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
									<input class="form-control" type="email" name="email" value="$emailSC" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" style="width:22em" onchange="changeUserOption('$user','email',this.value)">
                                                                        <span id=emailChanged class="change"><img src="check.gif" height="38" width="48"></img><span>
								</div>
								<div class="help-block with-errors text-danger">
								</div>
							</div>
						</div>
					</div>

				</form>
				<div class="clear">
				</div>
                                <div class="h-divider"></div>
                                <div class="row">
						<div class="col-md-6">
							$verMessage
						</div>
                                                <div class="col-md-6">
							$verLink
						</div>
				</div>

                                <p class="text-center mt-2"><i class="fa fa-arrow-left fa-fw"></i><a href="index.php">$T_Return</a></p>
			</div>
		</div>
	</div>
</div>

ENDform;

  } catch (Exception $e) { echo $e; }

  echo <<<EOD_BONN
        <div class="col-lg-12">
            $footer
        </div>
    </div>
</div>

</body>
</html>
EOD_BONN;
?>
