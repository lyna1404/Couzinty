<?php
require_once "ConnexionBDD.php";
class IngrédientModel{
    private $db;
    public function get_all_Ingrédients(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM Ingrédient");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_all_nutriments(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM `valeurs_nutritionnelles`");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_valeurs_nutritionnelles($id){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT valeurs_nutritionnelles_ingredients.Id_Valeurs_nutritionnelles,ingrédient.Nom_ingrédient, valeurs_nutritionnelles.Nom, valeurs_nutritionnelles_ingredients.Taux 
                                FROM valeurs_nutritionnelles_ingredients 
                                INNER JOIN valeurs_nutritionnelles 
                                ON valeurs_nutritionnelles_ingredients.Id_Valeurs_nutritionnelles = valeurs_nutritionnelles.Id_Valeurs_nutritionnelles 
                                INNER JOIN ingrédient 
                                ON valeurs_nutritionnelles_ingredients.Id_Ingrédient = ingrédient.Id_Ingrédient 
                                WHERE ingrédient.Id_ingrédient = ?");
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_ingrédient_info($id){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM Ingrédient WHERE Id_ingrédient = ?");
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_ingrédient_saison($id){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT disponibilité_ingrédients.Id_Saison, Saison.Nom
                                    FROM disponibilité_ingrédients
                                    INNER JOIN Saison
                                    ON disponibilité_ingrédients.Id_Saison = Saison.Id_Saison
                                    WHERE Id_Ingrédient = ? ");
        $stmt->bindParam(1,$id);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_unités(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM Unité");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function add_ingredient($nom){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("INSERT INTO `ingrédient` (`Nom_ingrédient`) 
                                VALUES (?)");
        $stmt->bindParam(1,$nom);
        $stmt->execute();
        $id_recette = $cnx->lastInsertId('Id_Ingrédient');
        $this->db->deconnexion($cnx);
        return $id_recette;
    }
    public function get_all_new_ingredients(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM `nouveaux_ingrédients_recettes_nonvalidée`");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function delete_new_ing($id_recette,$nom_ing){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM `nouveaux_ingrédients_recettes_nonvalidée`
                             WHERE `nouveaux_ingrédients_recettes_nonvalidée`.`Id_Recette` = ? 
                             AND `nouveaux_ingrédients_recettes_nonvalidée`.`Nom_Ingrédient` = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->bindParam(2,$nom_ing);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function delete_ing_info($id_ing){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM `ingrédient` WHERE `ingrédient`.`Id_Ingrédient` = ?");
        $stmt->bindParam(1,$id_ing);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }
    public function delete_ing_val_nut($id_ing){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM `valeurs_nutritionnelles_ingredients` WHERE `valeurs_nutritionnelles_ingredients`.`Id_Ingrédient` = ?");
        $stmt->bindParam(1,$id_ing);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }
    public function delete_ing_disponibilité($id_ing){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM `disponibilité_ingrédients` WHERE `disponibilité_ingrédients`.`Id_Ingrédient` = ?");
        $stmt->bindParam(1,$id_ing);
        $stmt->execute();
        $this->db->deconnexion($cnx);
    }


    public function ajouter_ingredient_avec_infos($nom,$healthy,$taux,$nbcal,$etat,$description,$lien_image){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("INSERT INTO `ingrédient` (`Nom_ingrédient`, `Healthy`, `Taux_`, `Calories`, `état`, `Description`, `Lien_image`)
                               VALUES ( ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1,$nom);
        $stmt->bindParam(2,$healthy);
        $stmt->bindParam(3,$taux);
        $stmt->bindParam(4,$nbcal);
        $stmt->bindParam(5,$etat);
        $stmt->bindParam(6,$description);
        $stmt->bindParam(7,$lien_image);
        $stmt->execute();
        $id_ing = $cnx->lastInsertId('Id_Ingrédient');
        $this->db->deconnexion($cnx);
        return $id_ing;
    
    }

    public function set_disponibilté_ing($id_ing,$saisons){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($saisons as $saison){
            $stmt = $cnx->prepare("INSERT INTO `disponibilité_ingrédients` (`Id_Ingrédient`, `Id_Saison`) VALUES (?, ?)");
            $stmt->bindParam(1,$id_ing);
            $stmt->bindParam(2,$saison['id_saison']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx); 
    }
    public function set_val_nut_ing($id_ing,$vals){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($vals as $val){
            $stmt = $cnx->prepare("INSERT INTO `valeurs_nutritionnelles_ingredients` (`Id_Ingrédient`, `Id_Valeurs_nutritionnelles`, `Taux`)
                                   VALUES (?, ?, ?)");
            $stmt->bindParam(1,$id_ing);
            $stmt->bindParam(2,$val['id_nut']);
            $stmt->bindParam(3,$val['quantité']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
    }
    
}
?>
