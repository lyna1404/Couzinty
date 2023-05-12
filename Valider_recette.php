<?php 
require_once $work_dir."Views\WebsiteView.php";
header('Content-type: text/html; charset=UTF-8');
session_start();
$web=new WebsiteView();
$web->build_valider_recette();
exit();

?>