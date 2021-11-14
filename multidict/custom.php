<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  try {
    $T = new SM_T('multidict/custom');
    $T_No_words_found = $T->h('Cha_d_fhuaireadh_facal');
    $T_Deasaich = $T->h('Deasaich');

    $sl   = $_REQUEST['sl']   ?? '';
    $tl   = $_REQUEST['tl']   ?? '';
    $word = $_REQUEST['word'] ?? '';  $wordLIKE = strtr($word,'*?','%_');
    if (empty($sl))   { throw new SM_Exception('Missing parameter: ‘sl’'); }
    if (empty($tl))   { throw new SM_Exception('Missing parameter: ‘tl’'); }
    if (empty($word)) { throw new SM_Exception('Missing parameter: ‘word’'); }

    $myCLIL = SM_myCLIL::singleton();
    $user = ( isset($myCLIL->id) ? $myCLIL->id : '' );

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $grp = "cw-$sl";
    $stmtPermission = $DbMultidict->prepare('SELECT 1 FROM userGrp WHERE user=:user AND grp=:grp');
    $stmtPermission->execute([':user'=>$user,':grp'=>$grp]);
    if ($stmtPermission->fetch()) { $editor = TRUE; } else { $editor = FALSE; }

    $editHtml = '';
    if ($editor) {
        $editHtml = " <img src='/icons-smo/peann.png'>";
        $editHtml = "<a href='customEdit.php?idc={idc}' title='$T_Deasaich' style='margin-left:2em'>$editHtml</a>";
    }

    $query = 'SELECT custom.idc, word, disambig, gram, meaning, pri FROM custom, customtr'
           . ' WHERE lang=:sl AND word LIKE :word AND custom.idc=customtr.idc AND tl=:tl'
           . ' UNION '
           . 'SELECT custom.idc, custom.word, disambig, gram, meaning, customwf.pri AS pri FROM customwf, custom, customtr'
           . ' WHERE wf LIKE :word AND customwf.idc=custom.idc AND lang=:sl AND customtr.idc=custom.idc AND tl=:tl'
           . ' ORDER BY pri, word, disambig';
    $stmt = $DbMultidict->prepare($query);
    $stmt->execute([':sl'=>$sl,':tl'=>$tl,':word'=>$wordLIKE]);
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $resHTML = '';
    foreach ($res as $r) {
       extract($r);
       $word = htmlspecialchars($word);
       $word = strtr ( $word,
        [ '&lt;ruby&gt;'  => '<ruby>',
          '&lt;/ruby&gt;' => '</ruby>',
          '&lt;rt&gt;'    => '<rt>',
          '&lt;/rt&gt;'   => '</rt>',
          '&lt;rp&gt;'    => '<rp>',
          '&lt;/rp&gt;'   => '</rp>' ] ); //Restore ruby markup which was messed up by htmlspecialchars
       $title = '';
       if (!empty($disambig)) {
           $title = htmlspecialchars($disambig);
           $title = " title='$title'";
       }
       if (!empty($gram)) { $gram = " <span class=gram>$gram</span>"; }
       $editHtml2 = str_replace('{idc}',$idc,$editHtml);
       $resHTML .= <<<resHTML
<p class=word title="$disambig"><b>$word</b>$gram$editHtml2</p>
<p class=meaning>$meaning</p>
resHTML;
    }
    if (empty($resHTML)) { $resHTML = "<p>$T_No_words_found</p>\n"; }

    echo <<<END_HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wordlist Dictionary</title>
    <link rel="StyleSheet" href="/css/smo.css">
    <link rel="icon" type="image/png" href="/favicons/multidict.png">
    <style>
        p.word { margin:0.2em 0; }
        p.meaning { margin:0 0 1em 1em; }
        span.gram { padding-left:0.3em; font-size:70%; color:red; }
    </style>
</head>
<body>
<div class="smo-body-indent" style="max-width:80em">

$resHTML

</body>
</html>
END_HTML;

  } catch (Exception $e) { echo $e; }
    
?>
