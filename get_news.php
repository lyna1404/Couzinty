<?php
global $work_dir;
require_once $work_dir."Conrtollers\NewsController.php";

$controller = new NewsController();
$as = $controller->get_all_news_affichables();
$finalres =array();
while($a=$as->fetch(PDO::FETCH_ASSOC)){
    array_push($finalres,$a);
}
echo json_encode($finalres);
?>