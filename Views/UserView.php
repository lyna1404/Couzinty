<?php
global $work_dir;
require_once $work_dir."Conrtollers\UserController.php";
require_once $work_dir."Views\RecetteView.php";
class UserView
{
    public function LoginForm()
    {
        echo "
        <div id='login-form'>
        <form method='post'>
            <label for='username'>Email</label>
            <input type='text' id='username' name='username'>
            <label>Mot de passe</label>
            <input type='password' id='password' name='password'>
            <div id='loginbuttons'>
            <button type='button' id='cancelbtn'>Retour</button>
            <button type='submit' id='loginbtn' name='submit'>Submit</button>
            </div>
            <button type='button' id='créer_compte'>Vous n'avez pas de compte ?</button>
        </form>
        </div>
        ";
        echo "
        <script>
        $('#connexionbtn').click(function(){
            $('#login-form').show();
        });
        $('#cancelbtn').click(function(){
            $('#login-form').hide();
        });
        $('#créer_compte').click(function(){
            $('#Register-form').show();
        });
        </script>
        ";
        $controller = new UserController();
        if(isset($_POST['submit'])){
            $umail = $_POST['username'];
            $upsw = $_POST['password'];
            $row = $controller->is_user($umail,$upsw);
            if($row!=null){
                $_SESSION["username"] = $row['Nom'].' '.$row['Prénom'];
                $_SESSION["userId"] = $row['Id_Utilisateur'];
                if($controller->is_admin($umail,$upsw)){
                    $_SESSION['is_admin']=true;
                }
                echo "<script type='text/javascript'>
                window.location.href = 'Accueil.php';
                </script>";
            }
        }
    }
    public function RegisterForm(){
        echo "
        <div id='Register-form'>
        <form method='post'>
            <label for='mail'>Email</label>
            <input type='text' id='mail' name='mail'>
            <label>Mot de passe</label>
            <input type='password' id='password' name='password'>
            <label>Nom</label>
            <input type='text' id='nom' name='nom'>
            <label>Prénom</label>
            <input type='text' id='prénom' name='prénom'>
            <label>Date de naissance</label>
            <input type='date' id='date_naiss' name='date_naiss'>
            <div>
            <label for='male'>Male</label><input type='radio' id='male' name='sexe' value='M'>
            <label for='female'>Female</label><input type='radio' id='female' name='sexe' value='F'>
            </div>
            <div id='loginbuttons'>
            <button type='button' id='cancelbtn2'>Retour</button>
            <button type='submit' id='registerbtn' name='submit_créer'>Submit</button>
            </div>
        </form>
        </div>
        ";
        echo "
        <script>
       
        $('#cancelbtn2').click(function(){
            $('#Register-form').hide();
        });
        
        </script>
        ";
        $controller = new UserController();
        if(isset($_POST['submit_créer'])){
            $umail = $_POST['mail'];
            $upsw = $_POST['password'];
            $uname = $_POST['nom'];
            $uprenom = $_POST['prénom'];
            $ubd = $_POST['date_naiss'];
            $usx = $_POST['sexe'];
            $row = $controller->is_user($umail,$upsw);
            if($row!=null){
                echo "utilisateur existant!";
            }
            else{
                $controller->add_user($umail,$upsw,$uname,$uprenom,$ubd,$usx);
            }
        }
       
       
    }
    public function Profilepage($id_user){
        $view = new RecetteView();
        $controller = new UserController();
        $recettes_list=$controller->get_recettes_sauv($id_user);
        echo "<form method='post'><input type='submit' name='logout' id='logout' value='Se déconnecter'></form>";
        echo "<div class='cadre'>
                <div class='sous_cadre'>
                <p class='cadre_titre'>Recettes sauvgardées</p>";
                echo "<button id='publier_recette' onclick=window.open('Publier_recette.php')>Publier</button>";
          echo "</div></div>";
            echo "<div class='cadre'><div class='sous_cadre_recette'>";
             foreach($recettes_list as $recette){
                $view->créer_cadre_recette($recette);
             }
             echo "</div></div>";
             if(isset($_POST['logout'])){
                $controller->user_logout();
             }
    }
    public function PublierRecette(){
        $view = new RecetteView();
        $controller = new UserController();
        echo "<div class='cadre'>
                <div class='sous_cadre'>
                <p class='cadre_titre'>Publier une recette</p>";
        echo "</div></div>";
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
                <option value='facile'>Facile</option>
                <option value='moyenne'>Moyenne</option>
                <option value='difficile'>Difficile</option>
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
            </div>
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Ingrédients de la recette</p>
            <p class='ins'>Sélectionnez l'ingrédient à partir de la liste des ingrédient, s'il n'existe pas ajoutez le à partir du champ 'nouvel Ingrédient' à droite</p>
            <div class='input_container'>
            <label for='ingredients'> Liste des ingrédients </label>
            <select id='ingredients' name='ingredients[]'>
            </select>
            </div>
            <div class='input_container'>
            <label for='new-ingredient'> Nouvel ingrédient </label>
            <input type='text' id='new-ingredient' name='new-ingredient'>
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
            <p class='publier_sous_titre'>Etapes de la recette</p>
            <p class='ins'>Introduisez les étapes de la recette une par une en appuyant le bouton + aprés chaque étape</p>
            <div class='input_container'>
            <input type ='text' id='etape'/> 
            <input type='button' value='+' id ='add_etape'/>
            </div>
        </div>
        <p class='publier_sous_titre'>Aperçu des élements introduits</p>
        <div id='preview'>
        <div id='ing_list'>
        <ul>Ingrédients de la recette</ul>
        </div>
        <div id='etapes_list'>
        <ol>Etapes de la recette</ol>
        </div>
        </div>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>
        var ing_list_existant = [];
        var ing_list_nonexistant = [];
        var ordre = 0;
        var list_steps = [];
        var json_ing_list_existant =[];
        var json_ing_list_nonexistant =[];
        var json_list_steps = [];
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
            var ing=[];
            if(jQuery('#new-ingredient').val()!=''){
                $.ajax({
                    type: 'POST',
                    url: 'check_ingredient.php',
                    data: { ingredient: $('#new-ingredient').val()},
                    success: function(data) {
                        if(data) {
                            alert('Cet ingrédient existe déjà, sélectionner le à partir de la liste des ingrédients fournie !');
                        }
                        else{
                            ing.length=0;
                            ing.push($('#new-ingredient').val());
                            ing.push($('#quantité').val());
                            ing.push($('#unités option:selected').attr('id_unité'));
                            var quantité = $('#quantité').val();
                            var newing = $('#new-ingredient').val();
                            var selectedunité = $('#unités option:selected').val();
                            $('#ing_list ul').append('<li>'+quantité+selectedunité+' '+newing+'</li>');
                            ing_list_nonexistant.push(ing);
                            json_ing_list_nonexistant = JSON.stringify(ing_list_nonexistant.map(function(smallArray) {
                                return {
                                    nom_ing: smallArray[0],
                                    quantité : smallArray[1],
                                    id_unité: smallArray[2]
                                };
                            }));
                            jQuery('#new-ingredient').attr('value','');
                            $('#quantité').attr('value','');
                        }
                    }
                });
            }
            else{
                ing.length=0;
                ing.push($('#ingredients option:selected').attr('id_ing'));
                ing.push($('#quantité').val());
                ing.push($('#unités option:selected').attr('id_unité'));
                var quantité = $('#quantité').val();
                var selectedOption = $('#ingredients option:selected').text();
                var selectedunité = $('#unités option:selected').val();
                $('#ing_list ul').append('<li>'+quantité+selectedunité+' '+selectedOption+'</li>');
                ing_list_existant.push(ing);
                json_ing_list_existant = JSON.stringify(ing_list_existant.map(function(smallArray) {
                    return {
                        id_ing: smallArray[0],
                        quantité : smallArray[1],
                        id_unité: smallArray[2]
                    };
                }));
                $('#quantité').attr('value','');

            }
            
        });
        $('#add_etape').click(function(){
            var step=[];
            step.length = 0;
            ordre ++;
            var etape = $('#etape').val();
            step.push(ordre);
            step.push(etape);
            list_steps.push(step);
            $('#etapes_list ol').append('<li>'+etape+'</li>');
            json_list_steps = JSON.stringify(list_steps.map(function(smallArray) {
                return {
                    ordre: smallArray[0],
                    etape : smallArray[1]
                };
            }));
        });
        
        
        $('#publierbtn').click(function(){
            
            var recipeData = {
                titre: $('#titre').val(),
                description : $('#description').val(),
                temp_prep :$('#temp_prep').val(),
                temp_repo :$('#temp_repo').val(),
                temp_cuiss :$('#temp_cuiss').val(),
                nbcalories :$('#nb_calories').val(),
                difficulté :$('#difficulté option:selected').val(),
                catégorie:$('#catégorie option:selected').attr('id_cat'),
                ingredients_existants: json_ing_list_existant,
                ingredients_nonexistants: json_ing_list_nonexistant,
                steps:json_list_steps,
                method:'ajouter_recette_user'
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
        </script>";
    }

    public function gestion_users(){
        $controller = new UserController();
        $users = $controller->get_all_users();
        echo "
        <div class='cadre'>
        <div class='sous_cadre'>
        <p class='cadre_titre'>Gestion des utilisateurs</p>
        <select id='fusers' name='fusers'>
            <option value='toutes'>Tous les utilisateurs</option>
            <option value='1'>Inscriptions validées</option>
            <option value='0'>Inscriptions non validées</option>
        </select>
        </div>
        <table id='table' class='table table-bordered table-striped'>
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>E-mail</th>
                <th>Sexe</th>
                <th>Valide</th>
                <th></th>
            </tr>
            </thead>
            <tbody id='myTable'>";
            foreach($users as $user){
                echo "<tr>";
                    echo "<td>".$user['Id_Utilisateur']."</td>";
                    echo "<td>".$user['Nom']."</td>";
                    echo "<td>".$user['Prénom']."</td>";
                    echo "<td>".$user['Mail']."</td>";
                    echo "<td>".$user['Sexe']."</td>";
                    echo "<td>".$user['Valide']."</td>";
                    echo "<td>";
                    if($user['Valide']==1){
                        echo "<button etat='valide' class='val_enl' id_user='".$user['Id_Utilisateur']."'>Enlever</button>";
                    }else{
                        echo "<button etat='nonvalide' class='val_enl' id_user='".$user['Id_Utilisateur']."'>Valider</button>";
                    }
                    echo "<button class='afficher' id_user='".$user['Id_Utilisateur']."'>Afficher</button>";
                    echo "</td>";
                echo "</tr>";
            }
            echo"
            </tbody>
        </table>
        ";
        

        echo "<script>
        $('select#fusers').change(function(){
            var value = $('#fusers option:selected').val();
            $('#myTable tr').filter(function () {
                if(value=='toutes'){
                    $(this).show();                
                }
                else{
                    $(this).toggle($(this.children[5]).text().indexOf(value) > -1) ;  }          
             });
        });
        $('button.afficher').click(function(){
            var id = $(this).attr('id_user');
            window.open('Afficher_user.php?Id='+id);
            
        });

        $('button.val_enl').click(function(){
            var id = $(this).attr('id_user');
            var etat = $(this).attr('etat');

            $.ajax({
                type: 'POST',
                url : 'validation_user_script.php',
                data : {etat:etat,id: id},
                dataType : 'json',
                error : function(ts){alert(ts.responseText);},
                complete : function(){location.reload();}
            });
        });
        
        </script>";
    }

    public function afficher_user($id_user){
        $view = new RecetteView();
        $controller = new UserController();
        $recettes_list=$controller->get_recettes_sauv($id_user);
        $recettes_publiées = $controller->get_recettes_publiées($id_user);
        echo "<div class='cadre'>
                <div class='sous_cadre'>
                <p class='cadre_titre'>Recettes sauvgardées</p>";
          echo "</div></div>";
            echo "<div class='cadre'><div class='sous_cadre_recette'>";
             foreach($recettes_list as $recette){
                $view->créer_cadre_recette($recette);
             }
             echo "</div></div>";

             echo "<div class='cadre'>
             <div class='sous_cadre'>
             <p class='cadre_titre'>Recettes publiées</p>";
       echo "</div></div>";
         echo "<div class='cadre'><div class='sous_cadre_recette'>";
          foreach($recettes_publiées as $recette){
             $view->créer_cadre_recette($recette);
          }
          echo "</div></div>";
        
    }
}