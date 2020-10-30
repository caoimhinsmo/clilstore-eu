<?php if (!include('autoload.inc.php'))
  header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header('Cache-Control: no-cache, no-store, must-revalidate');
  header("Cache-Control:max-age=0");

  $T = new SM_T('clilstore/serverhome');

  $T_Clilstore_studentWelcome = $T->h('Clilstore_studentWelcome');
  $T_Clilstore_teacherWelcome = $T->h('Clilstore_teacherWelcome');
  $T_Wordlink_welcome         = $T->h('Wordlink_welcome');
  $T_Multidict_welcome        = $T->h('Multidict_welcome');
  $T_register                 = $T->h('register');
  $T_Login                    = $T->h('Log_air');
  $T_Acceder                  = $T->h('Go_in');

  $EUlogo = '/EUlogos/' . SM_T::hl0() . '.png';
  if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $EUlogo)) { $EUlogo = '/EUlogos/en.png'; }

  $hlSelect = SM_mdNavbar::hlSelect();

  $menu = SM_clilHeadFoot::cabecera();
  $footer = SM_clilHeadFoot::pie();

echo <<< END_html
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Multidict, Wordlink and Clilstore - Tools for CLIL</title>
<meta name="description" content="Three tools for language learners. Multidict can link to online dictionaries in hundreds of languages.  Wordlink links (nearly) any webpage word by word to online dictionaries.  Clilstore is a store of audiovisual learning units, with every word linked automatically to dictionaries.">
<script src="js/jquery-3.4.1.min.js"></script>
<link href="lone.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link href="css/responsive/smartphone.css" rel="stylesheet">
<link href="css/responsive/tablets.css" rel="stylesheet">
<link href="css/responsive/desktops.css" rel="stylesheet">
<link href="css/responsive/ultra_desktops.css" rel="stylesheet">
<script src="js/bootstrap.bundle.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<style>
  .btn{
    border-radius:15px;
}

</style>

</head>
<body>
$menu

<div class="container mt-5">
  <div class="row align-items-center justify-content-center text-center">
    <div class="card-deck text-center">
        <div class="col-lg-4 col-sm-12">
          <div class="card mb-4 bg-transparent border-left-0 border-top-0 border-right border-bottom col" id="clilstore_hover">
            <a href="/clilstore/clilstore_ing.php"><img class="card-img-top imagen card-hover" src="lonelogo/Clilstore.png" alt="Clilstore"></a>
            <div class="card-body">
              <a href="/clilstore/clilstore_ing.php" class="btn btn-lg btn-block btn-outline-light rounded-pill card-hover" role="button">$T_Acceder</a>
            </div>
          </div>
        </div>
      <div class="col-lg-4 col-sm-12">
            <div class="card mb-4 bg-transparent border-left-0 border-top-0 border-right border-bottom" id="wordlink_hover">
                 <a href="/wordlink/"><img class="card-img-top imagen" src="lonelogo/Wordlink.png" alt="Wordlink"></a>
                <div class="card-body">
                 <a href="/wordlink/" class="btn btn-lg btn-block btn-outline-light rounded-pill" role="button">$T_Acceder</a>
                </div>
            </div>
      </div>
      <div class="col-lg-4 col-sm-12">
          <div class="card mb-4 bg-transparent border-left-0 border-top-0 border-right border-bottom col" id="multidict_hover">
              <a href="/multidict/index_h.php"><img class="card-img-top imagen" src="lonelogo/Multidict.png" alt="Multidict"></a>
              <div class="card-body">
              <a href="/multidict/index_h.php" class="btn btn-lg btn-block btn-outline-light rounded-pill" role="button">$T_Acceder</a>
              </div>
          </div>
      </div>
    </div>

        <div class="col-lg-4">
            <div class="bg-transparent p-2 text-white card-hover clilstore-dropdown ocultar">
              <p style="font-size:small">$T_Clilstore_studentWelcome</p>
              <p style="font-size:small">$T_Clilstore_teacherWelcome</p>
            </div>
        </div>
      <div class="col-lg-4">
                <div class="bg-transparent p-2 text-white wordlink-dropdown ocultar">
                  <p style="font-size:small">$T_Wordlink_welcome</p>
                </div>
      </div>
      <div class="col-lg-4">
              <div class="bg-transparent p-2 text-white multidict-dropdown ocultar">
                <p style="font-size:small">$T_Multidict_welcome</p>
              </div>
      </div>


    <div class="col-lg-12">
     $footer
    </div>
 </div>



</div>
<script>
$(document).ready(function() {
  jQuery('#clilstore_hover').hover(function() {
    $('.clilstore-dropdown').show();
    $('.wordlink-dropdown').hide();
    $('.multidict-dropdown').hide();

  });
  jQuery('#wordlink_hover').hover(function() {
    $('.wordlink-dropdown').show();
    $('.clilstore-dropdown').hide();
    $('.multidict-dropdown').hide();
  });
  jQuery('#multidict_hover').hover(function() {
    $('.multidict-dropdown').show();
    $('.wordlink-dropdown').hide();
    $('.clilstore-dropdown').hide();
  });
});
</script>
</body>
</html>

END_html;

?>
