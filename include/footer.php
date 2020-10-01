<?php
function pie($EUlogo,$T_Disclaimer,$T_Disclaimer_EuropeanCom){
    $footer= '<footer class="mt-5 mb-3">

    <div class="container mt-5 align-items-center">
      <div class="row">
        <div class="col-xs-12 col-md-6 text-center">
            <a href="http://eacea.ec.europa.eu/llp/index_en.php"><img src="'.$EUlogo.'" width="281" height="60"></a>
        </div>
        <div class="col-xs-12 col-md-6 text-center">
            <p style="font-size:x-small" class="text-white"><i>'.$T_Disclaimer.':</i> '.$T_Disclaimer_EuropeanCom.'</p>
        </div>
      </div>
    </div>
  </footer>';

    return $footer;

}
?>
