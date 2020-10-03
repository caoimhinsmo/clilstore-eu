<?php if (!include('autoload.inc.php'))
  header("Location:http://claran.smo.uhi.ac.uk/mearachd/include_a_dhith/?faidhle=autoload.inc.php");

  header('Cache-Control: no-cache, no-store, must-revalidate');
  header("Cache-Control:max-age=0");

$myCLIL = SM_myCLIL::singleton();
$csSess   = SM_csSess::singleton();
$DbMultidict = SM_DbMultidictPDO::singleton('rw');
$servername = SM_myCLIL::servername();
$serverhome = SM_myCLIL::serverhome();

$orderClause = $csSess->orderClause();

$query = 'SELECT clilstore.id,owner,fullname,sl,endonym,level,words,medtype,medlen,buttons,files,title,text,summary,created,changed,licence,test,views,clicks'
         .' FROM clilstore,users,lang';

$stmt = $DbMultidict->prepare($query);
$stmt->execute();

if (!$stmt){
    echo 'Error al ejecutar la consulta';
}else{
    echo 'Loading... <br>';
    $results = $stmt->fetchAll();
    echo json_encode($results);
}

?>
