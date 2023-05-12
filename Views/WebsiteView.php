<?php
global $work_dir;
require_once $work_dir."Views\MenuView.php";
require_once $work_dir."Views\ContactView.php";
require_once $work_dir."Views\ParametresView.php";
require_once $work_dir."Views\DiapoView.php";
require_once $work_dir."Views\RecetteView.php";
require_once $work_dir."Views\UserView.php";
require_once $work_dir."Views\IngrédientView.php";






class WebsiteView
{
    public function header()
    {
        echo"<head>";
            header('Content-Type: text/html; charset=UTF-8');
            echo"<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>";
            echo"<title>Kouzinty</title>";
            echo"<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
            echo"<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js'></script>";
            echo "<link href='Style.css' rel='stylesheet' type='text/css'/>";
        echo "</head>";

    }

    function navbar(){
        $menu =new MenuView();
        $contact = new ContactView();
        $param = new ParametresView();
        $user = new UserView();
        echo '<div class="navbar">';
        $param->logo();
        $contact->Contact();
        $menu->Menu();
        if(isset($_SESSION["username"])){
            if(isset($_SESSION['is_admin'])){
                echo "<a id='useraccount' href='Administration.php'>".$_SESSION["username"]."</a>";
            }
            else{echo "<a id='useraccount' href='Profile.php'>".$_SESSION["username"]."</a>";}
        }
        else{
            echo "<form id='connexion'><input id='connexionbtn' type='button' value='Se connecter'></form>";
        }
        $user->LoginForm();
        $user->RegisterForm();
        echo "</div>";
    }
    public function herosection()
    {   
        $menu =new MenuView();
        $contact = new ContactView();
        $param = new ParametresView();
        $diapo = new DiapoView();
        echo "<div id='diapo_img' >";
        $this->navbar();
        echo "<div class='diapo_principal'>";
        $diapo->diapo_principal();
        echo "</div>";
        echo "</div>";
        
    }
    public function Recette_details($id_recette)
    {
        $recette = new RecetteView();
        $recette->afficher_recette_details($id_recette);
        
    }
    
    public function footer(){
        $menu =new MenuView();
        $contact = new ContactView();
        $param = new ParametresView();
        echo '<div class="footer">';
        $param->logo_white();
        $menu->Menu();
        $contact->Contact_white();
        echo "</div>";
    }
    public function cadres_recette(){
        $diaporecette = new RecetteView();
        $diaporecette->diapo_recette_entrées();
        $diaporecette->diapo_recette_plats();
        $diaporecette->diapo_recette_boissons();
        $diaporecette->diapo_recette_desserts();
    }

    public function build_accueil(){
        echo"<!DOCTYPE html>";
        echo"<html>";
        echo"<head>";
        $this->header();
        echo"</head>";
        echo"<body>";
        $this->herosection();
        $this->cadres_recette();
        $this->footer();
        echo "<script>
        window.onload = function(){
            changediapo();
            changerecette_entrées(0);
            changerecette_plats(0);
            changerecette_boissons(0);
            changerecette_desserts(0);

        }
        </script>";
        echo"</body>";
        echo"</html>";
    }

    public function build_recette(){
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id_recette = $_REQUEST['Id'];
        $this->Recette_details($id_recette);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_news_details(){
        $view=New NewsView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id_news = $_REQUEST['Id'];
        $view-> News_details($id_news);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_ing_details(){
        $view=New IngrédientView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id = $_REQUEST['Id'];
        $view->ingrédient_infos($id);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_recettes_filtre(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $recette->filtre_recettes();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_saison(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $recette->Recette_de_saison();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_healthy(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $recette->healthy_recettes();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_profile(){
        $user = new UserView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id = $_SESSION['userId'];
        $user->Profilepage($id);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_nutrition(){
        $ing = new IngrédientView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $ing->nutrition_page();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_idées_recettes(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $recette->recettes_similaires();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_news(){
        $news = new NewsView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $news->afficher_news();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_fetes(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $recette->recettes_fetes();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_publier_recette(){
        $user = new UserView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $user->PublierRecette();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_administration(){
        $controller = new UserController();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        echo "<form method='post'><input type='submit' name='logout' id='logout' value='Se déconnecter'></form>";
        echo "<div id=gestions>";
        echo "<div><a href='Gestion_recettes.php'>Gestion des recettes</a></div>";
        echo "<div><a href='Gestion_news.php'>Gestion des news</a></div>";
        echo "<div><a href='Gestion_users.php'>Gestion des ustilisateurs</a></div>";
        echo "<div><a href='Gestion_nutrition.php'>Gestion de la nutrition</a></div>";
        echo "<div><a href='Gestion_diapo.php'>Gestion du Diaporama</a></div>";
        echo "<div><a href='Gestion_param.php'>Paramètres</a></div>";
        echo"</div>";
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
        if(isset($_POST['logout'])){
            $controller->user_logout();
         }
    }
    public function build_gestion_recettes(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $recette->gestion_recettes();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_gestion_param(){
        $param = new ParametresView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $param->gestion_param();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_gestion_diapo(){
        $diapo = new DiapoView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $diapo->gestion_diapo();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_gestion_news(){
        $news = new NewsView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $news->gestion_news();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_gestion_nutrition(){
        $ing = new IngrédientView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $ing->gestion_nutrition();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_gestion_users(){
        $users = new UserView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $users->gestion_users();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_modifier_recette(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id_recette = $_REQUEST['Id'];
        $recette->modifier_recette($id_recette);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_modifier_ing(){
        $ing = new IngrédientView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id = $_REQUEST['Id'];
        $ing->modifier_ingrédient($id);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_afficher_user(){
        $user = new UserView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id = $_REQUEST['Id'];
        $user->afficher_user($id);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_modifier_news(){
        $news = new NewsView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id = $_REQUEST['Id'];
        $news->modifier_news($id);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_valider_recette(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $id_recette = $_REQUEST['Id'];
        $recette->valider_recette($id_recette);
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    
    public function build_ajouter_recette(){
        $recette = new RecetteView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $recette->ajouter_recette();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_ajouter_news(){
        $news = new NewsView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $news->ajouter_news();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_ajouter_ing(){
        $ing = new IngrédientView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $ing->ajouter_ingrédient();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    public function build_contact_page(){
        $contact = new ContactView();
        echo"<!DOCTYPE html>";
        echo"<html>";
        $this->header();
        echo"<body>";
        $this->navbar();
        echo "<div class='cadre_page'>";
        $contact->ContactPage();
        echo"</div>";
        $this->footer();
        echo"</body>";
        echo"</html>";
    }
    
    
}
?>