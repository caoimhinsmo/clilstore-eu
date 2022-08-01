<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");

  $T = new SM_T('multidict/index');
  $T_Word        = $T->h('Facal');
  $T_Search      = $T->h('Search');
  $T_From        = $T->h('From');
  $T_To          = $T->h('To');
  $T_Dictionary  = $T->h('Dictionary');
  $T_swop        = $T->h('swop');
  $T_basic       = $T->h('basic');
  $T_advanced    = $T->h('advanced');

  $T_Word_to_translate   = $T->h('Word_to_translate');
  $T_Switch_to_advanced  = $T->h('Switch_to_advanced');
  $T_Switch_to_basic     = $T->h('Switch_to_basic');
  $T_Swop_basic_advanced = $T->h('Swop_basic_advanced');
  $T_Swop_to_http        = $T->h('Swop_to_http');
  $T_Swop_to_https       = $T->h('Swop_to_https');
  $T_Swop_http_https     = $T->h('Swop_http_https');
  $T_Source_language     = $T->h('Source_language');
  $T_Target_language     = $T->h('Target_language');
  $T_Choose_dictionary   = $T->h('Choose_dictionary');
  $T_Rotation_message    = $T->h('Rotation_message');
  $T_Page_forward        = $T->h('Page_forward');
  $T_Page_back           = $T->h('Page_back');

   $menu = SM_clilHeadFoot::cabecera();

  $sid = ( !empty($_GET['sid']) ? $_GET['sid'] : null);
  $wlSession = new SM_WlSession($sid);
  $wlSession->bestDict();
  $wlSession->storeVars();
  $sid = $wlSession->sid;
  $sl  = $wlSession->sl;
  $tl  = $wlSession->tl;
  $dict= $wlSession->dict;
  $word= $wlSession->word;
  $wfs = $wlSession->wfs;
  $mode= $wlSession->mode;
  $url = $wlSession->url;

  $standalone = ( empty($url) ? 1 : 0 );
  if ($standalone) {
      $mdAdvClass = 'advanced';
      $servername = $_SERVER['SERVER_NAME'];
      $serverlink = "<a class=button style='float:left;margin:0 1px 0 0;border-radius:0;padding:1px 2px;font-size:80%' href='/' target='_top'>$servername</a>";
      $slSelectOnDisplay  = 'block';
      $slSelectOffDisplay = 'block';
      $fromClass = '';
  } else {
      $mdAdvClass = 'basic';
      $serverlink = '';
      $slSelectOnDisplay  = 'block';
      $slSelectOffDisplay = 'block';
      $fromClass = '';
  }

