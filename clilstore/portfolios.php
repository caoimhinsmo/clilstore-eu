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

  $footer = SM_clilHeadFoot::pie();
  
  $T = new SM_T('clilstore/portfolios');

  $T_Portfolio    = $T->h('Portfolio');
  $T_Hide         = $T->h('Hide');
  $T_hideShow     = $T->h('hideShow');
  $T_Student_id   = $T->h('Student_id');
  $T_Student_name = $T->h('Student_name');
  $T_Error_in     = $T->j('Error_in');
  $T_Portfolios_viewable_by = $T->h('Portfolios_viewable_by');
  $T_Show_hidden_portfolios = $T->h('Show_hidden_portfolios');

  $mdNavbar = SM_mdNavbar::mdNavbar($T->domhan);

  try {
    $myCLIL->dearbhaich();
    $loggedinUser = $myCLIL->id;

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');

    $pfHtml = '';

    $user = $_REQUEST['teacher'] ?? $loggedinUser;
    $userSC = htmlspecialchars($user);

    $stmtPfs = $DbMultidict->prepare('SELECT cspf.pf, hidden, cspf.user AS student, fullname, title, cspfPermit.id AS pid'
                                   . ' FROM cspf, cspfPermit, users'
                                   . ' WHERE teacher=:teacher AND cspf.pf=cspfPermit.pf AND cspf.user=users.user ORDER BY student ASC, prio DESC');
    $stmtPfs->execute([':teacher'=>$user]);
    $rows = $stmtPfs->fetchAll(PDO::FETCH_ASSOC);
    $menu = SM_clilHeadFoot::cabecera0($user, 2);
    foreach ($rows as $row) {
        extract($row);
        $studentSC  = htmlspecialchars($student);
        $fullnameSC = htmlspecialchars($fullname);
        $titleSC    = ( empty($title) ? 'Portfolio' : htmlspecialchars($title) );
        $rowClass = $hiddenChecked = '';
        if ($hidden) { $rowClass= 'class=hidden'; $hiddenChecked = 'checked'; }
        $pfHtml .= <<<END_pfHtml
<tr id=row$pid $rowClass>
<td class="separacion" style="background-color: #70a0b3; border-bottom: 8px solid #59BDDC; text-align: center">
<label class=toggle-switchy for=hidden$pid data-size=xxs onChange="toggleHidden('$pid')">
  <input type=checkbox id=hidden$pid $hiddenChecked>
  <span class=toggle title='$T_hideShow'><span class=switch></span></span>
</label>
</td>
<td class="separacion" style="background-color: #70a0b3; border-bottom: 8px solid #59BDDC; text-align: center; color: #fff"><a href="userinfo.php?user=$student">$studentSC</a></td>
<td class="separacion" style="background-color: #70a0b3; border-bottom: 8px solid #59BDDC; text-align: center; color: #fff">$fullnameSC</td>
<td style="background-color: #70a0b3; border-bottom: 8px solid #59BDDC; text-align: center; color: #fff"><a class="btn  btn-success" role="button" href="portfolio.php?pf=$pf">$titleSC</a></td>
</tr>
END_pfHtml;
    }

    $pfTableHtml = <<<END_pfTable
<div class="table-responsive">
    <table id=pftab class="hiding table">
       <thead>
           <tr id=pftabhead>
               <th class="separacion back-th">$T_Hide</th>
               <th class="separacion back-th">$T_Student_id</th>
               <th class="separacion back-th">$T_Student_name</th>
               <th class="back-th">$T_Portfolio</th>
            </tr>     
       <thead>
        <tbody>
           $pfHtml     
        </tbody>
    </table>
</div>
END_pfTable;

    $HTML = <<<EOD


$pfTableHtml
<label class=toggle-switchy for=showHidden data-size=xxs style="margin:1em 0 1.5em 0" onChange="toggleHiding()">
  <input type=checkbox id=showHidden>
  <span class=toggle><span class=switch></span></span>
  <span class=label style="font-size:70%">$T_Show_hidden_portfolios</span>
</label>
EOD;

  } catch (Exception $e) { $HTML = $e; }

  $HTMLDOC = <<<END_HTMLDOC
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$T_Portfolios_viewable_by $userSC</title>
   
    
    <link rel="StyleSheet" href="/css/toggle-switchy.css">
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
          
        a {
            color: #ffffff;
            text-decoration: none;
            background-color: transparent;
        }
          
         .separacion{
           border-right: 15px solid #59BDDC;
        } 
          
        .borderless td, .borderless th {
            border: none;
        }
            
        .back-th{
           background-color: #8cc1dd;
           text-align: center;
           color: #fff;
        }  
        
        table#pftab.hiding tr.hidden { display:none; }
          
        .fila_titulo {
            flex: 0 0 auto;
            /* only manually resize */
            padding: 5px;
            width: 100%;
            min-height: 50px;
            min-width: 150px;
            white-space: nowrap;
            background: #696B73;
            color: white;
            margin-bottom: 2rem;
        }


        .titulo {
            padding: 5px;
            margin-bottom: 0rem;
        }  
        
       
    </style>
    <script>
        function toggleHiding() {
            var ch  = document.getElementById('showHidden').checked;
            var tab = document.getElementById('pftab');
            if (ch) { tab.classList.remove('hiding'); }
              else  { tab.classList.add('hiding');    }
        }
        function toggleHidden(pid) {
            var ch  = document.getElementById('hidden'+pid).checked
            var xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                var resp = this.responseText;
                if (resp!='OK') { alert('$T_Error_in toggleHidden:'+resp); return; }
                var row = document.getElementById('row'+pid);
                if (ch) { row.classList.add('hidden'); }
                  else  { row.classList.remove('hidden'); }
            }
            xhttp.open('GET', 'ajax/pfSetHide.php?pid=' + pid + '&hidden=' + ch);
            xhttp.send();
        }
    </script>
</head>
<body>
$menu
<div class="fila_titulo">
       <div class="container">
          <div class="row">   
             <p class="titulo display-4 text-white" style="font-size: 1.8rem;"><a href="/clilstore">CLILSTORE</a> | $T_Portfolios_viewable_by <span>$userSC</span></p>
        </div>
    </div>  
</div>          
<div class="container">           
    <div class="row">
       $HTML
    </div>
</div>
$footer

</body>
</html>
END_HTMLDOC;

  echo $HTMLDOC;
?>
