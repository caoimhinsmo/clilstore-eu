<?php if (!include('autoload.inc.php'))
  header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

//  header('Cache-Control: no-cache, no-store, must-revalidate');
//  header("Cache-Control:max-age=0");
  header("Cache-Control:max-age=300"); //Cache for up to 5 minutes - temporary(?) measure while loading is so slow

$refresh = $_GET['refresh'] ?? '';
$refresh2 = substr($refresh,0,2);
if ($refresh2=='16' || $refresh2=='17') { //start of timestamp
    header('Location: https://clilstore.eu/clilstore/index.php?mode=1&refresh=yes',true,301);
    exit;
}

  try {
    $T = new SM_T('clilstore/index');

    $T_First_visit_to_CS      = $T->h('First_visit_to_CS');
    $T_CS_needs_cookies       = $T->h('CS_needs_cookies');
    $T_Got_it                 = $T->h('Got_it');
    $T_If_message_persists    = $T->h('If_message_persists');
    $T_CS_is_well_behaved     = $T->h('CS_is_well_behaved');
    $T_setNewbieAlert         = $T->j('setNewbieAlert');

    $T_Teaching_units         = $T->h('Teaching_units');
    $T_for_CLIL               = $T->h('for_CLIL');
    $T_See_as_newbie          = $T->h('See_as_newbie');
    $T_For_students           = $T->h('For_students');
    $T_For_teachers           = $T->h('For_teachers');
    $T_Include_test_units     = $T->h('Include_test_units');
    $T_Include_test_units_o   = $T->h('Include_test_units_o');

    $T_UnitID                 = $T->h('csCol_id');
    $T_Views                  = $T->h('csCol_views');
    $T_Clicks                 = $T->h('csCol_clicks');
    $T_Likes                  = $T->h('csCol_likes');
    $T_Created                = $T->h('csCol_created');
    $T_Changed                = $T->h('csCol_changed');
    $T_Licence                = $T->h('csCol_licence');
    $T_Owner                  = $T->h('csCol_owner');
    $T_Language               = $T->h('Language');
    $T_Level                  = $T->h('csCol_level');
    $T_Words                  = $T->h('csCol_words');
    $T_Media                  = $T->h('csCol_medtype');
    $T_MedLength              = $T->h('csCol_medlen');
    $T_Buttons                = $T->h('csCol_buttons');
    $T_Files                  = $T->h('csCol_files');
    $T_Title                  = $T->h('Title');
    $T_Summary                = $T->h('csCol_summary');
    $T_TextOrSummary          = $T->h('csCol_text');

    $T_UnitID_title           = $T->h('UnitID_title');
    $T_Views_title            = $T->h('Views_title');
    $T_Clicks_title           = $T->h('Clicks_title');
    $T_Created_title          = $T->h('Created_title');
    $T_Changed_title          = $T->h('Changed_title');
    $T_Licence_title          = $T->h('Licence_title');
    $T_Owner_title            = $T->h('Owner_title');
    $T_by_language_code       = $T->h('by_language_code');
    $T_Level_title            = $T->h('Level_title');
    $T_Words_title            = $T->h('Words_title');
    $T_Media_title            = $T->h('Media_title');
    $T_MedLength_title        = $T->h('MedLength_title');
    $T_Buttons_title          = $T->h('Buttons_title');
    $T_Files_title            = $T->h('Files_title');

    $T_minimum_views          = $T->h('minimum_views');
    $T_maximum_views          = $T->h('maximum_views');
    $T_minimum_clicks         = $T->h('minimum_clicks');
    $T_maximum_clicks         = $T->h('maximum_clicks');
    $T_start_date             = $T->h('start_date');
    $T_minimum_CEFR_level     = $T->h('minimum_CEFR_level');
    $T_maximum_CEFR_level     = $T->h('maximum_CEFR_level');
    $T_minimum_words          = $T->h('minimum_words');
    $T_maximum_words          = $T->h('maximum_words');
    $T_media_field_title      = $T->h('media_field_title');
    $T_minimum_media_length   = $T->h('minimum_media_length');
    $T_maximum_media_length   = $T->h('maximum_media_length');
    $T_maximum_buttons        = $T->h('maximum_buttons');
    $T_minimum_buttons        = $T->h('minimum_buttons');
    $T_maximum_files          = $T->h('maximum_files');
    $T_minimum_files          = $T->h('minimum_files');
    $T_title_title            = $T->h('title_title');
    $T_text_title             = $T->h('text_title');

    $T_Click_to_sort          = $T->h('Click_to_sort','hsc');
    $T_min                    = $T->h('min');
    $T_max                    = $T->h('max');
    $T_part_placeholder       = $T->h('part_placeholder');
    $T_or_wildcard_pattern    = $T->h('or_wildcard_pattern');
    $T_Clear_filter           = $T->h('Clear_filter');
    $T_Clear_filter_title     = $T->h('Clear_filter_title');
    $T_units_found            = $T->h('units_found');
    $T_Total                  = $T->h('Iomlan');
    $T_Average                = $T->h('Average');
    $T_First_choose_language  = $T->h('First_choose_language');
    $T_No_units_match_filter  = $T->h('No_units_match_filter');
    $T_inc_d_attached_files   = $T->h('inc_d_attached_files');
    $T_incUnitMessage         = $T->h('incUnitMessage');
    $T_hours                  = $T->h('hours');
    $T_minutes                = $T->h('minutes');
    $T_seconds                = $T->h('seconds');
    $T_Search_for_t_units     = $T->h('Search_for_t_units');

    $T_If_message_persists = sprintf($T_If_message_persists,$T_Got_it);
    $T_CS_is_well_behaved = strtr( $T_CS_is_well_behaved, ['{'=>'<a href=privacyPolicy.php>','}'=>'</a>'] );

    $T_DT_sEmptyTable    = $T->j('DT_sEmptyTable');
    $T_DT_sInfo          = $T->j('DT_sInfo');
    $T_DT_sInfoEmpty     = $T->j('DT_sInfoEmpty');
    $T_DT_sInfoFiltered  = $T->j('DT_sInfoFiltered');
    $T_DT_sInfoThousands = $T->j('DT_sInfoThousands');
    $T_DT_sLengthMenu    = $T->j('DT_sLengthMenu');
    $T_DT_sLoadingRecords= $T->j('DT_sLoadingRecords');
    $T_DT_sProcessing    = $T->j('DT_sProcessing');
    $T_DT_sSearch        = $T->j('DT_sSearch');
    $T_DT_sZeroRecords   = $T->j('DT_sZeroRecords');
    $T_DT_oPaginate_sFirst       = $T->j('DT_oPaginate_sFirst');
    $T_DT_oPaginate_sPrevious    = $T->j('DT_oPaginate_sPrevious');
    $T_DT_oPaginate_sNext        = $T->j('DT_oPaginate_sNext');
    $T_DT_oPaginate_sLast        = $T->j('DT_oPaginate_sLast');
    $T_DT_buttons_colvis         = $T->j('DT_buttons_colvis');
    $T_DT_buttons_pageLength_all = $T->j('DT_buttons_pageLength_all');
    $T_DT_buttons_pageLength_    = $T->j('DT_buttons_pageLength_');
    $T_DT_oAria_sSortAscending   = $T->j('DT_oAria_sSortAscending');
    $T_DT_oAria_sSortDescending  = $T->j('DT_oAria_sSortDescending');

    $T_Clilstore_studentWelcome = $T->h('Clilstore_studentWelcome');
    $T_Clilstore_teacherWelcome = $T->h('Clilstore_teacherWelcome');

    $T_DT_rows     = $T->j('DT_rows');
    $T_DT_Show_All = $T->j('DT_Show_All');

    $tableHtml = $modoTabla = $modeAlumno= $modeProfesor = $cookieMessage = $incUnitMessage = '';
    $timeNow = time();

    if (!isset($_COOKIE['csSessionId'])) $cookieMessage = <<<EOD_cookieMessage
     <div class="alert text-center cookiealert" role="alert">
    <p class="text-white"><b>$T_First_visit_to_CS</b> $T_CS_needs_cookies
    <a type="button" class="btn btn-primary btn-sm acceptcookies" onclick=location.reload() href="?mode=1&amp;refresh=yes">
        $T_Got_it
    </a></p>
    <p class="text-white" style='font-size:80%'>$T_If_message_persists</p>
    <p class="text-white" style='font-size:80%'>$T_CS_is_well_behaved</p>
    </div>
EOD_cookieMessage;

    $myCLIL = SM_myCLIL::singleton();
    $user = ( isset($myCLIL->id) ? $myCLIL->id : '' );
    $csSess   = SM_csSess::singleton();
    $DbMultidict = SM_DbMultidictPDO::singleton('rw');
    $servername = SM_myCLIL::servername();
    $serverhome = SM_myCLIL::serverhome();

    $idioma = SM_T::hl0();

    if (isset($_GET['mode']))  { $csSess->setMode($_GET['mode']); }

    $footer = SM_clilHeadFoot::pie();

    $mode    = $csSess->getCsSession()->mode;
//$csid = $csSess->getCsSession()->csid;

    $autor = $_GET['owner'] ?? '';


    if (isset($_POST['incTest2'])){
        $incTest = 1;
    } else {
        $incTest = 0;
    }

    //$incTest = $csSess->getCsSession()->incTest;

    if ($mode == 0 || $mode == 1) { $mode1radio ='checked'; } else { $mode1radio =''; }
    if ($mode == 2 || $mode == 3) { $mode3radio ='checked'; } else { $mode3radio =''; }

    $tabletopChoices = '';

    if ($mode<=1) { $checkboxesHtml = ""; } else {
        $incTestLabel = ( empty($user)
                        ? "$T_Include_test_units"
                        : "$T_Include_test_units_o" );
       if ($incTest==1){
             $incTestChecked = 'checked';
        } else {
            $incTestChecked = '';
        }
        $checkboxesHtml = <<<CHECKBOXES
        <form id="filterForm2" method="post">
<input type="checkbox" class="custom-control-input" name="incTest2" id="incTest2" $incTestChecked tabindex=2 onChange="submitFForm2()" value="1">
<label for="incTest2" class="custom-control-label">$incTestLabel</label>
        </form>
CHECKBOXES;
    }

    if (!empty($user)) {
       //Check whether the user has any incomplete unit, and if so either delete it (if it is over 24 hours old) or issue a warning
        $stmtIncUnit = $DbMultidict->prepare('SELECT id AS incUnit, created AS incCreated FROM clilstore WHERE test=2 and owner=:user');
        $stmtIncUnit->execute([':user'=>$user]);
        $row = $stmtIncUnit->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            extract($row);
            $secondsToLive = $incCreated + 86400 - $timeNow; //Delete automatically after 1 day
            if ($secondsToLive<=0) {
                $DbMultidict->prepare('DELETE FROM csButtons WHERE id=:id')->execute(['id'=>$incUnit]);
                $DbMultidict->prepare('DELETE FROM csFiles   WHERE id=:id')->execute(['id'=>$incUnit]);
                $DbMultidict->prepare('DELETE FROM clilstore WHERE id=:id')->execute(['id'=>$incUnit]);
            } else {
                if ($secondsToLive<100) {
                    $deleteTimeMess = "$secondsToLive $T_seconds";
                } else {
                    $minutesToLive = round($secondsToLive/60);
                    if ($minutesToLive<100) {
                        $deleteTimeMess = "$minutesToLive $T_minutes";
                    } else {
                        $hoursToLive = round($minutesToLive/60);
                        $deleteTimeMess = "$hoursToLive $T_hours";
                    }
                }
                $stmtIncUnitFcount = $DbMultidict->prepare('SELECT COUNT(1) As cnt FROM csFiles WHERE id=:id');
                $stmtIncUnitFcount->execute([':id'=>$incUnit]);
                $incUnitFcount = $stmtIncUnitFcount->fetchColumn();
                $fcountMessage = ( $incUnitFcount ? ' <i>('.sprintf($T_inc_d_attached_files,$incUnitFcount).')</i>' : '' );
                $incUnitMessage = sprintf($T_incUnitMessage,$fcountMessage,$deleteTimeMess);
                $incUnitMessage = strtr($incUnitMessage,
                                         [ '{' => "<a href='edit.php?id=$incUnit' class=mybutton>",
                                           '}' => '</a>',
                                           '[' => "<a href='delete.php?id=$incUnit' class=mybutton>",
                                           ']' => '</a>',
                                           '&lt;br&gt;' => '<br>'
                                         ]);
                $incUnitMessage = "<p style='margin:0.5em 0;padding:0.5em;background-color:red;color:white;font-size:150%'>$incUnitMessage</p>";
            }
        }
    }
    $menu = SM_clilHeadFoot::cabecera0($user, $mode);

    function wildChars (&$s,&$sVis,$con) {
     //Standardises wildcard characters to SQL format (% and _), removing duplicates
     //Adds a wildcard at the end if $con='starts', and at the start if $con='contains'
     //Sets the visible parameter $sVis appropriately (using * and ? instead of % and _)
        if (empty($s)) { $sVis = ''; return; }
        if ($con=='starts')   { $s =       $s . '%'; }
        if ($con=='contains') { $s = '%' . $s . '%'; }
        $s    = strtr( $s, array('*'=>'%','?'=>'_') );
        $s    = strtr( $s, array('%_'=>'_%')        );
        $s    = strtr( $s, array('%%'=>'%')         );
        $s    = strtr( $s, array('%%'=>'%')         );
        $sVis = strtr( $s, array('%'=>'*','_'=>'?') );
        if ($con=='starts'
         || $con=='contains') { $sVis = substr($sVis,0,-1); }
        if ($con=='contains') { $sVis = substr($sVis,1);    }
    }



    $f['idFil']      =
    $f['viewsMin']   =
    $f['viewsMax']   =
    $f['clicksMin']  =
    $f['clicksMax']  =
    $f['createdMin'] =
    $f['createdMax'] =
    $f['changedMin'] =
    $f['changedMax'] =
    $f['licenceFil'] =
    $f['ownerFil']   =
    $f['slFil']      =
    $f['levelMin']   =
    $f['levelMax']   =
    $f['wordsMin']   =
    $f['wordsMax']   =
    $f['medtypeFil'] =
    $f['medlenMin']  =
    $f['medlenMax']  =
    $f['buttonsMin']  =
    $f['buttonsMax']  =
    $f['filesMin']  =
    $f['filesMax']  =
    $f['titleFil']   =
    $f['textFil']    = '';

    if (empty($_GET)) { $csSess->getFilter($f); }  //if we have no GET parameters, restore stored filter values
    elseif ( count($_GET)==1                       //and do the same if we have just a single GET parameter consisting of one of the commands mode..levelBut
          && in_array( array_keys($_GET)[0], array('mode','levelBut'))
           ) { $csSess->getFilter($f); }

    if (isset($_REQUEST['id']))         { $f['idFil']      = $_REQUEST['id'];         }
    if (isset($_REQUEST['viewsMin']))   { $f['viewsMin']   = $_REQUEST['viewsMin'];   }
    if (isset($_REQUEST['viewsMax']))   { $f['viewsMax']   = $_REQUEST['viewsMax'];   }
    if (isset($_REQUEST['clicksMin']))  { $f['clicksMin']  = $_REQUEST['clicksMin'];  }
    if (isset($_REQUEST['clicksMax']))  { $f['clicksMax']  = $_REQUEST['clicksMax'];  }
    if (isset($_REQUEST['createdMin'])) { $f['createdMin'] = $_REQUEST['createdMin']; }
    if (isset($_REQUEST['createdMax'])) { $f['createdMax'] = $_REQUEST['createdMax']; }
    if (isset($_REQUEST['changedMin'])) { $f['changedMin'] = $_REQUEST['changedMin']; }
    if (isset($_REQUEST['changedMax'])) { $f['changedMax'] = $_REQUEST['changedMax']; }
    if (isset($_REQUEST['licence']))    { $f['licenceFil'] = $_REQUEST['licence'];    }
    if (isset($_REQUEST['owner']))      { $f['ownerFil']   = $_REQUEST['owner'];      }
    if (isset($_REQUEST['sl']))         { $f['slFil']      = $_REQUEST['sl'];         }
    if (isset($_REQUEST['levelMin']))   { $f['levelMin']   = $_REQUEST['levelMin'];   }
    if (isset($_REQUEST['levelMax']))   { $f['levelMax']   = $_REQUEST['levelMax'];   }
    if (isset($_REQUEST['wordsMin']))   { $f['wordsMin']   = $_REQUEST['wordsMin'];   }
    if (isset($_REQUEST['wordsMax']))   { $f['wordsMax']   = $_REQUEST['wordsMax'];   }
    if (isset($_REQUEST['medtype']))    { $f['medtypeFil'] = $_REQUEST['medtype'];    }
    if (isset($_REQUEST['medlenMin']))  { $f['medlenMin']  = $_REQUEST['medlenMin'];  }
    if (isset($_REQUEST['medlenMax']))  { $f['medlenMax']  = $_REQUEST['medlenMax'];  }
    if (isset($_REQUEST['buttonsMin'])) { $f['buttonsMin'] = $_REQUEST['buttonsMin']; }
    if (isset($_REQUEST['buttonsMax'])) { $f['buttonsMax'] = $_REQUEST['buttonsMax']; }
    if (isset($_REQUEST['filesMin']))   { $f['filesMin']   = $_REQUEST['filesMin'];   }
    if (isset($_REQUEST['filesMax']))   { $f['filesMax']   = $_REQUEST['filesMax'];   }
    if (isset($_REQUEST['title']))      { $f['titleFil']   = $_REQUEST['title'];      }
    if (isset($_REQUEST['text']))       { $f['textFil']    = $_REQUEST['text'];       }

    if ($mode==0) {  // Keep things really simple for mode 0, basic student mode
        $f['idFil']      =
        $f['viewsMin']   =
        $f['viewsMax']   =
        $f['clicksMin']  =
        $f['clicksMax']  =
        $f['createdMin'] =
        $f['createdMax'] =
        $f['changedMin'] =
        $f['changedMax'] =
        $f['licenceFil'] =
        $f['ownerFil']   =
        $f['wordsMin']   =
        $f['wordsMax']   =
        $f['medtypeFil'] =
        $f['medlenMin']  =
        $f['medlenMax']  =
        $f['buttonsMin'] =
        $f['buttonsMax'] =
        $f['filesMin']   =
        $f['filesMax']   =
//        $f['titleFil']   =
//        $f['textFil']    =
        '';
//        if (empty($f['slFil'])) { $csSess->csFilter['sl']['m0'] = 1; }
//         else                   { $csSess->csFilter['sl']['m0'] = 0; }  // No need to display Language if it is being filtered for
        $csSess->csFilter['sl']['m0'] = 0;  // No need to display Language because it is always filtered for in mode 0
       // Set up checked values for level radio buttons
        $level = $csSess->csFilter['level']['val1'];
        if ($level==='') {
            $levelAnychecked = 'checked';
        } else {
            $levelAnychecked = '';
            $levelA1checked = ( $level== 0 ? 'checked' : '');
            $levelA2checked = ( $level==10 ? 'checked' : '');
            $levelB1checked = ( $level==20 ? 'checked' : '');
            $levelB2checked = ( $level==30 ? 'checked' : '');
            $levelC1checked = ( $level==40 ? 'checked' : '');
            $levelC2checked = ( $level==50 ? 'checked' : '');
        }
    }

    $idDisplay      = $csSess->display('id');
    $viewsDisplay   = $csSess->display('views');
    $clicksDisplay  = $csSess->display('clicks');
    $createdDisplay = $csSess->display('created');
    $changedDisplay = $csSess->display('changed');
    $licenceDisplay = $csSess->display('licence');
    $ownerDisplay   = $csSess->display('owner');
    $slDisplay      = $csSess->display('sl');
    $levelDisplay   = $csSess->display('level');
    $wordsDisplay   = $csSess->display('words');
    $medtypeDisplay = $csSess->display('medtype');
    $medlenDisplay  = $csSess->display('medlen');
    $buttonsDisplay = $csSess->display('buttons');
    $filesDisplay   = $csSess->display('files');
    $titleDisplay   = $csSess->display('title');

    if ($mode==3) {
        $deleteDisplay = 'table-cell';
        $editDisplay   = 'table-cell';
        $nowlDisplay   = 'table-cell';
    } elseif ($mode==2) {
        $deleteDisplay = 'table-cell';
        $editDisplay   = 'table-cell';
        $nowlDisplay   = 'none';
    } else {
        $deleteDisplay = 'none';
        $editDisplay   = 'none';
        $nowlDisplay   = 'none';
    }

    $f['levelMin'] = SM_csSess::levelVis2Num($f['levelMin'],'min');
    $f['levelMax'] = SM_csSess::levelVis2Num($f['levelMax'],'max');

    $whereClauses['BASE'] = 'clilstore.sl=lang.id AND clilstore.owner=users.user';
    if ($f['idFil']<>'')      { $whereClauses['id']         = 'clilstore.id=?';     }
    if ($f['viewsMin']<>'')   { $whereClauses['viewsMin']   = 'views>=?';           }
    if ($f['viewsMax']<>'')   { $whereClauses['viewsMax']   = 'views<=?';           }
    if ($f['clicksMin']<>'')  { $whereClauses['clicksMin']  = 'clicks>=?';          }
    if ($f['clicksMax']<>'')  { $whereClauses['clicksMax']  = 'clicks<=?';          }
    if ($f['createdMin']<>'') { $whereClauses['createdMin'] = 'created>=?';         }
    if ($f['createdMax']<>'') { $whereClauses['createdMax'] = 'created<=?';         }
    if ($f['changedMin']<>'') { $whereClauses['changedMin'] = 'changed>=?';         }
    if ($f['changedMax']<>'') { $whereClauses['changedMax'] = 'changed<=?';         }
    if ($f['licenceFil']<>'') { $whereClauses['licence']    = 'licence LIKE ?';     }
    if ($f['slFil']<>'')      { $whereClauses['sl']         = 'sl=?';               }
    if ($f['levelMin']!=='')  { $whereClauses['levelMin']   = 'level>=?';           }
    if ($f['levelMax']!=='')  { $whereClauses['levelMax']   = 'level<=?';           }
    if ($f['wordsMin']<>'')   { $whereClauses['wordsMin']   = 'words>=?';           }
    if ($f['wordsMax']<>'')   { $whereClauses['wordsMax']   = 'words<=?';           }
    if ($f['medtypeFil']<>'') { $whereClauses['medtype']    = 'clilstore.medtype=?';}
    if ($f['medlenMin']<>'')  { $whereClauses['medlenMin']  = 'medlen>=?';          }
    if ($f['medlenMax']<>'')  { $whereClauses['medlenMax']  = 'medlen<=?';          }
    if ($f['buttonsMin']<>'') { $whereClauses['buttonsMin'] = 'buttons>=?';         }
    if ($f['buttonsMax']<>'') { $whereClauses['buttonsMax'] = 'buttons<=?';         }
    if ($f['filesMin']<>'')   { $whereClauses['filesMin']   = 'files>=?';           }
    if ($f['filesMax']<>'')   { $whereClauses['filesMax']   = 'files<=?';           }
    if ($f['titleFil']<>'')   { $whereClauses['title']      = 'title LIKE ?';       }
    if ($f['textFil']<>'')    { $whereClauses['text']       = '(text LIKE ? OR summary LIKE ?)';  }
    if ($incTest==0)          { $whereClauses['test']       = ( empty($user)
                                                              ? 'test=0'
                                                              : "(test=0 OR (test=1 AND owner='$user'))" ); }
    //if ($mode==1 && $f['slFil']=='') { $whereClauses['zap'] = '0'; } //Zap everything and select no units if no language selected in mode 0

    $whereClause = implode(' AND ',$whereClauses);

    wildChars($f['licenceFil'],$licenceVis,'=');
    //wildChars($f['ownerFil'],  $ownerVis,  '=');
    wildChars($f['titleFil'],  $titleVis,  'contains');
    wildChars($f['textFil'],   $textVis,   'contains');

    $csSess->setFilter($f);

    $idFil      = $f['idFil'];
    $viewsMin   = $f['viewsMin'];
    $viewsMax   = $f['viewsMax'];
    $clicksMin  = $f['clicksMin'];
    $clicksMax  = $f['clicksMax'];
    $createdMin = $f['createdMin'];
    $createdMax = $f['createdMax'];
    $changedMin = $f['changedMin'];
    $changedMax = $f['changedMax'];
    $licenceFil = $f['licenceFil'];
    //$ownerFil   = $f['ownerFil'];
    $slFil      = $f['slFil'];
    $levelMin   = $f['levelMin'];
    $levelMax   = $f['levelMax'];
    $wordsMin   = $f['wordsMin'];
    $wordsMax   = $f['wordsMax'];
    $medtypeFil = $f['medtypeFil'];
    $medlenMin  = $f['medlenMin'];
    $medlenMax  = $f['medlenMax'];
    $buttonsMin = $f['buttonsMin'];
    $buttonsMax = $f['buttonsMax'];
    $filesMin   = $f['filesMin'];
    $filesMax   = $f['filesMax'];
    $titleFil   = $f['titleFil'];
    $textFil    = $f['textFil'];

    date_default_timezone_set('UTC');
    if ($createdMin<>'') { $createdMinQ = strtotime($f['createdMin'].'T00:00:00'); }
    if (!empty($createdMax)) { $createdMaxQ = strtotime($f['createdMax'].'T23:59:59'); }
    if (!empty($changedMin)) { $changedMinQ = strtotime($f['changedMin'].'T00:00:00'); }
    if (!empty($changedMax)) { $changedMaxQ = strtotime($f['changedMax'].'T23:59:59'); }
    $levelMin    = SM_csSess::levelVis2Num($levelMin,'min');
    $levelMax    = SM_csSess::levelVis2Num($levelMax,'max');
    $levelMinVis = SM_csSess::levelNum2Vis($levelMin,'min');
    $levelMaxVis = SM_csSess::levelNum2Vis($levelMax,'max');

    $idVal         =
    $viewsMinVal   =
    $viewsMaxVal   =
    $clicksMinVal  =
    $clicksMaxVal  =
    $createdMinVal =
    $createdMaxVal =
    $changedMinVal =
    $changedMaxVal =
    $licenceVal    =
    $ownerVal      =
    $slVal         =
    $levelMinVal   =
    $levelMaxVal   =
    $wordsMinVal   =
    $wordsMaxVal   =
    $medtypeVal    =
    $medlenMinVal  =
    $medlenMaxVal  =
    $buttonsMinVal =
    $buttonsMaxVal =
    $filesMinVal   =
    $filesMaxVal   =
    $titleVal      =
    $textVal       = '';

    if (!empty($idFil))      { $idVal         = "value=\"$idFil\"";      }
    if (!empty($viewsMin))   { $viewsMinVal   = "value=\"$viewsMin\"";   }
    if (!empty($viewsMax))   { $viewsMaxVal   = "value=\"$viewsMax\"";   }
    if (!empty($clicksMin))  { $clicksMinVal  = "value=\"$clicksMin\"";  }
    if (!empty($clicksMax))  { $clicksMaxVal  = "value=\"$clicksMax\"";  }
    if (!empty($createdMin)) { $createdMinVal = "value=\"$createdMin\""; }
    if (!empty($createdMax)) { $createdMaxVal = "value=\"$createdMax\""; }
    if (!empty($changedMin)) { $changedMinVal = "value=\"$changedMin\""; }
    if (!empty($changedMax)) { $changedMaxVal = "value=\"$changedMax\""; }
    if (!empty($licenceVis)) { $licenceVal    = "value=\"$licenceVis\""; }
    if (!empty($ownerVis))   { $ownerVal      = "value=\"$ownerVis\"";   }
    if (!($levelMin===''))   { $levelMinVal   = "value=\"$levelMinVis\"";}
    if (!($levelMax===''))   { $levelMaxVal   = "value=\"$levelMaxVis\"";}
    if (!empty($wordsMin))   { $wordsMinVal   = "value=\"$wordsMin\"";   }
    if (!empty($wordsMax))   { $wordsMaxVal   = "value=\"$wordsMax\"";   }
    if ($medtypeFil<>'')     { $medtypeVal    = "value=\"$medtypeFil\""; }
    if (!empty($medlenMin))  { $medlenMinVal  = "value=\"$medlenMin\"";  }
    if (!empty($medlenMax))  { $medlenMaxVal  = "value=\"$medlenMax\"";  }
    if (!empty($buttonsMin)) { $buttonsMinVal = "value=\"$buttonsMin\""; }
    if (!empty($buttonsMax)) { $buttonsMaxVal = "value=\"$buttonsMax\""; }
    if (!empty($filesMin))   { $filesMinVal   = "value=\"$filesMin\"";   }
    if (!empty($filesMax))   { $filesMaxVal   = "value=\"$filesMax\"";   }
    if (!empty($titleVis))   { $titleVal      = "value=\"$titleVis\"";   }
    if (!empty($textVis))    { $textVal       = "value=\"$textVis\"";    }


    $query = 'SELECT DISTINCT sl, endonym, script FROM clilstore,lang WHERE clilstore.sl=lang.id ORDER BY endonym';
    $stmt = $DbMultidict->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $slOptions = [];
    $scriptPrev = 'Latn';
    foreach ($results as $res) {
        extract($res);
        if ($script<>$scriptPrev) {
            $slOptions[] = "<option value='' disabled>&nbsp; &nbsp; &nbsp; -$script-</option>";
            $scriptPrev = $script;
        }
        $selected = ( $sl==$slFil ? ' selected=selected' : '');
        $slOptions[] = "<option value='$sl'$selected>$endonym ($sl)</option>";
    }
    $slOptionsHtml = implode("\n",$slOptions);
    $slSelectColor = ( $slFil=='' ? 'white' : 'yellow' );

    $stmt = $DbMultidict->prepare('SELECT DISTINCT owner FROM clilstore ORDER BY owner');
    $stmt->execute();
    $ownerList = $stmt->fetchAll(PDO::FETCH_COLUMN,0);
    foreach ($ownerList as &$owner) {
        $selected_o = ( $owner==$autor ? ' selected' : '');
        $owOptions[] = "<option value=\"$owner\"$selected_o>$owner</option>";
    }
    $owOptionsHtml = implode("\n",$owOptions);

        $levelButHtml = $csSess->levelButHtml();
        $tabletopChoices = <<<ENDtabletopChoices

        <form id="selectForm" method="post">

            <div class="form-group">
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group mr-2 input-group-sm" role="group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-language mr-1" aria-hidden="true"></i>$T_Language</span>
                            </div>
                            <select name="sl" id="sl" onchange="document.getElementById('selectForm').submit();" class="form-control form-control-sm mySelect ampliar">
                            <option value='' style='background-color:white'></option>
                            $slOptionsHtml
                            </select>
                    </div>
                </div>
            </div>

        </form>

