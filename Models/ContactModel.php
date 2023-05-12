<?php
require_once "ConnexionBDD.php";
class ContactModel{
    private $db;
    public function get_contact(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM contact");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
}
?>