// The following lines are ad-hoc, to cure in a hurry a problem with the display of dictionary headword suggestions after converting Multidict from frames to iframe.
// They should be replaced with clean logic!!
$DbMultidict = SM_DbMultidictPDO::singleton('rw');
$stmt = $DbMultidict->prepare('SELECT wfs FROM wlSession WHERE sid=:sid');
$stmt->execute([':sid'=>$sid]);
$wlSession->wfs = $wfs = $stmt->fetch()['wfs'];

  $wlSession->csClickCounter(); //If called from a Clilstore unit, add 1 to the click count
  $robots = ( empty($wlSession->word) ? 'index,follow' : 'noindex,nofollow' );

  try {
  $nbSlHtml = $wlSession->nbSlHtml();
  $wordformArr = explode('|',$wlSession->wfs);
  if (sizeof($wordformArr)<2) { $wordformHtmlFull = ''; }
  else {
      foreach ($wordformArr as $key=>&$wf) {
          if ($wf==$word) { $wf = '<span class="lemmaword">' . $wf . '</span>';}
          if ($key==0)    { $wf = '<span class="lemma0">' . $wf . '</span>'; }
          if ($key<>0)    { $wf = "<a href=\"/multidict/?sid=$sid&amp;word=$word&amp;rot=$key\" class=\"lemmalink\">$wf</a>"; }
      }
      $wordformHtml = implode(' <span dir="ltr">←</span> ',$wordformArr);
      $wordformHtmlFull = <<<EODWFFH
<div class="formItem" style="margin:0 0 0 0.5%px;width:63%;border2:1px solid purple">
<div class="label" style="padding:4px 0 1px 0;overflow:hidden">$T_Rotation_message</div>
<div style="font-size:85%;color:brown">$wordformHtml ↩</div>
</div>
EODWFFH;
  }
  $dictClass = $wlSession->dictClass();
  if (substr($dictClass,0,1)=='p') { $pageNav = <<<EODpageNav
<input type="submit" name="go" value="<" style="padding:0 3px;margin-left:1.2em" title="$T_Page_back">
<input type="submit" name="go" value=">" style="padding:0 3px" title="$T_Page_forward">
EODpageNav;
  } else { $pageNav = ''; }

  $slOptionsHtml = $tlSelectHtml = $formItems = $dictSelectHtml = $dictIconsHtml = $dictIconHtml = $nbTlHtml = '';

  $slArr = SM_WlSession::slArr();
  $scriptPrev = 'Latn';
  foreach ($slArr as $lang=>$langInfo) {
      $endonym = $langInfo['endonym'];
      $script  = $langInfo['script'];
      if ($script<>$scriptPrev) {
          $slOptionsHtml .= "<option value='' disabled>&nbsp; &nbsp; $script</option>\n";
          $scriptPrev = $script;
      }
      $selectHtml = ( $sl==$lang ? ' selected=selected' : '');
      $slOptionsHtml .= "  <option value=$lang$selectHtml>$endonym</option>\n";
  }

  if (!empty($sl)) {
      $tlArray = $wlSession->tlArr();
      $scriptPrev = 'Latn';
      foreach ($tlArray as $lang=>$langInfo) {
          $endonym = $langInfo['endonym'];
          $script  = $langInfo['script'];
          if ($script<>$scriptPrev) {
              $tlSelectHtml .= "  <option value='' disabled>&nbsp; &nbsp; $script</option>\n";
              $scriptPrev = $script;
          }
          $selectedHtml = ( $tl==$lang ? ' selected=selected' : '');
          $tlSelectHtml .= "  <option value=$lang$selectedHtml>$endonym</option>\n";
      }
      $dictSelectHtml = $wlSession->dictSelectHtml();
      $dictIconsHtml  = $wlSession->dictIconsHtml();
      $dictIconHtml   = $wlSession->dictIconHtml();
      $nbTlHtml       = $wlSession->nbTlHtml();
      $formItems = <<<EOD3
<div class="formItem" style="min-width:95px;max-width:28%"><div class="label">$T_To</div>
<select name="tl" id="tl" title="$T_Target_language" onchange="submitForm('tl');">
  <option value="">-Choose-</option>
$tlSelectHtml
</select>
</div>
<div class="formItem" style="min-width:110px;max-width:40%;overflow:visible"><div class="label">$T_Dictionary $dictIconHtml</div>
<select id="dict" name="dict" onchange="submitForm();" title="$T_Choose_dictionary">
  <option value="">-Choose-</option>
$dictSelectHtml
</select><br>

</div>
<div id="noJSinfo" style="position:absolute;bottom:4px;left:6px;font-size:55%;color:green;white-space:normal">
If JavaScript is disabled you must click Search after each language change</div>
EOD3;
  }

  if (!$standalone) {    //Don’t use schemeSwop with Wordlink because doesn’t work in a frame
      $schemeSwopHtml = '';
  } else {               //Only use with standalone Multidict
      $scheme = ( empty($_SERVER['HTTPS']) ? 'http' : 'https' );
      $server_name = $_SERVER['SERVER_NAME'];
      $php_self = $_SERVER['PHP_SELF'];
      $php_self = str_replace('index.php','',$php_self);
      $schemeValue = ( $scheme=='https' ? 1 : 0 );
      $schemeSwopRange = "<input type=range min=0 max=1 step=1 value=$schemeValue style=width:3em;margin:0;padding:0>";
      if ($scheme=='https') {
          $schemeSwopHtml = "<a title='$T_Swop_to_http'>http</a>" . $schemeSwopRange . '<b>https</b>';
          $schemeSwopLocation = 'http';
      } else {
          $schemeSwopHtml = '<b>http</b>' . $schemeSwopRange . "<a title='$T_Swop_to_https'>https</a>";
          $schemeSwopLocation = 'https';
      }
      $schemeSwopLocation .= "://$server_name$php_self";
      if (!empty($_GET)) { $schemeSwopLocation .= '?' . $_SERVER['QUERY_STRING']; }
      $schemeSwopHtml = "<span class=toggle title='$T_Swop_http_https' onclick=\"window.location.replace('$schemeSwopLocation')\">$schemeSwopHtml</span>";
  }

  $advSwopHtml = "<span class=basOnly><b>$T_basic</b><input type=range id=basRange min=0 max=1 step=1 value=0 style=width:3em;margin:0;padding:0><a title='$T_Switch_to_advanced'>$T_advanced</a></span>"
                ."<span class=advOnly><a title='$T_Switch_to_basic'>$T_basic</a><input type=range id=advRange min=0 max=1 step=1 value=1 style=width:3em;margin:0;padding:0><b>$T_advanced</b></span>";

  $advSwopHtml = "<span class=toggle title='$T_Swop_basic_advanced' onclick='mdAdvSet(1-mdAdv)'>$advSwopHtml</span>";

  echo <<<EOD4
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Multidict</title>
    <meta name="robots" content="$robots">
    <link rel="icon" type="image/png" href="/favicons/wordlink.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <style>

        html,body {
        height: 100%;
        padding: 0;
        margin: 0;
        overflow: auto;
      }

       .page-container {
            margin: 0px;
        }

        .milabel{
           font-size: 0.675rem;
           margin-bottom: 0rem;
        }
        .btn-sm, .btn-group-sm > .btn {
          padding: 0.25rem 0.5rem;
          font-size: 0.675rem;
          line-height: 1.1;
          border-radius: 0.2rem;
        }

        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.675rem;
            line-height: 1.1;
            border-radius: 0.2rem;
        }
        .panel-container-vertical {
            display: flex;
            flex-direction: column;
            border: 0px solid silver;
            overflow: hidden;
        }
        .panel-top {
            flex: 0 0 auto;
            /* only manually resize */
            padding: 10px;
            height: 170px;
            width: 100%;
            white-space: nowrap;
            background: #C69C6D;
            color: white;
          }

          .splitter-horizontal {
            flex: 0 0 auto;
            height: 5px;
            background: url(../favicons/hsizegrip.png) center center no-repeat #13557A;
            z-index: -1;
          }

          .panel-bottom {
            flex: 1 1 auto;
            /* resizable */
            padding: 10px;
            min-height: 200px;
            background: #eee;
          }

           .altura{
                height: 100vh;
           }

          .fila_titulo {
            flex: 0 0 auto;
            /* only manually resize */
            padding: 0px;
            width: 100%;
            min-height: 50px;
            min-width: 150px;
            white-space: nowrap;
            background: #696B73;
            color: white;
        }
        .titulo {
            padding: 5px;
            margin-bottom: 0rem;
        }

    </style>
    <script>
        var standalone, mdAdv, mdAdvClass;
        function bodyLoad() {
            mdAdv = standalone = $standalone;
            if (standalone==1) {
                mdAdvSes = sessionStorage.getItem('mdAdvSa');
                mdAdvLoc = localStorage.getItem('mdAdvSa');
            } else {
                mdAdvSes = sessionStorage.getItem('mdAdvWl');
                mdAdvLoc = localStorage.getItem('mdAdvWl');
            }
            if       (mdAdvSes!==null) { mdAdv = mdAdvSes; }
             else if (mdAdvLoc!==null) { mdAdv = mdAdvLoc; }
            mdAdvSet(mdAdv);

            document.getElementById('noJSinfo').style.display = 'none';
            document.getElementById('dictIcons').style.display = 'block';
            document.getElementById('swop').style.display = 'block';
        }
        function mdAdvSet(val) {
            mdAdv = val;
            if (mdAdv==1) {
                mdAdvClass = 'advanced';
            } else {
                mdAdvClass = 'basic';
            }
            document.getElementById('mdAdvDiv').className = mdAdvClass;
            document.getElementById('basRange').value = mdAdv;
            document.getElementById('advRange').value = mdAdv;
            if (standalone==1) {
                sessionStorage.setItem('mdAdvSa',mdAdv);
                localStorage.setItem('mdAdvSa',mdAdv);
            } else {
                sessionStorage.setItem('mdAdvWl',mdAdv);
                localStorage.setItem('mdAdvWl',mdAdv);
            }
        }
        function slSelOn() {
            document.getElementById('slSelOn').style.display = 'block';
            document.getElementById('slSelOff').style.display = 'none';
        }
        function submitForm(langChanged) {
            if (langChanged=='sl') { document.getElementById('tl').value   = ''; }
            if (langChanged>'')    { document.getElementById('dict').value = ''; }
            document.getElementById('mdForm').submit();
        }
        function changeDict(dict) {
            document.getElementById('dict').value = dict;
            submitForm();
        }
        function swopLangs() {
            var slSelect = document.getElementById('sl');
            var tlSelect = document.getElementById('tl');
            var sl = slSelect.value;
            var tl = tlSelect.value;
            opt = document.createElement('option'); //Cruthaich option ùr airson sl, gus a bhith cinnteach gu bheil e sa liosta airson tl
            opt.setAttribute('value',sl);
            opt.appendChild(document.createTextNode(sl));
            tlSelect.appendChild(opt);
            slSelect.value = tl;
            tlSelect.value = sl;
            submitForm();
        }
        function slChange(lang) {
            document.getElementById('sl').value = lang;
            submitForm();
        }
        function tlChange(lang) {
            document.getElementById('tl').value = lang;
            submitForm();
        }
        function escapeWL() {
            window.top.location = document.getElementById('url').value;
        }
    </script>
