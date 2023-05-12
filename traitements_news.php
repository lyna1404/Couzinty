<?php
require_once $work_dir."Conrtollers\RecetteController.php";
require_once $work_dir."Conrtollers\NewsController.php";

session_start();
$method = $_POST['method'];
switch ($method){
    case 'ajouter_news':
        $id_user = $_SESSION['userId'];
        $paragraphes= json_decode($_POST['paragraphes'], true);
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $afficher = $_POST['afficher'];
        $lien_image = $_POST['lien_image'];
        $lien_video = $_POST['lien_video'];
        
        
        
        $controller = new NewsController();
        $controller->add_news($titre,$lien_image,$lien_video,$description,$afficher,$paragraphes);
        
        echo "ajouté avec succés!";
        
        break;

    case 'supprimer_news':
        $id_user = $_SESSION['userId'];
        $id_news = $_POST['id'];

        $controller = new NewsController();
        $controller->supprimer_news($id_news);


        echo "supprimé !";


        break;
    

    case 'modifier_news':
        $id_user = $_SESSION['userId'];
        $id_news = $_POST['id'];
        $paragraphes= json_decode($_POST['paragraphes'], true);
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $afficher = $_POST['afficher'];
        $lien_image = $_POST['lien_image'];
        $lien_video = $_POST['lien_video'];


        $controller = new NewsController();
        $controller->supprimer_news($id_news);
        $controller->add_news($titre,$lien_image,$lien_video,$description,$afficher,$paragraphes);

        echo 'modifié avec succes';
        break;

    

   

}

?>