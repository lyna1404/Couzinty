<?php
global $work_dir;
require_once $work_dir."Views\MenuView.php";
require_once $work_dir."Models\MenuModel.php";
class MenuController{
    public function get_menu(){
        $model = new MenuModel();
        $res = $model->get_menu();
        return $res;
    }
}
?>