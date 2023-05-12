<?php
require_once "ConnexionBDD.php";
class UserModel{
    private $db;
    public function get_all_users(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM utilisateur");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function is_user($umail,$upsw){
            $this->db = new ConnexionBDD();
            $cnx = $this->db->connexion(); 
            $stmt = $cnx->prepare("SELECT * FROM utilisateur WHERE Mail = ? AND Mot_de_passe = ? AND Valide=1 ");
            $stmt->bindParam(1,$umail);
            $stmt->bindParam(2,$upsw);
        $stmt->execute();
        if($stmt!=null){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        }else{
            return null;
        }
        $this->db->deconnexion($cnx);
    }
    public function get_recettes_sauv($id_user){
        $this->db = new ConnexionBDD();
            $cnx = $this->db->connexion(); 
            $stmt = $cnx->prepare("SELECT * FROM préferences_utilisateur WHERE Id_Utilisateur = ? ");
            $stmt->bindParam(1,$id_user);
            $stmt->execute();
            $this->db->deconnexion($cnx);
        return $stmt;

    }
    
    public function unsave_recette($id_user,$id_recette){
        $this->db = new ConnexionBDD();
            $cnx = $this->db->connexion(); 
            $stmt = $cnx->prepare("DELETE FROM préferences_utilisateur WHERE Id_Utilisateur = ? AND Id_Recette = ?");
            $stmt->bindParam(1,$id_user);
            $stmt->bindParam(2,$id_recette);
            $stmt->execute();
            $this->db->deconnexion($cnx);
    }
    public function save_recette($id_user,$id_recette){
        $this->db = new ConnexionBDD();
            $cnx = $this->db->connexion(); 
            $stmt = $cnx->prepare("INSERT INTO préferences_utilisateur (Id_Recette,Id_Utilisateur) VALUES (?, ?)");
            $stmt->bindParam(2,$id_user);
            $stmt->bindParam(1,$id_recette);
            $stmt->execute();
            $this->db->deconnexion($cnx);
    }
    public function add_user($mail,$psw,$nom,$prénom,$date_naiss,$sexe){
        $this->db = new ConnexionBDD();
            $cnx = $this->db->connexion();
            $date = date('Y-m-d', strtotime($date_naiss)); 
            $stmt = $cnx->prepare("INSERT INTO utilisateur (Nom, Prénom, Sexe, Mail, Date_naissance, Mot_de_passe, Is_admin,Valide) 
                                    VALUES (?,?,?,?,?,?,0,0)");
            $stmt->bindParam(1,$nom);
            $stmt->bindParam(2,$prénom);
            $stmt->bindParam(3,$sexe);
            $stmt->bindParam(4,$mail);
            $stmt->bindParam(5,$date);
            $stmt->bindParam(6,$psw);
            $stmt->execute();
            $this->db->deconnexion($cnx);
    }
    public function is_admin($mail,$psw){
        $user=$this->is_user($mail,$psw);
        if($user!=null){
            if($user['Is_admin']==1){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    public function valider_user($id_user){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("UPDATE `utilisateur` SET `Valide` = '1' WHERE `utilisateur`.`Id_Utilisateur` = ?");
        $stmt->bindParam(1,$id_user);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }
    public function enlever_user($id_user){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("UPDATE `utilisateur` SET `Valide` = '0' WHERE `utilisateur`.`Id_Utilisateur` = ?");
        $stmt->bindParam(1,$id_user);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }

    public function get_recettes_publiées($id_user){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM recette 
                               WHERE Id_Utilisateur= ?");
        $stmt->bindParam(1,$id_user);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }

    public function has_user_rated($id_user,$id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM notation 
                               WHERE Id_user= ? AND Id_recette= ? ");
        $stmt->bindParam(1,$id_user);
        $stmt->bindParam(2,$id_recette);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if( ! $row){
            return false;
        }else{
            return true;
        }
        $this->db->deconnexion($cnx);
    }

    public function update_user_rate($id_user,$id_recette,$note){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $state =$this->has_user_rated($id_user,$id_recette);
        echo "state".$state;
        if($state==true){
            $stmt = $cnx->prepare("UPDATE `notation` 
                                  SET `Note` = ? 
                                  WHERE `notation`.`Id_user` = ? AND `notation`.`Id_recette` = ?");
            $stmt->bindParam(1,$note);
            $stmt->bindParam(2,$id_user);
            $stmt->bindParam(3,$id_recette);
            $stmt->execute();

        }
        else{
            $stmt = $cnx->prepare("INSERT INTO `notation` (`Id_user`, `Id_recette`, `Note`) VALUES (?,?,?)");
            $stmt->bindParam(1,$id_user);
            $stmt->bindParam(2,$id_recette);
            $stmt->bindParam(3,$note);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);

    }

    


}

?>