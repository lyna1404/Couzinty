<?php
global $work_dir;
require_once $work_dir."Conrtollers\IngrédientController.php";
$controller = new IngrédientController();
$a = $controller->get_unités();
echo json_encode($a);
?>