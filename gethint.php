<?php
global $work_dir;
require_once $work_dir."Conrtollers\IngrédientController.php";

$controller = new IngrédientController();
$a = $controller->get_all_ingrédients_names();

$q = $_REQUEST["q"];

$hint = "";

if ($q !== "") {
  $q = strtolower($q);
  $len=strlen($q);
  foreach($a as $name) {
    if (stristr($q, substr($name, 0, $len))) {
      if ($hint === "") {
        $hint = $name;
      } else {
        $hint .= ",$name";
      }
    }
  }
}

echo $hint === "" ? "no suggestion" : $hint;
?>