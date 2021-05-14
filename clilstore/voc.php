<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Pragma: no-cache");

  try {
      $myCLIL = SM_myCLIL::singleton();
      if (!$myCLIL->cead('{logged-in}')) { $myCLIL->diultadh(''); }
  } catch (Exception $e) {
      $myCLIL->toradh = $e->getMessage();
  }

  $T = new SM_T('clilstore/voc');
  $T_Return = $T->h('Return');

  $T_Language   = $T->h('Language');
  $T_Word       = $T->h('Facal');
  $T_words      = $T->h('facail');
  $T_Meaning    = $T->h('Meaning');
  $T_Error_in   = $T->h('Error_in');
  $T_Hide_all   = $T->h('Hide_all');
  $T_Reveal_all = $T->h('Reveal_all');
  $T_Reveal     = $T->h('Reveal');
  $T_Randomize  = $T->h('Randomize');

  $T_Vocabulary_list_for_user_ = $T->h('Vocabulary_list_for_user_');
  $T_Clicked_in_unit           = $T->h('Clicked_in_unit');
  $T_Delete_instantaneously    = $T->h('Delete_instantaneously');
  $T_Lookup_with_Multidict     = $T->h('Lorg le Multidict');
  $T_No_words_in_voc_list      = $T->h('No_words_in_voc_list');
  $T_No_words_in_voc_list_for_ = $T->h('No_words_in_voc_list_for_');
  $T_No_words_in_voc_list_info = $T->h('No_words_in_voc_list_info');
  $T_Sort_the_column           = $T->h('Sort_the_column');
  $T_Empty_voc_list_question   = $T->h('Empty_voc_list_question');
  $T_Export_to_csv             = $T->h('Export_to_csv');
  $T_Export_to_tsv             = $T->h('Export_to_tsv');
  $T_Write_meaning_here        = $T->h('Write_meaning_here');
  $T_Test_yourself             = $T->h('Test_yourself');
  $T_Empty_voc_list_confirm    = $T->j('Empty_voc_list_confirm');

  $T_No_words_in_voc_list_info = strtr ( $T_No_words_in_voc_list_info, [ '{'=>'<i>', '}'=>'</i>' ] );

  $menu   = SM_clilHeadFoot::cabecera();
  $footer = SM_clilHeadFoot::pie();

  try {
    $myCLIL->dearbhaich();
    $loggedinUser = $myCLIL->id;
    $user = $_REQUEST['user'] ?? $loggedinUser;
    $userSC = htmlspecialchars($user);

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $vocHtml = $langButHtml = '';

    $stmt = $DbMultidict->prepare('SELECT sl, COUNT(1) AS cnt, endonym FROM csVoc,lang WHERE user=:user AND csVoc.sl=lang.id GROUP BY sl ORDER BY cnt DESC, sl');
    $stmt->execute([':user'=>$user]);
    $vocLangs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($vocLangs)) {
        $vocTableHtml = <<<END_noVocTable1
<h5 class="text-white">$T_No_words_in_voc_list</h5>
<h5 class="text-white">$T_No_words_in_voc_list_info</h5>
END_noVocTable1;
    } else {
        $slLorg  = $_GET['sl'] ?? $vocLangs[0]['sl'];
        foreach ($vocLangs as $vocLang) {
            extract($vocLang);
            if ($sl==$slLorg) {
                $slLorgEndonym = $endonym;
                $class = 'btn btn-success btn-lg mr-2 text-uppercase';
            } else {
                $class = 'btn btn-primary btn-lg mr-2 text-uppercase';
            }
            $langButArray[$sl] = "<a role='button' href='voc.php?user=$userSC&amp;sl=$sl' title='$endonym ($cnt $T_words)' class='$class'><i class='fa fa-language'></i> $sl</a>";
        }
        $langButHtml = implode(' ',$langButArray);
        $langButHtml = "$langButHtml";
        if (empty($langButArray[$slLorg])) {
            $vocTableHtml = <<<END_noVocTable2
<h5 class="text-white">$T_No_words_in_voc_list_for_ &lsquo;$slLorg&rsquo;.</h5>
<h5 class="text-white">$T_No_words_in_voc_list_info</h5>
END_noVocTable2;
        } else {
            $vocidClickOrder   = 'vocid';
            $wordClickOrder    = 'word';
            $meaningClickOrder = 'meaning';
            $order = $_REQUEST['order'] ?? 'vocidDESC';
            if ($order=='vocid') {
                $orderCondition  = 'vocid';
                $vocidClickOrder = 'vocidDESC';
            } elseif ($order=='vocidDESC') {
                $orderCondition = 'vocid DESC';
                $vocidClickOrder = 'vocid';
            } elseif ($order=='word') {
                $orderCondition = 'word';
                $wordClickOrder  = 'wordDESC';
            } elseif ($order=='wordDESC') {
                $orderCondition = 'word DESC';
                $wordClickOrder = 'word';
            } elseif ($order=='meaning') {
                $orderCondition = 'meaning, word';
                $meaningClickOrder  = 'meaningDESC';
            } elseif ($order=='meaningDESC') {
                $orderCondition = 'meaning DESC, word';
                $meaningClickOrder = 'meaning';
            } else {
                $orderCondition = 'vocid';
            }
            $stmt = $DbMultidict->prepare("SELECT vocid,word,calls,head,meaning FROM csVoc WHERE user=:user AND sl=:sl ORDER BY $orderCondition");
            $stmt->execute([':user'=>$user,':sl'=>$slLorg]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                extract($row);
                $queryVU = 'SELECT unit, calls AS callsVU, title FROM csVocUnit, clilstore WHERE vocid=:vocid AND clilstore.id=unit ORDER BY unit';
                $stmtVU = $DbMultidict->prepare($queryVU);
                $stmtVU->execute([':vocid'=>$vocid]);
                $rowsVU = $stmtVU->fetchAll(PDO::FETCH_ASSOC);
                $unitHtmlArr = [];
                foreach ($rowsVU as $rowVU) {
                    extract($rowVU);
                    $title = addslashes($title);
                    $callsVUhtml = ( $callsVU==1 ? '' : "<span class=callcount>×$callsVU</span>" );
                    $unitHtmlArr[] = "<a href='/cs/$unit' title='$title'>$unit</a>$callsVUhtml";
                }
                $unitsHtml = implode(' ',$unitHtmlArr);
                $meaningSC  = htmlspecialchars($meaning);
                $vocHtml .= <<<END_vocHtml
<tbody>
<tr id=row$vocid class="bg-primary text-white">
<td><img src='/icons-smo/curAs.png' alt='Delete' title='$T_Delete_instantaneously' onclick="deleteVocWord('$vocid')"></td>
<td title='$T_Lookup_with_Multidict'><a href='/multidict/?sl=$slLorg&amp;word=$word' target=vocmdtab><img src=/favicons/multidict.png alt=''> $word</a></td>
<td class=meaning><a class=reveal href="javascript:;" onclick="reveal(this)">$T_Reveal</a>
    <input type="text" class="form-control" value='$meaningSC' style='min-width:45em;max-width:55em' onchange="changeMeaning('$vocid',this.value)" title="$T_Write_meaning_here"></td>
<td><span id="$vocid-tick" class="change"><img src="check.png" height="28" width="28"></img><span></td>
<td class="text-center">$unitsHtml</td>
</tr>
</tbody>
END_vocHtml;
            }
            $T_Export_to_csv = strtr ( $T_Export_to_csv,
                                      [ '{' => '<input type=submit value="',
                                        '}' => '" class="btn-primary mr-2" style="padding:0px 8px">',
                                        '|' => '<input name=separator required value="|" maxlength=1 style="width:1.8em;text-align:center">'
                                      ] );
            $T_Export_to_tsv = strtr ( $T_Export_to_tsv,
                                      [ '{' => '<input type=submit value="',
                                        '}' => '" class="btn-primary mr-2"style="padding:0px 8px">'
                                      ] );
            $T_Empty_voc_list_question = strtr ( $T_Empty_voc_list_question,
                                      [ '{'    => "<a class=\"btn btn-outline-danger mr-2\" style=\"padding:0 3px;font-size:90%\" id=emptyBut onclick=\"emptyVocList('$user','$slLorg')\"><i class=\"fa fa-trash\" style=\"padding:0 0.3em 0 0\"></i>",
                                        '}'    => '</a>',
                                        '[sl]' => "$slLorgEndonym"
                                      ] );
            $exportHtml = <<<END_exportHtml
<div id=export>
<p><form action=vocExport.php>$T_Export_to_csv
<input type=hidden name=user value='$userSC'>
<input type=hidden name=sl value='$slLorg'>
<i>(UTF-8 encoding)</i>
</form></p>
<p><form action=vocExport.php>$T_Export_to_tsv
<input type=hidden name=user value='$userSC'>
<input type=hidden name=sl value='$slLorg'>
<i>(UTF-8 encoding)</i>
</form></p>
<p>$T_Empty_voc_list_question</p>
</div>
END_exportHtml;
            $vocTableHtml = <<<END_vocTable
<table id=vocab class="table table-hover">
<thead class="thead-light">
    <tr id=vocabhead>
      <th><a href="./voc.php?user=$user&amp;sl=$slLorg&amp;order=$vocidClickOrder" title='$T_Sort_the_column'>*</a></th>
      <th><a href="./voc.php?user=$user&amp;sl=$slLorg&amp;order=$wordClickOrder" title='$T_Sort_the_column'>$T_Word</a></th>
      <th><a href="./voc.php?user=$user&amp;sl=$slLorg&amp;order=$meaningClickOrder" title='$T_Sort_the_column'>$T_Meaning</a></th>
      <th scope="col"></th>
      <th scope="col">$T_Clicked_in_unit</th>
    </tr>
  </thead>

$vocHtml
</table>
END_vocTable;
        }
    }
    $HTML = <<<EOD
