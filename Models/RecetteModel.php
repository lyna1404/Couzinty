<?php
require_once "ConnexionBDD.php";
class RecetteModel{
    private $db;
    public function get_recette_by_id($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM recette 
                               INNER JOIN catégorie
                               ON recette.Id_Catégorie = catégorie.Id_Catégorie 
                               WHERE recette.Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_nonvalidée_by_id($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM recette_nonvalidée 
                               INNER JOIN catégorie
                               ON recette_nonvalidée.Id_Catégorie = catégorie.Id_Catégorie 
                               WHERE recette_nonvalidée.Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_by_categorie($categorie_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM recette 
                               INNER JOIN catégorie
                               ON recette.Id_Catégorie = catégorie.Id_Catégorie 
                               WHERE catégorie.Nom_Categorie = ?
                               ORDER BY recette.Notation DESC");
        $stmt->bindParam(1,$categorie_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_ingredients($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare(" SELECT ingrédient.Id_Ingrédient, ingrédient.Nom_ingrédient, ingrédients_recettes.quantité , ingrédients_recettes.Id_Unité , unité.Symbole
                                FROM ingrédient 
                                INNER JOIN ingrédients_recettes 
                                ON ingrédient.Id_Ingrédient = ingrédients_recettes.Id_Ingrédient 
                                INNER JOIN unité
                                ON ingrédients_recettes.Id_Unité = unité.Id_Unité
                                WHERE ingrédients_recettes.Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_nonvalide_nvx_ingredients($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare(" SELECT nouveaux_ingrédients_recettes_nonvalidée.Nom_ingrédient, nouveaux_ingrédients_recettes_nonvalidée.quantité , nouveaux_ingrédients_recettes_nonvalidée.Id_Unité , unité.Symbole
                                FROM nouveaux_ingrédients_recettes_nonvalidée 
                                INNER JOIN unité
                                ON nouveaux_ingrédients_recettes_nonvalidée.Id_Unité = unité.Id_Unité
                                WHERE nouveaux_ingrédients_recettes_nonvalidée.Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_nonvalide_ingredients($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare(" SELECT ingrédient.Id_Ingrédient, ingrédient.Nom_ingrédient, ingrédients_recettes_nonvalidée.quantité , ingrédients_recettes_nonvalidée.Id_Unité , unité.Symbole
                                FROM ingrédient 
                                INNER JOIN ingrédients_recettes_nonvalidée
                                ON ingrédient.Id_Ingrédient = ingrédients_recettes_nonvalidée.Id_Ingrédient 
                                INNER JOIN unité
                                ON ingrédients_recettes_nonvalidée.Id_Unité = unité.Id_Unité
                                WHERE ingrédients_recettes_nonvalidée.Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_etapes($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare(" SELECT etape.Id_Etape, etape.Description , etape.Ordre , etape.Id_Recette
                                FROM etape
                                INNER JOIN recette 
                                ON etape.Id_Recette = recette.Id_Recette 
                                WHERE recette.Id_Recette =  ?
                                ORDER BY etape.Ordre ASC");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_nonvalide_etapes($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare(" SELECT etape_nonvalidée.Id_Etape, etape_nonvalidée.Description , etape_nonvalidée.Ordre , etape_nonvalidée.Id_Recette
                                FROM etape_nonvalidée
                                INNER JOIN recette_nonvalidée 
                                ON etape_nonvalidée.Id_Recette = recette_nonvalidée.Id_Recette 
                                WHERE recette_nonvalidée.Id_Recette =  ?
                                ORDER BY etape_nonvalidée.Ordre ASC");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_all_recettes(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM recette 
                               ");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_all_recettes_nonvalidées(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM recette_nonvalidée 
                               ");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recette_saison($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $res = $this->get_recette_ingredients($id_recette);
        $ing_list = array();
        while ($row = $res->fetch(PDO::FETCH_ASSOC)){
            array_push($ing_list,$row['Id_Ingrédient']);
        }
        $ing_list=array_unique($ing_list);
        $saisons = ['Automne','Hiver','Printemps','Eté'];
        foreach($ing_list as $ing){
            $stmt = $cnx->prepare("SELECT disponibilité_ingrédients.Id_Saison, Saison.Nom
                                    FROM disponibilité_ingrédients
                                    INNER JOIN Saison
                                    ON disponibilité_ingrédients.Id_Saison = Saison.Id_Saison
                                    WHERE Id_Ingrédient = ? ");
            $stmt->bindParam(1,$ing);
            $stmt->execute();
            $saisons_ing = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($saisons_ing,$row['Nom']);
            }
            $saisons = array_intersect($saisons,$saisons_ing);
        }
        $this->db->deconnexion($cnx);
        return $saisons;

    }
    public function get_recette_de_saison($saison){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $all_recettes = $this->get_all_recettes();
        $result = array();
        while ($row = $all_recettes->fetch(PDO::FETCH_ASSOC)){
            if(in_array($saison,$this->get_recette_saison($row['Id_Recette']))){
                array_push($result,$row['Id_Recette']);
            }
        }
        $this->db->deconnexion($cnx);
        return $result;

    }
    public function get_healthy_recettes(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * 
                               FROM recette 
                               WHERE Healthy=1");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_fetes(){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM occasion");
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function get_recettes_de_fetes($fete){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT recette.Id_Recette 
                            FROM recette_occasion 
                            INNER JOIN recette 
                            ON recette_occasion.Id_Recette = recette.Id_Recette 
                            INNER JOIN occasion 
                            ON occasion.Id_occasion = recette_occasion.Id_occasion 
                            WHERE occasion.Nom = ?");
        $stmt->bindParam(1,$fete);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function user_add_recette($titre,$diff,$temp_prep,$temp_repo,$temp_cuiss,$nb_calories,$description,$id_cat,$id_user){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("INSERT INTO `recette_nonvalidée` (`Nom_Recette`, `Healthy`, `Lien_Image`, `Lien_Vidéo`, `Difficulté`, `Temp_total`, `Temp_Prep`, `Temp_Repo`, `Temp_Cuisson`, `Nb_Calories`, `Notation`, `Description`, `Id_Catégorie`, `Id_Utilisateur`) 
                                VALUES (?, 0, NULL, NULL, ?, ?, ?, ?, ?, ?, 3, ?, ?, ?)");
        $stmt->bindParam(1,$titre);
        $stmt->bindParam(2,$diff);
        $temp_total=$temp_prep+$temp_cuiss+$temp_repo;
        $stmt->bindParam(3,$temp_total);
        $stmt->bindParam(4,$temp_prep);
        $stmt->bindParam(5,$temp_repo);
        $stmt->bindParam(6,$temp_cuiss);
        $stmt->bindParam(7,$nb_calories);
        $stmt->bindParam(8,$description);
        $stmt->bindParam(9,$id_cat);
        $stmt->bindParam(10,$id_user);
        $stmt->execute();
        $id_recette = $cnx->lastInsertId('Id_Recette');
        $this->db->deconnexion($cnx);
        return $id_recette;
    }
    public function user_add_ingrédients_existants_recette($id_recette,$ing_existants){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($ing_existants as $ing){
            $stmt = $cnx->prepare("INSERT INTO `ingrédients_recettes_nonvalidée` (`Id_Recette`, `Id_Ingrédient`, `quantité`, `Id_Unité`) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->bindParam(1,$id_recette);
            $stmt->bindParam(2,$ing['id_ing']);
            $stmt->bindParam(3,$ing['quantité']);
            $stmt->bindParam(4,$ing['id_unité']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
    }  

    public function user_add_ingrédients_nonexistants_recette($id_recette,$ing_nonexistants){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($ing_nonexistants as $ing){
            $stmt = $cnx->prepare("INSERT INTO `nouveaux_ingrédients_recettes_nonvalidée` (`Id_Recette`, `Nom_Ingrédient`, `quantité`, `Id_Unité`) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->bindParam(1,$id_recette);
            $stmt->bindParam(2,$ing['nom_ing']);
            $stmt->bindParam(3,$ing['quantité']);
            $stmt->bindParam(4,$ing['id_unité']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
    }    
    public function user_add_etapes_recette($id_recette,$etapes){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($etapes as $ing){
            $stmt = $cnx->prepare("INSERT INTO `etape_nonvalidée` ( `Description`, `Ordre`, `Id_Recette`)
                                   VALUES ( ?, ?, ?)");
            $stmt->bindParam(3,$id_recette);
            $stmt->bindParam(2,$ing['ordre']);
            $stmt->bindParam(1,$ing['etape']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
    } 
    public function supprimer_etapes_recette($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM etape WHERE Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function supprimer_etapes_recette_nonvalide($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM etape_nonvalidée WHERE Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function supprimer_ingrédients_recette($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM ingrédients_recettes WHERE Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function supprimer_ingrédients_recette_nonvalide($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM ingrédients_recettes_nonvalidée WHERE Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function supprimer_nvx_ingrédients_recette_nonvalide($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM nouveaux_ingrédients_recettes_nonvalidée WHERE Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function supprimer_infos_recette($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM recette WHERE Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function supprimer_infos_recette_nonvalide($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("DELETE FROM recette_nonvalidée WHERE Id_Recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $this->db->deconnexion($cnx);
        return $stmt;
    }
    public function ajouter_infos_recette($titre,$healthy,$lien_image,$lien_video,$diff,$temp_prep,$temp_repo,$temp_cuiss,$nb_calories,$notation,$description,$id_cat,$id_user){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("INSERT INTO `recette` (`Nom_Recette`, `Healthy`, `Lien_Image`, `Lien_Vidéo`, `Difficulté`, `Temp_total`, `Temp_Prep`, `Temp_Repo`, `Temp_Cuisson`, `Nb_Calories`, `Notation`, `Description`, `Id_Catégorie`, `Id_Utilisateur`) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindParam(1,$titre);
        $stmt->bindParam(2,$healthy);
        $temp_total=$temp_prep+$temp_cuiss+$temp_repo;
        $stmt->bindParam(3,$lien_image);
        $stmt->bindParam(4,$lien_video);
        $stmt->bindParam(5,$diff);
        $stmt->bindParam(6,$temp_total);
        $stmt->bindParam(7,$temp_prep);
        $stmt->bindParam(8,$temp_repo);
        $stmt->bindParam(9,$temp_cuiss);
        $stmt->bindParam(10,$nb_calories);
        $stmt->bindParam(11,$notation);
        $stmt->bindParam(12,$description);
        $stmt->bindParam(13,$id_cat);
        $stmt->bindParam(14,$id_user);
        $stmt->execute();
        $id_recette = $cnx->lastInsertId('Id_Recette');
        $this->db->deconnexion($cnx);
        return $id_recette;
    }

    public function ajouter_etapes_recette($id_recette,$etapes){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($etapes as $ing){
            $stmt = $cnx->prepare("INSERT INTO `etape` ( `Description`, `Ordre`, `Id_Recette`)
                                   VALUES ( ?, ?, ?)");
            $stmt->bindParam(3,$id_recette);
            $stmt->bindParam(2,$ing['ordre']);
            $stmt->bindParam(1,$ing['etape']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
    } 

    public function ajouter_ingrédients_recette($id_recette,$ing_existants){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        foreach($ing_existants as $ing){
            $stmt = $cnx->prepare("INSERT INTO `ingrédients_recettes` (`Id_Recette`, `Id_Ingrédient`, `quantité`, `Id_Unité`) 
                                    VALUES (?, ?, ?, ?)");
            $stmt->bindParam(1,$id_recette);
            $stmt->bindParam(2,$ing['id_ing']);
            $stmt->bindParam(3,$ing['quantité']);
            $stmt->bindParam(4,$ing['id_unité']);
            $stmt->execute();
        }
        $this->db->deconnexion($cnx);
    } 
    public function set_recette_note($id_recette){
        $this->db = new ConnexionBDD();
        $cnx = $this->db->connexion();
        $stmt = $cnx->prepare("SELECT * FROM notation WHERE Id_recette = ?");
        $stmt->bindParam(1,$id_recette);
        $stmt->execute();
        $note = array();
        $sum = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $sum = $sum + $row['Note'];
            array_push($note,$row['Note']);
        }

        $notation = $sum/count($note);
        echo $notation;
        $stmt = $cnx->prepare("UPDATE `recette` SET `Notation` = ? WHERE `recette`.`Id_Recette` = ?");
        $stmt->bindParam(1,$notation);
        $stmt->bindParam(2,$id_recette);
        $stmt->execute();

    }
    
}
?>