<?php 
function cabecera($idioma,$about,$help,$idioma_texto,$usuario,$modo,$T_My_vocabulary,$T_My_units,$T_Logout,$T_My_options,$T_Create_a_unit,$T_Login,$T_register){
$header= '<nav class="navbar navbar-expand-lg fondo_menu navbar-dark" id="mainNav">
  <div class="container">
    <a class="navbar-brand text-white" href="https://www.languages.dk/" target="_blank"><img src="../lonelogo/logo.png" width="150px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse align-items-center" id="navbarResponsive">
      <ul class="navbar-nav ml-auto align-items-center list-unstyled">
        <li class="nav-item">
          <a class="nav-link" href="https://yerany.multidict.net">Home</a>
        </li>
         <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.$about.'
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Clilstore</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Wordlink</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Multidict</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          '.$help.'
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Clilstore</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Wordlink</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Multidict</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link">'.$idioma_texto.': '. $idioma .'</a>
        </li>
     
    </div>';
    if ($usuario!='' && $modo<=1){   
  $header.='
  <div class="dropdown" id="dmenu">
    <button type="button" class="btn btn-outline-light dropdown-toggle ml-2" data-toggle="dropdown" id="navbarDropdown"><i class="fa fa-user"></i> '.$usuario.'</button>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a href="options.php?user='.$usuario.'" class="dropdown-item">'.$T_My_options.'</a> 
        <a href="voc.php?user='.$usuario.'" class="dropdown-item">'.$T_My_vocabulary.'</a>               
        <div class="dropdown-divider"></div>
        <a href="logout.php" class="dropdown-item">'.$T_Logout.'</a>        
    </div>
   </div>
   
   </ul>  ';
  } else if ($usuario!='' && $modo>1){
  $header.='
  <div class="dropdown" id="dmenu">
    <button type="button" class="btn btn-outline-light dropdown-toggle ml-2" data-toggle="dropdown" id="navbarDropdown"><i class="fa fa-user"></i> '.$usuario.'</button>
    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <a href="options.php?user='.$usuario.'" class="dropdown-item">'.$T_My_options.'</a> 
        <a href="./?owner='.$usuario.'" class="dropdown-item">'.$T_My_units.'</a> 
        <a href="edit.php?id=0" class="dropdown-item">'.$T_Create_a_unit.'</a>               
        <div class="dropdown-divider"></div>
        <a href="logout.php" class="dropdown-item">'.$T_Logout.'</a>        
    </div>
   </div>
  
    </ul>'; 
  } else {
  $header.='
    
    <a class="btn btn-outline-light ml-2 mr-2" href="login.php">'.$T_Login.'</a> 
  
    <a class="btn btn-outline-light" href="register.php">'.$T_register.'</a> 
  </ul>'; 
  }
 $header.='
  </div>
</nav>';    
 return $header;
    
}