<div class="col-lg-12 text-center">
<h1 style="font-size:140%;margin:0;padding-top:0.5em" class="text-white">$T_Vocabulary_list_for_user_ <span style="color:brown">$user</span></h1>
</div>
<div class="col-lg-12 text-center">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group mr-2" role="group">
            $langButHtml
        </div>
   </div>
</div>
<div class="col-lg-12" style="margin:0.5em 0">
<h5 class="text-white">$T_Language: $slLorgEndonym</h5>
</div>
<div class="col-lg-12 text-white">
$exportHtml
</div>
<div class="col-lg-12">
<h5 class="text-white">$T_Test_yourself&nbsp;
<a role='button' href='javascript:hideAll();'  class='btn btn-primary btn-lg mr-2'>$T_Hide_all</a>
<a role='button' href='javascript:randomize()' class='btn btn-primary btn-lg mr-2'>$T_Randomize</a>
<a role='button' href='javascript:revealAll()' class='btn btn-primary btn-lg mr-2'>$T_Reveal_all</a>
</h5>
</div>

$vocTableHtml
EOD;

  } catch (Exception $e) { $HTML = $e; }

  $HTMLDOC = <<<END_HTMLDOC
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$T_Vocabulary_list_for_user_ $user</title>
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/scripts.js"></script>
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/login.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/favicons/clilstore.png">
    <style>
        .bg-primary {
           background-color: #35a4bf !important;
        }
        .bg-primary:hover {
           background-color: #35a4bf !important;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary.active, .open>.dropdown-toggle.btn-primary {
            color: #fff;
            background-color: #28a745;
            border-color: #28a745; /*set the color you want here*/
        }

        a {
            color: #ffffff;
            text-decoration: none;
            background-color: transparent;
        }
        table#vocab th a       { color:#495047; }
        table#vocab th a:hover { color:blue;    }
        span.change { opacity:0; color:white; }
        span.change.changed { color:green; animation:appearFade 5s; }
        @keyframes appearFade { from { opacity:1; background-color:#35a4bf; } 20% { opacity:0.8; background-color:transparent; } to { opacity:0; } }
        table#vocab td.meaning a.reveal { display:none; margin-left:1em; font-size:120%; padding:0 5px; background-color:#28a; color:white; border-radius:4px; }
        table#vocab td.meaning a.reveal:hover {background-color:#090; text-decoration:none; }
        table#vocab td.meaning.hide input { display:none }
        table#vocab td.meaning.hide a.reveal { display:inline; }
        div#export { margin:0 2em 2em 3em; border:2px solid white; border-radius:0.5em; padding:0.1em 0.6em; font-size:85%; }
        div#export p { margin:0.3em 0; }
        div#export i { padding-left:1em; font-size:90%; color:#eee; }
        div#export a.btn-outline-danger       { background-color:#28a; border:0; margin-left:0.5em; }
        div#export a.btn-outline-danger:hover { background-color:red; }
    </style>
    <script>
        function deleteVocWord(vocid) {
            var xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                var resp = this.responseText;
                if (resp!='OK') { alert('$T_Error_in deleteVocWord:'+resp); return; }
                var el = document.getElementById('row'+vocid);
                el.parentNode.removeChild(el);
            }
            xhttp.open('GET', 'ajax/deleteVocWord.php?vocid=' + vocid);
            xhttp.send();
        }
        function changeMeaning(vocid,meaning) {
            var xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                var resp = this.responseText;
                if (resp!='OK') { alert('$T_Error_in changeMeaning:'+resp); return; }
                var tickel = document.getElementById(vocid+'-tick');
                tickel.classList.remove('changed'); //Remove the class (if required) and add again after a tiny delay, to restart the animation
                setTimeout(function(){tickel.classList.add('changed');},50);
            }
            xhttp.open('GET', 'ajax/changeMeaning.php?vocid=' + vocid + '&meaning=' + encodeURI(meaning));
            xhttp.send();
        }
        function emptyVocList(user,sl) {
            var confirmMessage = "$T_Empty_voc_list_confirm".replace('[sl]','‘'+sl+'’');
            var r = confirm(confirmMessage);
            if (r==true) {
                var xhttp = new XMLHttpRequest();
                xhttp.onload = function() {
                    var resp = this.responseText;
                    if (resp!='OK') { alert('$T_Error_in emptyVocList:'+resp); return; }
                    window.location = window.location.href.split('?')[0];
                }
                xhttp.open('GET', 'ajax/emptyVocList.php?user='+user+'&sl=' +sl,true);
                xhttp.send();
                return false;
            }
        }
        function hideAll() {
            var inputEls = document.querySelectorAll('table#vocab td.meaning input');
            for (let inputEl of inputEls) {
                if (inputEl.value > '') { inputEl.closest('td.meaning').classList.add('hide'); }
            }
        }
        function revealAll() {
            var inputEls = document.querySelectorAll('table#vocab td.meaning input');
            for (let inputEl of inputEls) {
                if (inputEl.value > '') { inputEl.closest('td.meaning').classList.remove('hide'); }
            }
        }
        function reveal(el) {
            el.closest('td.meaning').classList.remove('hide');
            return false;
        }
        function randomize() {
            alert('This feature has not yet been implemented - Coming soon');
        }
    </script>
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
END_HTMLDOC;

  echo $HTMLDOC;
?>
