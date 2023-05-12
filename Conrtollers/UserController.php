<?php
global $work_dir;
require_once $work_dir."Views\UserView.php";
require_once $work_dir."Models\UserModel.php";
class UserController{
    public function is_user($umail,$upsw){
        $model = new UserModel();
        $res = $model->is_user($umail,$upsw);
        return $res;
    }
    public function is_admin($umail,$upsw){
        $model = new UserModel();
        $res = $model->is_admin($umail,$upsw);
        return $res;
    }
    public function get_recettes_sauv($id_user){
        $model = new UserModel();
        $res = $model->get_recettes_sauv($id_user);
        $list_recettes = array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($list_recettes,$row['Id_Recette']);
        }
        return $list_recettes;
    }
    public function unsave_recette($id_user,$id_recette){
        $model = new UserModel();
        $res = $model->unsave_recette($id_user,$id_recette);
    }
    public function save_recette($id_user,$id_recette){
        $model = new UserModel();
        $res = $model->save_recette($id_user,$id_recette);
    }
    public function add_user($mail,$psw,$nom,$prénom,$date_naiss,$sexe){
        $model = new UserModel();
        $res = $model->add_user($mail,$psw,$nom,$prénom,$date_naiss,$sexe);
    }

    public function get_all_users(){
        $model = new UserModel();
        $res = $model->get_all_users();
        return $res;
    }

    public function valider_user($id_user){
        $model = new UserModel();
        $res = $model->valider_user($id_user);
    }
    public function enlever_user($id_user){
        $model = new UserModel();
        $res = $model->enlever_user($id_user);
    }
    public function get_recettes_publiées($id_user){
        $model = new UserModel();
        $res = $model->get_recettes_publiées($id_user);
        $finalres = array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($finalres,$row['Id_Recette']);
        }
        return $finalres;
    }
    public function has_user_rated($id_user,$id_recette){
        $model = new UserModel();
        $res = $model->has_user_rated($id_user,$id_recette);
        return $res;
    }
    public function update_user_rate($id_user,$id_recette,$note){
        $model = new UserModel();
        $model->update_user_rate($id_user,$id_recette,$note);
    }
    public function user_logout(){
        session_unset();
        session_destroy();
        echo"<script type='text/javascript'>
        window.location.href = 'Accueil.php';
        </script>";
    }
}
?>