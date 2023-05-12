<?php
global $work_dir;
require_once $work_dir."Views\DiapoView.php";
require_once $work_dir."Models\DiapoModel.php";
class DiapoController{
    public function get_diapo_principal(){
        $model = new DiapoModel();
        $res = $model->get_diapo_principal();
        $diapo = array();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
        array_push($diapo,$row);
        }
        return $diapo;
    }
    public function set_diapo_principal($rows){
        $model = new DiapoModel();
        $res = $model->set_diapo_principal($rows);
    }
}
?>