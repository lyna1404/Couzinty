<?php
global $work_dir;
require_once $work_dir."Conrtollers\MenuController.php";
class MenuView
{
    public function Menu()
    {

        echo "<ul class='menu'>";
        $controller = new MenuController();
        $res = $controller->get_menu();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    echo "<li><a class='menuitem' href='".$row['Lien']."'>".$row['Item_Menu']."</a></li>";
                }
        echo "</ul>";
    }
}