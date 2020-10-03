<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");

  try {
      $myCLIL = SM_myCLIL::singleton();
      $user = ( isset($myCLIL->id) ? $myCLIL->id : '' );
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }
  if (isset($_GET['user'])) { $user = $_GET['user']; } //for generating edit button

  try {
    if (!isset($_GET['id'])) { throw new SM_MDexception('No id parameter'); }
    $id = $_GET['id'];
    if (!is_numeric($id)) { throw new SM_MDexception('id parameter is not numeric'); }

    $serverhome = SM_myCLIL::serverhome();
    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $stmt = $DbMultidict->prepare('SELECT sl,owner,title,text,medembed,medfloat FROM clilstore WHERE id=:id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    if (!($r = $stmt->fetch(PDO::FETCH_OBJ))) { throw new SM_MDexception("No unit exists for id=$id"); }
    $stmt = null;
    $sl       = $r->sl;
    $owner    = $r->owner;
    $title    = $r->title;
    $text     = $r->text;
    $medembed = $r->medembed;
    $medfloat = $r->medfloat;

    if ($sl<>'ar') { $left = 'left';  $right = 'right'; }
     else          { $left = 'right'; $right = 'left';  }

    //Prepare media (or picture)
    if ($medfloat=='') { $medfloat = 'none'; }
    $scroll = $recordVocHtml = '';
    if ($medfloat=='scroll') { $medfloat = 'none'; $scroll='scroll'; }
    $medembedHtml = ( empty($medembed) ? '' : "<div class=\"$medfloat\">$medembed</div>" );

    //Prepare linkbuttons
    $buttonsHtml = '';
    $stmt = $DbMultidict->prepare('SELECT but,wl,new,link FROM csButtons WHERE id=:id ORDER BY ord');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->bindColumn(1,$but);
    $stmt->bindColumn(2,$wl);
    $stmt->bindColumn(3,$new);
    $stmt->bindColumn(4,$link);
    while ($stmt->fetch()) {
       if (empty($but) or empty($link)) { continue; }
        if (is_numeric($link))          { $link = "/cs/$link"; unset($wl); } //a link to another unit
        if (substr($link,0,5)=='file:') { $link = "/cs/$id/".substr($link,5); }         //a link to an attached file
        if (empty($wl)) { $class = 'class="authorbut nowordlink"'; }
         else           { $class = 'class="authorbut"';  }
        if ($new) { $target = 'target="_blank"'; }
         else     { $target = 'target="_top"';   }
        $buttonsHtml .= "<a role=\"button\" class=\"nowordlink btn btn-success text-white btn-sm align-middle ml-1 mt-1 mb-1 mr-1\" href=\"$link\" $class $target>$but</a>";
    }
    $buttonedit = ( $user<>$owner && $user<>'admin'
                  ? ''
                  : "<a href=\"edit.php?id=$id&amp;view\" class=\"nowordlink btn btn-success btn-sm rounded\" target=\"_parent\" role=\"button\"><i class=\"fa fa-edit\" aria-hidden=\"true\"></i></a>"
                  );
    $stmt = $DbMultidict->prepare('SELECT record FROM users WHERE user=:user');
    $stmt->execute([':user'=>$user]);
    if (!empty($user)) {
        $record = $stmt->fetch(PDO::FETCH_COLUMN);
        $vocClass = ( $record ? 'vocOn' : 'vocOff');
        $recordVocHtml = "<span class=$vocClass onclick='vocClicked(this.className);'>"
                        ."<img src='/favicons/recordOff.png' alt='VocOff' title='Vocabulary recording is currently disabled - Click to enable'>"
                        ."<img src='/favicons/record.gif' alt='VocOn' title='Vocabulary recording is currently enabled - Click to disable'>"
                        ."</span>"
                        ."<a role='button' target='_parent' class='nowordlink btn btn-primary text-white btn-sm mt-1 mb-1' href='voc.php?user=$user&amp;sl=$sl' nowordlink target=voctab title='Open your vocabulary list in a separate tab'>Vocabulario</a>";
       $stmtGetLike = $DbMultidict->prepare('SELECT likes FROM user_unit WHERE user=:user AND unit=:id');
       $stmtGetLikes = $DbMultidict->prepare('SELECT SUM(likes) FROM user_unit WHERE unit=:id');
       $stmtGetLike->execute([':user'=>$user,':id'=>$id]);
       $stmtGetLikes->execute([':id'=>$id]);

//$likes = $stmtGetLike->fetchColumn();
//error_log("\$likes=$likes");
       if ($stmtGetLike->fetchColumn()>0) { $likeClass = 'liked'; } else { $likeClass = 'unliked'; }
       $likes = $stmtGetLikes->fetchColumn();
       $likeHtml = "<span id=likeLI class='$likeClass' onclick='likeClicked();'><img id=heartUnliked src='/favicons/unlike.png' alt='unlike'><img id=heartLiked src='/favicons/like.png' alt='like'></span><span id='likesBadge' class='badge badge-pill badge-danger'>$likes</span>";
    }

    $shareTitle = 'Clilstore unit: ' . urlencode($title);
    $shareURL = urlencode("https://clilstore.eu/cs/$id");
    $sharebuttonFB="<a href='http://www.facebook.com/sharer.php?u=$shareURL' target='_blank' class='nowordlink' title='Share via Facebook'><img src='facebook.png' alt='Facebook' /></a>";
    $sharebuttonTw = "<a class='nowordlink' target=_blank href='https://twitter.com/intent/tweet?text=$shareTitle&amp;url=$shareURL' title='Share via Twitter'><img src='twitter.png'></a>";
    $sharebuttonWA = "<a class='nowordlink' target=_blank href='whatsapp://send?text=$shareTitle $shareURL' title='Share via Whatsapp'><img src='whatsapp.png' alt='WA'></a>";
    $sharebuttonLI = "<a class='nowordlink' target=_blank href='http://www.linkedin.com/shareArticle?mini=true&amp;url=$shareURL' title='Share via Linkedin'><img src='linkedin.png' alt='Linkedin'></a>";
    $sharebuttonEM = "<a class='nowordlink' target=_blank href='mailto:?Subject=$shareTitle&amp;Body=$shareTitle $shareURL' title='Share via Email'><img src='email.png' alt='Email'></a>";
//    if (stripos('Mobi',$_SERVER['HTTP_USER_AGENT'])===false) { $sharebuttonWA = ''; }
    $navbar1 = <<<EOD_NB1
<div class="row no-margin" style="background-color: #2c6692;">
    <div class="col-md-8" id="share-buttons">
        <a role="button" href="/clilstore" class="nowordlink btn btn-primary text-white btn-sm align-middle ml-1 mt-1 mb-1 mr-1" title="Clilstore index page" target="_parent">Clilstore</a>
             $sharebuttonFB
             $sharebuttonTw
             $sharebuttonWA
             $sharebuttonLI
             $sharebuttonEM
             $likeHtml
    </div>
    <div class="col-md-4">
             $buttonedit
             $recordVocHtml
             <a role="button" href="unitinfo.php?id=$id" class="nowordlink btn btn-primary text-white btn-sm float-right mt-1 mb-1" title="Summary and other details on this unit">Unit info</a>
    </div>
</div>


EOD_NB1;
    $navbar2 = <<<EOD_NB2
<div class="row no-margin" style="background-color: #2c6692;">
    <div class="col-md-12" id="share-buttons">
        <a role="button" href="/clilstore" class="nowordlink btn btn-primary text-white btn-sm align-middle ml-1 mt-1 mb-1 mr-1" title="Clilstore index page" target="_parent">Clilstore</a>
             $buttonsHtml
    </div>
</div>
EOD_NB2;

    echo <<<EOD1
<!DOCTYPE html>
<html lang="$sl">
<head>
    <meta charset="UTF-8">
    <title>CLILstore unit $id: $title</title>

    <link rel="icon" type="image/png" href="/favicons/clilstore.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <style>
        #share-buttons img {
            width: 40px;
            padding: 5px;
            border: 0;
            box-shadow: 0;
            display: inline;
        }
        html,body { height:100%; width:100%; overflow:auto;}
        div.none  { margin:0.5em; }
        div.left  { float:left;  margin:0.5em; }
        div.right { float:right; margin:0.5em; }
        div.text  { margin-bottom:3px; }
        div.scroll{ width:100%; height:400px; padding:2px 1px 1px 2px; overflow:auto; }
        ul.linkbuts { float:$left; }
        ul.linkbuts li { float:$left; }
        ul.linkbuts li.right { float:$right; }
        body { margin:0; padding:0;}
        div.body-indent { clear:both; margin:0 0.25%; padding:0 0.25% 0px 0.25%; border-top:1px solid white; }
        div.body-indent:lang(ar),
        div.body-indent:lang(ur) { direction:rtl; font-size:140%; line-height:1.25em; }
        h1:lang(ar) { font-size:150%; }
        ul.linkbuts:lang(ar) { font-size:120%; }
        span.csinfo      { border:1px solid green; border-radius:5px; padding:3px 5px; background-color:#ff0; }
        span.csbutton    { padding:0 1px 0 3px; background-color:#ff6; font-weight:bold; }
        span.csinfo a    { text-decoration:none; }
        a.csinfo:link    { color:#00f; }
        a.csinfo:visited { color:#909; }

        span.vocOff img:nth-child(1) {
            width: 40px;
            padding: 5px;
            border: 0;
            box-shadow: 0;
            display:inline;
        }

        span.vocOff img:nth-child(2) {
            width: 40px;
            padding: 5px;
            border: 0;
            box-shadow: 0;
            display:none;
        }
        span.vocOn  img:nth-child(1) {
            width: 40px;
            padding: 5px;
            border: 0;
            box-shadow: 0;
            display:none;
        }
        span.vocOn  img:nth-child(2) {
            width: 40px;
            padding: 5px;
            border: 0;
            box-shadow: 0;
            display:inline;
        }
        a.vocOff { display:none; }
        span#likeLI.liked   #heartLiked   { display:inline; }
        span#likeLI.liked   #heartUnliked { display:none;   }
        span#likeLI.unliked #heartLiked   { display:none;   }
        span#likeLI.unliked #heartUnliked { display:inline; }


   .no-margin{
    margin-right: 0px;
    margin-left: 0px;
   }

    </style>
    <script>
        function likeClicked() {
            var likeEl = document.getElementById('likeLI');
            var newLikeStatus = 'unliked', increment = -1;
            if (likeEl.className=='unliked') { newLikeStatus = 'liked'; increment = 1; }
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                if (this.status!=200 || this.responseText!='OK') { alert('$T_Error_in likeClicked:'+this.status+' '+this.responseText); return; }
                likeEl.className = newLikeStatus;
                var lbEl = document.getElementById('likesBadge');
                lbEl.innerHTML = parseInt(lbEl.innerHTML,10) + increment;
            }
            xhr.open('GET', '/clilstore/ajax/setLike.php?unit=$id&newLikeStatus=' + newLikeStatus);
            xhr.send();
        }

        function vocClicked(cl) {
            var clnew, i;
            if (cl=='vocOff') { clnew = 'vocOn';  }
                         else { clnew = 'vocOff'; }
            var vocTogReq = new XMLHttpRequest();
            vocTogReq.onload = function() {
                if (this.status!=200) { alert('Error in vocClicked:'+this.status); return; }
                var vocEls = document.getElementsByClassName(cl);
                while (vocEls.length>0) { vocEls[0].className = clnew; }
            }
            vocTogReq.open('GET', '/clilstore/ajax/setVocRecord.php?vocRecord=' + clnew);
            vocTogReq.send();
        }

        function sizeTextDiv() {
            if (document.getElementById('textDiv').className.indexOf('scroll')==-1) { return; } //No need to do anything if non-scrolling
            var winH = 460;
           //Find window height.  Next three lines copied from Internet bulletin board.  Should suit most browsers.
            if (document.body && document.body.offsetWidth) { winH = document.body.offsetHeight; }
            if (document.compatMode=='CSS1Compat' && document.documentElement && document.documentElement.offsetWidth ) { winH = document.documentElement.offsetHeight; }
            if (window.innerWidth && window.innerHeight) { winH = window.innerHeight; }
           //Find top of scrolling area and work out what is still available for it.
            var rectTop = document.getElementById('textDiv').getBoundingClientRect().top;
            var available = winH - rectTop -70;
            if (available<160) { available = 160; }
            document.getElementById('textDiv').style.height = available+'px';
/*
//Unsuccessful attempts to get momentum scrolling back working on the iPad after the resize of the scrolling area.
//Delete all this sometime, or perhaps continue again sometime with trying to find a method which works.
            var userAgent = navigator.userAgent;
            alert('userAgent='+userAgent);
            if ( userAgent.indexOf('iPad') > -1 ) {
                alert('Have guessed this is an iPad');
                document.getElementById('textDiv').style.overflow = 'scroll';
                document.getElementById('textDiv').style.WebkitOverflowScrolling = 'touch';
                document.getElementById('textDiv').style.backgroundColor = '#f99';
            }
*/
        }

        window.addEventListener('load',sizeTextDiv);

//Causes too many resizes on tablets
//        function resizeAlert() {
//            alert('Window resize detected.  About to resize the scrolling area to fit the window height.');
//            sizeTextDiv();
//        }
//        window.addEventListener('resize',resizeAlert);


//Doesn’t seem to be working
//        function reorientAlert() {
//            alert('Orientation change detected.  About to resize the scrolling area to fit the window height.');
//            sizeTextDiv();
//        }
//        window.addEventListener('orientationchange',reorientAlert);

    </script>
</head>
<body lang="$sl">
$navbar1
<div>
<wordlink noshow><p class="noshow" style="direction:ltr"><span class="csinfo">  <!--class="noshow" is for stupid IE7. Delete when IE7 is dead-->
This is a <a href="$serverhome/clilstore" class="csinfo">Clilstore</a> unit.
You can <span class="csbutton"><a href="$serverhome/cs/$id" class="csinfo">link all words to dictionaries</a></span>.
</span></p></wordlink>

<h1 style="margin:0px 0; background-color: #ffffff; padding-left: 10px">$title</h1>
$medembedHtml
<div class="$scroll" id="textDiv" style="background-color: #ffffff; padding-left: 10px; padding-right: 10px">
$text
</div>

</div>
$navbar2
<p style="clear:both;font-size:70%;margin:0;text-align:center">Short url:&nbsp;&nbsp; $serverhome/cs/$id</p>
</body>
</html>
EOD1;

    //Update hit count
    $stmt = $DbMultidict->prepare('UPDATE clilstore SET views=views+1 WHERE id=:id');
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $stmt = null;


  } catch (Exception $e) { echo $e; }

?>
