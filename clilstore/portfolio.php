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

  $T = new SM_T('clilstore/portfolio');

  $T_Clilstore_unit = $T->h('Clilstore_unit');
  $T_userid         = $T->h('userid');
  $T_Edit_the_title = $T->h('Edit_the_title');
  $T_Add_a_teacher  = $T->h('Add_a_teacher');
  $T_My_portfolios  = $T->h('My_portfolios');
  $T_Create         = $T->h('Create');
  $T_Promote        = $T->h('Promote');
  $T_Move_up        = $T->h('Move_up');
  $T_Move_down      = $T->h('Move_down');
  $T_Edit_this_item = $T->h('Edit_this_item');
  $T_Save_your_edit = $T->h('Save_your_edit');
  $T_Add_an_item    = $T->h('Add_an_item');
  $T_My_work        = $T->h('My_work');
  $T_Close          = $T->h('Close');
  $T_Sharing        = $T->h('Sharing');
  $T_Error_in       = $T->j('Error_in');
  $T_Is_this_OK     = $T->j('Is_this_OK');

  $T_Portfolio_for_user_     = $T->h('Portfolio_for_user_');
  $T_What_I_have_learned     = $T->h('What_I_have_learned');
  $T_Links_to_my_work        = $T->h('Links_to_my_work');
  $T_Delete_instantaneously  = $T->h('Delete_instantaneously');
  $T_Create_a_new_portfolio  = $T->h('Create_a_new_portfolio');
  $T_Title_of_your_portfolio = $T->h('Title_of_your_portfolio');
  $T_New_portfolio_advice    = strtr( $T->h('New_portfolio_advice'), ['&lt;br&gt;'=>'<br>'] );
  $T_New_teacher_advice      = strtr( $T->h('New_teacher_advice'),   ['&lt;br&gt;'=>'<br>'] );
  $T_active_portfolio        = $T->h('active_portfolio');
  $T_this_portfolio          = $T->h('this_portfolio');
  $T_Delete_this_portfolio   = $T->h('Delete_this_portfolio');
  $T_Delete_this_item        = $T->h('Delete_this_item');
  $T_Edit_unit_in_portfolio  = $T->h('Edit_unit_in_portfolio');
  $T_Completely_remove_unit  = $T->h('Completely_remove_unit');
  $T_A_URL_must_begin_with_  = $T->j('A_URL_must_begin_with_');
  $T_No_such_userid_as_      = $T->j('No_such_userid_as_');
  $T_Have_changed_URL_to_    = $T->j('Have_changed_URL_to_');
  $T_Err_Title_too_short     = $T->j('Err_Title_too_short');

  $T_No_teachers_can_yet_view       = $T->h('No_teachers_can_yet_view');
  $T_Following_teacher_can_view     = $T->h('Following_teacher_can_view');
  $T_Following_2_teachers_can_view  = $T->h('Following_2_teachers_can_view');
  $T_Following_teachers_can_view    = $T->h('Following_teachers_can_view');
  $T_Your_teachers_Clilstore_id     = $T->h('Your_teachers_Clilstore_id');
  $T_Promote_to_active_portfolio    = $T->h('Promote_to_active_portfolio');
  $T_Portfolio__does_not_exist      = $T->h('Portfolio__does_not_exist');
  $T_You_do_not_have_read_access    = $T->h('You_do_not_have_read_access');
  $T_Portfolio_contains_no_units    = $T->h('Portfolio_contains_no_units');
  $T_You_can_add_Clilstore_units    = $T->h('You_can_add_Clilstore_units');
  $T_Remove_permission_from_teacher = $T->h('Remove_permission_from_teacher');
  $T_Remove_unit_from_portfolio     = $T->h('Remove_unit_from_portfolio');
  $T_Move_this_unit_to_portfolio    = $T->h('Move_this_unit_to_portfolio');
  $T_Completely_delete_portfolio    = $T->j('Completely_delete_portfolio');
  $T_Teacher_already_has_access     = $T->j('Teacher_already_has_access');
  $T_Must_give_portfolio_a_name     = $T->j('Must_give_portfolio_a_name');
  $T_Unit_already_in_that_portfolio = $T->j('Unit_already_in_that_portfolio');  

  $T_Err_Title_too_short = sprintf($T_Err_Title_too_short,1);

  $mdNavbar = SM_mdNavbar::mdNavbar($T->domhan);

  try {
    $myCLIL->dearbhaich();
    $loggedinUser = $user = $myCLIL->id;
    $menu = SM_clilHeadFoot::cabecera0($user, 1);

    $DbMultidict = SM_DbMultidictPDO::singleton('rw');

    $unitsHtml = $unitsTableHtml = $titleHtml = $permitTableHtml = $pfsTableHtml = $addTeacherHtml = $itemEditHtml = $unitMoveHtml = '';
    $maxItemlen = 250;

    $pf = $_REQUEST['pf'] ?? -1;
    if ($pf == -1) {
        $stmt = $DbMultidict->prepare('SELECT pf,title FROM cspf WHERE user=:user ORDER BY prio DESC LIMIT 1');
        $stmt->execute([':user'=>$user]);
        if (($row = $stmt->fetch(PDO::FETCH_ASSOC))) { extract($row); } else { $pf = 0; $title = ''; }
    } elseif ( $pf <> 0 ) {
        $stmt = $DbMultidict->prepare('SELECT title,user FROM cspf WHERE pf=:pf');
        $stmt->execute([':pf'=>$pf]);
        if (!($row = $stmt->fetch(PDO::FETCH_ASSOC))) { throw new SM_MDexception(strtr("$T_Portfolio__does_not_exist",['{pf}'=>$pf])); }
        extract($row);
        if ($user<>$loggedinUser) {
            $stmt2 = $DbMultidict->prepare('SELECT id FROM cspfPermit WHERE pf=:pf AND teacher=:teacher');
            $stmt2->execute([':pf'=>$pf,':teacher'=>$loggedinUser]);
            if (!$stmt2->fetch()) { throw new SM_MDexception("$T_You_do_not_have_read_access"); }
        }
    }

    $edit = ( in_array($loggedinUser, [$user,'admin']) ? 1 : 0 ); //$edit=1 indicates that the user has edit rights over the portfolio
    if ($edit) {
        $stmtPfs = $DbMultidict->prepare('SELECT pf AS portf,title AS pfTitle FROM cspf WHERE user=:user ORDER BY prio DESC');
        $stmtPfs->execute([':user'=>$user]);
        $pfsRows = $stmtPfs->fetchAll(PDO::FETCH_ASSOC);
        if (count($pfsRows)>1) {
            $ddownItems = '';
            foreach ($pfsRows as $row) {
                extract($row);
                if ($portf<>$pf) {$ddownItems .= "<div data-pf=$portf class=ddown-item onClick=movetoPf(this)>$pfTitle</div>\n"; }
            }
            $unitMoveHtml = <<<END_unitMoveHtml
<div class=edit>
<div class=ddown>
<img src='/icons-smo/pfMove.png' alt='Move portfolio' class=ddown-toggle style='width:40px;height:40px;padding:10px 0 0 10px'>
<div class=ddown-content>
<div style=padding-left:1em;font-style:italic>$T_Move_this_unit_to_portfolio   </div>
$ddownItems
</div>
</div>
</div>
END_unitMoveHtml;
        }
        $itemEditHtml = "<span class=upArrow title='$T_Move_up' onClick=moveItem(this,'up')><img src='/icons-smo/up-arrow.png' class=tool></span>"
                      . "<span class=downArrow title='$T_Move_down' onClick=moveItem(this,'down')><img src='/icons-smo/down-arrow.png' class=tool></span>"
                      . "<img src='/icons-smo/trash.png' alt='Delete' title='$T_Delete_this_item' onClick='itemDelete(this)' class=tool>";
        $LitemEditHtml = "<img src='/icons-smo/pencil.png' alt='Edit' title='$T_Edit_this_item' onClick='LitemEdit(this)' class='tool editIcon'>"
                       . "<img src='/icons-smo/save.png' class=saveIcon alt='Save' title='Save_your_edit' onClick='LitemSave(this)' class=tool> "
                       . $itemEditHtml;
        $itemEditHtml  = "<span class=edit>$itemEditHtml</span>";
        $LitemEditHtml = "<span class=edit>$LitemEditHtml</span>";
    }

    $userSC = htmlspecialchars($user) ?? '';
    $titulo = htmlspecialchars($title);
    if ($pf==0) {
        $h1 = $T_Create_a_new_portfolio;
        $h2 = '';
    } else {
        $h1 = "<span>$T_Portfolio_for_user_ <span style='color:white'>$user</span></span>";
        $h2 = "<span id=h2Span onKeypress='titleKeypress(event)'>$titulo</span>";
        if ($edit) {
            $h2 .= " <img src='/icons-smo/pencil.png' class=editIcon alt='Edit' title='$T_Edit_the_title' onClick=titleEdit(1)>";
            $h2 .= " <img src='/icons-smo/save.png' class=saveIcon alt='Save' title='$T_Save_your_edit' onClick=titleEdit(0)>";
            $h2 .= " <span id=titletick class=change>✔<span>";
        }
        $h2 = "<div class='col-md-12 mb-3 mt-3'><h4 id=h2 style='color:white'>$h2</h4></div>";
    }

    $unitToEdit = $_REQUEST['unit'] ?? 0;
    if ($unitToEdit) {
        $ord = time();
        $stmtAddUnit = $DbMultidict->prepare('INSERT IGNORE INTO cspfUnit (pf,unit,ord) VALUES (:pf,:unit,:ord)');
        $stmtAddUnit->execute([ ':pf'=>$pf, ':unit'=>$unitToEdit, ':ord'=>$ord ]);
    }

    $stmtPfu = $DbMultidict->prepare('SELECT cspfUnit.pfu, cspfUnit.unit AS csUnit, clilstore.title AS csTitle FROM cspfUnit,clilstore'
                                    . ' WHERE pf=:pf AND unit=clilstore.id ORDER BY ord');
    $stmtPfu->execute([':pf'=>$pf]);
    $pfuRows = $stmtPfu->fetchAll(PDO::FETCH_ASSOC);
    foreach ($pfuRows as $pfuRow) {
        extract($pfuRow);
        $stmtPfuL = $DbMultidict->prepare('SELECT id AS pfuL, learned FROM cspfUnitLearned WHERE pfu=:pfu ORDER BY ord');
        $stmtPfuL->execute([':pfu'=>$pfu]);
        $learnedHtml = $workHtml = $newLearnedItem = $newWorkItem = '';
        $unitidHtml = str_pad($csUnit, 5, '_', STR_PAD_LEFT);
        $unitidHtml = str_replace('_','&nbsp;',$unitidHtml);
        $rowClass = ( $csUnit==$unitToEdit ? 'class=edit' : '');
        if ($edit) {
            $upArrowHtml   = "<img title='$T_Move_up'   onClick=moveUnit(this,'up') src='/icons-smo/up-arrow.png' class='tool upArrow'>";
            $downArrowHtml = "<img title='$T_Move_down' onClick=moveUnit(this,'down') src='/icons-smo/down-arrow.png' class='tool downArrow'>";
            $moveUnitHtml   = "<span style='white-space:nowrap'>$upArrowHtml$downArrowHtml</span>";
            $removeUnitHtml = "<img src='/icons-smo/trash.png' alt='Delete' title='$T_Remove_unit_from_portfolio' onClick=\"removeUnit('$pfu')\" class=tool>";
            $editUnitHtml   = "<img src='/icons-smo/pencil.png' alt='Edit' title='$T_Edit_unit_in_portfolio' onClick=\"toggleUnitEdit('$pfu')\" class='tool editIcon'>";
        }
        $pfuLRows = $stmtPfuL->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pfuLRows as $pfuLRow) {
            extract ($pfuLRow);
            $learnedHtml .= "<li  class=\"list-group-item list-group-item-light mb-1\" id=pfuL$pfuL><span id=pfuLtext$pfuL onBlur='LitemSave(this)' onKeypress='keypress(event,this)' style='padding:1px'>$learned</span> $LitemEditHtml\n";
        }
        if ($edit) { $newLearnedItem = "<input id=pfuLnew$pfu class='edit form-control' maxlength=$maxItemlen placeholder='$T_Add_an_item' onChange=\"pfuLadd('$pfu')\">"; }
        $learnedHtml = <<<END_learnedHtml
<ul id=pfuLul$pfu class="list-group list-group-flush">
$learnedHtml
</ul>
$newLearnedItem
END_learnedHtml;
        $stmtPfuW = $DbMultidict->prepare('SELECT id AS pfuW, work, url AS workurl FROM cspfUnitWork WHERE pfu=:pfu ORDER BY ord');
        $stmtPfuW->execute([':pfu'=>$pfu]);
        $pfuWRows = $stmtPfuW->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pfuWRows as $pfuWRow) {
            extract ($pfuWRow);
            $workHtml .= "<li class=\"list-group-item list-group-item-light mb-1\" id=pfuW$pfuW><a href='$workurl'>$work</a> $itemEditHtml\n";
        }
        if ($edit) {
            $newWorkItem = "<input placeholder='$T_My_work' class='form-control mb-1' maxlength=$maxItemlen id=pfuWnewWork$pfu>"
                         . "<input class='form-control' placeholder='URL' maxlength=$maxItemlen id=pfuWnewURL$pfu>";
            $newWorkItem = "<span class=edit onChange=\"pfuWadd('$pfu')\">$newWorkItem</span>";
        }
        $workHtml = <<<END_workHtml
