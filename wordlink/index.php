<?php
  if (!include('autoload.inc.php'))
    header("Location:https://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  try {

    header("Cache-Control:max-age=0");
    header('P3P: CP="CAO PSA OUR"');
    
    //Load Menu and Footer
    include ("../include/header_p.php");
    include ("../include/footer.php");
   $T = new SM_T('wordlink/index');
   
   $T_Disclaimer               = $T->h('Disclaimer');
   $T_Disclaimer_EuropeanCom   = $T->h('Disclaimer_EuropeanCom');
   $T_Help                     = $T->h('Cobhair');
   $T_About                    = $T->h('About');  
   $T_Language                 = $T->h('Language');
   
   $mdNavbar = $hlSelect = SM_mdNavbar::hlSelect();
   
    $menu = cabecera($hlSelect,$T_Help,$T_About,$T_Language);
   
    $EUlogo = '/EUlogos/' . SM_T::hl0() . '.png';
    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $EUlogo)) { $EUlogo = '/EUlogos/en.png'; }

    
//---- Experimental code to clean up the URL a bit ----
    $qs0 = $_SERVER['QUERY_STRING'];
    $qs = '&' . $qs0;
    $qs = str_replace('&mode=ss','',$qs);
    $qs = str_replace('&upload=0','',$qs);
    $qs = str_replace('&go=Go','',$qs);
    $qs = substr($qs,1);
    if ($qs<>$qs0) {
        $cleaned_location = 'http://' . $_SERVER['SERVER_NAME']
                          . $script_name = str_replace('index.php','',$_SERVER['SCRIPT_NAME'])
                          . '?' . $qs;
        header("Location:$cleaned_location");
    }
