<?php
require_once $work_dir."Conrtollers\RecetteController.php";
require_once $work_dir."Conrtollers\IngrédientController.php";

session_start();
$method = $_POST['method'];
switch ($method){
    case 'ajouter_ing_admin':
        $id_user = $_SESSION['userId'];
        $saisons= json_decode($_POST['saisons'], true);
        $vals = json_decode($_POST['val_nut'], true);
        $nom = $_POST['titre'];
        $description = $_POST['description'];
        $taux = $_POST['taux'];   
        $etat = $_POST['etat'];          
        $nbcal = $_POST['nbcalories'];
        $healthy = $_POST['healthy'];
        $lien_image = $_POST['lien_image'];

        $controller = new IngrédientController();
        $controller->ajouter_ingredient_avec_infos($nom,$healthy,$taux,$nbcal,$etat,$description,$lien_image,$saisons,$vals);

        echo "ingrédient ajouté !";
        break;

    case 'decider_ing_ajouté_user':
        $id_recette = $_POST['id'];
        $nom_ing = $_POST['nom'];
        $method = $_POST['action'];
        
        $controller = new IngrédientController();
        if($method == 'valider'){
            $controller->ajouter_ingredient($nom_ing);
            $controller->delete_new_ing($id_recette,$nom_ing);
            echo "ingrédient validé";
        }
        else{
            $controller->delete_new_ing($id_recette,$nom_ing);
            echo "ingrédient non validé";
        }
        break;
    

    case 'modifier_ing':
        $id_user = $_SESSION['userId'];
        $id = $_POST['id'];
        $id_user = $_SESSION['userId'];
        $saisons= json_decode($_POST['saisons'], true);
        $vals = json_decode($_POST['val_nut'], true);
        $nom = $_POST['titre'];
        $description = $_POST['description'];
        $taux = $_POST['taux'];   
        $etat = $_POST['etat'];          
        $nbcal = $_POST['nbcalories'];
        $healthy = $_POST['healthy'];
        $lien_image = $_POST['lien_image'];


        $controller = new IngrédientController();
        $controller->delete_ing($id);
        $controller->ajouter_ingredient_avec_infos($nom,$healthy,$taux,$nbcal,$etat,$description,$lien_image,$saisons,$vals);

        echo "ingrédient modifié !";


        break;

    case 'supprimer_ing':
        $id_user = $_SESSION['userId'];
        $id = $_POST['id'];
        
        $controller = new IngrédientController();
        $controller->delete_ing($id);
        
        echo "ingrédient supprimé!";
                
        break;

   

}

?>