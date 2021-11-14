<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  try {
    $T = new SM_T('multidict/custom');
    $T_No_words_found = $T->h('Cha_d_fhuaireadh_facal');
    $T_Deasaich = $T->h('Deasaich');

    $idc = $_REQUEST['idc'] ?? '';
    if (empty($idc)) { throw new SM_Exception('Missing parameter: ‘idc’'); }

    $HTML = '';

    $HTML = "<p>Editing word $idc</p>";

/*
    $myCLIL = SM_myCLIL::singleton();
    $user = ( isset($myCLIL->id) ? $myCLIL->id : '' );

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $grp = "cw-$sl";
    $stmtPermission = $DbMultidict->prepare('SELECT 1 FROM userGrp WHERE user=:user AND grp=:grp');
    $stmtPermission->execute([':user'=>$user,':grp'=>$grp]);
    if ($stmtPermission->fetch()) { throw new SM_Exception("User $user has no permission to edit $sl Custom Wordlist entries"); }

    $stmt = $DbMultidict->prepare('SELECT word, disambig, gram, meaning, pri FROM custom');
    $stmt->execute([':sl'=>$sl,':tl'=>$tl,':word'=>$wordLIKE]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    $resHTML = '';

       extract($res);
       $word = htmlspecialchars($word);
       $word = strtr ( $word,
        [ '&lt;ruby&gt;'  => '<ruby>',
          '&lt;/ruby&gt;' => '</ruby>',
          '&lt;rt&gt;'    => '<rt>',
          '&lt;/rt&gt;'   => '</rt>',
          '&lt;rp&gt;'    => '<rp>',
          '&lt;/rp&gt;'   => '</rp>' ] ); //Restore ruby markup which was messed up by htmlspecialchars
       if (!empty($gram)) { $gram = " <span class=gram>$gram</span>"; }
       $editHtml2 = str_replace('{idc}',$idc,$editHtml);
       $resHTML .= <<<resHTML
<p class=word title="$disambig"><b>$word</b>$gram$editHtml2</p>
<p class=meaning>$meaning</p>
resHTML;
    }
*/

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

$HTML

</body>
</html>
END_HTML;

  } catch (Exception $e) { echo $e; }
    
?>
