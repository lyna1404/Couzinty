<?php
global $work_dir;
require_once $work_dir."Views\ParametresView.php";
require_once $work_dir."Models\ParametresModel.php";
class ParametresController{
    public function get_param(){
        $model = new ParametresModel();
        $res = $model->get_param();
        return $res;
    }
    public function set_logo($src){
        $model = new ParametresModel();
        $res = $model->set_logo($src);
    }
    public function set_white_logo($src){
        $model = new ParametresModel();
        $res = $model->set_white_logo($src);
    }
    public function set_pourcentage($prc){
        $model = new ParametresModel();
        $res = $model->set_pourcentage($prc);
    }
    public function get_logo_white_src(){
        $res = $this->get_param();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    if ($row['nom_param']=='Logo_white'){
                       return $row['contenu'];
                    }
                }
    }
    public function get_pourcentage(){
        $res = $this->get_param();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            if ($row['nom_param']=='pourcentage'){
               return $row['contenu'];
            }
        }
    }
    public function get_logo_src(){
        $res = $this->get_param();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    if ($row['nom_param']=='Logo'){
                       return $row['contenu'];
                    }
                }
    }
}
?>