ENDtabletopChoices;

    if ($mode==1 && $f['slFil']=='') { $noTable = false; } else { $noTable = false; }

    if (!$noTable) {
        $tableHtml = <<<END_tableHtmlBarr
<form id="filterForm" method="post">
<input type="hidden" name="filterForm" value="1">
<table id="mitabla" class="hover order-column" style="width:100%">
<thead>
<tr>
 <th>$T_UnitID</th>
 <th>$T_Views</th>
 <th>$T_Clicks</th>
 <th>$T_Created</th>
 <th>$T_Changed</th>
 <th>$T_Licence</th>
 <th>$T_Owner</th>
 <th>$T_Language</th>
 <th>$T_Level</th>
 <th>$T_Words</th>
 <th>$T_Media</th>
 <th>$T_MedLength</th>
 <th>$T_Buttons</th>
 <th>$T_Files</th>
 <th>$T_Title</th>
 <th>$T_Summary</th>
 <th style="min-width:48px"></th>
 <th>$T_Likes</th>
</tr>
</thead>
END_tableHtmlBarr;


        $orderClause = $csSess->orderClause();
        $query = 'SELECT clilstore.id,owner,fullname,sl,endonym,level,words,medtype,medlen,buttons,files,title,summary,created,changed,licence,test,views,clicks,likes'
                .' FROM clilstore,users,lang'
                ." WHERE $whereClause ORDER BY $orderClause";
        $stmt = $DbMultidict->prepare($query);
        $i = 1;
        if (!empty($whereClauses['id']))         { $stmt->bindParam($i++,$idFil);       }
        if (!empty($whereClauses['viewsMin']))   { $stmt->bindParam($i++,$viewsMin);    }
        if (!empty($whereClauses['viewsMax']))   { $stmt->bindParam($i++,$viewsMax);    }
        if (!empty($whereClauses['clicksMin']))  { $stmt->bindParam($i++,$clicksMin);   }
        if (!empty($whereClauses['clicksMax']))  { $stmt->bindParam($i++,$clicksMax);   }
        if (!empty($whereClauses['createdMin'])) { $stmt->bindParam($i++,$createdMinQ); }
        if (!empty($whereClauses['createdMax'])) { $stmt->bindParam($i++,$createdMaxQ); }
        if (!empty($whereClauses['changedMin'])) { $stmt->bindParam($i++,$changedMinQ); }
        if (!empty($whereClauses['changedMax'])) { $stmt->bindParam($i++,$changedMaxQ); }
        if (!empty($whereClauses['licence']))    { $stmt->bindParam($i++,$licenceFil);  }
        if (!empty($whereClauses['owner']))      { $stmt->bindParam($i++,$ownerFil);    }
        if (!empty($whereClauses['sl']))         { $stmt->bindParam($i++,$slFil);       }
        if (!empty($whereClauses['levelMin']))   { $stmt->bindParam($i++,$levelMin);    }
        if (!empty($whereClauses['levelMax']))   { $stmt->bindParam($i++,$levelMax);    }
        if (!empty($whereClauses['wordsMin']))   { $stmt->bindParam($i++,$wordsMin);    }
        if (!empty($whereClauses['wordsMax']))   { $stmt->bindParam($i++,$wordsMax);    }
        if (!empty($whereClauses['medtype']))    { $stmt->bindParam($i++,$medtypeFil);  }
        if (!empty($whereClauses['medlenMin']))  { $stmt->bindParam($i++,$medlenMin);   }
        if (!empty($whereClauses['medlenMax']))  { $stmt->bindParam($i++,$medlenMax);   }
        if (!empty($whereClauses['buttonsMin'])) { $stmt->bindParam($i++,$buttonsMin);  }
        if (!empty($whereClauses['buttonsMax'])) { $stmt->bindParam($i++,$buttonsMax);  }
        if (!empty($whereClauses['filesMin']))   { $stmt->bindParam($i++,$filesMin);    }
        if (!empty($whereClauses['filesMax']))   { $stmt->bindParam($i++,$filesMax);    }
        if (!empty($whereClauses['title']))      { $stmt->bindParam($i++,$titleFil);    }
        if (!empty($whereClauses['text']))       { $stmt->bindParam($i++,$textFil);
                                                   $stmt->bindParam($i++,$textFil);     }
        $stmt->execute();
       //Initialise statistics
        $nunits = 0;
        $cnt['level'] = $cnt['medlen'] = 0;
        $tot['views'] = $tot['clicks'] = $tot['likes'] = $tot['created'] = $tot['created2'] = $tot['changed'] = $tot['level']
                      = $tot['words'] = $tot['medlen'] = $tot['buttons'] = $tot['files'] = 0;
        $totalsRow = $avgRow = '';
       //
        while ($page = $stmt->fetch(PDO::FETCH_OBJ)) {
            $nunits++;
            $id      = $page->id;
            $owner   = $page->owner;
            $fullname= $page->fullname;
            $sl      = $page->sl;
            $endonym = $page->endonym;
            $level   = $page->level;
            $words   = $page->words;
            $medtype = $page->medtype;
            $medlen  = $page->medlen;
            $buttons = $page->buttons;
            $files   = $page->files;
            $title   = $page->title;
            $summary = htmlspecialchars($page->summary);
            $created = $page->created;
            $changed = $page->changed;
            $licence = $page->licence;
            $test    = $page->test;
            $views   = $page->views;
            $clicks  = $page->clicks;
            $likes  = $page->likes;
           //Increment statistics
            $tot['views']   += $views;
            $tot['clicks']  += $clicks;
            $tot['likes']   += $likes;
            $tot['created'] += $created;
            $tot['created2']+= max($created,1395144000); //Adjusted for click count time, which only started on 2014-03-18
            $tot['changed'] += $changed;
            $tot['level']   += $level;
            $tot['words']   += $words;
            $tot['medlen']  += $medlen;
            $tot['buttons'] += $buttons;
            $tot['files']   += $files;
            $cnt['level']   += ($level>-1);
            $cnt['medlen']  += ($medlen>0);
           //
            $createdObj = new DateTime("@$created");
            $createdDate     = date_format($createdObj, 'Y-m-d');
            $createdDateTime = date_format($createdObj, 'Y-m-d H:i:s');
            $changedObj = new DateTime("@$changed");
            $changedDate     = date_format($changedObj, 'Y-m-d');
            $changedDateTime = date_format($changedObj, 'Y-m-d H:i:s');
            if ($changed==$created) { $changedDate = $changedDateTime = ''; }
            $ownerHtml    = htmlspecialchars($owner);
            $fullnameHtml = htmlspecialchars($fullname);
            $ownerHtml = "<a href='userinfo.php?user=$ownerHtml' title='$fullnameHtml'>$ownerHtml</a>";
            $editHtml = $deleteHtml = '';
            $cefrHtml = SM_csSess::cefrHtml($level);
            $testHtml  = ( empty($test) ? '' : '<img src="/icons-smo/undConst.gif" alt=""> ' );
            $titleClass = ( empty($test) ? 'title' : 'title italic' );
            if ($sl=='ar') { $titleClass .= ' arabicfont'; }
            $medlenHtml = ( ( empty($medlen) && ($medtype==0 || $owner<>$user  ) )
                          ? ''
                          : SM_csSess::secs2minsecs($medlen)
                          );
            if      ($medtype==1) { $medtypeHtml = "<span class=\"fa fa-headphones\" title=\"$medlenHtml\"><label style=\"visibility: hidden;\">$medtype</label></span>"; }
             elseif ($medtype==2) { $medtypeHtml = "<span class=\"fa fa-film\" title=\"$medlenHtml\"><label style=\"visibility: hidden;\">$medtype</label></span>"; }
             else                 { $medtypeHtml = "<span class=\"fa fa-file\"><label style=\"visibility: hidden;\">$medtype</label></span>"; }
            $buttonsHtml = ( empty($buttons) ? '' : $buttons );
            $filesHtml   = ( empty($files)   ? '' : $files   );
            if ($user==$owner || $user=='admin')  {
                    $deleteHtml = '<a href="edit.php?id='.$id.'" class="btn btn-success btn-sm rounded" role="button"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a href="delete.php?id='.$id.'" class="btn btn-danger btn-sm rounded" role="button"><i class="fa fa-trash" aria-hidden="true"></i></a>
                          <input id="action" name="action" type="hidden" value="true">';
                } else {
                    $deleteHtml = '<input id="action" name="action" type="hidden" value="false">';
                }
            $titleHtml = htmlspecialchars($title);
            $titleCool = strtr($titleHtml,[ "[COOL]" => "<img src='Cool.png' alt='[COOL]' style='padding-right:0.3em'>" ]);
            $tableHtml .= '<tr>'
                        . "<td><a href='/cs/$id' title='$views views'>$id</a></td>"
                        . "<td>$views</td>"
                        . "<td>$clicks</td>"
                        . "<td title='$createdDateTime UT'>$createdDate</td>"
                        . "<td title='$changedDateTime UT'>$changedDate</td>"
                        . "<td>$licence</td>"
                        . "<td>$ownerHtml</td>"
                        . "<td title='language code: $sl'>$endonym</td>"
                        . "<td>$cefrHtml</td>"
                        . "<td>$words</td>"
                        . "<td>$medtypeHtml</td>"
                        . "<td>$medlenHtml</td>"
                        . "<td>$buttonsHtml</td>"
                        . "<td>$filesHtml</td>"
                        . "<td><a href='page.php?id=$id'>"
                        .    '</a>'
                        . "$testHtml<a href='/cs/$id' title='$summary'>$titleCool</a></td>"
                        . "<td>$summary</td>"
                        . "<td>$deleteHtml</td>"
                        . "<td>$likes</td>"
                        . "</tr>";
        }
        $stmt = null;
        $DbMultidict = null;
        if ($nunits==0) {
            $unitsFoundMessage = ( $mode==0 && $f['slFil']==''
                                 ? "$T_First_choose_language"
                                 : "$T_No_units_match_filter" );
            $unitsFoundMessage = "<p><span style='color:red'>$unitsFoundMessage</span></p>\n";
        } elseif ($nunits<2) {
            $unitsFoundMessage = '';
        } else { //Calculate and display statistics
             $unitsFoundMessage  = "<p style='margin-top:0;color:grey;font-size:70%'>$nunits $T_units_found</p>";
             if ($mode>1) {
                 $avgLevel  = ( $cnt['level']==0  ? '' : sprintf('%.1f',$tot['level']/$cnt['level']) );
                 $avgLevelHtml = SM_csSess::cefrHtml($avgLevel);
                 $avgMedlen = ( $cnt['medlen']==0 ? '' : SM_csSess::secs2minsecs(round($tot['medlen']/$cnt['medlen'])) );
                 $avgCreated = round($tot['created']/$nunits);
                 $avgCreatedObj = new DateTime("@$avgCreated");
                 $avgCreatedDate     = date_format($avgCreatedObj, 'Y-m-d');
                 $avgCreatedDateTime = date_format($avgCreatedObj, 'Y-m-d H:i:s');
                 $avgChanged = round($tot['changed']/$nunits);
                 $avgChangedObj = new DateTime("@$avgChanged");
                 $avgChangedDate     = date_format($avgChangedObj, 'Y-m-d');
                 $avgChangedDateTime = date_format($avgChangedObj, 'Y-m-d H:i:s');
                 $totViewTime  = $nunits*$timeNow - $tot['created'];
                 $totClickTime = $nunits*$timeNow - $tot['created2'];
                 $viewRate  = $tot['views']/$totViewTime;
                 $clickRate = $tot['clicks']/$totClickTime;
                 function rateMessage($rate) { return sprintf('%.4g/day, %.4g/month, %.4g/year', $rate*86400, $rate*2630000, $rate*31557600); }
                 $viewRateMessage  = rateMessage($viewRate);
                 $clickRateMessage = rateMessage($clickRate);
                 $totViewRateMessage  = rateMessage($viewRate *$nunits);
                 $totClickRateMessage = rateMessage($clickRate*$nunits);


             }
        }

        $tableHtml .= <<<END_tableHtmlBun
   <tfoot class="text-primary">
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
$unitsFoundMessage
</form>
END_tableHtmlBun;

$modeAlumno = <<<END_tableModeAlumno
<script>

$(document).ready(function() {

      $('#userTypeFilter').select2({
        selectOnClose: true,
        width: 'resolve',
        theme: "bootstrap"
      });

     $('#userTypeFilter').on('select2:select', function (e) {
        filterTable();
      });

    /* Custom filtering function which will search data in column four between two values */
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = parseInt( $('#min').val(), 10 );
            var max = parseInt( $('#max').val(), 10 );
            var words = parseFloat( data[9] ) || 0; // use data for the words column

            if ( ( isNaN( min ) && isNaN( max ) ) ||
                 ( isNaN( min ) && words <= max ) ||
                 ( min <= words   && isNaN( max ) ) ||
                 ( min <= words   && words <= max ) )
            {
                return true;
            }
            return false;
        },
        function( settings, data, dataIndex ) {
            var min_v = parseInt( $('#min_v').val(), 10 );
            var max_v = parseInt( $('#max_v').val(), 10 );
            var views = parseFloat( data[1] ) || 0; // use data for the words column

            if ( ( isNaN( min_v ) && isNaN( max_v ) ) ||
                 ( isNaN( min_v ) && views <= max_v ) ||
                 ( min_v <= views   && isNaN( max_v ) ) ||
                 ( min_v <= views   && views <= max_v ) )
            {
                return true;
            }
            return false;
        },
        function (settings, data, dataIndex) { //'data' contiene los datos de la fila
            //En la columna 1 estamos mostrando el tipo de usuario
            let userTypeColumnData = data[6] || 0;
            if (!filterByUserType(userTypeColumnData)) {
                return false;
            }
            return true;
        }
    );

    function filterByUserType(userTypeColumnData) {
        let userTypeSelected = $('#userTypeFilter').val();
        //Si la opción seleccionada es 'All', devolvemos 'true' para que pinte la fila
        if (userTypeSelected === "All") {
            return true;
        }
        //La fila sólo se va a pintar si el valor de la columna coincide con el del filtro seleccionado
        return userTypeColumnData === userTypeSelected;
    }


    var langMap = {
    en: {
          path: 'English',
          mods: {
            sInfo: "Showing del _START_ al _END_ de un total de _TOTAL_ registros"
          }
        },
    es: {
          path: 'Spanish',
          mods: {
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros"
          }
        }
    };


        var select = document.getElementById("language");
        var value = select.value;
        var lang = value.substr(0, 2);

      function getLanguage() {
        var result = null;
        var path = '//cdn.datatables.net/plug-ins/1.10.13/i18n/';
        $.ajax({
          async: false,
          url: path + langMap[lang].path + '.json',
          success: function(obj) {
            result = $.extend({}, obj, langMap[lang].mods)
          }
        })
        return result
      }

    var table = $('#mitabla').DataTable( {

        "language": {
            "sEmptyTable":      "$T_DT_sEmptyTable",
            "sInfo":            "$T_DT_sInfo",
            "sInfoEmpty":       "$T_DT_sInfoEmpty",
            "sInfoFiltered":    "$T_DT_sInfoFiltered",
//          "sInfoPostFix":     "",
            "sInfoThousands":   "$T_DT_sInfoThousands",
            "sLengthMenu":      "$T_DT_sLengthMenu",
            "sLoadingRecords":  "$T_DT_sLoadingRecords",
            "sProcessing":      "$T_DT_sProcessing",
            "sSearch":          "$T_DT_sSearch",
            "sZeroRecords":     "$T_DT_sZeroRecords",
            "oPaginate": {
                "sFirst":       "$T_DT_oPaginate_sFirst",
                "sPrevious":    "$T_DT_oPaginate_sPrevious",
                "sNext":        "$T_DT_oPaginate_sNext",
                "sLast":        "$T_DT_oPaginate_sLast"
            },
            "oAria": {
                "sSortAscending":  "$T_DT_oAria_sSortAscending",
                "sSortDescending": "DT_DT_oAria_sSortDescending"
            },
            "select": {
                "rows": {
                    "_": "%d Zeilen ausgewählt",
                    "0": "",
                    "1": "1 Zeile ausgewählt"
                }
            },
            "buttons": {
                "print":    "Drucken",
                "colvis":   "$T_DT_buttons_colvis",
                "copy":     "Kopieren",
                "copyTitle":    "In Zwischenablage kopieren",
                "copyKeys": "Taste <i>ctrl</i> oder <i>\u2318</i> + <i>C</i> um Tabelle<br>in Zwischenspeicher zu kopieren.<br><br>Um abzubrechen die Nachricht anklicken oder Escape drücken.",
                "copySuccess": {
                    "_": "%d Zeilen kopiert",
                    "1": "1 Zeile kopiert"
                },
                "pageLength": {
                    "-1": "$T_DT_buttons_pageLength_all",
                    "_":  "$T_DT_buttons_pageLength_"
                }
            }
        },

        "paging": true,
        "order": [[ 0, "desc" ]],
        "searching": true,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ 0 ],
                className: 'dt-body-center',
                "visible": true
            },
            {
                "targets": [ 1 ],
                className: 'dt-body-center',
                "visible": false
            },
            {
                "targets": [ 2 ],
                className: 'dt-body-center',
                "visible": false
            },
            {
                "targets": [ 3 ],
                "visible": false
            },
            {
                "targets": [ 4 ],
                "visible": false
            },
            {
                "targets": [ 5 ],
                "visible": false
            },
            {
                "targets": [ 7 ]

            },
            {
                "targets": [ 8 ],
                className: 'dt-body-center'
            },
            {
                "targets": [ 9 ],
                className: 'dt-body-center',
                "visible": false
            },
            {
                "targets": [ 10 ],
                className: 'dt-body-center'
            },
            {
                "targets": [ 11 ],
                "visible": false
            },
            {
                "targets": [ 12 ],
                "visible": false
            },
            {
                "targets": [ 13 ],
                "visible": false
            },
            {
                "targets": [ 15 ],
                "visible": false,
                className: 'noVis'

            },
            {
                "targets": [ 16 ],
                "visible": false,
                className: 'noVis'

            }

        ],
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10 $T_DT_rows', '25 $T_DT_rows', '50 $T_DT_rows', '100 $T_DT_rows', '$T_DT_Show_All' ]
        ],
        buttons: [
            'pageLength',
          {
            extend: 'colvis',
            columns: ':not(.noVis)',
            text: function ( dt, button, config ) {
                return dt.i18n( 'buttons.colvis', '$T_DT_buttons_colvis' );
            }
          }
        ]

    });

    //Search
    $('#buscar').on( 'keyup', function () {
    table.search( this.value ).draw();
    } );

    //Search Owner
    $('#buscar_autor').on( 'keyup', function () {
    table
        .columns( 6 )
        .search( this.value )
        .draw();
    } );

    //Search Title
    $('#buscar_titulo').on( 'keyup', function () {
    table
        .columns( 14 )
        .search( this.value )
        .draw();
    } );


    //Filter Media
    $('input:checkbox').on('change', function () {
   //build a regex filter string with an or(|) condition
   var positions = $('input:checkbox[name="media"]:checked').map(function() {
     return '^' + this.value + '$';
   }).get().join('|');

   //filter in column 1, with an regex, no smart filtering, not case sensitive
   table.column(10).search(positions, true, false, false).draw(false);

   });

   function filterTable() {
     table.draw();
    }

 //Show - Hide Columns
 $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();

        // Get the column API object
        var column = table.column( $(this).attr('data-column') );

        // Toggle the visibility
        column.visible( ! column.visible() );
    });

    //Max - Min Words
    $('#min, #max').keyup( function() {
        table.draw();
    } );

    //Max - Min Views
    $('#min_v, #max_v').keyup( function() {
        table.draw();
    } );



    $("#reset").click(function (e) {
      table.search( '' ).columns().search( '' ).draw();
      $('#buscar').val('');
      $('#userTypeFilter').val('All');
      $('#userTypeFilter').trigger('change');
      $("#media_aud").bootstrapToggle('off');
      $("#media_vid").bootstrapToggle('off');
      $("#media_doc").bootstrapToggle('off');
    });

});
   </script>
