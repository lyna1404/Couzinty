<?php
require_once $work_dir."Conrtollers\RecetteController.php";
require_once $work_dir."Conrtollers\IngrédientController.php";

session_start();
$method = $_POST['method'];
switch ($method){
    case 'ajouter_recette_user':
        $id_user = $_SESSION['userId'];
        
       
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $temp_prep = $_POST['temp_prep'];    
        $temp_repo = $_POST['temp_repo'];         
        $temp_cuiss = $_POST['temp_cuiss'];         
        $nbcalories = $_POST['nbcalories'];
        $difficulté = $_POST['difficulté'];
        $catégorie = $_POST['catégorie'];


        $controller = new RecetteController();
        
        $id_recette=$controller->user_add_recette($titre,$difficulté,$temp_prep,$temp_repo,$temp_cuiss,$nbcalories,$description,$catégorie,$id_user);
        
        if(isset($_POST['ingredients_existants'])){
            $ing_existant = json_decode($_POST['ingredients_existants'], true);
            $controller->user_add_ingrédients_existants_recette($id_recette,$ing_existant);
        }
       
        if(isset($_POST['ingredients_nonexistants'])){
            $ing_nonexistant = json_decode($_POST['ingredients_nonexistants'], true);
            $controller->user_add_ingrédients_nonexistants_recette($id_recette,$ing_nonexistant);
        }
        if(isset($_POST['steps'])){
            $etapes = json_decode($_POST['steps'], true);
            $controller->user_add_etapes_recette($id_recette,$etapes);
        }
      

        echo "recette ajoutée, en attente d'etre validée !";
        break;

    case 'ajouter_recette':
        $id_user = $_SESSION['userId'];
        $ingredients= json_decode($_POST['ingredients'], true);
        $etapes = json_decode($_POST['steps'], true);
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $temp_prep = $_POST['temp_prep'];    
        $temp_repo = $_POST['temp_repo'];         
        $temp_cuiss = $_POST['temp_cuiss'];         
        $nbcalories = $_POST['nbcalories'];
        $difficulté = $_POST['difficulté'];
        $catégorie = $_POST['catégorie'];
        $healthy = $_POST['healthy'];
        $lien_image = $_POST['lien_image'];
        $lien_video = $_POST['lien_video'];
        $notation = $_POST['notation'];



        $controller = new RecetteController();
        $controller->ajouter_recette($titre,$healthy,$lien_image,$lien_video,$difficulté,$temp_prep,$temp_repo,$temp_cuiss,$nbcalories,$notation,$description,$catégorie,$id_user,$ingredients,$etapes);

        echo "recette ajoutée avec succes !";

        break;
    

    case 'modifier_recette':
        $id_user = $_SESSION['userId'];
        $id_recette = $_POST['id'];
        $ingredients= json_decode($_POST['ingredients'], true);
        $etapes = json_decode($_POST['steps'], true);
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $temp_prep = $_POST['temp_prep'];    
        $temp_repo = $_POST['temp_repo'];         
        $temp_cuiss = $_POST['temp_cuiss'];         
        $nbcalories = $_POST['nbcalories'];
        $difficulté = $_POST['difficulté'];
        $catégorie = $_POST['catégorie'];
        $healthy = $_POST['healthy'];
        $lien_image = $_POST['lien_image'];
        $lien_video = $_POST['lien_video'];
        $notation = $_POST['notation'];


        $controller = new RecetteController();
        $controller->supprimer_recette($id_recette);
        $controller->ajouter_recette($titre,$healthy,$lien_image,$lien_video,$difficulté,$temp_prep,$temp_repo,$temp_cuiss,$nbcalories,$notation,$description,$catégorie,$id_user,$ingredients,$etapes);

        echo "recette modifiée!";

        break;

    case 'supprimer_recette':
        $id_user = $_SESSION['userId'];
        $id_recette = $_POST['id'];
        $etat = $_POST['etat'];

        $controller = new RecetteController();
        if($etat == 'valide'){
            $controller->supprimer_recette($id_recette);
            echo "recette supprimée avec succés!";
        }
        else{
            $controller->supprimer_recette_nonvalide($id_recette);
            echo "recette rejetée!";}
        break;
    case 'valider_recette':
        $id_user = $_SESSION['userId'];
        $id_recette = $_POST['id'];
        $ingredients= json_decode($_POST['ingredients'], true);
        $nvx_ingredients= json_decode($_POST['nvx_ingredients'], true);
        $etapes = json_decode($_POST['steps'], true);
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $temp_prep = $_POST['temp_prep'];    
        $temp_repo = $_POST['temp_repo'];         
        $temp_cuiss = $_POST['temp_cuiss'];         
        $nbcalories = $_POST['nbcalories'];
        $difficulté = $_POST['difficulté'];
        $catégorie = $_POST['catégorie'];
        $healthy = $_POST['healthy'];
        $lien_image = $_POST['lien_image'];
        $lien_video = $_POST['lien_video'];
        $notation = $_POST['notation'];
        $ingcontroller = new IngrédientController();

        if(count($nvx_ingredients)!=0){
            echo "cette recette contient des ingrédient non disponibles dans la base de données, ils seront ajoutés automatiquement et vous pourrez le modifier à partir de la gestion de la nutrition";
        }

        foreach($nvx_ingredients as $ing){
            $id_ing=$ingcontroller->ajouter_ingredient($ing['nom_ing']);
            $new_ing['id_ing'] = $id_ing;
            $new_ing['quantité'] = $ing['quantité'];
            $new_ing['id_unité'] = $ing['id_unité'];
            array_push($ingredients,$new_ing);
        }

        $controller = new RecetteController();
        $controller->ajouter_recette($titre,$healthy,$lien_image,$lien_video,$difficulté,$temp_prep,$temp_repo,$temp_cuiss,$nbcalories,$notation,$description,$catégorie,$id_user,$ingredients,$etapes);
        $controller->supprimer_recette_nonvalide($id_recette);

        echo "recette validée";
        break;

    case 'save_unsave_recette':
        $id_user = $_SESSION['userId'];

        $etat = $_POST['etat'];
        $id_recette = $_POST['recipe_id'];
        $controller= new UserController();

        if($etat == 'Sauvegardée'){
            $controller->unsave_recette($id_user,$id_recette);
        }
        else{
            $controller->save_recette($id_user,$id_recette);
        }
        break;

    case 'update_note_recette':
        $id_user = $_SESSION['userId'];
        $id_recette = $_POST['recipe_id'];
        $note = $_POST['note'];



        $usercontroller = new UserController();
        $recettecontroller = new RecetteController();

        $f=$usercontroller->update_user_rate($id_user,$id_recette,$note);
        $recettecontroller->set_recette_note($id_recette);
        break;



}

?>



