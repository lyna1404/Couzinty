<?php
global $work_dir;
require_once $work_dir."Views\RecetteView.php";
require_once $work_dir."Models\RecetteModel.php";
require_once $work_dir."Conrtollers\ParametresController.php";
class RecetteController{
    public function get_recette_by_id($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_by_id($id_recette);
        return $res;
    }
    public function get_recette_nonvalidée_by_id($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_nonvalidée_by_id($id_recette);
        return $res;
    }
    public function get_recette_title($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_by_id($id_recette);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Nom_Recette'];       
    }
    public function get_recette_categorie($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_by_id($id_recette);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Nom_Categorie'];  
    }
    public function get_recette_nonvalidée_categorie($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_nonvalidée_by_id($id_recette);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Nom_Categorie'];  
    }
    public function get_recette_description($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_by_id($id_recette);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Description'];       
    }
    public function get_recette_image($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_by_id($id_recette);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Lien_Image'];       
    }
    public function get_recette_video($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_by_id($id_recette);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Lien_Vidéo'];       
    }
    public function get_recette_ingredients($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_ingredients($id_recette);
        return $res;
    }
    public function get_recette_nonvalide_ingredients($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_nonvalide_ingredients($id_recette);
        return $res;
    }
    public function get_recette_nonvalide_nvx_ingredients($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_nonvalide_nvx_ingredients($id_recette);
        return $res;
    }
    public function get_recette_etapes($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_etapes($id_recette);
        return $res;
    }
    public function get_recette_nonvalide_etapes($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_nonvalide_etapes($id_recette);
        return $res;
    }
    public function get_recettes_de_categorie($categorie,$nombre){
        $model = new RecetteModel();
        $res = $model->get_recette_by_categorie($categorie);
        $finalres = array();
        $i = 0;
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            if($i<$nombre){
                array_push($finalres,$row);
            }
            $i=$i+1;
        }
        return $finalres;
    }
    public function get_recettes_intervalle($mesure,$min,$max){
        $model = new RecetteModel();
        $res = $model->get_all_recettes();
        $finalres = array();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            if($row[$mesure]>=$min and $row[$mesure]<=$max){
                array_push($finalres,$row);
            }
        }
        return $finalres;
    }
    public function get_recette_saison($id_recette){
        $model = new RecetteModel();
        $res = $model->get_recette_saison($id_recette);
        return $res;
        
    }
    public function get_recette_de_saison($saison){
        $model = new RecetteModel();
        $res = $model->get_recette_de_saison($saison);
        $finalres = array();
        foreach($res as $recette){
            $row = $model->get_recette_by_id($recette)->fetch(PDO::FETCH_ASSOC);
            array_push($finalres,$row);
        }
        return $finalres;
        
    }
    public function get_recettes_de_lasaison(){
        $today = new DateTime();
        $printemps = new DateTime('March 20');
        $été = new DateTime('June 20');
        $automne = new DateTime('September 22');
        $hiver = new DateTime('December 21');
        switch(true) {
            case $today >= $printemps && $today < $été:
                $saison = 'Printemps';
                break;

            case $today >= $été && $today < $automne:
                $saison = 'Eté';
                break;

            case $today >= $automne && $today < $hiver:
                $saison = 'Automne';
                break;

            default:
                $saison = 'Hiver';
        }
        $controller = new RecetteController();
        $recettes_list=$controller->get_recette_de_saison($saison);
        return $recettes_list;
    }
    public function get_recettes_similaires($ing_disponibles){
        $model = new RecetteModel();
        $controller = new ParametresController();
        $prc = number_format($controller->get_pourcentage())/100;
        $list_recette = $model->get_all_recettes();
        $recettes_similaires=array();
        $matches=array();
        foreach($list_recette as $recette){
            $res_ing = $this->get_recette_ingredients($recette['Id_Recette']);
            $recette_ing=array();
            foreach($res_ing as $ingc){
                array_push($recette_ing,$ingc['Nom_ingrédient']);
            }

            $matches = array_intersect($recette_ing,$ing_disponibles);
            $taux = round(count($matches))/count($recette_ing);
            if($taux>=$prc){
                array_push($recettes_similaires,$recette['Id_Recette']);
            }
        }
        return $recettes_similaires;
    }
    public function get_healthy_recettes(){
        $model = new RecetteModel();
        $res = $model->get_healthy_recettes();
        $healthy_recipes = array();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
                array_push($healthy_recipes,$row['Id_Recette']);
            
        }
        return $healthy_recipes;
    }
    public function get_fetes(){
        $model = new RecetteModel();
        $res = $model->get_fetes();
        $fetes = array();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
                array_push($fetes,$row['Nom']);
            
        }
        return $fetes;
    }
    public function get_recettes_de_fete($fetes){
        $model = new RecetteModel();
        $res = $model->get_recettes_de_fetes($fetes);
        $recettes = array();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
                array_push($recettes,$row['Id_Recette']);
        }
        return $recettes;
    }
    public function user_add_recette($titre,$diff,$temp_prep,$temp_repo,$temp_cuiss,$nb_calories,$description,$id_cat,$id_user){
        $model = new RecetteModel();
        $res = $model->user_add_recette($titre,$diff,$temp_prep,$temp_repo,$temp_cuiss,$nb_calories,$description,$id_cat,$id_user);
        return $res;
    }
    public function user_add_ingrédients_existants_recette($id_recette,$ing_existants){
        $model = new RecetteModel();
        $res = $model->user_add_ingrédients_existants_recette($id_recette,$ing_existants);
    }
    public function user_add_ingrédients_nonexistants_recette($id_recette,$ing_nonexistants){
        $model = new RecetteModel();
        $res = $model->user_add_ingrédients_nonexistants_recette($id_recette,$ing_nonexistants);
    }
    public function user_add_etapes_recette($id_recette,$etapes){
        $model = new RecetteModel();
        $res = $model->user_add_etapes_recette($id_recette,$etapes);
    }
    public function get_all_recettes(){
        $model = new RecetteModel();
        $res = $model->get_all_recettes();
        $finalres = array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($finalres,$row);
        }
        return $finalres;
    }
    public function get_all_recettes_nonvalidées(){
        $model = new RecetteModel();
        $res = $model->get_all_recettes_nonvalidées();
        $finalres = array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($finalres,$row);
        }
        return $finalres;
    }
    public function supprimer_recette($id_recette){
        $model = new RecetteModel();
        $model->supprimer_etapes_recette($id_recette);
        $model->supprimer_ingrédients_recette($id_recette);
        $model->supprimer_infos_recette($id_recette);

    }
    public function supprimer_recette_nonvalide($id_recette){
        $model = new RecetteModel();
        $model->supprimer_etapes_recette_nonvalide($id_recette);
        $model->supprimer_ingrédients_recette_nonvalide($id_recette);
        $model->supprimer_nvx_ingrédients_recette_nonvalide($id_recette);
        $model->supprimer_infos_recette_nonvalide($id_recette);

    }
    public function ajouter_recette($titre,$healthy,$lien_image,$lien_video,$diff,$temp_prep,$temp_repo,$temp_cuiss,$nb_calories,$notation,$description,$id_cat,$id_user,$ing,$etapes){
        $model = new RecetteModel();
        $id_recette=$model->ajouter_infos_recette($titre,$healthy,$lien_image,$lien_video,$diff,$temp_prep,$temp_repo,$temp_cuiss,$nb_calories,$notation,$description,$id_cat,$id_user);
        $model->ajouter_ingrédients_recette($id_recette,$ing);
        $model->ajouter_etapes_recette($id_recette,$etapes);


    }
    public function set_recette_note($id_recette){
        $model = new RecetteModel();
        $model->set_recette_note($id_recette);
    }
    
    
}
?>