<?php
global $work_dir;
require_once $work_dir."Views\IngrédientView.php";
require_once $work_dir."Models\IngrédientModel.php";
class IngrédientController{
    public function get_all_ingrédients_names(){
        $model = new IngrédientModel();
        $res = $model->get_all_ingrédients();
        $names=array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($names,$row['Nom_ingrédient']);
        }
        return $names;
    }
    public function get_all_ingrédients_ids(){
        $model = new IngrédientModel();
        $res = $model->get_all_ingrédients();
        $names=array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($names,$row['Id_Ingrédient']);
        }
        return $names;
    }
    public function get_all_ingrédients(){
        $model = new IngrédientModel();
        $res = $model->get_all_ingrédients();
        $names=array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($names,[$row['Id_Ingrédient'],$row['Nom_ingrédient']]);
        }
        return $names;
    }
    public function get_all_ingrédients_details(){
        $model = new IngrédientModel();
        $res = $model->get_all_ingrédients();
        $names=array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($names,$row);
        }
        return $names;
    }
    public function get_all_nutriments(){
        $model = new IngrédientModel();
        $res = $model->get_all_nutriments();
        $names=array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($names,$row);
        }
        return $names;
    }
    public function get_valeurs_nutritionnelles($id){
        $model = new IngrédientModel();
        $res = $model->get_valeurs_nutritionnelles($id);
        return $res;
    }
    public function get_ingrédient_saison($id){
        $model = new IngrédientModel();
        $res = $model->get_ingrédient_saison($id);
        return $res;
    }
    public function get_ingrédient_info($id){
        $model = new IngrédientModel();
        $res = $model->get_ingrédient_info($id);
        return $res;
    }
    public function get_ingrédient_nom($id){
        $res=$this->get_ingrédient_info($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Nom_ingrédient'];
    }
    public function get_ingrédient_img($id){
        $res=$this->get_ingrédient_info($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Lien_image'];
    }
    public function get_ingrédient_desc($id){
        $res=$this->get_ingrédient_info($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Description'];
    }
    public function get_ingrédient_état($id){
        $res=$this->get_ingrédient_info($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['état'];
    }
    public function get_ingrédient_calories($id){
        $res=$this->get_ingrédient_info($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Calories'];
    }
    public function is_healthy($id){
        $res=$this->get_ingrédient_info($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        if($row['Healthy']==1){
            return true;
        }
        else{
            return false;
        }
    }
    public function get_all_healthy_ing(){
        $res=$this->get_all_ingrédients_ids();
        $finalres=array();
        foreach($res as $r){
            if($this->is_healthy($r)){array_push($finalres,$r);}
        }
        return $finalres;
    }
    public function get_ing_in_calories_range($min,$max){
        $res=$this->get_all_ingrédients_ids();
        $finalres=array();
        foreach($res as $r){
            if($this->get_ingrédient_calories($r)>=$min && $this->get_ingrédient_calories($r)<=$max){array_push($finalres,$r);}
        }
        return $finalres;
    }

    public function taux_healthy($id){
        $res=$this->get_ingrédient_info($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Taux_'];

    }
    public function get_unités(){
        $model = new IngrédientModel();
        $res = $model->get_unités();
        $names=array();
        
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($names,[$row['Id_Unité'],$row['Nom'],$row['Symbole']]);
        }
        return $names;
    }

    public function ajouter_ingredient($nom){
        $model = new IngrédientModel();
        $res = $model->add_ingredient($nom);
        return $res;
    }
    public function delete_new_ing($id_recette,$nom_ing){
        $model = new IngrédientModel();
        $res = $model->delete_new_ing($id_recette,$nom_ing);
    }
    public function delete_ing($id_ing){
        $model = new IngrédientModel();
        $model->delete_ing_val_nut($id_ing);
        $model->delete_ing_disponibilité($id_ing);
        $model->delete_ing_info($id_ing);


    }

    public function get_all_new_ingredients(){
        $model = new IngrédientModel();
        $res = $model->get_all_new_ingredients();
        $names=array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($names,$row);
        }
        return $names;
    }

    public function ajouter_ingredient_avec_infos($nom,$healthy,$taux,$nbcal,$etat,$description,$lien_image,$saisons,$vals){
        $model = new IngrédientModel();
        $id = $model->ajouter_ingredient_avec_infos($nom,$healthy,$taux,$nbcal,$etat,$description,$lien_image);
        $model->set_disponibilté_ing($id,$saisons);
        $model->set_val_nut_ing($id,$vals);

    }
    
}
?>