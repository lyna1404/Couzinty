<?php 
global $work_dir;
require_once $work_dir."Views\WebsiteView.php";
require_once $work_dir."Conrtollers\RecetteController.php";
header('Content-type: text/html; charset=UTF-8');
$web=new WebsiteView();
session_start();
$web->build_idées_recettes();
exit();

?>