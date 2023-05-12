<?php
require_once $work_dir."Conrtollers\DiapoController.php";
session_start();


$rows= json_decode($_POST['rows'], true);

$controller = new DiapoController();
$controller->set_diapo_principal($rows);

echo "diapo mis à jour!";

?>