//---- End of experimental code -----------------------

    $sid = ( !empty($_GET['sid']) ? $_GET['sid'] : null );
    $wlSession = new SM_WlSession($sid);
    $sid     = $wlSession->sid;
    $wlSession->rmLi = ( empty($_GET['rmLi']) ? 0 : 1 );
    $mode    = $wlSession->mode;
    $navsize = $wlSession->navsize;  if ($navsize == -1) { $navsize = 140; }
    $url  = $wlSession->url;
    $csid = $wlSession->csid();
    if ($csid==-1)     { $startAdvice = ''; }
     elseif ($csid==0) { $startAdvice = 'src="startAdvice.php"'; }
     else              { $startAdvice = 'src="startAdvice.php?cs=1"'; }
    if (!empty($_GET['upload'])) {
        if ($_GET['upload']==1) { $wlSession->url = '{upload}'; }
        if ($_GET['upload']==2) { $wlSession->url = '{compose}'; }
    }
    $robots = ( empty($wlSession->url) ? 'index,follow' : 'noindex,nofollow' );
    $wlSession->storeVars();
    if ($csid) {
        $favicon = 'clilstore';
        $noframes = "Clilstore unit $csid<br>(Clilstore and Wordlink link webpages word-by-word to online dictionaries)";
        $logo = '/lonelogo/COOLfb.png';
        $pagetitle = "Clilstore unit $csid";
        $description = htmlspecialchars(SM_csSess::csTitle($csid));
    } else {
        $favicon = 'wordlink';
        $noframes = 'Wordlink links webpages word-by-word to online dictionaries';
        $logo = '/lonelogo/wordlink-blue.png';
        $pagetitle = 'Wordlink';
        $description = "Wordlink is a facility which links webpages word-by-word to online dictionaries";
    }
        
    if ($mode=='ss' && $csid>0) { echo <<<EOD1
<html>
<head>
    <title>$pagetitle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">        
    <meta name="description" content="$description">
    <meta name="robots" content="$robots"/>
    <link rel="icon" type="image/png" href="/favicons/$favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">        
    <link href="../css/styles.css" rel="stylesheet">         
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>    
    <script src="../js/bootstrap.bundle.min.js"></script>    
    <script src="../js/bootstrap.min.js"></script>         
            
            
    <style>  
      html,body {
        height: 100%;        
        padding: 0;
        margin: 0;
        overflow: auto;
      }  
            
      .altura{        
        height: 100vh;
      }       

      .page-container {
        margin: 0px;
      }


      /* horizontal panel*/

      .panel-container {
        display: flex;
        flex-direction: row;
        border: 0px solid silver;
        overflow: hidden;

        /* avoid browser level touch actions */
        xtouch-action: none;
      }

      .panel-left {
        flex: 0 0 auto;
        /* only manually resize */
        padding: 0px;
        width: 70%;
        min-height: 200px;
        min-width: 150px;
        white-space: nowrap;
        background: #60B8D3;
        color: white;
      }

      .splitter {
        flex: 0 0 auto;
        width: 5px;  
        background: url(../favicons/vsizegrip.png) center center no-repeat #2c6692;
        min-height: 200px;
        cursor: col-resize;  
      }

      .panel-right {
        flex: 1 1 auto;
        /* resizable */
        padding: 0px;
        width: 300px;
        min-height: 200px;
        min-width: 200px;
        background: #5ABCE1;
      }
      
       .titulo {
            background-color: #696B73;
            padding: 5px;
            margin-bottom: 0rem;
        }
        
   </style>
            
</head>
<body>
<div class="page-container">            
      <div class="panel-container altura">

            <div class="panel-left">
                 <iframe id="WLmainframe$sid" name="WLmainframe$sid" src="wordlink.php?sid=$sid" style="width: 100%; height: 100%; border: none"></iframe>
            </div>

            <div class="splitter">
            </div>

            <div class="panel-right">
                <iframe id="MD$sid" name="MD$sid" $startAdvice style="width: 100%; height: 100%; border: none"></iframe>
            </div>
        </div>
</div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>       
 <script src="../js/jquery-resizable.js"></script>   
 <script>
        $(".panel-left").resizable({
            handleSelector: ".splitter",
            resizeHeight: false
        });
    </script>           
</body>    
</html>
EOD1;
    } else if($mode=='ss' && $csid<=0) { echo <<<EOD2
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <title>$pagetitle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">        
    <meta name="description" content="$description">
    <meta name="robots" content="$robots"/>
    <link rel="icon" type="image/png" href="/favicons/$favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">        
    <link href="../css/styles.css" rel="stylesheet">         
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>    
    <script src="../js/bootstrap.bundle.min.js"></script>    
    <script src="../js/bootstrap.min.js"></script>   

    <style>
         html,body {
        height: 100%;        
        padding: 0;
        margin: 0;
        overflow: auto;
      }  
            
      .altura{        
        height: 100vh;
      }       

      .page-container {
        margin: 0px;
      }


      /* horizontal panel*/

      .panel-container {
        display: flex;
        flex-direction: row;
        border: 0px solid silver;
        overflow: hidden;

        /* avoid browser level touch actions */
        xtouch-action: none;
      }

      .panel-left {
        flex: 0 0 auto;
        /* only manually resize */
        padding: 0px;
        width: 70%;
        min-height: 200px;
        min-width: 150px;
        white-space: nowrap;
        background: #60B8D3;
        color: white;
      }

      .splitter {
        flex: 0 0 auto;
        width: 5px;  
        background: url(../favicons/vsizegrip.png) center center no-repeat #13557A;
        min-height: 200px;
        cursor: col-resize;  
      }

      .panel-right {
        flex: 1 1 auto;
        /* resizable */
        padding: 0px;
        width: 300px;
        min-height: 200px;
        min-width: 200px;
        background: #5ABCE1;
      }
            
      .panel-top {
            flex: 0 0 auto;
            /* only manually resize */
            padding: 0px;
            height: 80px;
            width: 100%;
            white-space: nowrap;            
            color: white;
          }

          .splitter-horizontal {
            flex: 0 0 auto;
            height: 18px;
            background: url(https://raw.githubusercontent.com/RickStrahl/jquery-resizable/master/assets/hsizegrip.png) center center no-repeat #535353;
            cursor: row-resize;
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
            
        .navigation {
            width:100%;
            height: 90px;
            background-color: #999898;
        }
        .wordlink {
            width:100%;
            height:600px;   
        }   
        .multidict {
            width:100%;
            height:750px;   
        }  
        .padding-0{
            padding-right:0;
            padding-top:0;
        } 
        .padding-1{            
            padding-left:0;
        }
        .titulo {
            background-color: #696B73;
            padding: 10px;
            margin-bottom: 0rem;
        }    
   </style>
</head>
<body>
$menu
<div class="fila_titulo"> 
     <p class="titulo display-4 text-white" style="font-size: 1.8rem;">WORDLINK</p>
</div>  
    
<div class="page-container">            
      <div class="panel-container altura">
            
            <div class="panel-left">
                <div class="panel-top">
                 <iframe id="WLnavframe$sid"  name="WLnavframe$sid"  src="navigation.php?sid=$sid" style="width: 100%; height: 100%; border: none"></iframe>
                 </div>
                 <iframe id="WLmainframe$sid" name="WLmainframe$sid" src="wordlink.php?sid=$sid" style="width: 100%; height: 100%; border: none"></iframe>
            </div>

            <div class="splitter">
            </div>

            <div class="panel-right">
                <iframe id="MD$sid" name="MD$sid" $startAdvice style="width: 100%; height: 100%; border: none"></iframe>
            </div>
        </div>
</div>            
            
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>       
 <script src="../js/jquery-resizable.js"></script>   
 <script>
        $(".panel-left").resizable({
            handleSelector: ".splitter",
            resizeHeight: false
        });
 </script>               
</body>    
</html>
EOD2;
    }

  } catch (exception $e) { echo <<<EOD2
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head>
    <title>Wordlink error</title>
</head>
<body>
$e
</body>
</html>
EOD2;
  }
?>
