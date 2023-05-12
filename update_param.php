<?php
require_once $work_dir."Conrtollers\ParametresController.php";
session_start();


$logo= $_POST['logo'];
$logow= $_POST['logow'];
$prc= $_POST['prc'];


$controller = new ParametresController();
$controller->set_logo($logo);
$controller->set_white_logo($logow);
$controller->set_pourcentage($prc);

echo "mis à jour!";

?>