<ul id=pfuWul$pfu class="list-group list-group-flush">
$workHtml
</ul>
$newWorkItem
END_workHtml;
        $unitsHtml .= <<<END_unitsHtml
<tr id=pfuRow$pfu $rowClass>
<td class="separacion" style="background-color: #70a0b3; border-bottom: 8px solid #59BDDC;" width="33%">
    <table class="table borderless">
      <tr style='backgroud=color:#70a0b3'>
        <td style="vertical-align: middle; text-align: left">
           <a href='/cs/$csUnit'>$unitidHtml</a>$unitMoveHtml
        </td>
        <td style="vertical-align: middle; text-align: left">
             <a href='/cs/$csUnit'>$csTitle</a>
        </td>
        <td style="vertical-align: middle; text-align: right">
             $editUnitHtml
        </td>
         <td style="vertical-align: middle; text-align: right">
             <span class=edit>$moveUnitHtml</span>
         </td>
         <td style="vertical-align: middle; text-align: right">
             <span class=edit>$removeUnitHtml</span>
         </td>
       </tr>
    </table>
</td>
<td width="33%" class="separacion" style="background-color: #70a0b3; border-bottom: 8px solid #59BDDC;">$learnedHtml <!-- <span id="\$vocid-tick" class=change>✔<span> --></td>
<td width="33%" style="background-color: #70a0b3; border-bottom: 8px solid #59BDDC;">$workHtml</td>
</tr>
END_unitsHtml;
    }
    if (empty($unitsHtml)) {
        if ($edit) { $unitsHtml = "<i>$T_You_can_add_Clilstore_units</i>"; }
         else      { $unitsHtml = "<i>$T_Portfolio_contains_no_units</i>"; }
       $unitsHtml = "<tr><td colspan=3>$unitsHtml</td></tr>";
    }

    if ($pf<>0) {
        $unitsTableHtml = <<<END_unitsTable
<div class="table-responsive">
<table id=unitstab class="table">
<tr id=unitstabhead>
    <th width="33%" scope="col" class="separacion back-th rounded-top"><font color="#fff">$T_Clilstore_unit</font></th>
    <th width="33%" scope="col" class="separacion back-th rounded-top"><font color="#fff">$T_What_I_have_learned</font></th>
    <th width="33%" scope="col" class="back-th rounded-top"><font color="#fff">$T_Links_to_my_work</font></th>
</tr>
$unitsHtml
</table>
</div>
END_unitsTable;

        $stmtPermit = $DbMultidict->prepare('SELECT cspfPermit.id AS permitId, teacher,fullname FROM cspfPermit,users WHERE teacher=user AND pf=:pf ORDER BY teacher');
        $stmtPermit->execute([':pf'=>$pf]);
        $rows = $stmtPermit->fetchall(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            extract($row);
            $editHtml = '';
            if ($edit) {
                $editHtml = "<img src='/icons-smo/trash.png' alt='Remove'class=tool title='$T_Remove_permission_from_teacher' onclick=\"pfRemovePermit('$permitId')\">";
            }
            $permitTableHtml .= "<tr id=permitRow$permitId><td>$editHtml</td><td  style='color:#fff'> $teacher ($fullname)</td></tr>\n";
        }
        $nTeachers = count($rows);
        if      ($nTeachers==0) { $teachersMessage = "$T_No_teachers_can_yet_view";      }
         elseif ($nTeachers==1) { $teachersMessage = "$T_Following_teacher_can_view";    }
         elseif ($nTeachers==2) { $teachersMessage = "$T_Following_2_teachers_can_view"; }
         else                   { $teachersMessage = "$T_Following_teachers_can_view";   }
        if ($edit) { $addTeacherHtml = <<<END_addTeacher
<tr><td colspan=2><div class="form-group"><label style='font-size: 0.9em; font-weigth: bold; color: #fff' for="addTeacher" class='mb-0'>$T_Add_a_teacher :</label><input id=addTeacher class='form-control' placeholder="$T_userid" onChange="pfAddTeacher('$pf')"></div></td></tr>
END_addTeacher;
        }
        $permitTableHtml = <<<END_pt
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
              <span style='font-size: 1.5em; font-weigth: bold; color: #fff'><img src='/icons-smo/shared.png' width='50' alt='My Portfolios'>$T_Sharing<span>
            </div>
            <table class="card-table table">
            <tbody>
            <tr><td colspan=2 class='text-center mb-0'><p style='font-size: small; color: #fff'>$teachersMessage</p></td></tr>
            $permitTableHtml
            $addTeacherHtml
            </tbody>
            </table>
         </div>
    </div>
END_pt;
    }

    if ($edit) {
        foreach ($pfsRows as $n=>$row) {
            extract($row);
            if ($n==0) { $promoteHtml = "<span style='font-weigth: bold; color: #fff'><b>$T_active_portfolio</b>";
                         $pfTitle = "<b>$pfTitle</b>"; }
             else      { $promoteHtml = "<a class=\"btn  btn-outline-danger\" href=\"#\" role=\"button\" title='$T_Promote_to_active_portfolio' onClick=\"pfPromote('$portf')\"><span class='fa fa-arrow-up fa-sm' aria-hidden='true'></span> $T_Promote</a>"; }
            $editHtml = "<img src='/icons-smo/trash.png' class=tool alt='Delete' title='$T_Delete_this_portfolio' onclick=\"pfDelete('$portf','$pf','$n')\">";
            if ($portf==$pf) { $promoteHtml .= " &nbsp; [$T_this_portfolio]</span>"; }
            $promoteHtml = "<span>$promoteHtml</span>";
            $pfsTableHtml .= "<tr id=pfsRow$portf><td>$editHtml </td><td><a href='./portfolio.php?pf=$portf'>$pfTitle</a> </td><td>$promoteHtml</td></tr>\n";
        }
        if ($pfsTableHtml=='') { $pfsTableHtml = "<tr><td colspan=3 style='text-align:center'>You have no portfolios</td></tr>"; }
        $pfsTableHtml = <<<END_pfstab
<div class="col-md-6">
      <div class="card">
            <div class="card-header text-center">
              <span style='font-size: 1.5em; font-weigth: bold; color: #fff'><img src='/icons-smo/PORTAFOLIO.png' width='50' alt='My Portfolios' title='$T_My_portfolios'>$T_My_portfolios<span>
            </div>

            <table class="card-table table">
                <tbody>
                $pfsTableHtml
                <tr><td colspan=3 class='text-center'><a class="btn btn-success" role="button" href="portfolio.php?pf=0&amp;unit=$unitToEdit" data-toggle="modal" data-target="#createNewPortfolio">{$T_Create_a_new_portfolio}...</a></td></tr>
                <tbody>
            </table>
       </div>
</div>
<!-- Modal -->
<div class="modal fade" id="createNewPortfolio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <span style='font-size: 1.5em; font-weigth: bold; color: #fff'><img src='/icons-smo/PORTAFOLIO.png' width='50' alt='My Portfolios' title='$T_Create_a_new_portfolio'>$T_Create_a_new_portfolio</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="$T_Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    $T_New_portfolio_advice
                </div>
                <div class="form-group">
                    <label for="title_port">$T_Title_of_your_portfolio</label>
                    <input id="title_port" required class="form-control">
                </div>
                 <div class="alert alert-success" role="alert">
                    $T_New_teacher_advice
                </div>
                <div class="form-group">
                    <label for="teacher_port">$T_Your_teachers_Clilstore_id</label>
                    <input id="teacher_port" required class="form-control">
                </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="unit_port" value = '$unitToEdit'>
        <button type="button" class="btn btn-danger" data-dismiss="modal">$T_Close</button>
        <button type="button" class="btn btn-success sendPort">$T_Create</button>
      </div>
    </div>
  </div>
</div>
END_pfstab;
    }

    $HTML = <<<EOD
