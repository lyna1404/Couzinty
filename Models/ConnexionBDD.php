<?php
global $work_dir;
$work_dir = $_SERVER['DOCUMENT_ROOT'].'/projet_WEB/';
class ConnexionBDD{
    private $DB_host = "localhost";
    private $DB_user = "root";
    private $DB_pass = "";
    private $DB_name = "tdw";

    public function connexion(){
        try{
            $DB_con = new PDO("mysql:host={$this->DB_host};dbname={$this->DB_name};charset=UTF8",$this->DB_user,$this->DB_pass);
            $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $ex){
            printf("erreur lors de la connexion à la base de données");
            exit();
        }
        return $DB_con;
    }
    public function deconnexion($DB_con){
        $DB_con = null;
    }
    public function requete($DB_con, $r){
        $stmt = $DB_con->prepare($r);
        return $stmt->execute();
    }


    
}

?>