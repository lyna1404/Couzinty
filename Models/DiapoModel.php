<?php
require_once "ConnexionBDD.php";
class DiapoModel{
    private $db;
    public function get_diapo_principal(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM diapo_principal ORDER BY Ordre");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function set_diapo_principal($rows){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM diapo_principal WHERE 1");
        $stmt->execute();
        foreach($rows as $row){
            $stmt = $cnx->prepare("INSERT INTO `diapo_principal` (`id_news_recette`, `Type`, `Lien`, `Ordre`) 
                                 VALUES (?, ?, ?, ?)");
            $stmt->bindParam(1,$row['id']);
            $stmt->bindParam(2,$row['type']);
            $stmt->bindParam(3,$row['lien']);
            $stmt->bindParam(4,$row['ordre']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
        return $stmt;
    }
}
?>
