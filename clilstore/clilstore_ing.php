<?php if (!include('autoload.inc.php'))
  header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header('Cache-Control: no-cache, no-store, must-revalidate');
  header("Cache-Control:max-age=0");

  $T = new SM_T('clilstore/index');

  $T_s = new SM_T('clilstore/serverhome');

  $T_Clilstore_studentWelcome = $T_s->h('Clilstore_studentWelcome');
  $T_Clilstore_teacherWelcome = $T_s->h('Clilstore_teacherWelcome');
  $T_Wordlink_welcome         = $T->h('Wordlink_welcome');
  $T_Multidict_welcome        = $T->h('Multidict_welcome');
  $T_register                 = $T->h('register');
  $T_For_students             = $T->h('For_students');
  $T_For_teachers             = $T->h('For_teachers');

  $menu   = SM_clilHeadFoot::cabecera();
  $footer = SM_clilHeadFoot::pie();

echo <<< END_html
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Clilstore - Tools for CLIL</title>
<meta name="description" content="Three tools for language learners.  Multidict can link to online dictionaries in hundreds of languages.  Wordlink links (nearly) any webpage word by word to online dictionaries.  Clilstore is a store of audiovisual learning units, with every word linked automatically to dictionaries.">
<script src="../js/jquery-3.4.1.min.js"></script>
<script src="../js/scripts.js"></script>
<link href="../lone.css" rel="stylesheet">
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/styles.css" rel="stylesheet">
<link href="../css/responsive/smartphone.css" rel="stylesheet">
<link href="../css/responsive/tablets.css" rel="stylesheet">
<link href="../css/responsive/desktops.css" rel="stylesheet">
<link href="../css/responsive/ultra_desktops.css" rel="stylesheet">
<script src="../js/bootstrap.bundle.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<style>
  .btn{
    border-radius:15px;
}

.btn-primary{
    background-color: rgb(53, 164, 191);
    border-color:rgb(255, 255, 255);
    border-width:5px !important;
    letter-spacing: 0.2em;

}

.btn-primary:hover{
    background-color: rgb(53, 164, 191);
    border-color:rgb(255, 255, 255);
    transition: transform .5s;
    transform: scale(1.3);
}

.btn-primary:focus{
    background-color: rgb(53, 164, 191);
    border-color:rgb(255, 255, 255);
    transition: transform .5s;
    transform: scale(1.3);
}

.navbar .dropdown-menu a:hover {
    color: #616161 !important;
}
</style>

</head>

<body>
$menu

<div class="container mt-5">
    <div class="row align-items-center justify-content-center text-center">
        <div class="mx-auto text-center">
            <h1 class="display-3 text-white"><strong>CLIL</strong>STORE</h1>
        </div>
            <div class="row justify-content-center">

                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <div class="card mb-4 bg-transparent border-left-0 border-top-0 border-right-0 border-bottom-0 card-hover">
                             <a href="/clilstore/?mode=1"><img class="card-img-top imagen img-adjusted" src="../lonelogo/man.png" alt="Alumnos"></a>
                             <div class="card-body">
                               <a href="/clilstore/?mode=1" class="btn btn-lg btn-block btn-outline-light rounded-pill" role="button"> $T_For_students</a>
                             </div>
                             <div class="bg-transparent p-2 text-white">
                               <p class="parrafo">$T_Clilstore_studentWelcome</p>
                             </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-12 col-sm-12">
                        <div class="card mb-4 bg-transparent border-left-0 border-top-0 border-right-0 border-bottom-0 card-hover">
                            <a href="/clilstore/?mode=3"><img class="card-img-top imagen img-adjusted" src="../lonelogo/girl.png" alt="Profesores"></a>
                                <div class="card-body">
                                    <a href="/clilstore/?mode=3" class="btn btn-lg btn-block btn-outline-light rounded-pill" role="button">$T_For_teachers</a>
                                </div>
                            <div class="bg-transparent p-2 text-white">
                                <p class="parrafo">$T_Clilstore_teacherWelcome</p>
                            </div>
                        </div>
                    </div>

            </div>
    </div>
    <div class="row h-100 align-items-center justify-content-center text-center">
          <div class="col-lg-4">
          </div>
          <div class="col-lg-4">
            <div class="mx-auto text-center">
              <a href="register.php" class="btn btn-primary btn-lg" role="button">$T_register</a>
            </div>
          </div>
          <div class="col-lg-4">
          </div>
    </div>
        <div class="col-lg-12">
          $footer
        </div>

</div>
</body>
</html>
END_html;

?>
