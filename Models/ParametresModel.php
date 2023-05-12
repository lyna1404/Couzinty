<?php
require_once "ConnexionBDD.php";
class  ParametresModel{
    private $db;
    public function get_param(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM `param`");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function set_logo($src){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("UPDATE `param` SET `contenu` = ? WHERE `param`.`nom_param` = 'Logo' ");
        $stmt->bindParam(1,$src);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }
    public function set_pourcentage($prc){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("UPDATE `param` SET `contenu` = ? WHERE `param`.`nom_param` = 'pourcentage' ");
        $stmt->bindParam(1,$prc);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }
    public function set_white_logo($src){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("UPDATE `param` SET `contenu` = ? WHERE `param`.`nom_param` = 'Logo_white' ");
        $stmt->bindParam(1,$src);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }
}
?>