<?php
require_once $work_dir."Conrtollers\UserController.php";
session_start();
$id_user = $_SESSION['userId'];
$id = $_POST['id'];
$etat = $_POST['etat'];

$controller = new UserController();
if($etat == 'valide'){
    $controller->enlever_user($id);
    echo 'user supprimé !';
}
else{
    $controller->valider_user($id);
    echo 'user validé !';

}

?>