<?php
global $work_dir;
require_once $work_dir."Conrtollers\RecetteController.php";
require_once $work_dir."Conrtollers\UserController.php";

class RecetteView
{   
    public function afficher_recette_details($id_recette){
        $controller = new RecetteController();
        $controlleruser = new UserController();
        $res=$controller->get_recette_by_id($id_recette);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        echo "<div class='recette_cadre'>";
            echo "<div class='recette_header'>";
                echo "<div class='recette_info'>";
                    echo"<div class='info_principale'>";
                        echo"<div class='info_item'>";
                        echo"<p class='titre_info'>Temp. Total</p>";
                        echo "<p class='contenu_info'>".$row['Temp_total']."<p>";
                        echo "</div>";
                        echo"<div class='info_item'>";
                        echo"<p class='titre_info'>Difficulté</p>";
                        echo "<p class='contenu_info'>".$row['Difficulté']."<p>";
                        echo "</div>";
                        echo"<div class='info_item'>";
                        echo"<p class='titre_info'>Calories</p>";
                        echo "<p class='contenu_info'>".$row['Nb_Calories']."<p>";
                        echo "</div>";
                    if(isset($_SESSION['username'])){
                        echo"<div class='info_item'>";
                        echo"<p class='titre_info'>Notation</p>";
                        echo "<p class='contenu_info'>".$row['Notation']."<p>";
                        echo "</div>";}
                    echo "</div>";
                    echo"<div class='info_secondaire'>";
                        echo"<div class='info_item'>";
                        echo"<p class='titre_info'>Temp. Prep</p>";
                        echo "<p class='contenu_info'>".$row['Temp_Prep']."<p>";
                        echo "</div>";
                        echo"<div class='info_item'>";
                        echo"<p class='titre_info'>Temp. Repos</p>";
                        echo "<p class='contenu_info'>".$row['Temp_Repo']."<p>";
                        echo "</div>";
                        echo"<div class='info_item'>";
                        echo"<p class='titre_info'>Temp. Cuiss</p>";
                        echo "<p class='contenu_info'>".$row['Temp_Cuisson']."<p>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                echo "<div id='recette_image'>";
                    echo"<script type='text/javascript'>
                    document.getElementById('recette_image').style.backgroundImage = 'url(".$row['Lien_Image'].")';
                    </script>";
                echo "</div>";
                echo "<div class=recette_titre_desc>";
                echo "<div id='recette_titre'>";
                    echo "<p>".$controller->get_recette_title($id_recette)."</p>";
                echo "</div>";
                echo "<div id='recette_description'>";
                    echo "<p>".$controller->get_recette_description($id_recette)."</p>";
                echo "</div>";
                if(isset($_SESSION['username'])){
                    $list_recettes_sauv = $controlleruser->get_recettes_sauv($_SESSION['userId']);
                    if(in_array($id_recette,$list_recettes_sauv)){
                        echo "<input id='save_button' recette_id=".$id_recette." class='recette_saved' value='Sauvegardée' type='button'/>";
                    }
                    else{
                        echo "<input id='save_button' recette_id=".$id_recette." class='save_recette' value='Sauvegarder' type='button'/>";
                    }
                    echo "<div><input id='note' recette_id=".$id_recette." type='number' value='5'/>"; 
                    echo "<input id='noterbtn' recette_id=".$id_recette." type='button' value='Noter(sur 5)'/></div>"; 
                    echo "<script>
                    $('#save_button').click(function(){
                        var state = $(this).val();
                        var recipe_id = $(this).attr('recette_id');
                        if($(this).val() == 'Sauvegardée'){
                            $(this).attr('value','Sauvegarder');
                        }
                        else{
                            $(this).attr('value','Sauvegardée');
                    
                        }
                        var data ={etat : state,recipe_id : recipe_id,method:'save_unsave_recette'};
                        console.log(data);
                        $.ajax({
                                    type :'POST', 
                                    url : 'traitements_recettes.php',
                                    data : data,
                                    dataType:'json',
                                    succes:function(data){console.log(data);},
                                    complete:function(){location.reload();}
                                });
                        $(this).toggleClass('recette_saved save_recette');
                        
                    });
                    $('#noterbtn').click(function(){
                        var note = $('#note').val();
                        var recipe_id = $(this).attr('recette_id');
                        console.log(recipe_id);
                        $.ajax({
                            type: 'POST',
                            url : 'traitements_recettes.php',
                            data : {note: note, recipe_id : recipe_id,method:'update_note_recette'},
                            dataType : 'json',
                            
                            succes : function(){
                                console.log('succes');
                            },
                            complete:function(){location.reload();}
                        });
                    });
                    </script>";
                    
                    
                }

                echo "</div>";
                echo "<div class=recette_ing_etape>";
                echo "<div id='recette_ing'>";
                
                $ings=$controller->get_recette_ingredients($id_recette);
                    echo "<ul id='titre_ing'><p>Liste des ingrédients </p>";
                    while($ing=$ings->fetch(PDO::FETCH_ASSOC)){
                        echo "<li class='ing_list'><p>".$ing['quantité'].$ing['Symbole'].' '.$ing['Nom_ingrédient']."</p></li>";
                    }
                    echo "</ul>";
                echo "</div>";
                echo "<div id='recette_etape'>";
                $etapes=$controller->get_recette_etapes($id_recette);
                echo "<ol id='titre_etape'><p>Préparation </p>";
                while($etape=$etapes->fetch(PDO::FETCH_ASSOC)){
                    echo "<li class='etape_list'><p>".$etape['Description']."</p></li>";
                }
                echo "</ol>";
                echo "</div>";
                echo "</div>";
                $video=$controller->get_recette_video($id_recette); 
                if($video!=null){
                    echo "<video width='1240px' height='600px' autoplay muted><source src='".$video."' type='video/mp4'></video> ";
                }
            echo "</div>";
        echo "</div>";
    }
    public function filtrer(){
        echo"
        <div class='cadre'>
        <div class='sous_cadre'>
            <p class='cadre_titre'>Filtres des recettes</p>
            <p class='cadre_sous_titre'>Filtrer selon </p>
            <div id='filtre_bar'>
                    <form id='filtres_form' method='post' action=''>
                        <select name='Filtres' id='filtres'>
                            <option value='tous'>Afficher toutes les recettes</option>
                            <option value='catégorie'>Catégorie de la recette</option>
                            <option value='temp_prep'>Temps de préparation</option>
                            <option value='temp_cuiss'>Temps de cuisson</option>
                            <option value='temp_total'>Temps total</option>
                            <option value='saison'>Saison</option>
                            <option value='notation'>Notation</option>
                            <option value='calories'>Nombre de calories</option>
                        </select>
                        <div id='filtrer_selon'>
                            <select name='Catégories' id='catégories'>
                                <option value='Entrées'>Entrées</option>
                                <option value='Plats'>Plats</option>
                                <option value='Boissons'>Boissons</option>
                                <option value='Desserts'>Desserts</option>
                            </select>
                            <select name='Saisons' id='saisons'>
                                <option value='Eté'>Eté</option>
                                <option value='Printemps'>Printemps</option>
                                <option value='Hiver'>Hiver</option>
                                <option value='Automne'>Automne</option>
                            </select>
                            <div id='intervalle'>
                                <label for='min'>min </label>
                                <input type='number' id='min' name='min'>
                                <label for='max'>max </label>
                                <input type='number' id='max' name='max'>
                            </div>
                        </div>
                        <button id='filtrer_recettebtn' type='submit' name='submit_recherche'>Lancer la recherche</button>
                    </form>
            </div>
        </div>
        </div>";
        echo "
        <script>
            $('#filtrer_selon').children().hide();
            $('#filtrer_recettebtn').show();
            $('#filtres').change(function(){
                $('#filtrer_selon').children().hide();
                $('#filtrer_recettebtn').show();
                switch($('#filtres option:selected').val()){
                    case 'catégorie':
                        $('#catégories').show();
                        break; 
                    case 'saison':
                        $('#saisons').show();
                        break; 
                    case 'temp_prep':
                        $('#intervalle').show();
                        break; 
                    case 'temp_total':
                        $('#intervalle').show();
                        break; 
                    case 'temp_cuiss':
                        $('#intervalle').show();
                        break; 
                    case 'temp_cuiss':
                        $('#intervalle').show();
                        break; 
                    case 'notation':
                        $('#intervalle').show();
                        break; 
                    case 'calories':
                        $('#intervalle').show();
                        break; 
                }
            })
        </script>
        ";
        $recettes_list=array();
        $controller = new RecetteController();
        if(isset($_POST['submit_recherche'])){
            if(!empty($_POST['Filtres'])) {
                $selected = $_POST['Filtres'];
                switch($selected){
                    case 'tous':
                        $recettes_list=$controller->get_all_recettes();
                        break;
                    case 'catégorie':
                        if(!empty($_POST['Catégories'])){
                            $selected2 = $_POST['Catégories'];
                            $recettes_list=$controller->get_recettes_de_categorie($selected2,100);
                        }
                        break;
                    case 'saison':
                        if(!empty($_POST['Saisons'])){
                            $selected2 = $_POST['Saisons'];
                            $recettes_list=$controller->get_recette_de_saison($selected2);
                        }
                        break; 
                    case 'temp_prep':
                        $min = $_POST['min'];
                        $max = $_POST['max'];
                        $recettes_list=$controller->get_recettes_intervalle('Temp_Prep',$min,$max);
                        break; 
                    case 'temp_total':
                        $min = $_POST['min'];
                        $max = $_POST['max'];
                        $recettes_list=$controller->get_recettes_intervalle('Temp_total',$min,$max);
                        break; 
                    case 'temp_cuiss':
                        $min = $_POST['min'];
                        $max = $_POST['max'];
                        $recettes_list=$controller->get_recettes_intervalle('Temp_Cuisson',$min,$max);
                        break; 
                    case 'notation':
                        $min = $_POST['min'];
                        $max = $_POST['max'];
                        $recettes_list=$controller->get_recettes_intervalle('Notation',$min,$max);
                        break; 
                    case 'calories':
                        $min = $_POST['min'];
                        $max = $_POST['max'];
                        $recettes_list=$controller->get_recettes_intervalle('Nb_Calories',$min,$max);
                        break; 
                }
            } 
            unset($_POST);
         }
         else{
            $recettes_list=$controller->get_all_recettes();
         }
         
         return $recettes_list;
    }
    public function filtre_recettes(){
       $recettes_list = $this->filtrer();
         echo "<div class='cadre'><div class='sous_cadre_recette'>";
         foreach($recettes_list as $recette){
            $this->créer_cadre_recette($recette['Id_Recette']);
         }
         echo "</div></div>";

    }
    public function diapo_recette_entrées()
    {
        echo "<div class='cadre_recette'>";
        echo "<div class='cadre_header'>
                <p class='titre_recette_categorie' id='titre_recette_categorie_entreés'></p>
                <p class='link_recette_categorie' id='link_recette_categorie_entreés'><a target='_blank 'href='Recettes.php'>Afficher plus de recettes</a></p></div>";              
        $controller = new RecetteController();
        echo "<div class='diapo_recette_img' id='diapo_recette_img_entrées'>";
        echo "<div class='diapo_recette_text_area' id='diapo_recette_text_area_entrées'>";
        echo "<a id='prev_entrées' onclick='previousrecette_entrées()'><img src ='Media\Images\Utilitaires\Précedent.svg'></a>";
        echo "<div class='diapo_recette_text_container'>";
        echo "<div class='title_container'><p id='diapo_recette_title_entrées' class='diapo_recette_title'></p></div>";
        echo "<div class=title_underline></div>";
        echo "<div class='description_container'>
        <p class='diapo_recette_description' id='diapo_recette_description_entrées'></p>
        </div>";  
        echo "<form id='afficher_recette_entrées' class='afficher_recette' method='post' action='Recette.php?Id=' target='_blank'><button class='afficher_recettebtn' type='submit'>Afficher plus</button></form>";
        echo "</div>";
        echo "<a id='next_entrées' onclick='nextrecette_entrées()'><img src ='Media\Images\Utilitaires\Suivant.svg'></a>";
        echo "</div>";
        echo "</div>";
        echo "<div class='dots_container'>";
        $img_recette_entrées = array();
        $title_recette_entrées = array();
        $desc_recette_entrées = array();
        $id_recette_entrées = array();
        $recette_entrées=$controller->get_recettes_de_categorie("Entrées",10);
        $nbrecette_entrées = count($recette_entrées);
        $dotnum_entrées = 0;
        foreach($recette_entrées as $row){
            array_push($img_recette_entrées,addslashes($controller->get_recette_image($row['Id_Recette'])));
            array_push($title_recette_entrées,addslashes($controller->get_recette_title($row['Id_Recette'])));
            array_push($desc_recette_entrées,addslashes($controller->get_recette_description($row['Id_Recette'])));
            array_push($id_recette_entrées,$row['Id_Recette']);
            echo "<span class='dot' id='dot_entrées_".$dotnum_entrées."'></span>";
            $dotnum_entrées++;
        }   
        echo "</div>";
        echo "</div>"; 
        echo "<script type='text/javascript'>";
        echo "var images_recette_entrées = "; echo json_encode($img_recette_entrées); echo ";";
        echo "var titres_recette_entrées  = "; echo json_encode($title_recette_entrées); echo ";";
        echo "var descriptions_recette_entrées  = "; echo json_encode($desc_recette_entrées); echo ";";
        echo "var ids_recette_entrées  = "; echo json_encode($id_recette_entrées); echo ";";
        echo "var cat_entrées  = "; echo json_encode("Entrées"); echo ";";
        echo "var nbrecettes_entrées  = "; echo json_encode($nbrecette_entrées); echo ";";
        echo "var num_recette_entrées = 0;";
            echo "function changerecette_entrées(num_recette_entrées){";
                echo "
                document.getElementById('diapo_recette_img_entrées').style.backgroundImage = 'url('+images_recette_entrées[num_recette_entrées]+')';
                document.getElementById('diapo_recette_title_entrées').innerHTML = titres_recette_entrées[num_recette_entrées];
                document.getElementById('diapo_recette_description_entrées').innerHTML =descriptions_recette_entrées[num_recette_entrées];
                document.getElementById('titre_recette_categorie_entreés').innerHTML =cat_entrées;
                document.getElementById('afficher_recette_entrées').action ='Recette.php?Id='+ids_recette_entrées[num_recette_entrées];
                document.getElementById('dot_entrées_'+num_recette_entrées).style.backgroundColor ='#5E5E5E';
                }";
            echo "function nextrecette_entrées(){
                document.getElementById('dot_entrées_'+num_recette_entrées).style.backgroundColor ='#CAC8C8';
                num_recette_entrées++;
                if(num_recette_entrées>nbrecettes_entrées - 1){num_recette_entrées=0;}
                changerecette_entrées(num_recette_entrées);
                
            }";
            echo "function previousrecette_entrées(){
                document.getElementById('dot_entrées_'+num_recette_entrées).style.backgroundColor ='#CAC8C8';
                num_recette_entrées--;
                if(num_recette_entrées<0){num_recette_entrées=nbrecettes_entrées-1;}
                changerecette_entrées(num_recette_entrées);
            }";
        echo "</script>";
        
    }
    public function diapo_recette_plats()
    {
        echo "<div class='cadre_recette'>";
        echo "<div class='cadre_header'>
                <p class='titre_recette_categorie' id='titre_recette_categorie_plats'></p>
                <p class='link_recette_categorie' id='link_recette_categorie_plats'><a target='_blank' href='Recettes.php'>Afficher plus de recettes</a></p></div>";              
        $controller = new RecetteController();
        echo "<div class='diapo_recette_img' id='diapo_recette_img_plats'>";
        echo "<div class='diapo_recette_text_area' id='diapo_recette_text_area_plats'>";
        echo "<a id='prev_plats' onclick='previousrecette_plats()'><img src ='Media\Images\Utilitaires\Précedent.svg'></a>";
        echo "<div class='diapo_recette_text_container'>";
        echo "<div class='title_container'><p id='diapo_recette_title_plats' class='diapo_recette_title'></p></div>";
        echo "<div class=title_underline></div>";
        echo "<div class='description_container'>
        <p class='diapo_recette_description' id='diapo_recette_description_plats'></p>
        </div>";  
        echo "<form id='afficher_recette_plats' class='afficher_recette' method='post' action='Recette.php?Id=' target='_blank'><button class='afficher_recettebtn' type='submit'>Afficher plus</button></form>";
        echo "</div>";
        echo "<a id='next_plats' onclick='nextrecette_plats()'><img src ='Media\Images\Utilitaires\Suivant.svg'></a>";
        echo "</div>";
        echo "</div>";
        echo "<div class='dots_container'>";
        $img_recette_plats = array();
        $title_recette_plats = array();
        $desc_recette_plats = array();
        $id_recette_plats = array();
        $recette_plats=$controller->get_recettes_de_categorie("Plats",10);
        $nbrecette_plats = count($recette_plats);
        $dotnum_plats = 0;
        foreach($recette_plats as $row){
            array_push($img_recette_plats,addslashes($controller->get_recette_image($row['Id_Recette'])));
            array_push($title_recette_plats,addslashes($controller->get_recette_title($row['Id_Recette'])));
            array_push($desc_recette_plats,addslashes($controller->get_recette_description($row['Id_Recette'])));
            array_push($id_recette_plats,$row['Id_Recette']);
            echo "<span class='dot' id='dot_plats_".$dotnum_plats."'></span>";
            $dotnum_plats++;
        }   
        echo "</div>";
        echo "</div>"; 
        echo "<script type='text/javascript'>";
        echo "var images_recette_plats = "; echo json_encode($img_recette_plats); echo ";";
        echo "var titres_recette_plats = "; echo json_encode($title_recette_plats); echo ";";
        echo "var descriptions_recette_plats  = "; echo json_encode($desc_recette_plats); echo ";";
        echo "var ids_recette_plats  = "; echo json_encode($id_recette_plats); echo ";";
        echo "var cat_plats  = "; echo json_encode("Plats"); echo ";";
        echo "var nbrecettes_plats  = "; echo json_encode($nbrecette_plats); echo ";";
        echo "var num_recette_plats = 0;";
            echo "function changerecette_plats(num_recette_plats){";
                echo "
                document.getElementById('diapo_recette_img_plats').style.backgroundImage = 'url('+images_recette_plats[num_recette_plats]+')';
                document.getElementById('diapo_recette_title_plats').innerHTML = titres_recette_plats[num_recette_plats];
                document.getElementById('diapo_recette_description_plats').innerHTML =descriptions_recette_plats[num_recette_plats];
                document.getElementById('titre_recette_categorie_plats').innerHTML =cat_plats;
                document.getElementById('afficher_recette_plats').action ='Recette.php?Id='+ids_recette_plats[num_recette_plats];
                document.getElementById('dot_plats_'+num_recette_plats).style.backgroundColor ='#5E5E5E';
                }";
            echo "function nextrecette_plats(){
                document.getElementById('dot_plats_'+num_recette_plats).style.backgroundColor ='#CAC8C8';
                num_recette_plats++;
                if(num_recette_plats>nbrecettes_plats - 1){num_recette_plats=0;}
                changerecette_plats(num_recette_plats);
                
            }";
            echo "function previousrecette_plats(){
                document.getElementById('dot_plats_'+num_recette_plats).style.backgroundColor ='#CAC8C8';
                num_recette_plats--;
                if(num_recette_plats<0){num_recette_plats=nbrecettes_plats-1;}
                changerecette_plats(num_recette_plats);
            }";
        echo "</script>";
        
    }
    public function diapo_recette_boissons()
    {
        echo "<div class='cadre_recette'>";
        echo "<div class='cadre_header'>
                <p class='titre_recette_categorie' id='titre_recette_categorie_boissons'></p>
                <p class='link_recette_categorie' id='link_recette_categorie_boissons'><a target='_blank' href='Recettes.php'>Afficher plus de recettes</a></p></div>";              
        $controller = new RecetteController();
        echo "<div class='diapo_recette_img' id='diapo_recette_img_boissons'>";
        echo "<div class='diapo_recette_text_area' id='diapo_recette_text_area_boissons'>";
        echo "<a id='prev_boissons' onclick='previousrecette_boissons()'><img src ='Media\Images\Utilitaires\Précedent.svg'></a>";
        echo "<div class='diapo_recette_text_container'>";
        echo "<div class='title_container'><p id='diapo_recette_title_boissons' class='diapo_recette_title'></p></div>";
        echo "<div class=title_underline></div>";
        echo "<div class='description_container'>
        <p class='diapo_recette_description' id='diapo_recette_description_boissons'></p>
        </div>";  
        echo "<form id='afficher_recette_boissons' class='afficher_recette' method='post' action='Recette.php?Id=' target='_blank'><button class='afficher_recettebtn' type='submit'>Afficher plus</button></form>";
        echo "</div>";
        echo "<a id='next_boissons' onclick='nextrecette_boissons()'><img src ='Media\Images\Utilitaires\Suivant.svg'></a>";
        echo "</div>";
        echo "</div>";
        echo "<div class='dots_container'>";
        $img_recette_boissons = array();
        $title_recette_boissons = array();
        $desc_recette_boissons = array();
        $id_recette_boissons = array();
        $recette_boissons=$controller->get_recettes_de_categorie("Boissons",10);
        $nbrecette_boissons = count($recette_boissons);
        $dotnum_boissons = 0;
        foreach($recette_boissons as $row){
            array_push($img_recette_boissons,addslashes($controller->get_recette_image($row['Id_Recette'])));
            array_push($title_recette_boissons,addslashes($controller->get_recette_title($row['Id_Recette'])));
            array_push($desc_recette_boissons,addslashes($controller->get_recette_description($row['Id_Recette'])));
            array_push($id_recette_boissons,$row['Id_Recette']);
            echo "<span class='dot' id='dot_boissons_".$dotnum_boissons."'></span>";
            $dotnum_boissons++;
        }   
        echo "</div>";
        echo "</div>"; 
        echo "<script type='text/javascript'>";
        echo "var images_recette_boissons = "; echo json_encode($img_recette_boissons); echo ";";
        echo "var titres_recette_boissons = "; echo json_encode($title_recette_boissons); echo ";";
        echo "var descriptions_recette_boissons  = "; echo json_encode($desc_recette_boissons); echo ";";
        echo "var ids_recette_boissons  = "; echo json_encode($id_recette_boissons); echo ";";
        echo "var cat_boissons  = "; echo json_encode("Boissons"); echo ";";
        echo "var nbrecettes_boissons  = "; echo json_encode($nbrecette_boissons); echo ";";
        echo "var num_recette_boissons = 0;";
            echo "function changerecette_boissons(num_recette_boissons){";
                echo "
                document.getElementById('diapo_recette_img_boissons').style.backgroundImage = 'url('+images_recette_boissons[num_recette_boissons]+')';
                document.getElementById('diapo_recette_title_boissons').innerHTML = titres_recette_boissons[num_recette_boissons];
                document.getElementById('diapo_recette_description_boissons').innerHTML =descriptions_recette_boissons[num_recette_boissons];
                document.getElementById('titre_recette_categorie_boissons').innerHTML =cat_boissons;
                document.getElementById('afficher_recette_boissons').action ='Recette.php?Id='+ids_recette_boissons[num_recette_boissons];
                document.getElementById('dot_boissons_'+num_recette_boissons).style.backgroundColor ='#5E5E5E';
                }";
            echo "function nextrecette_boissons(){
                document.getElementById('dot_boissons_'+num_recette_boissons).style.backgroundColor ='#CAC8C8';
                num_recette_boissons++;
                if(num_recette_boissons>nbrecettes_boissons - 1){num_recette_boissons=0;}
                changerecette_boissons(num_recette_boissons);
                
            }";
            echo "function previousrecette_boissons(){
                document.getElementById('dot_boissons_'+num_recette_boissons).style.backgroundColor ='#CAC8C8';
                num_recette_boissons--;
                if(num_recette_boissons<0){num_recette_boissons=nbrecettes_boissons-1;}
                changerecette_boissons(num_recette_boissons);
            }";
        echo "</script>";
    }
    public function diapo_recette_desserts()
    {
        echo "<div class='cadre_recette'>";
        echo "<div class='cadre_header'>
                <p class='titre_recette_categorie' id='titre_recette_categorie_desserts'></p>
                <p class='link_recette_categorie' id='link_recette_categorie_desserts'><a target='_blank' href='Recettes.php'>Afficher plus de recettes</a></p></div>";              
        $controller = new RecetteController();
        echo "<div class='diapo_recette_img' id='diapo_recette_img_desserts'>";
        echo "<div class='diapo_recette_text_area' id='diapo_recette_text_area_desserts'>";
        echo "<a id='prev_desserts' onclick='previousrecette_desserts()'><img src ='Media\Images\Utilitaires\Précedent.svg'></a>";
        echo "<div class='diapo_recette_text_container'>";
        echo "<div class='title_container'><p id='diapo_recette_title_desserts' class='diapo_recette_title'></p></div>";
        echo "<div class=title_underline></div>";
        echo "<div class='description_container'>
        <p class='diapo_recette_description' id='diapo_recette_description_desserts'></p>
        </div>";  
        echo "<form id='afficher_recette_desserts' class='afficher_recette' method='post' action='Recette.php?Id=' target='_blank'><button class='afficher_recettebtn' type='submit'>Afficher plus</button></form>";
        echo "</div>";
        echo "<a id='next_desserts' onclick='nextrecette_desserts()'><img src ='Media\Images\Utilitaires\Suivant.svg'></a>";
        echo "</div>";
        echo "</div>";
        echo "<div class='dots_container'>";
        $img_recette_desserts = array();
        $title_recette_desserts = array();
        $desc_recette_desserts = array();
        $id_recette_desserts = array();
        $recette_desserts=$controller->get_recettes_de_categorie("Desserts",10);
        $nbrecette_desserts = count($recette_desserts);
        $dotnum_desserts = 0;
        foreach($recette_desserts as $row){
            array_push($img_recette_desserts,addslashes($controller->get_recette_image($row['Id_Recette'])));
            array_push($title_recette_desserts,addslashes($controller->get_recette_title($row['Id_Recette'])));
            array_push($desc_recette_desserts,addslashes($controller->get_recette_description($row['Id_Recette'])));
            array_push($id_recette_desserts,$row['Id_Recette']);
            echo "<span class='dot' id='dot_desserts_".$dotnum_desserts."'></span>";
            $dotnum_desserts++;
        }   
        echo "</div>";
        echo "</div>"; 
        echo "<script type='text/javascript'>";
        echo "var images_recette_desserts = "; echo json_encode($img_recette_desserts); echo ";";
        echo "var titres_recette_desserts = "; echo json_encode($title_recette_desserts); echo ";";
        echo "var descriptions_recette_desserts  = "; echo json_encode($desc_recette_desserts); echo ";";
        echo "var ids_recette_desserts  = "; echo json_encode($id_recette_desserts); echo ";";
        echo "var cat_desserts  = "; echo json_encode("Desserts"); echo ";";
        echo "var nbrecettes_desserts  = "; echo json_encode($nbrecette_desserts); echo ";";
        echo "var num_recette_desserts = 0;";
            echo "function changerecette_desserts(num_recette_desserts){";
                echo "
                document.getElementById('diapo_recette_img_desserts').style.backgroundImage = 'url('+images_recette_desserts[num_recette_desserts]+')';
                document.getElementById('diapo_recette_title_desserts').innerHTML = titres_recette_desserts[num_recette_desserts];
                document.getElementById('diapo_recette_description_desserts').innerHTML =descriptions_recette_desserts[num_recette_desserts];
                document.getElementById('afficher_recette_desserts').action ='Recette.php?Id='+ids_recette_desserts[num_recette_desserts];
                document.getElementById('titre_recette_categorie_desserts').innerHTML =cat_desserts;
                document.getElementById('dot_desserts_'+num_recette_desserts).style.backgroundColor ='#5E5E5E';
                }";
            echo "function nextrecette_desserts(){
                document.getElementById('dot_desserts_'+num_recette_desserts).style.backgroundColor ='#CAC8C8';
                num_recette_desserts++;
                if(num_recette_desserts>nbrecettes_desserts - 1){num_recette_desserts=0;}
                changerecette_desserts(num_recette_desserts);
                
            }";
            echo "function previousrecette_desserts(){
                document.getElementById('dot_desserts_'+num_recette_desserts).style.backgroundColor ='#CAC8C8';
                num_recette_desserts--;
                if(num_recette_desserts<0){num_recette_desserts=nbrecettes_desserts-1;}
                changerecette_desserts(num_recette_desserts);
            }";
        echo "</script>";
        
    }
    public function créer_cadre_recette($id_recette){
        $controller = new RecetteController();
        $img = $controller->get_recette_image($id_recette);
        $titre = $controller->get_recette_title($id_recette);
        $categorie = $controller->get_recette_categorie($id_recette);
        echo "
        <div class = 'recette_cadre' style='background-image: url(".$img.")'>
        <a href='Recette.php?Id=".$id_recette."'></a>
            <div class = 'recette_info'>
                <p class = 'recette_categorie'>".$categorie."</p>
                <p class = 'recette_titre'>".$titre."</p>
            </div>
        </div>
        ";

    }
    
    public function recette_de_saison(){
        $today = new DateTime();
        $printemps = new DateTime('March 20');
        $été = new DateTime('June 20');
        $automne = new DateTime('September 22');
        $hiver = new DateTime('December 21');
        switch(true) {
            case $today >= $printemps && $today < $été:
                $saison = 'Printemps';
                break;

            case $today >= $été && $today < $automne:
                $saison = 'Eté';
                break;

            case $today >= $automne && $today < $hiver:
                $saison = 'Automne';
                break;

            default:
                $saison = 'Hiver';
        }
        $controller = new RecetteController();
        $recettes_list=$controller->get_recette_de_saison($saison);
        echo "<div class='cadre'>
            <div class='sous_cadre'>
            <p class='cadre_titre'>Recettes de saison </p>
            <p class='cadre_sous_titre'>Nous sommes actuellement en ".$saison." ! </p>";
            echo "</div></div>";
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
         foreach($recettes_list as $recette){
            $this->créer_cadre_recette($recette['Id_Recette']);
         }
         echo "</div></div>";

    }
    public function recettes_similaires(){
        $controller = new RecetteController();
        
        echo "<div class='cadre'>
                <div class='sous_cadre'>
                <p class='cadre_titre'>Idées de recettes</p>";
        echo "<div id ='idées_recettes' >";
        echo "<div>";
        echo "<p class='cadre_sous_titre'>Introduisez vos ingrédients:</p>
        <form action=''>
          <input type='text' id='fname' name='fname' onkeyup='showHint(this.value)'>
          <input id='btnAdd' type='button' name='add' value='+'/>
        </form>
        <div id='listing'></div>";
        echo "</div>";
        echo"
        <div  class='list_ingrédients'>
        <ul class='cadre_sous_titre' id='ing_choisis'> Ingrédients choisis :</ul>
        </div>";
        echo "</div>";
        echo"
        <form  action='Idees_recettes.php' method='get'>
        <input id='checkeding' type='hidden' name='checkeding' value=''/>
        <input id='Submit' type='submit' name='submit_idées' value='Lancer la recherche'/>
        </form>";
        echo "</div></div>";

        echo"<script>
        function createing(ing){
            var txt3 = document.createElement('p');  
            txt3.innerHTML = ing;
        }
        function showHint(str) {
          if (str.length >= 0) {
            var xmlhttp = new XMLHttpRequest();
            var ingarr;
            xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                var ingArr = this.responseText.split(',');
                var arrayLength = ingArr.length;
                jQuery('#listing').empty();
                for (var i = 0; i < arrayLength; i++) {
                var label = ingArr[i];
                var value = ingArr[i].replace(/\s/g,'/');
                var str  = '<input type=\'checkbox\' name=\'check_list[]\' value='+value+' id='+ingArr[i]+'>';
                if(ingArr[i] != 'no suggestion'){
                    jQuery('#listing').append(str+'<label>'+label+'</label>');}
                }
              }
            };
            xmlhttp.open('GET', 'gethint.php?q=' + str, true);
            xmlhttp.send();
          }
        }
        var checked ='';
        jQuery('#btnAdd').click(function(){
            jQuery('input[type=checkbox]').each(function () {
                if (this.checked) {
                    var ing = jQuery(this).val().replaceAll('/',' ');
                    jQuery('#ing_choisis').append('<li>'+ing+'</li>');
                    checked = checked+','+jQuery(this).val();
                    jQuery('#checkeding').attr('value',checked);
                }
            });
        }); 
        </script>";
        if(isset($_GET['submit_idées'])){
            $ing = $_GET['checkeding'];
            $ing = str_replace('/',' ',$ing);
            $ing_list=explode(',',$ing);
            $ing_list=array_filter($ing_list);
            $finalinglist=array();
            foreach($ing_list as $ing){
                array_push($finalinglist,$ing);
            }
            $res = $controller->get_recettes_similaires($finalinglist);
            echo "<div class='cadre'><div class='sous_cadre_recette'>";
            if($res !=null){
                foreach($res as $recette){
                    $this->créer_cadre_recette($recette);}
                }
            else{
                echo "<p class='cadre_sous_titre'>Aucune recette trouvée !</p>";
            }
            }
        else{
            echo "<div class='cadre'><div class='sous_cadre_recette'>";
            $recettes_list=$controller->get_recettes_de_lasaison();
            foreach($recettes_list as $recette){
            $this->créer_cadre_recette($recette['Id_Recette']);
         }
        }
            
            echo"</div></div>";
    }
    public function healthy_recettes(){
        $controller = new RecetteController();
        $recettes_list=$controller->get_healthy_recettes();
        echo "<div class='cadre'>
            <div class='sous_cadre'>
            <p class='cadre_titre'>Recettes Healthy </p>";
            echo "</div></div>";
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
         foreach($recettes_list as $recette){
            $this->créer_cadre_recette($recette);
         }
         echo "</div></div>";
    }
    public function recettes_fetes(){
        $controller = new RecetteController();
        $fetes_list=$controller->get_fetes();
        echo "<div class='cadre'>
            <div class='sous_cadre'>
            <p class='cadre_titre'>Recettes de fetes </p>";
            echo "<select id='fetes' name='fetes'>";
            foreach($fetes_list as $fete){
            echo "<option value=".$fete.">".$fete."</option>";
            }
            echo "</select>";
        echo "</div></div>";
        echo "<div class='cadre'><div class='sous_cadre_fetes'>";
        foreach($fetes_list as $fete){
            echo "<div class='fete' id=".$fete.">";
            echo "<p class='cadre_titre'>".$fete."</p>";
            echo "<div class='sous_cadre_recette'>";
            $recettes = $controller->get_recettes_de_fete($fete);
            foreach($recettes as $recette){
                $this->créer_cadre_recette($recette);
            }
            echo "</div></div>";
        }
         
        echo "</div></div>";
        echo "<script>
        $('select#fetes').change(function(){
            $('div.sous_cadre_fetes>div').css('display','none');
            var selected = $('#fetes option:selected').val();
            $('div#'+selected).show();
        });
        </script>";
    }
    public function gestion_recettes(){
        $controller = new RecetteController();
        $recettes = $controller->get_all_recettes();
        $recettes = $this->filtrer();
        echo "
        <div class='cadre'>
        <div class='sous_cadre'>
        <p class='cadre_titre'>Gestion des recettes validées</p>
        <button value='Ajouter recette' id='ajouterbtn'>Ajouter recette</button>
        </div>
        <div class='sous_cadre_table'>
        <table id='table' class='table table-bordered table-striped'>
            <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Healthy</th>
                <th>Lien Image</th>
                <th>Lien Vidéo</th>
                <th>Difficulté</th>
                <th>Temp_prep</th>
                <th>Temp_repo</th>
                <th>Temp_cuiss</th>
                <th>Calories</th>
                <th>Notation</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th></th>
            </tr>
            </thead>
            <tbody id='myTable'>";
            foreach($recettes as $recette){
                echo "<tr>";
                    echo "<td>".$recette['Id_Recette']."</td>";
                    echo "<td>".$recette['Nom_Recette']."</td>";
                    echo "<td>".$recette['Healthy']."</td>";
                    echo "<td>".$recette['Lien_Image']."</td>";
                    echo "<td>".$recette['Lien_Vidéo']."</td>";
                    echo "<td>".$recette['Difficulté']."</td>";
                    echo "<td>".$recette['Temp_Prep']."</td>";
                    echo "<td>".$recette['Temp_Repo']."</td>";
                    echo "<td>".$recette['Temp_Cuisson']."</td>";
                    echo "<td>".$recette['Nb_Calories']."</td>";
                    echo "<td>".$recette['Notation']."</td>";
                    echo "<td>".$recette['Description']."</td>";
                    echo "<td>".$controller->get_recette_categorie($recette['Id_Recette'])."</td>";
                    echo "<td><button class='modifier' id_recette='".$recette['Id_Recette']."'>Modifier</button>
                    <button class='supprimer' etat='valide' id_recette='".$recette['Id_Recette']."'>Supprimer</button>
                    </td>";
                echo "</tr>";
            }
            echo"
            </tbody>
        </table>
        ";
        $recettes2 = $controller->get_all_recettes_nonvalidées();
        echo "
        <p class='cadre_titre'>Gestion des recettes non validées</p>
        <table id='table2' class='table table-bordered table-striped'>
            <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Healthy</th>
                <th>Lien Image</th>
                <th>Lien Vidéo</th>
                <th>Difficulté</th>
                <th>Temp_prep</th>
                <th>Temp_repo</th>
                <th>Temp_cuiss</th>
                <th>Calories</th>
                <th>Notation</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th></th>
            </tr>
            </thead>
            <tbody id='myTable2'>";
            foreach($recettes2 as $recette){
                echo "<tr>";
                    echo "<td>".$recette['Id_Recette']."</td>";
                    echo "<td>".$recette['Nom_Recette']."</td>";
                    echo "<td>".$recette['Healthy']."</td>";
                    echo "<td>".$recette['Lien_Image']."</td>";
                    echo "<td>".$recette['Lien_Vidéo']."</td>";
                    echo "<td>".$recette['Difficulté']."</td>";
                    echo "<td>".$recette['Temp_Prep']."</td>";
                    echo "<td>".$recette['Temp_Repo']."</td>";
                    echo "<td>".$recette['Temp_Cuisson']."</td>";
                    echo "<td>".$recette['Nb_Calories']."</td>";
                    echo "<td>".$recette['Notation']."</td>";
                    echo "<td>".$recette['Description']."</td>";
                    echo "<td>".$controller->get_recette_nonvalidée_categorie($recette['Id_Recette'])."</td>";
                    echo "<td><button class='valider' id_recette='".$recette['Id_Recette']."'>Valider</button>
                    <button class='supprimer' etat='nonvalide' id_recette='".$recette['Id_Recette']."'>Supprimer</button>
                    </td>";
                echo "</tr>";
            }
            echo"
            </tbody>
        </table>
        </div>
        ";
        echo "<script>
        $('button.modifier').click(function(){
            var id = $(this).attr('id_recette');
            window.open('Modifier_recette.php?Id='+id);
            
        });
        $('button.valider').click(function(){
            var id = $(this).attr('id_recette');
            window.open('Valider_recette.php?Id='+id);
            
        });
        $('button.supprimer').click(function(){
            var id = $(this).attr('id_recette');
            var etat = $(this).attr('etat');

            $.ajax({
                type: 'POST',
                url : 'traitements_recettes.php',
                data : {etat:etat, id: id, method:'supprimer_recette'},
                dataType : 'json',
                error : function(ts){alert(ts.responseText);},
                complete : function(){location.reload();}
            });
        });
        $('button#ajouterbtn').click(function(){
            window.open('Ajouter_recette.php');
        });
        
        </script>";
    }
    public function modifier_recette($id_recette){
        $controller = new RecetteController();
        $ingrédients = $controller->get_recette_ingredients($id_recette);
        $etapes=$controller->get_recette_etapes($id_recette);
        $res=$controller->get_recette_by_id($id_recette);
        $recette =$res->fetch(PDO::FETCH_ASSOC);
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
        echo "
        <form id='publier_form'>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Informations génerales sur la recette</p>
            <div class='input_container'>
            <label for='titre'> Titre de la recette </label>
            <input name ='titre' id ='titre' type='text'>
            </div>
            <div class='input_container'>
            <label for='description'> Description de la recette </label>
            <textarea name ='description' id ='description' form='publier_form'> </textarea>
            </div>
            <div class='input_container'>
            <label for='healthy'> Healthy </label>
            <select id='healthy' name='healthy'>
                <option value='1'>Oui</option>
                <option value='0'>Non</option>
            </select>
            </div>
            <div class='input_container'>
            <label for='lien_image'> Lien image (avec '/')</label>
            <input name ='lien_image' id ='lien_image' type='text'>
            </div>
            <div class='input_container'>
            <label for='lien_vidéo'> Lien vidéo (avec '/')</label>
            <input name ='lien_vidéo' id ='lien_vidéo' type='text'>
            </div>
            <div class='input_container'>
            <label for='temp_prep'> Temps de préparation(min) </label>
            <input name ='temp_prep' id ='temp_prep' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='temp_repo'> Temps de repos(min) </label>
            <input name ='temp_repo' id ='temp_repo' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='temp_cuiss'> Temps de cuisson(min) </label>
            <input name ='temp_cuiss' id ='temp_cuiss' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='nb_calories'> Nombre de calories(Kcal) </label>
            <input name ='nb_calories' id ='nb_calories' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='difficulté'> Difficulté de la recette  </label>
            <select id='difficulté' name='difficulté'>
                <option value='Facile'>Facile</option>
                <option value='Moyenne'>Moyenne</option>
                <option value='Difficile'>Difficile</option>
            </select>
            </div>
            <div class='input_container'>
            <label for='catégorie'> Catégorie de la recette  </label>
            <select id='catégorie' name='catégorie'>
                <option id_cat='1' value='Entrées'>Entrées</option>
                <option id_cat='2' value='Plats'>Plats</option>
                <option id_cat='3' value='Boissons'>Boissons</option>
                <option id_cat='4' value='Desserts'>Desserts</option>
            </select>
            <div class='input_container'>
            <label for='notation'> Notation </label>
            <input name ='notation' id ='notation' type='number' value='4'>
            </div>
            </div>
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Ingrédients de la recette</p>";
            echo "
            <table id='table_ing' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ingrédient</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_ing'>";
                foreach($ingrédients as $ing){
                    echo "<tr>";
                        echo "<td id_ing='".$ing['Id_Ingrédient']."'>".$ing['Nom_ingrédient']."</td>";
                        echo "<td>".$ing['quantité']."</td>";
                        echo "<td id_unité='".$ing['Id_Unité']."'>".$ing['Symbole']."</td>";
                        echo "<td><button onclick=this.closest('tr').remove() class='supprimer' id_ing='".$ing['Id_Ingrédient']."'>Supprimer</button></td>";
                    echo "</tr>";
                }
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Rajouter un ingrédient </p>
            <div class='input_container'>
            <label for='ingredients'> Liste des ingrédients </label>
            <select id='ingredients' name='ingredients[]'>
            </select>
            </div>
            <p class='ins'>Spécifiez la quantité de l'ingrédient ainsi que l'unité de mesure</p>
            <div class='input_container'>
            <span>
            <label> Quantité </label>
            <input type='number' id='quantité' value='0'>
            </span><span>
            <label> Unité de mesure </label>
            <select id='unités' name='unités[]'>
            </select>
            </span>
            </div>
            <p class='ins'>Cliquez sur le + pour rajoutez cet ingrédient à la liste des ingrédients de votre recette</p>
        </div>
        <input type='button' value='+' id ='add_ing'/>
        
        <div class='partie_form'>
            <p class='publier_sous_titre'>Etapes de la recette</p>";
            echo "
            <table id='table_ing' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Etape</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_etape'>";
                foreach($etapes as $etape){
                    echo "<tr>";
                        echo "<td ordre='".$etape['Ordre']."'>".$etape['Ordre']."</td>";
                        echo "<td>".$etape['Description']."</td>";
                        echo "<td><input onclick=this.closest('tr').remove() value='supprimer' class='supprimer' ordre='".$etape['Ordre']."' type='button'/></td>";
                    echo "</tr>";
                }
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Rajouter une étape</p>
            <div class='input_container'>
            <label> Ordre </label>
            <input type ='number' id='ordre' value='0'/>
            <label> Etape </label>
            <input type ='text' id='etape'/> 
            <input type='button' value='+' id ='add_etape'/>
            </div>
        </div>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>";
        echo "var recette = "; echo json_encode($recette); echo ";";
        echo "
            $('#titre').attr('value',recette.Nom_Recette);
            $('#description').val(recette.Description);
            $('#healthy').val(recette.Healthy);
            $('#lien_image').attr('value',recette.Lien_Image);
            $('#lien_vidéo').attr('value',recette.Lien_Vidéo);
            $('#temp_prep').attr('value',recette.Temp_Prep);
            $('#temp_repo').attr('value',recette.Temp_Repo);
            $('#temp_cuiss').attr('value',recette.Temp_Cuisson);
            $('#nb_calories').attr('value',recette.Nb_Calories);
            $('#difficulté').val(recette.Difficulté);
            $('#catégorie').val(recette.Nom_Categorie);
            $('#notation').attr('value',recette.Notation);
            $.ajax({
                type: 'GET',
                url: 'get_ingredients.php',
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, ingredient) {
                        var str  = '<option id_ing='+ingredient[0]+'>';
                        $('#ingredients').append(str + ingredient[1] + '</option>');
                    });
                }
            });
            $.ajax({
                type: 'GET',
                url: 'get_unités.php',
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, unité) {
                        var str  = '<option value='+unité[2]+' id_unité='+unité[0]+'>';
                        $('#unités').append(str + unité[1] + '</option>');
                    });
                }
            });
            
            $('#add_ing').click(function(){
                var quantité = $('#quantité').val();
                var ing_name = $('#ingredients option:selected').text();
                var id_ing = $('#ingredients option:selected').attr('id_ing');
                var symbole_unité = $('#unités option:selected').val();
                var id_unité = $('#unités option:selected').attr('id_unité');
                $('#myTable_ing').append('<tr><td id_ing='+id_ing+'>'+ing_name+'</td><td>'+quantité+'</td><td id_unité='+id_unité+'>'+symbole_unité+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' id_ing='+id_ing+'>Supprimer</button></td></tr>');
            });

            $('#add_etape').click(function(){
                var ordre = $('#ordre').val();
                var etape = $('#etape').val();
                $('#myTable_etape').append('<tr><td ordre='+ordre+'>'+ordre+'</td><td>'+etape+'</td><td><input type=button value=supprimer class=supprimer onclick=this.closest(\'tr\').remove()></td></tr>');
            });

            $('#publierbtn').click(function(){
                var ingredients = [];
                $(function () {
                $('#myTable_ing tr').each(function () {
                var id_ing = $(this).find('td:nth-child(1)').attr('id_ing');
                var quantité = $(this).find('td:nth-child(2)').text();
                var id_unité =$(this).find('td:nth-child(3)').attr('id_unité');
                ingredients.push({ id_ing: id_ing, quantité: quantité, id_unité: id_unité });
                });
                    json_ingredients = JSON.stringify(ingredients);
                    });

                var etapes = [];
                $(function () {
                $('#myTable_etape tr').each(function () {
                var ordre = $(this).find('td:nth-child(1)').text();
                var etape = $(this).find('td:nth-child(2)').text();

                etapes.push({ ordre: ordre, etape: etape});
                });
                    json_etapes = JSON.stringify(etapes);
                    });

                if(jQuery.isEmptyObject(json_ingredients)) {
                    alert('veuillez introduire les ingrédients');
                    return;
                } else {
                    if(jQuery.isEmptyObject(json_etapes)){
                        alert('veuillez introduire les étapes');
                        return;}
                    else{
                        if($('#titre').val()==''){
                            alert('veuillez introduire le titre de votre recette');
                            return;
                        }
                    }
                }
                
                var recipeData = {
                    id: recette.Id_Recette,
                    titre: $('#titre').val(),
                    healthy: $('#healthy option:selected').val(),
                    lien_image: $('#lien_image').val(),
                    lien_video: $('#lien_vidéo').val(),
                    notation: $('#notation').val(),
                    description : $('#description').val(),
                    temp_prep :$('#temp_prep').val(),
                    temp_repo :$('#temp_repo').val(),
                    temp_cuiss :$('#temp_cuiss').val(),
                    nbcalories :$('#nb_calories').val(),
                    difficulté :$('#difficulté option:selected').val(),
                    catégorie:$('#catégorie option:selected').attr('id_cat'),
                    ingredients: json_ingredients,
                    steps:json_etapes,
                    method:'modifier_recette'
                };
                $.ajax({
                    type: 'POST',
                    url : 'traitements_recettes.php',
                    data : recipeData,
                    dataType : 'json',
                    error : function(ts){alert(ts.responseText);},
                    complete : function(){window.close();location.reload();}
                });
                console.log(recipeData);
            });
                

                
        ";
               
        echo"</script>";
        
    }

    public function ajouter_recette(){
        $controller = new RecetteController();
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
        echo "
        <form id='publier_form'>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Informations génerales sur la recette</p>
            <div class='input_container'>
            <label for='titre'> Titre de la recette </label>
            <input name ='titre' id ='titre' type='text'>
            </div>
            <div class='input_container'>
            <label for='description'> Description de la recette </label>
            <textarea name ='description' id ='description' form='publier_form'> </textarea>
            </div>
            <div class='input_container'>
            <label for='healthy'> Healthy </label>
            <select id='healthy' name='healthy'>
                <option value='1'>Oui</option>
                <option value='0'>Non</option>
            </select>
            </div>
            <div class='input_container'>
            <label for='lien_image'> Lien image (avec '/')</label>
            <input name ='lien_image' id ='lien_image' type='text'>
            </div>
            <div class='input_container'>
            <label for='lien_vidéo'> Lien vidéo (avec '/')</label>
            <input name ='lien_vidéo' id ='lien_vidéo' type='text'>
            </div>
            <div class='input_container'>
            <label for='temp_prep'> Temps de préparation(min) </label>
            <input name ='temp_prep' id ='temp_prep' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='temp_repo'> Temps de repos(min) </label>
            <input name ='temp_repo' id ='temp_repo' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='temp_cuiss'> Temps de cuisson(min) </label>
            <input name ='temp_cuiss' id ='temp_cuiss' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='nb_calories'> Nombre de calories(Kcal) </label>
            <input name ='nb_calories' id ='nb_calories' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='difficulté'> Difficulté de la recette  </label>
            <select id='difficulté' name='difficulté'>
                <option value='Facile'>Facile</option>
                <option value='Moyenne'>Moyenne</option>
                <option value='Difficile'>Difficile</option>
            </select>
            </div>
            <div class='input_container'>
            <label for='catégorie'> Catégorie de la recette  </label>
            <select id='catégorie' name='catégorie'>
                <option id_cat='1' value='Entrées'>Entrées</option>
                <option id_cat='2' value='Plats'>Plats</option>
                <option id_cat='3' value='Boissons'>Boissons</option>
                <option id_cat='4' value='Desserts'>Desserts</option>
            </select>
            <div class='input_container'>
            <label for='notation'> Notation </label>
            <input name ='notation' id ='notation' type='number' value='0'>
            </div>
            </div>
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Ingrédients de la recette</p>";
            echo "
            <table id='table_ing' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ingrédient</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_ing'>";
                
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Rajouter un ingrédient </p>
            <div class='input_container'>
            <label for='ingredients'> Liste des ingrédients </label>
            <select id='ingredients' name='ingredients[]'>
            </select>
            </div>
            <p class='ins'>Spécifiez la quantité de l'ingrédient ainsi que l'unité de mesure</p>
            <div class='input_container'>
            <span>
            <label> Quantité </label>
            <input type='number' id='quantité' value='0'>
            </span><span>
            <label> Unité de mesure </label>
            <select id='unités' name='unités[]'>
            </select>
            </span>
            </div>
            <p class='ins'>Cliquez sur le + pour rajoutez cet ingrédient à la liste des ingrédients de votre recette</p>
        </div>
        <input type='button' value='+' id ='add_ing'/>
        
        <div class='partie_form'>
            <p class='publier_sous_titre'>Etapes de la recette</p>";
            echo "
            <table id='table_ing' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Etape</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_etape'>";
                
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Rajouter une étape</p>
            <div class='input_container'>
            <label> Ordre </label>
            <input type ='number' id='ordre' value='0'/>
            <label> Etape </label>
            <input type ='text' id='etape'/> 
            <input type='button' value='+' id ='add_etape'/>
            </div>
        </div>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>";
        echo "
            
            $.ajax({
                type: 'GET',
                url: 'get_ingredients.php',
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, ingredient) {
                        var str  = '<option id_ing='+ingredient[0]+'>';
                        $('#ingredients').append(str + ingredient[1] + '</option>');
                    });
                }
            });
            $.ajax({
                type: 'GET',
                url: 'get_unités.php',
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, unité) {
                        var str  = '<option value='+unité[2]+' id_unité='+unité[0]+'>';
                        $('#unités').append(str + unité[1] + '</option>');
                    });
                }
            });
            $('#add_ing').click(function(){
                var quantité = $('#quantité').val();
                var ing_name = $('#ingredients option:selected').text();
                var id_ing = $('#ingredients option:selected').attr('id_ing');
                var symbole_unité = $('#unités option:selected').val();
                var id_unité = $('#unités option:selected').attr('id_unité');
                $('#myTable_ing').append('<tr><td id_ing='+id_ing+'>'+ing_name+'</td><td>'+quantité+'</td><td id_unité='+id_unité+'>'+symbole_unité+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' id_ing='+id_ing+'>Supprimer</button></td></tr>');
            });
            $('#add_etape').click(function(){
                var ordre = $('#ordre').val();
                var etape = $('#etape').val();
                $('#myTable_etape').append('<tr><td ordre='+ordre+'>'+ordre+'</td><td>'+etape+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' ordre='+ordre+'>Supprimer</button></td></tr>');
            });
           
            $('#publierbtn').click(function(){
                var ingredients = [];
                $(function () {
                $('#myTable_ing tr').each(function () {
                var id_ing = $(this).find('td:nth-child(1)').attr('id_ing');
                var quantité = $(this).find('td:nth-child(2)').text();
                var id_unité =$(this).find('td:nth-child(3)').attr('id_unité');
                ingredients.push({ id_ing: id_ing, quantité: quantité, id_unité: id_unité });
                });
                    json_ingredients = JSON.stringify(ingredients);
                    });

                var etapes = [];
                $(function () {
                $('#myTable_etape tr').each(function () {
                var ordre = $(this).find('td:nth-child(1)').text();
                var etape = $(this).find('td:nth-child(2)').text();

                etapes.push({ ordre: ordre, etape: etape});
                });
                    json_etapes = JSON.stringify(etapes);
                    });

                if(jQuery.isEmptyObject(json_ingredients)) {
                    alert('veuillez introduire les ingrédients');
                    return;
                } else {
                    if(jQuery.isEmptyObject(json_etapes)){
                        alert('veuillez introduire les étapes');
                        return;}
                    else{
                        if($('#titre').val()==''){
                            alert('veuillez introduire le titre de votre recette');
                            return;
                        }
                    }
                }
                
                var recipeData = {
                    titre: $('#titre').val(),
                    healthy: $('#healthy option:selected').val(),
                    lien_image: $('#lien_image').val(),
                    lien_video: $('#lien_vidéo').val(),
                    notation: $('#notation').val(),
                    description : $('#description').val(),
                    temp_prep :$('#temp_prep').val(),
                    temp_repo :$('#temp_repo').val(),
                    temp_cuiss :$('#temp_cuiss').val(),
                    nbcalories :$('#nb_calories').val(),
                    difficulté :$('#difficulté option:selected').val(),
                    catégorie:$('#catégorie option:selected').attr('id_cat'),
                    ingredients: json_ingredients,
                    steps:json_etapes,
                    method:'ajouter_recette'
                };
                $.ajax({
                    type: 'POST',
                    url : 'traitements_recettes.php',
                    data : recipeData,
                    dataType : 'json',
                    error : function(ts){alert(ts.responseText);},
                    complete : function(){window.close();location.reload();}
                });
                console.log(recipeData);
            });
                
        ";
        echo"</script>";
        
    }

    public function valider_recette($id_recette){
        $controller = new RecetteController();
        $ingrédients = $controller->get_recette_nonvalide_ingredients($id_recette);
        $nvx_ingrédients = $controller->get_recette_nonvalide_nvx_ingredients($id_recette);
        $etapes=$controller->get_recette_nonvalide_etapes($id_recette);
        $res=$controller->get_recette_nonvalidée_by_id($id_recette);
        $recette =$res->fetch(PDO::FETCH_ASSOC);
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
        echo "
        <form id='publier_form'>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Informations génerales sur la recette</p>
            <div class='input_container'>
            <label for='titre'> Titre de la recette </label>
            <input name ='titre' id ='titre' type='text'>
            </div>
            <div class='input_container'>
            <label for='description'> Description de la recette </label>
            <textarea name ='description' id ='description' form='publier_form'> </textarea>
            </div>
            <div class='input_container'>
            <label for='healthy'> Healthy </label>
            <select id='healthy' name='healthy'>
                <option value='1'>Oui</option>
                <option value='0'>Non</option>
            </select>
            </div>
            <div class='input_container'>
            <label for='lien_image'> Lien image (avec '/')</label>
            <input name ='lien_image' id ='lien_image' type='text'>
            </div>
            <div class='input_container'>
            <label for='lien_vidéo'> Lien vidéo (avec '/')</label>
            <input name ='lien_vidéo' id ='lien_vidéo' type='text'>
            </div>
            <div class='input_container'>
            <label for='temp_prep'> Temps de préparation(min) </label>
            <input name ='temp_prep' id ='temp_prep' type='number'>
            </div>
            <div class='input_container'>
            <label for='temp_repo'> Temps de repos(min) </label>
            <input name ='temp_repo' id ='temp_repo' type='number'>
            </div>
            <div class='input_container'>
            <label for='temp_cuiss'> Temps de cuisson(min) </label>
            <input name ='temp_cuiss' id ='temp_cuiss' type='number'>
            </div>
            <div class='input_container'>
            <label for='nb_calories'> Nombre de calories(Kcal) </label>
            <input name ='nb_calories' id ='nb_calories' type='number'>
            </div>
            <div class='input_container'>
            <label for='difficulté'> Difficulté de la recette  </label>
            <select id='difficulté' name='difficulté'>
                <option value='Facile'>Facile</option>
                <option value='Moyenne'>Moyenne</option>
                <option value='Difficile'>Difficile</option>
            </select>
            </div>
            <div class='input_container'>
            <label for='catégorie'> Catégorie de la recette  </label>
            <select id='catégorie' name='catégorie'>
                <option id_cat='1' value='Entrées'>Entrées</option>
                <option id_cat='2' value='Plats'>Plats</option>
                <option id_cat='3' value='Boissons'>Boissons</option>
                <option id_cat='4' value='Desserts'>Desserts</option>
            </select>
            <div class='input_container'>
            <label for='notation'> Notation </label>
            <input name ='notation' id ='notation' type='number'>
            </div>
            </div>
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Ingrédients de la recette</p>";
            echo "
            <table id='table_ing' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ingrédient</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_ing'>";
                foreach($ingrédients as $ing){
                    echo "<tr>";
                        echo "<td id_ing='".$ing['Id_Ingrédient']."'>".$ing['Nom_ingrédient']."</td>";
                        echo "<td>".$ing['quantité']."</td>";
                        echo "<td id_unité='".$ing['Id_Unité']."'>".$ing['Symbole']."</td>";
                        echo "<td><button onclick=this.closest('tr').remove() class='supprimer' id_ing='".$ing['Id_Ingrédient']."'>Supprimer</button></td>";
                    echo "</tr>";
                }
                echo"
                </tbody>
            </table>

            <p class='publier_sous_titre'>Nouveux Ingrédients de la recette</p>";
            echo "
            <table id='table_ing_nvx' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ingrédient</th>
                    <th>Quantité</th>
                    <th>Unité</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_ing_nvx'>";
                foreach($nvx_ingrédients as $ing){
                    echo "<tr>";
                        echo "<td nom_ing='".$ing['Nom_ingrédient']."'>".$ing['Nom_ingrédient']."</td>";
                        echo "<td>".$ing['quantité']."</td>";
                        echo "<td id_unité='".$ing['Id_Unité']."'>".$ing['Symbole']."</td>";
                    echo "</tr>";
                }
                echo"
                </tbody>
            </table>
            ";
            echo"
            <p class='ins'>Rajouter un ingrédient </p>
            <div class='input_container'>
            <label for='ingredients'> Liste des ingrédients </label>
            <select id='ingredients' name='ingredients[]'>
            </select>
            </div>
            <p class='ins'>Spécifiez la quantité de l'ingrédient ainsi que l'unité de mesure</p>
            <div class='input_container'>
            <span>
            <label> Quantité </label>
            <input type='number' id='quantité'>
            </span><span>
            <label> Unité de mesure </label>
            <select id='unités' name='unités[]'>
            </select>
            </span>
            </div>
            <p class='ins'>Cliquez sur le + pour rajoutez cet ingrédient à la liste des ingrédients de votre recette</p>
        </div>
        <input type='button' value='+' id ='add_ing'/>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Etapes de la recette</p>";
            echo "
            <table id='table_ing' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Etape</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_etape'>";
                foreach($etapes as $etape){
                    echo "<tr>";
                        echo "<td ordre='".$etape['Ordre']."'>".$etape['Ordre']."</td>";
                        echo "<td>".$etape['Description']."</td>";
                        echo "<td><button onclick=this.closest('tr').remove() class='supprimer' ordre='".$etape['Ordre']."'>Supprimer</button></td>";
                    echo "</tr>";
                }
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Rajouter une étape</p>
            <div class='input_container'>
            <label> Ordre </label>
            <input type ='number' id='ordre'/>
            <label> Etape </label>
            <input type ='text' id='etape'/> 
            <input type='button' value='+' id ='add_etape'/>
            </div>
        </div>
        <input type='button' value='valider' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>";
        echo "var recette = "; echo json_encode($recette); echo ";";
        echo "
            $('#titre').attr('value',recette.Nom_Recette);
            $('#description').val(recette.Description);
            $('#healthy').val(recette.Healthy);
            $('#lien_image').attr('value',recette.Lien_Image);
            $('#lien_vidéo').attr('value',recette.Lien_Vidéo);
            $('#temp_prep').attr('value',recette.Temp_Prep);
            $('#temp_repo').attr('value',recette.Temp_Repo);
            $('#temp_cuiss').attr('value',recette.Temp_Cuisson);
            $('#nb_calories').attr('value',recette.Nb_Calories);
            $('#difficulté').val(recette.Difficulté);
            $('#catégorie').val(recette.Nom_Categorie);
            $('#notation').attr('value',recette.Notation);
            $.ajax({
                type: 'GET',
                url: 'get_ingredients.php',
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, ingredient) {
                        var str  = '<option id_ing='+ingredient[0]+'>';
                        $('#ingredients').append(str + ingredient[1] + '</option>');
                    });
                }
            });
            $.ajax({
                type: 'GET',
                url: 'get_unités.php',
                dataType: 'json',
                success: function(data) {
                    $.each(data, function(index, unité) {
                        var str  = '<option value='+unité[2]+' id_unité='+unité[0]+'>';
                        $('#unités').append(str + unité[1] + '</option>');
                    });
                }
            });
            $('#add_ing').click(function(){
                var quantité = $('#quantité').val();
                var ing_name = $('#ingredients option:selected').text();
                var id_ing = $('#ingredients option:selected').attr('id_ing');
                var symbole_unité = $('#unités option:selected').val();
                var id_unité = $('#unités option:selected').attr('id_unité');
                $('#myTable_ing').append('<tr><td id_ing='+id_ing+'>'+ing_name+'</td><td>'+quantité+'</td><td id_unité='+id_unité+'>'+symbole_unité+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' id_ing='+id_ing+'>Supprimer</button></td></tr>');
            });
            $('#add_etape').click(function(){
                var ordre = $('#ordre').val();
                var etape = $('#etape').val();
                $('#myTable_etape').append('<tr><td ordre='+ordre+'>'+ordre+'</td><td>'+etape+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' ordre='+ordre+'>Supprimer</button></td></tr>');
            });
            
            $('#publierbtn').click(function(){
                var ingredients = [];
                $(function () {
                $('#myTable_ing tr').each(function () {
                var id_ing = $(this).find('td:nth-child(1)').attr('id_ing');
                var quantité = $(this).find('td:nth-child(2)').text();
                var id_unité =$(this).find('td:nth-child(3)').attr('id_unité');
                ingredients.push({ id_ing: id_ing, quantité: quantité, id_unité: id_unité });
                });
                    json_ingredients = JSON.stringify(ingredients);
                    });
                
                var nvx_ingredients = [];
                $(function () {
                $('#myTable_ing_nvx tr').each(function () {
                var nom_ing = $(this).find('td:nth-child(1)').attr('nom_ing');
                var quantité = $(this).find('td:nth-child(2)').text();
                var id_unité =$(this).find('td:nth-child(3)').attr('id_unité');
                nvx_ingredients.push({ nom_ing: nom_ing, quantité: quantité, id_unité: id_unité });
                });
                    json_nvx_ingredients = JSON.stringify(nvx_ingredients);
                    });
                
                var etapes = [];
                $(function () {
                $('#myTable_etape tr').each(function () {
                var ordre = $(this).find('td:nth-child(1)').text();
                var etape = $(this).find('td:nth-child(2)').text();

                etapes.push({ ordre: ordre, etape: etape});
                });
                    json_etapes = JSON.stringify(etapes);
                    });

                if(jQuery.isEmptyObject(json_ingredients)) {
                    alert('veuillez introduire les ingrédients');
                    return;
                } else {
                    if(jQuery.isEmptyObject(json_etapes)){
                        alert('veuillez introduire les étapes');
                        return;}
                    else{
                        if($('#titre').val()==''){
                            alert('veuillez introduire le titre de votre recette');
                            return;
                        }
                    }
                }
                
                var recipeData = {
                    id: recette.Id_Recette,
                    titre: $('#titre').val(),
                    healthy: $('#healthy option:selected').val(),
                    lien_image: $('#lien_image').val(),
                    lien_video: $('#lien_vidéo').val(),
                    notation: $('#notation').val(),
                    description : $('#description').val(),
                    temp_prep :$('#temp_prep').val(),
                    temp_repo :$('#temp_repo').val(),
                    temp_cuiss :$('#temp_cuiss').val(),
                    nbcalories :$('#nb_calories').val(),
                    difficulté :$('#difficulté option:selected').val(),
                    catégorie:$('#catégorie option:selected').attr('id_cat'),
                    ingredients: json_ingredients,
                    nvx_ingredients : json_nvx_ingredients,
                    steps:json_etapes,
                    method:'valider_recette'
                };
                $.ajax({
                    type: 'POST',
                    url : 'traitements_recettes.php',
                    data : recipeData,
                    dataType : 'json',
                    error : function(ts){alert(ts.responseText);},
                    complete : function(){window.close();location.reload();}
                });
                console.log(recipeData);
            });
                

                
        ";
               
        echo"</script>";
        
    }
    

}