END_tableModeAlumno;
$modeProfesor = <<<END_tableModeProfesor
<script>
$(document).ready(function() {

   var estado=document.getElementById("action").value;

    $('#userTypeFilter').select2({
        selectOnClose: true,
        width: 'resolve',
        theme: "bootstrap"
      });

     $('#userTypeFilter').on('select2:select', function (e) {
        filterTable();
      });

   /* Custom filtering function which will search data in column four between two values */
    $.fn.dataTable.ext.search.push(
        function( settings, data, dataIndex ) {
            var min = parseInt( $('#min').val(), 10 );
            var max = parseInt( $('#max').val(), 10 );
            var words = parseFloat( data[9] ) || 0; // use data for the words column

            if ( ( isNaN( min ) && isNaN( max ) ) ||
                 ( isNaN( min ) && words <= max ) ||
                 ( min <= words   && isNaN( max ) ) ||
                 ( min <= words   && words <= max ) )
            {
                return true;
            }
            return false;
        },
        function( settings, data, dataIndex ) {
            var min_v = parseInt( $('#min_v').val(), 10 );
            var max_v = parseInt( $('#max_v').val(), 10 );
            var views = parseFloat( data[1] ) || 0; // use data for the words column

            if ( ( isNaN( min_v ) && isNaN( max_v ) ) ||
                 ( isNaN( min_v ) && views <= max_v ) ||
                 ( min_v <= views   && isNaN( max_v ) ) ||
                 ( min_v <= views   && views <= max_v ) )
            {
                return true;
            }
            return false;
        },
        function (settings, data, dataIndex) { //'data' contiene los datos de la fila
            //En la columna 1 estamos mostrando el tipo de usuario
            let userTypeColumnData = data[6] || 0;
            if (!filterByUserType(userTypeColumnData)) {
                return false;
            }
            return true;
        }

    );

    function filterByUserType(userTypeColumnData) {
        let userTypeSelected = $('#userTypeFilter').val();
        //Si la opción seleccionada es 'All', devolvemos 'true' para que pinte la fila
        if (userTypeSelected === "All") {
            return true;
        }
        //La fila sólo se va a pintar si el valor de la columna coincide con el del filtro seleccionado
        return userTypeColumnData === userTypeSelected;
    }

    var langMap = {
    en: {
          path: 'English',
          mods: {
            sInfo: "Showing del _START_ al _END_ de un total de _TOTAL_ registros"
          }
        },
    es: {
          path: 'Spanish',
          mods: {
            sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros"
          }
        }
    };


        var select = document.getElementById("language");
        var value = select.value;
        var lang = value.substr(0, 2);

      function getLanguage() {
        var result = null;
        var path = '//cdn.datatables.net/plug-ins/1.10.13/i18n/';
        $.ajax({
          async: false,
          url: path + langMap[lang].path + '.json',
          success: function(obj) {
            result = $.extend({}, obj, langMap[lang].mods)
          }
        })
        return result
      }

   //Datatable
    var table = $('#mitabla').DataTable( {

        "language": {
            "sEmptyTable":      "$T_DT_sEmptyTable",
            "sInfo":            "$T_DT_sInfo",
            "sInfoEmpty":       "$T_DT_sInfoEmpty",
            "sInfoFiltered":    "$T_DT_sInfoFiltered",
//          "sInfoPostFix":     "",
            "sInfoThousands":   "$T_DT_sInfoThousands",
            "sLengthMenu":      "$T_DT_sLengthMenu",
            "sLoadingRecords":  "$T_DT_sLoadingRecords",
            "sProcessing":      "$T_DT_sProcessing",
            "sSearch":          "$T_DT_sSearch",
            "sZeroRecords":     "$T_DT_sZeroRecords",
            "oPaginate": {
                "sFirst":       "$T_DT_oPaginate_sFirst",
                "sPrevious":    "$T_DT_oPaginate_sPrevious",
                "sNext":        "$T_DT_oPaginate_sNext",
                "sLast":        "$T_DT_oPaginate_sLast"
            },
            "oAria": {
                "sSortAscending":  "$T_DT_oAria_sSortAscending",
                "sSortDescending": "DT_DT_oAria_sSortDescending"
            },
            "select": {
                "rows": {
                    "_": "%d Zeilen ausgewählt",
                    "0": "",
                    "1": "1 Zeile ausgewählt"
                }
            },
            "buttons": {
                "print":    "Drucken",
                "colvis":   "$T_DT_buttons_colvis",
                "copy":     "Kopieren",
                "copyTitle":    "In Zwischenablage kopieren",
                "copyKeys": "Taste <i>ctrl</i> oder <i>\u2318</i> + <i>C</i> um Tabelle<br>in Zwischenspeicher zu kopieren.<br><br>Um abzubrechen die Nachricht anklicken oder Escape drücken.",
                "copySuccess": {
                    "_": "%d Zeilen kopiert",
                    "1": "1 Zeile kopiert"
                },
                "pageLength": {
                    "-1": "$T_DT_buttons_pageLength_all",
                    "_":  "$T_DT_buttons_pageLength_"
                }
            }
        },

        "paging": true,
        "order": [[ 0, "desc" ]],
        "searching": true,
        responsive: true,
        "columnDefs": [
            {
                "targets": [ 0 ],
                className: 'dt-body-center',
                "visible": true
            },
            {
                "targets": [ 1 ],
                className: 'dt-body-center',
                "visible": false
            },
            {
                "targets": [ 2 ],
                className: 'dt-body-center',
                "visible": false
            },
            {
                "targets": [ 3 ],
                "visible": false
            },
            {
                "targets": [ 4 ],
                "visible": false
            },
            {
                "targets": [ 5 ],
                "visible": false
            },
            {
                "targets": [ 7 ]
            },
            {
                "targets": [ 8 ],
                className: 'dt-body-center'
            },
            {
                "targets": [ 9 ],
                className: 'dt-body-center',
                "visible": false
            },
            {
                "targets": [ 10 ],
                className: 'dt-body-center'
            },
            {
                "targets": [ 11 ],
                "visible": false
            },
            {
                "targets": [ 12 ],
                "visible": false
            },
            {
                "targets": [ 13 ],
                "visible": false
            },
            {
                "targets": [ 15 ],
                "visible": false,
                className: 'noVis'

            },
            {
                "targets": [ 16 ],
                "visible": true,
                className: 'noVis'

            }

        ],
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 50, 100, -1 ],
            [ '10 $T_DT_rows', '25 $T_DT_rows', '50 $T_DT_rows', '100 $T_DT_rows', '$T_DT_Show_All' ]
        ],
        buttons: [
            'pageLength',

          {
            extend: 'colvis',
            columns: ':not(.noVis)',
            text: function ( dt, button, config ) {
                return dt.i18n( 'buttons.colvis', '$T_DT_buttons_colvis' );
            }
          }
        ]

    });

    //Search
    $('#buscar').on( 'keyup', function () {
    table.search( this.value ).draw();
    } );

    //Search Owner
    $('#buscar_autor_2').on( 'change', function () {
    table
        .columns( 6 )
        .search( this.value )
        .draw();
    } );

    //Search Title
    $('#buscar_titulo').on( 'keyup', function () {
    table
        .columns( 14 )
        .search( this.value )
        .draw();
    } );

    //Filter Media
    $('input:checkbox').on('change', function () {
   //build a regex filter string with an or(|) condition
   var positions = $('input:checkbox[name="media"]:checked').map(function() {
     return '^' + this.value + '$';
   }).get().join('|');

   //filter in column 1, with an regex, no smart filtering, not case sensitive
   table.column(10).search(positions, true, false, false).draw(false);

 });

  function filterTable() {
   table.draw();
  }

 //Show - Hide Column
 $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();

        // Get the column API object
        var column = table.column( $(this).attr('data-column') );

        // Toggle the visibility
        column.visible( ! column.visible() );
    });
 //Max - Min Words
    $('#min, #max').keyup( function() {
        table.draw();
    } );

 //Max - Min Views
    $('#min_v, #max_v').keyup( function() {
        table.draw();
    } );

    if (estado==='true'){
       $('#mitabla').DataTable().column(15).visible(true);
   }

   $("#reset").click(function (e) {
      table.search( '' ).columns().search( '' ).draw();
      $('#buscar').val('');
      $('#userTypeFilter').val('All');
      $('#userTypeFilter').trigger('change');
      $("#media_aud").bootstrapToggle('off');
      $("#media_vid").bootstrapToggle('off');
      $("#media_doc").bootstrapToggle('off');
    });

});
   </script>
