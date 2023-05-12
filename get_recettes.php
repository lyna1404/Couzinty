<?php
global $work_dir;
require_once $work_dir."Conrtollers\RecetteController.php";

$controller = new RecetteController();
$a = $controller->get_all_recettes();
echo json_encode($a);
?>