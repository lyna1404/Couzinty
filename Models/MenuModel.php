<?php
require_once "ConnexionBDD.php";
class MenuModel{
    private $db;
    public function get_menu(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM menu");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
}
?>