<div class="container">
<div class="row">
$h2
</div>
</div>
<div class="container">
<div class="row">
$unitsTableHtml
</div>
</div>
<div class="container">
<div class="row">
$permitTableHtml
$pfsTableHtml
</div>
</div>
EOD;

  } catch (Exception $e) { $HTML = $e; }

  $HTMLDOC = <<<END_HTMLDOC
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>$T_Portfolio_for_user_ $user</title>
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
        .separacion{
           border-right: 15px solid #59BDDC;
        }

        .borderless td, .borderless th {
            border: none;
        }

        .back-th{
           background-color: #8cc1dd;
           text-align: center;
        }

        .card {
                position: relative;
                display: flex;
                flex-direction: column;
                min-width: 0;
                word-wrap: break-word;
                background-color: #70a0b3;
                background-clip: border-box;
                border: 0px solid rgba(0, 0, 0, 0.125);
                border-radius: 0.25rem;
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: #8cc1dd;
            border-bottom: 1px solid #8cc1dd;
        }

        .modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            background-color: #8cc1dd;
            padding: 1rem 1rem;
            border-bottom: 1px solid #dee2e6;
            border-top-left-radius: calc(0.3rem - 1px);
            border-top-right-radius: calc(0.3rem - 1px);
        }

        .modal-body {
            position: relative;
            flex: 1 1 auto;
            padding: 1rem;
            background-color: #70a0b3;
        }

        .modal-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-end;
            padding: 0.75rem;
            background-color: #8cc1dd;
            border-top: 1px solid #dee2e6;
            border-bottom-right-radius: calc(0.3rem - 1px);
            border-bottom-left-radius: calc(0.3rem - 1px);
        }

        .back-tr{
           background-color: #70a0b3;
        }

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
        }


        .titulo {
            padding: 5px;
            margin-bottom: 0rem;
        }



        table#unitstab tr      td .edit { display:none; }
        table#unitstab tr.edit td .edit { display:inline; }


        a#emptyBut:hover,
        a#emptyBut:active,

        h4 img.editIcon { display:inline; }
        h4 img.saveIcon { display:none; }
        h4.editing img.editIcon { display:none; }
        h4.editing img.saveIcon { display:inline; }
        h4.editing span#h2Span { border:1px solid white; padding:0 0.3em; }

        li img.editIcon { display:inline; }
        li img.saveIcon { display:none; }

        img.tool { width:24px; margin-left:3px; cursor:pointer; }

        li.editing img.editIcon { display:none; }
        li.editing img.saveIcon { display:inline; }
        li:first-child span.upArrow { display:none; }
        li:last-child span.downArrow { display:none; }
        table#unitstab > tbody > tr:nth-child(2) img.upArrow   { display:none; }
        table#unitstab > tbody > tr:last-child   img.downArrow { display:none; }

        .list-group-flush .list-group-item{
            border-radius: 5px;
            background-color: #5e878e;
            color: #ffffff;
        }

        .ddown { display:inline-block; }
        .ddown-content { display:none; position:absolute; padding:2px; background-color:white; white-space:nowrap; box-shadow:0 8px 16px 0 rgba(0,0,0,0.7); z-index:1; }
        .ddown:hover .ddown-content { display:block; }
        .ddown-item:hover, .ddown-item:focus { background-color:#fca; color:#111; text-decoration:none; cursor:pointer; }

        span.change { opacity:0; color:red; }
        span.change.changed { color:green; animation:appearFade 5s; }
        @keyframes appearFade { from { opacity:1; } to { opacity:0; } }

    </style>
    <script>
        function createPortfolio(title,teacher,unit) {
            if (title=='') { alert('$T_Must_give_portfolio_a_name'); return; }
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    var resp = this.responseText;
                    if       (resp=='OK')     { window.location.href = '/clilstore/portfolio.php?unit='+unit; }
                     else if (resp=='nouser') { alert('$T_No_such_userid_as_'.replace('{userid}',teacher)); }
                     else                     { alert('$T_Error_in pfCreate: '+resp); }
                }
            }
            var formData = new FormData();
            formData.append('title',title);
            formData.append('teacher',teacher);
            formData.append('unit',unit);
            xhr.open('POST', 'ajax/pfCreate.php');
            xhr.send(formData);
        }


        function removeUnit (pfu) {
            if (confirm('$T_Completely_remove_unit')) {
                var xhr = new XMLHttpRequest();
                xhr.onload = function() {
                    var resp = this.responseText;
                    if (resp!='OK') { alert('$T_Error_in portfolio.php removeUnit\\r\\n\\r\\n'+resp); return; }
                    var el = document.getElementById('pfuRow'+pfu);
                    el.parentNode.removeChild(el);
                }
                xhr.open('GET', 'ajax/pfRemoveUnit.php?pfu='+pfu);
                xhr.send();
            }
        }

        function pfDelete (pf,thisPf,n) {
            var pfsRow = document.getElementById('pfsRow'+pf);
            pfsRow.style.backgroundColor = 'pink';
            if (confirm('$T_Completely_delete_portfolio')) {
                var xhr = new XMLHttpRequest();
                xhr.onload = function() {
                    var resp = this.responseText;
                    var nl = '\\r\\n'; //newline
                    if (resp!='OK') { alert('$T_Error_in portfolio.php pfDelete'+nl+nl+resp+nl); return; }
                    if (pf==thisPf) { window.location.href = 'portfolio.php'; } //this portfolio has now been deleted
                     else if (n==0) { window.location.href = 'portfolio.php?pf='+thisPf; } // the active portfolio has now been deleted, but but not this one
                     else           { pfsRow.parentNode.removeChild(pfsRow); }
                }
                xhr.open('GET', 'ajax/pfDelete.php?pf='+pf);
                xhr.send();
            } else { pfsRow.style.backgroundColor = ''; }
        }

        function pfPromote (pf) {
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var resp = this.responseText;
                var nl = '\\r\\n'; //newline
                if (resp!='OK') { alert('$T_Error_in portfolio.php pfPromote'+nl+nl+resp+nl); return; }
                window.location.href = 'portfolio.php';
            }
            xhr.open('GET', 'ajax/pfPromote.php?pf='+pf);
            xhr.send();
        }

        function pfAddTeacher (pf) {
            var teacher = document.getElementById('addTeacher').value.trim();
            if (teacher=='') { return; }
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    var resp = this.responseText;
                    var nl = '\\r\\n'; //newline
                    if       (resp=='OK')        { window.location.href = location.href; }
                     else if (resp=='nouser')    { alert('$T_No_such_userid_as_'.replace('{userid}',teacher)); }
                     else if (resp=='duplicate') { alert('$T_Teacher_already_has_access'); }
                     else                        { alert('$T_Error_in pfAddTeacher.php'+nl+nl+resp+nl); }
                }
            }
            var formData = new FormData();
            formData.append('pf',pf);
            formData.append('teacher',teacher);
            xhr.open('POST', 'ajax/pfAddTeacher.php'); //Safer to use POST in case of rubbish in teacher userid
            xhr.send(formData);
        }

        function pfRemovePermit (permitId) {
            var xhr = new XMLHttpRequest();
            var permitRow = document.getElementById('permitRow'+permitId);
            xhr.onload = function() {
                var resp = this.responseText;
                var nl = '\\r\\n'; //newline
                if (resp!='OK') { alert('$T_Error_in portfolio.php pfRemovePermit'+nl+nl+resp+nl); return; }
                 else           { permitRow.parentNode.removeChild(permitRow); }
            }
            xhr.open('GET', 'ajax/pfRemovePermit.php?permitId='+permitId);
            xhr.send();
        }

        function toggleUnitEdit(pfu) {
            rowEl = document.getElementById('pfuRow'+pfu);
            rowEl.classList.toggle('edit');
        }

        function pfuLadd(pfu) {
            var xhr = new XMLHttpRequest();
            var inputEl = document.getElementById('pfuLnew'+pfu);
            var newText = inputEl.value.trim();
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    var nl = '\\r\\n'; //newline
                    var resp = this.responseText;
                    var found = resp.match(/^OK:(\d+)$/)
                    if (!found) { alert('$T_Error_in pfuLadd.php'+nl+nl+resp+nl); return; }
                    var newLI = document.createElement('li');
                    var pfuL = found[1];
                    newLI.className = 'list-group-item list-group-item-light mb-1';
                    newLI.id = 'pfuL' + pfuL;
                    newLI.innerHTML = '<span id=pfuLtext' + pfuL + ' onBlur=LitemSave(this) onKeypress=keypress(event,this) style=padding:1px>' + newText + '</span> ' + "$LitemEditHtml";
                    document.getElementById('pfuLul'+pfu).appendChild(newLI);
                    inputEl.value = '';
                }
            }
            var formData = new FormData();
            formData.append('pfu',pfu);
            formData.append('newText',newText);
            xhr.open('POST', 'ajax/pfuLadd.php'); //Safer to use POST in case of rubbish in the text
            xhr.send(formData);
        }

        function pfuWadd(pfu) {
            var xhr = new XMLHttpRequest();
            var workEl = document.getElementById('pfuWnewWork'+pfu);
            var urlEl  = document.getElementById('pfuWnewURL'+pfu);
            var newWork = workEl.value.trim();
            var newURL  = urlEl.value.trim();
            var nl = '\\r\\n'; //newline
            var validURLpattern = new RegExp('^http://|^https://','i');
            if ( newURL!='' && !validURLpattern.test(newURL) ) {
                if ( ! /\//.test(newURL) ) { newURL = '/' + newURL; } //Add a leading / to newURL if necessary
                newURL = 'http:/' + newURL;
                urlEl.value = newURL;
                var message = '$T_A_URL_must_begin_with_'.replace('{http}','http://').replace('{https}','https;//') + nl+nl
                            + '$T_Have_changed_URL_to_' + nl+nl + newURL + nl+nl
                            + '$T_Is_this_OK';
                if (!confirm(message)) { return; }
            }
            if (newWork=='' || newURL=='') return;
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    var resp = this.responseText;
                    var found = resp.match(/^OK:(\d+)$/)
                    if (!found) { alert('$T_Error_in pfuWadd.php'+nl+nl+resp+nl); return; }
                    var newLI = document.createElement('li');
                    newLI.id = 'pfuW' + found[1];
                    newLI.className = 'list-group-item list-group-item-light mb-1';
                    newLI.innerHTML = '<a href="' + newURL + '">' + newWork + '</a> ' + "$itemEditHtml";
                    document.getElementById('pfuWul'+pfu).appendChild(newLI);
                    workEl.value = '';
                    urlEl.value  = '';
                }
            }
            var formData = new FormData();
            formData.append('pfu',pfu);
            formData.append('newWork',newWork);
            formData.append('newURL',newURL);
            xhr.open('POST', 'ajax/pfuWadd.php'); //Safer to use POST in case of rubbish in the text
            xhr.send(formData);
        }

        function itemDelete(el) {
            var liEl = el.closest('li');
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var resp = this.responseText;
                if (resp!='OK') { alert('$T_Error_in pfItemDelete.php\\r\\n\\r\\n'+resp); return; }
                liEl.parentNode.removeChild(liEl);
            }
            xhr.open('GET', 'ajax/pfItemDelete.php?liId='+liEl.id);
            xhr.send();
        }

        function LitemEdit(el) {
            var liEl = el.closest('li');
            var pfuL = liEl.id.substring(4);
            var textEl = document.getElementById('pfuLtext'+pfuL);
            liEl.classList.add('editing');
            textEl.setAttribute('contenteditable','true');
            textEl.focus();
        }

        function LitemSave(el) {
            var liEl = el.closest('li');
            var pfuL = liEl.id.substring(4);
            var textEl = document.getElementById('pfuLtext'+pfuL);
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var resp = this.responseText;
                if (resp!='OK') { alert('$T_Error_in pfuLsave.php\\r\\n\\r\\n'+resp); return; }
                liEl.classList.remove('editing');
                textEl.setAttribute('contenteditable','false');
            }
            var newtxt = textEl.innerText;
            var maxItemlen = $maxItemlen;
            if (newtxt.length>maxItemlen) {
                newtxt = newtxt.substring(0,maxItemlen);
                textEl.innerHTML = newtxt;
                alert('Too long - Truncated to ' + maxItemlen + ' characters');
            }
            var formData = new FormData();
            formData.append('pfuL',pfuL);
            formData.append('text',newtxt);
            xhr.open('POST', 'ajax/pfuLsave.php'); //Safer to use POST in case of rubbish in the text
            xhr.send(formData);
        }

        function keypress(event,el) {
            if (event.keyCode === 13) {
                event.preventDefault();
                LitemSave(el);
            } else if (el.innerHTML.length > $maxItemlen-1) {
                alert('Too long');
            }
        }

        function moveItem(el,direction) {
            var liEl = el.closest('li');
            if       (direction=='up')   { var swopEl = liEl.previousElementSibling; }
             else if (direction=='down') { var swopEl = liEl.nextElementSibling;     }
             else { alert('$T_Error_in moveItem. Invalid direction:'+direction); }
            if (swopEl == null) { return; } //This shouldn’t happen anyway
            var id = liEl.id;
            var swopId = swopEl.id;
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var resp = this.responseText;
                if (resp!='OK') { alert('$T_Error_in pfItemSwop.php\\r\\n\\r\\n'+resp); return; }
                if (direction=='up') { liEl.parentNode.insertBefore(liEl,swopEl); }
                 else                { liEl.parentNode.insertBefore(swopEl,liEl); }
            }
            xhr.open('GET', 'ajax/pfItemSwop.php?id='+id+'&swopId='+swopId);
            xhr.send();
        }

        function moveUnit(el,direction) {
            var trEl = el.closest('table#unitstab > tbody > tr');
            if       (direction=='up')   { var swopEl = trEl.previousElementSibling; }
             else if (direction=='down') { var swopEl = trEl.nextElementSibling;     }
             else { alert('$T_Error_in moveItem. Invalid direction:'+direction); }
            if (swopEl == null) { return; } //This shouldn’t happen anyway
            var id = trEl.id;
            var swopId = swopEl.id;
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var resp = this.responseText;
                if (resp!='OK') { alert('$T_Error_in pfItemSwop.php\\r\\n\\r\\n'+resp); return; }
                if (direction=='up') { trEl.parentNode.insertBefore(trEl,swopEl); }
                 else                { trEl.parentNode.insertBefore(swopEl,trEl); }
            }
            xhr.open('GET', 'ajax/pfUnitSwop.php?id='+id+'&swopId='+swopId);
            xhr.send();
        }

        function movetoPf(el) {
            var pf = el.getAttribute('data-pf');
            var trEl = el.closest('tr.edit');
            var pfu = trEl.id.substring(6);
            var xhr = new XMLHttpRequest();
            xhr.onload = function() {
                var resp = this.responseText;
                if (resp=='KO') { alert('$T_Unit_already_in_that_portfolio'); return; }
                if (resp!='OK') { alert('$T_Error_in pfMoveUnit.php\\r\\n\\r\\n'+resp); return; }
                trEl.style.backgroundColor = 'pink';
                trEl.remove();
            }
            xhr.open('GET', 'ajax/pfMoveUnit.php?pfu='+pfu+'&pf='+pf);
            xhr.send();
        }

        function titleEdit(onOff) {
            var h2El     = document.getElementById('h2');
            var h2SpanEl = document.getElementById('h2Span');
            if (onOff==1) {
                h2El.classList.add('editing');
                h2SpanEl.setAttribute('contenteditable','true');
                h2SpanEl.focus();
            } else {
                h2El.classList.remove('editing');
                h2SpanEl.setAttribute('contenteditable','false');
                var newTitle = h2SpanEl.innerHTML;
                if (newTitle.length < 1) {
                    alert('$T_Err_Title_too_short');
                    titleEdit(1);
                    return;
                }
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        var resp = this.responseText;
                        if (resp!='OK') { alert('$T_Error_in titleEdit~~\\r\\n\\r\\n'+resp); return; }
                    } else {
                        var tickel = document.getElementById('titletick');
                        tickel.classList.remove('changed'); //Remove the class (if required) and add again after a tiny delay, to restart the animation
                        setTimeout(function(){tickel.classList.add('changed');},50);
                    }
                }
                var formData = new FormData();
                formData.append('pf','$pf');
                formData.append('title',newTitle);
                xhr.open('POST', 'ajax/pfTitleEdit.php');
                xhr.send(formData);
            }
        }

        function titleKeypress(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                titleEdit(0);
            }
        }

    </script>

</head>
<body>
$menu
<div class="fila_titulo">
       <div class="container">
          <div class="row">
             <p class="titulo display-4 text-white" style="font-size: 1.8rem;"><a href="/clilstore">CLILSTORE</a> | $h1</p>
        </div>
    </div>
</div>
<div class="container">

    <div class="row">
       $HTML
    </div>
</div>
$footer
<script>
    $('.sendPort').click(function(){
	var title_port = $('#title_port').val();
        var teacher_port = $('#teacher_port').val();
        var unit_port = $('#unit_port').val();
        createPortfolio(title_port,teacher_port,unit_port);
    });
</script>
</body>
</html>
END_HTMLDOC;

  echo $HTMLDOC;
?>
