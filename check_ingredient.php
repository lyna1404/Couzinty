<?php
global $work_dir;
require_once $work_dir."Conrtollers\IngrédientController.php";

$controller = new IngrédientController();
$a = $controller->get_all_ingrédients_names();

if (in_array(ucfirst($_POST['ingredient']),$a)){
    echo true;
}else{
    echo false;
}
?>