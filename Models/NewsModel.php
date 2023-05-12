<?php
require_once "ConnexionBDD.php";
class NewsModel{
    private $db;
    public function get_news($id){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM news WHERE Id_News = ?");
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_news_details($id){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM paragraphe WHERE Id_News = ? ORDER BY ordre");
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_all_news(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM news");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_all_news_affichables(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM news WHERE Afficher=1");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function add_news_info($titre,$lien_image,$lien_video,$description,$afficher){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("INSERT INTO `news` (`Titre`,`Lien_Image`, `Lien_Vidéo`, `Description`, `Afficher`) 
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bindParam(1,$titre);
        $stmt->bindParam(2,$lien_image);
        $stmt->bindParam(3,$lien_video);
        $stmt->bindParam(4,$description);
        $stmt->bindParam(5,$afficher);
        $stmt->execute();
        $id_news = $cnx->lastInsertId('Id_News');
        $this->db->deconnexion($cnx);
        return $id_news;
    }
    public function add_news_paragraphes($id_news,$paragraphes){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($paragraphes as $ing){
            $stmt = $cnx->prepare("INSERT INTO `paragraphe` ( `titre`, `Contenu`, `Ordre`, `Id_News`)
                                     VALUES (?, ?, ?, ?)");
            $stmt->bindParam(4,$id_news);
            $stmt->bindParam(3,$ing['ordre']);
            $stmt->bindParam(1,$ing['titre']);
            $stmt->bindParam(2,$ing['contenu']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
    }
    public function supprimer_news_info($id_news){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM news WHERE Id_News = ?");
        $stmt->bindParam(1,$id_news);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function supprimer_news_paragraphes($id_news){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM paragraphe WHERE Id_News = ?");
        $stmt->bindParam(1,$id_news);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
}
?>
