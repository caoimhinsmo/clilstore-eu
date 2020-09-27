<?php
  if (!include('autoload.inc.php'))
    header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header("Cache-Control:max-age=0");
  header('P3P: CP="CAO PSA OUR"');

  $T = new SM_T('wordlink/index');

  $T_Help           = $T->h('Cobhair');
  $T_About_Wordlink = $T->h('About_Wordlink');
  $T_Process_page   = $T->h('Process_page');
  $T_Compose_page   = $T->h('Compose_page');
  $T_Copy_url_here  = $T->h('Copy_url_here');
  $T_Dictionary_in  = $T->h('Dictionary_in');
  $T_New_tab        = $T->h('New_tab');
  $T_Same_tab       = $T->h('Same_tab');
  $T_Popup          = $T->h('Popup');
  $T_Splitscreen    = $T->h('Splitscreen');
  $T_Esc_title      = $T->h('Esc_title');
  $T_Process        = $T->h('Process');

  $T_Webpage_language        = $T->h('Webpage_language');
  $T_Remove_exist_links      = $T->h('Remove_exist_links');
  $T_Remove_exist_links_info = $T->h('Remove_exist_links_info');
  $T_modeSelect_title        = $T->h('modeSelect_title');

  $T_modeSelect_title = strtr( $T_modeSelect_title, [ '{{Process}}' => '‘' . $T_Process . '’' ] );

  // Handy abbreviations
  $checked  = ' checked="checked"';
  $selected = ' selected="selected"';

  $sid = ( !empty($_GET['sid']) ? $_GET['sid'] : null);
  $wlSession = new SM_WlSession($sid);
  $wlSession->storeVars();
  $sid  = $wlSession->sid;
  $sl   = $wlSession->sl;
  $url  = $wlSession->url;
  $rmLi = $wlSession->rmLi;
  $mode = $wlSession->mode;

  if (empty($mode)) { $mode = 'ss'; }
  $rmLiHtml = ( $rmLi ? $checked : '' );
  $modeHtmlNt = ( ($mode=='nt') ? $selected : '');
  $modeHtmlSt = ( ($mode=='st') ? $selected : '');
  $modeHtmlPu = ( ($mode=='pu') ? $selected : '');
  $modeHtmlSs = ( ($mode=='ss') ? $selected : '');
  $nbSlHtml = $wlSession->nbSlHtml();

  $hlSelect = SM_mdNavbar::hlSelect();

  $robots = ( empty($wlSession->url) ? 'index,follow' : 'noindex,nofollow' );
  $servername = $_SERVER['SERVER_NAME'];
  
  $slOptions = array();
  $slArr = SM_WlSession::slArr();
  foreach ($slArr as $lang=>$langInfo) { $slArray[$lang] = $langInfo['endonym']; }
  setlocale(LC_COLLATE,'en_GB.UTF-8');
  uasort($slArray,'strcoll');
  $slArray = array_merge ( array(''=>'- Choose -'), $slArray, array('null'=>'--null--') );
  foreach ($slArray as $lang=>$endonym) {
      $selectHtml = ( $sl==$lang ? ' selected="selected"' : '');
      $slOptions[] = "<option value=\"$lang\"$selectHtml>$endonym</option>\n";
  }
   $slOptionsHtml = implode("\n",$slOptions);
  echo <<<EOD1
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Wordlink <span navigation frame</title>
    <meta name="robots" content="$robots">
    <link rel="icon" type="image/png" href="/favicons/wordlink.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">         
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">        
    <link href="../css/styles.css" rel="stylesheet">    
        
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script> 
    <script src="../js/bootstrap.bundle.min.js"></script>    
    <script src="../js/bootstrap.min.js"></script>       
    
        <script>
        function escapeAmpersands() {
            document.getElementById('url').value = document.getElementById('url').value.replace('&','{and}');
            return;
        }
        function submitForm() {
            document.getElementById('wlForm').submit();
        }
        function slChange(lang) {
            document.getElementById('sl').value = lang;
            submitForm();
        }
        function escapeWL() {
            window.top.location = document.getElementById('url').value;
        }
    </script>
    <style>
         .fondo {
            background-color: #13557A;
            padding: 10px;            
        }  
        .padding-0{
            padding-right:0;
            padding-left:0;
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
          
   </style>      
</head>
<body class="fondo">
  <div class="container padding-0"> 
   <form id="wlForm" action="./" target="_top" onsubmit="escapeAmpersands('testing');">
        
    <div class="row">       
       <div class="col-md-6">         
          <input type="hidden" name="sid" value="$sid"/>          
          <input type="text" class="form-control form-control-sm rounded" id="url" name="url" value="$url" title="URL of the webpage" placeholder="$T_Copy_url_here">
       </div> 
       <div class="col-md-4">
          <select class="form-control form-control-sm" name="sl" id="sl" title="The language the above page is written in" onchange="submitForm()">
          $slOptionsHtml
          </select>
       </div>
        <div class="col-md-2">
          <a role="button" class="btn btn-danger text-white btn-sm float-right" title="$T_Esc_title" onclick="escapeWL()">ESC</a>        
       </div>   
    </div> 
    <div class="row mt-1">       
       <div class="col-md-4">         
          <select name="upload" class="form-control form-control-sm">
            <option value="0" selected>$T_Process_page</option>
            <option value="2">Compose a page</option>
          </select>
       </div> 
       <div class="col-md-2 mx-auto">
          <div class="custom-control custom-switch text-center">            
            <input type="checkbox" class="custom-control-input" name="rmLi"$rmLiHtml/ id="rexl">              
            <label class="custom-control-label text-white" for="rexl" style="font-size: 0.5rem;">$T_Remove_exist_links</label>
          </div>
       </div>
       <div class="col-md-4">
          <select class="form-control form-control-sm" name="mode" title="$T_modeSelect_title">
            <option value="nt"$modeHtmlNt>$T_New_tab</option>
            <option value="st"$modeHtmlSt>$T_Same_tab</option>
            <option value="pu"$modeHtmlPu>$T_Popup</option>
            <option value="ss"$modeHtmlSs>$T_Splitscreen</option>
          </select>
       </div>
       <div class="col-md-2">
          <input type="submit" class="btn btn-primary btn-sm float-right" name="go" value="$T_Process">          
       </div>    
    </div>      
EOD1;
  
echo <<<EOD2

   
</form>
</div> 
EOD2;
?>