END_tableModeProfesor;

if ($mode == 1) { $modoTabla = $modeAlumno;   }
if ($mode == 3) { $modoTabla = $modeProfesor; }

    }

    echo <<<EOD1
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta property="og:description" content="$T_Clilstore_studentWelcome >$T_Clilstore_teacherWelcome"/>
    <title>Clilstore - $T_Teaching_units $T_for_CLIL</title>

    <link rel="icon" type="image/png" href="/favicons/clilstore.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <link href="../css/styles.css" rel="stylesheet">
    <link href="../css/cookiealerts.css" rel="stylesheet">
    <link href="../css/responsive/smartphone.css" rel="stylesheet">
    <link href="../css/responsive/tablets.css" rel="stylesheet">
    <link href="../css/responsive/desktops.css" rel="stylesheet">
    <link href="../css/responsive/ultra_desktops.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />


    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.colVis.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/cookiealert.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>


    <script src="../js/clil.js"></script>






    <style>
        select#sl option[disabled] { background-color:#686; color:#aca; }

        @font-face {
            font-family: "Lato";
            src: url('698242188-Lato-Bla.eot');
            src: url('698242188-Lato-Bla.eot?#iefix') format('embedded-opentype'),
            url('698242188-Lato-Bla.svg#Lato Black') format('svg'),
            url('698242188-Lato-Bla.woff') format('woff'),
            url('698242188-Lato-Bla.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }


        body {
            margin: 0;
            font-family: 'Lato', sans-serif;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            background-color: #59BDDC;
          }


        .btn{
           border-radius:0px;
        }
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #ffffff;
            text-align: center;
            white-space: nowrap;
            background-color: #2c6692;
            border: 1px solid #2c6692;
            border-radius: 0.00rem;
        }

        .ampliar{
            height: 48px;
        }


        .mySelect {
            border-radius: 0;
        }

        table.dataTable thead {background-color:#2c6692; color:#ffffff;}


        th {
            border-right: 3px solid #59BDDC;
          }

        td {
            border-right: 3px solid #59BDDC;
          }

        a {
            color: #35a4bf;
            text-decoration: none;
            background-color: transparent;
          }
        a:hover {
            color: #d6370c;
            text-decoration: underline;
          }
        .btn-primary {
            color: #fff;
            background-color: #35a4bf;
            border-color: #35a4bf;
        }

        .ex_highlight #mitabla tbody tr.even:hover, #example tbody tr.even td.highlighted {
	background-color: #ECFFB3;
        }

        .ex_highlight #mitabla tbody tr.odd:hover, #example tbody tr.odd td.highlighted {
                background-color: #E6FF99;
        }

        .dataTables_wrapper .dataTables_filter {
            display: none;
        }

        .btn-secondary {
            color: #fff;
            background-color: #35a4bf;
            border-color: #35a4bf;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .btn-secondary:hover {
            color: #fff;
            background-color: #35a4bf;
            border-color: #35a4bf;
        }

        .dropdown-item.active, .dropdown-item:active {
            color: #fff;
            text-decoration: none;
            background-color: #2c6692;
        }

        .inputbuscar{
          height:65px;
          padding-left: 60px;
          z-index: 1;
        }

        .input-group-lg > .form-control, .input-group-lg > .input-group-prepend > .input-group-text, .input-group-lg > .input-group-append > .input-group-text, .input-group-lg > .input-group-prepend > .btn, .input-group-lg > .input-group-append > .btn {
            border-radius: 0.0rem;
        }

        .custom-radio-button div {
            display: inline-block;
          }
          .custom-radio-button input[type="radio"] {
            display: none;
          }
          .custom-radio-button input[type="radio"] + label {
            color: #333;
            font-family: 'Lato', sans-serif;
            font-size: 14px;
          }
          .custom-radio-button input[type="radio"] + label span {
            display: inline-block;
            width: 40px;
            height: 40px;
            margin: -1px 4px 0 0;
            vertical-align: middle;
            cursor: pointer;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.33);
            background-repeat: no-repeat;
            background-position: center;
            text-align: center;
            line-height: 44px;
          }
          .custom-radio-button input[type="radio"] + label span img {
            opacity: 0;
            transition: all 0.3s ease;
          }
          .custom-radio-button input[type="radio"]#color-red + label span {
            background-color: #35a4bf;
          }
          .custom-radio-button input[type="radio"]#color-blue + label span {
            background-color: #35a4bf;
          }

          .custom-radio-button input[type="radio"]:checked + label span {
            opacity: 1;
            background: url("tick-icon-4657-01.png") center center no-repeat;
            width: 40px;
            height: 40px;
            display: inline-block;
          }

          .card-img-top {
            width: 60%;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: 0.5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            *cursor: hand;
            color: #fff !important;
            border: 1px solid white;
            background-color: #35a4bf;
            border-radius: 2px;
          }
          .dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #333 !important;
            border: 1px solid #2c6692;
            background-color: #2c6692;
          }
          .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            cursor: default;
            color: #fff !important;
            border: 1px solid transparent;
            background: transparent;
            box-shadow: none;
          }
          .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: white !important;
            border: 1px solid #111;
            background-color: #2c6692;

          }
          .dataTables_wrapper .dataTables_paginate .paginate_button:active {
            outline: none;
            background-color: #2b2b2b;

          }

          .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
                color: #fff;
            }

         .toggle-on.btn-lg {
            padding-right: 2rem;
            background-color: #ee3e0d;
            border-color: #e23a0d;
         }

         .input-group-lg .select2-container--bootstrap .select2-selection--single {
            font-size: 1.25rem;
            line-height: 1.3;
         }

        div.dropdown-menu { border:1px solid #212529; background-color:#f3f3f3; margin-top:0; }
        a.buttons-columnVisibility { padding-left:2em; }
        a.buttons-columnVisibility.active { padding-left:1em; }
        a.buttons-columnVisibility.active::before { content:"✓ "; }
        a.mybutton, button { background-color:#55a8eb; color:white; padding:1px 8px; border-radius:8px; white-space:nowrap; }
        a.mybutton:hover, button:hover { background-color:blue; }

        div#dmenu:hover > .dropdown-menu { display:block; }

    </style>
    <script>
        function clearFields () {
            var el,elType;
            form = document.getElementById('filterForm');
            for(i=0; i<form.elements.length; i++) {
                el = form.elements[i];
                if (el.name=='incTest') continue;
                if (el.name=='wide')    continue;
                elType = el.type.toLowerCase();
                if (elType=='text' || elType=='date') {
                    el.value = '';
                    delete el.value; //All that is needed for Opera; the rest is for other browsers
                } else if (elType=='checkbox') {
                    el.checked = '';
                } else if (elType=='select-one') {
                    el.selectedIndex = 0;
                }
            }
            form.submit();
        }

        function submitFForm () {
            document.getElementById('filterForm').submit();
        }

        function submitFForm2 () {
            document.getElementById('filterForm2').submit();
        }

        function addColChange(fd) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onload = function() {
                if (this.status!=200) { alert('Error in addColChange:'+this.status); return; }
                window.location.href = window.location.href;
            }
            xmlhttp.open('GET', 'ajax/addCol.php?fd=' + fd);
            xmlhttp.send();
        }

        function setNewbie() {
            alert('$T_setNewbieAlert');
	    document.cookie = "myCLIL_authentication=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
	    document.cookie = "csSessionId=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/clilstore/;";
	    document.cookie = "wlUser=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
	    document.cookie = "Thl=; expires=Thu, 01 Jan 2021 00:00:00 UTC; path=/;";
            window.location = window.location.href;
        }



</script>
</head>
<body onload="history.pushState('','',location.pathname);">
$menu
$cookieMessage
<div class="container">
    <div class="row">
            <form id="modeForm" method="get">
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                       <div class="card text-center bg-transparent border-0">
                            <img class="card-img-top mx-auto" src="../lonelogo/man.png" alt="Alumnos">
                            <div class="card-body">
                              <p class="card-text text-white">$T_For_students</p>
                                <div class="custom-radio-button">
                                    <div>
                                      <input type="radio" id="color-red" name="mode" value="1" onchange="document.getElementById('modeForm').submit();" $mode1radio>
                                      <label for="color-red">
                                        <span>
                                        </span>
                                      </label>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="card text-center bg-transparent border-0">
                            <img class="card-img-top mx-auto" src="../lonelogo/girl.png" alt="Profesores">
                            <div class="card-body">
                              <p class="card-text text-white">$T_For_teachers</p>
                                <div class="custom-radio-button">
                                    <div>
                                      <input type="radio" id="color-blue" name="mode" value="3" onchange="document.getElementById('modeForm').submit();" $mode3radio>
                                      <label for="color-blue">
                                        <span>
                                        </span>
                                      </label>
                                    </div>
                                </div>
                            </div>
                       </div>
                    </div>
                </div>
            </form>
    </div>


    <div class="row">
        <div class="col col-md-12">
                            $incUnitMessage
            <div class="form-group">
			<div class="input-group">
                            <div class="input-group-prepend fixed" style="margin-right: -25px; z-index: 100;">
                                <span class="input-group-text rounded-circle" style="padding: 0.375rem 1.40rem;"><i class="fa fa-search" aria-hidden="true"></i></span>
                            </div>
                            <input type="text" class="form-control form-control-lg rounded inputbuscar" id="buscar" name="buscar" placeholder="$T_Search_for_t_units" >
                        </div>
            </div>
        </div>
    </div>
    <div class="row">
       <div class="col-lg-4 col-md-4 col-sm-12">
            $tabletopChoices
       </div>
       <div class="col-lg-6 col-md-6 col-sm-12">
            $levelButHtml
       </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
              <a id="reset" class="btn btn-primary btn-sm btn-block text-white" role="button"><i class="fa fa-refresh mr-1"></i> $T_Clear_filter</a>
        </div>

    </div>
    <div class="collapse show">
        <div class="row">
            <div class="col col-md-4">
                <div class="form-group">
                <div class="btn-toolbar" role="toolbar">
                    <div class="btn-group mr-2 input-group-sm" role="group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-language mr-1" aria-hidden="true"></i>$T_Owner</span>
                            </div>
                            <select id="userTypeFilter" class="form-control form-control-sm rounded-0 ampliar">
                            <option value="All"></option>
                            $owOptionsHtml
                            </select>
                    </div>
                </div>
            </div>
            </div>
            <div class="col col-md-4">
                <div class="form-group">
                            <div class="input-group input-group-sm">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-tv mr-1" aria-hidden="true"></i> $T_Media</span>
                                </div>
                                    <input type="checkbox" name="media" id="media_aud" value="1" data-toggle="toggle" data-size="small" data-on="<i class='fa fa-headphones'></i>" data-off="<i class='fa fa-headphones'></i>">
                                    <input type="checkbox" name="media" id="media_vid" value="2" data-toggle="toggle" data-size="small" data-on="<i class='fa fa-film'></i>" data-off="<i class='fa fa-film'></i>">
                                    <input type="checkbox" name="media" id="media_doc" value="0" data-toggle="toggle" data-size="small" data-on="<i class='fa fa-file'></i>" data-off="<i class='fa fa-file'></i>">

                            </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
              <div class="custom-control custom-switch float-right">
                 $checkboxesHtml
              </div>
            </div>
        </div>


    <div class="row">
        <div class="col col-md-12">
            $tableHtml
        </div>
    </div>
    <div class="row h-100">
        <div class="col-lg-12">
            $footer
        </div>
    </div>

</div>
$modoTabla
</body>
</html>
EOD1;

  } catch (Exception $e) { echo $e; }

?>