</head>
<body>
$menu
<div class="fila_titulo">
     <p class="titulo display-4 text-white" style="font-size: 1.8rem;">MULTIDICT</p>
</div>
<div class="page-container">
      <div class="panel-container-vertical altura">
          <div class="panel-top">
            <form id="mdForm" action="./" style="margin:0 0 0 2px;padding-top:1px">
                <div class="row mb-0">
                   <div class="col-sm-12">
                      <input type="hidden" name="sid" value="$sid">
                      <div class="form-group mb-0">
                        <label class="milabel" for="word">$T_Word</label>
                        <input type="text" class="form-control form-control-sm rounded" name="word" id="word" value="$word" title="The word to lookup in the dictionary" placeholder="$T_Word_to_translate">
                      </div>
                   </div>
                </div>
                <div class="row mb-0">
                   <div class="col-5">
                      <div class="form-group mb-0">
                        <label class="milabel" from="sl">$T_From</label>
                         <select name="sl" id="sl" class="form-control form-control-sm" required title="$T_Source_language" onchange="submitForm('sl');">
                         <option value=''>- Choose -</option>
                         $slOptionsHtml
                         </select>
                         <div id=slSelOff style="display:none" onclick="slSelOn()">
                         <b>$sl</b>
                        </div>
                      </div>
                   </div>
                   <div class="col-2">
                     <div class="form-group mb-0">
                       <label class="milabel" from="swop"></label>
                       <div id="swop" style="font-weight:bold; display:block; text-align:center; cursor:pointer;" title="$T_swop" onclick="swopLangs();"><a><img src='/favicons/arrows.png' width="25"></a></div>
                     </div>
                    </div>
                   <div class="col-5">
                      <div class="form-group mb-0">
                        <label class="milabel" from="tl">$T_To</label>
                          <select class="form-control form-control-sm" name="tl" id="tl" title="$T_Target_language" onchange="submitForm('tl');">
                            <option value="">-Choose-</option>
                            $tlSelectHtml
                          </select>
                      </div>
                   </div>
                </div>
                <div class="row mt-0">
                   <div class="col-9">
                      <div class="form-group mb-0">
                        <label class="milabel" from="dict">$T_Dictionary</label>
                          <select class="form-control form-control-sm" id="dict" name="dict" onchange="submitForm();" title="$T_Choose_dictionary">
                            <option value="">-Choose-</option>
                            $dictSelectHtml
                          </select>
                      </div>
                   </div>
                   <div class="col-3">
                            <input type="submit" class="btn btn-primary btn-sm float-right" name="go" id="go" value="$T_Search" style="margin-top: 1.6rem;">
                   </div>
                </div>
            </form>
          </div>
          <div class="splitter-horizontal">
          </div>

            <div class="panel-bottom altura" id="dictionary">
                <iframe id="WLmainframe$sid" src="/multidict/multidict.php?sid=$sid" name="MDiframe$sid" style="width: 100%; height: 100%; border: none"></iframe>
            </div>
        </div>
    </div>
</div>
EOD4;
?>

<?php
  } catch (exception $e) { echo $e; }
?>

</body>
<script>
 //Placed here (instead of <body onload...>) so it doesn’t wait for the dictionary iframe to be loaded
    bodyLoad();
</script>
</html>
