<?php
global $work_dir;
require_once $work_dir."Conrtollers\IngrédientController.php";
class IngrédientView
{
    public function cadre_ingrédient($id_ing){
        $controller = new IngrédientController();
        $img = $controller->get_ingrédient_img($id_ing);
        $titre = $controller->get_ingrédient_nom($id_ing);
        echo "
        <div class = 'recette_cadre' style='background-image: url(".$img.")'>
        <a href='Ingrédient.php?Id=".$id_ing."' ></a>
            <div class = 'recette_info'>
                <p class = 'recette_titre'>".$titre."</p>
            </div>
        </div>
        ";
    }
    public function nutrition_page(){
        $controller = new IngrédientController();
        $ing_list=$this->filtrer();
        echo "<div class='cadre'>
            <div class='sous_cadre'>
            <p class='cadre_titre'>Nutrition </p>
            <p class='cadre_sous_titre'>Cliquez sur un ingrédient pour afficher ses informations nutritionnelles !</p>";
            echo "</div></div>";
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
         foreach($ing_list as $ing){
            $this->cadre_ingrédient($ing);
         }
         echo "</div></div>";
    }
    public function ingrédient_infos($id_ing){
        $controller = new IngrédientController();
        $titre =$controller->get_ingrédient_nom($id_ing);
        $img =$controller->get_ingrédient_img($id_ing);
        $desc=$controller->get_ingrédient_desc($id_ing);
        $valeurs = $controller->get_valeurs_nutritionnelles($id_ing);
        $saisons =$controller->get_ingrédient_saison($id_ing);
        $état_ing = $controller->get_ingrédient_état($id_ing);
        $cal =$controller->get_ingrédient_calories($id_ing);
        echo "<div class='cadre_news'>";
                echo "<div id='news_image'>";
                echo "</div>";
                echo"<script type='text/javascript'>
                    document.getElementById('news_image').style.backgroundImage = 'url(".$img.")';
                    </script>";
                echo "<div class=news_titre_desc>";
                echo "<div id='news_titre'>";
                    echo "<p>".$titre."</p>";
                echo "</div>";
                echo "<div id='news_description'>";
                    echo "<p>".$desc."</p>";
                echo "</div>";
                echo "</div>";
                echo "<div class=recette_ing_etape>";
                echo "<div id='recette_ing'>";
                    echo "<ul id='titre_etape'><p>Calories (pour100g) : ".round($cal,2)." Kcal</p>";
                    echo "</ul>";
                    echo "<ul id='titre_etape'><p>Disponible en : </p>";
                    while($saison=$saisons->fetch(PDO::FETCH_ASSOC)){
                        echo "<li class='ing_list'><p>".$saison['Nom']."</p></li>";
                    }
                    echo "</ul>";
                    echo "<ul id='titre_ing'><p>Valeurs nutritionnelles (pour 100g)</p><p>Etat de l'ingrédient : ".$état_ing."</p>";
                    while($val=$valeurs->fetch(PDO::FETCH_ASSOC)){
                        echo "<li class='ing_list'><p>".$val['Nom'].' '.round($val['Taux'],2)."g</p></li>";
                    }
                    echo "</ul>";
                    echo "<ul id='titre_ing'><p>Healthy ?</p>";
                    if($controller->is_healthy($id_ing)){
                        echo "<li class='ing_list'><p id='healthy_ing'>Cet ingrédient est Healthy à ".$controller->taux_healthy($id_ing)."%</p></li>";
                    }
                    else{
                        echo "<li class='ing_list'><p id='healthy_ing'>Cet ingrédient n'est pas Healthy !</p></li>";
                    }
                echo "</div>";
                echo "</div>";
        echo "</div>";

    }
    public function gestion_nutrition(){
        $controller = new IngrédientController();
        $ings = $controller->get_all_ingrédients_details();
        echo "
        <div class='cadre'>
        <div class='sous_cadre'>
        <p class='cadre_titre'>Gestion de la nutrition</p>
        <button value='Ajouter recette' id='ajouterbtn'>Ajouter ingrédient</button>
        </div>
        <table id='table' class='table table-bordered table-striped'>
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Healthy</th>
                <th>Taux</th>
                <th>Calories</th>
                <th>Etat</th>
                <th>Description</th>
                <th>Lien_image</th>
                <th></th>
            </tr>
            </thead>
            <tbody id='myTable'>";
            foreach($ings as $ing){
                echo "<tr>";
                    echo "<td>".$ing['Id_Ingrédient']."</td>";
                    echo "<td>".$ing['Nom_ingrédient']."</td>";
                    echo "<td>".$ing['Healthy']."</td>";
                    echo "<td>".$ing['Taux_']."</td>";
                    echo "<td>".$ing['Calories']."</td>";
                    echo "<td>".$ing['état']."</td>";
                    echo "<td>".$ing['Description']."</td>";
                    echo "<td>".$ing['Lien_image']."</td>";
                    echo "<td><button class='modifier' id_ing='".$ing['Id_Ingrédient']."'>Modifier</button>
                    <button class='supprimer' id_ing='".$ing['Id_Ingrédient']."'>Supprimer</button>
                    </td>";
                echo "</tr>";
            }
            echo"
            </tbody>
        </table>
        ";
        $ings2 = $controller->get_all_new_ingredients();
        echo "
        <div class='sous_cadre'>
        <p class='cadre_titre'>Gestion des nouveaux ingrédients</p>
        </div>
        <table id='table2' class='table table-bordered table-striped'>
            <thead>
            <tr>
                <th>Nom</th>
                <th></th>
            </tr>
            </thead>
            <tbody id='myTable2'>";
            foreach($ings2 as $ing){
                echo "<tr>";
                    echo "<td>".$ing['Nom_Ingrédient']."</td>";
                    echo "<td><button method ='valider' class='decider' id_recette = '".$ing['Id_Recette']."' nom_ing='".$ing['Nom_Ingrédient']."'>Valider</button>
                    <button method ='supprimer' class='decider' id_recette = '".$ing['Id_Recette']."' nom_ing='".$ing['Nom_Ingrédient']."'>Supprimer</button>
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
            var id = $(this).attr('id_ing');
            window.open('Modifier_ingrédient.php?Id='+id);
            
        });
        $('button.supprimer').click(function(){
            var id =$(this).attr('id_ing');
            $.ajax({
                type: 'POST',
                url : 'traitements_ings.php',
                data : {id:id,method:'supprimer_ing'},
                dataType : 'json',
                error : function(ts){alert(ts.responseText);},
                complete : function(){location.reload();}
            });
        });
        $('button.decider').click(function(){
            var nom = $(this).attr('nom_ing');
            var id =$(this).attr('id_recette');
            var action =$(this).attr('method');
            $.ajax({
                type: 'POST',
                url : 'traitements_ings.php',
                data : {id:id,nom:nom,action:action,method:'decider_ing_ajouté_user'},
                dataType : 'json',
                error : function(ts){alert(ts.responseText);},
                complete : function(){location.reload();}
            });
        });
        $('button#ajouterbtn').click(function(){
            window.open('Ajouter_ingrédient.php');
        });
        </script>";
    }
    public function ajouter_ingrédient(){
        $controller = new IngrédientController();
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
        echo "
        <form id='publier_form'>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Informations génerales</p>
            <div class='input_container'>
            <label for='titre'>Nom de l'ingrédient </label>
            <input name ='titre' id ='titre' type='text'>
            </div>
            <div class='input_container'>
            <label for='description'> Description </label>
            <textarea name ='description' id ='description' form='publier_form'> </textarea>
            </div>
            <div class='input_container'>
            <span>
            <label for='healthy'> Healthy </label>
            <select id='healthy' name='healthy'>
                <option value='1'>Oui</option>
                <option value='0'>Non</option>
            </select>
            </span><span>
            <label> Taux </label>
            <input type='number' id='taux' value='0'>
            </span>
            </div>
            <div class='input_container'>
            <label for='lien_image'> Lien image (avec '/')</label>
            <input name ='lien_image' id ='lien_image' type='text'>
            </div>
            
            <div class='input_container'>
            <label for='nb_calories'> Nombre de calories(Kcal) </label>
            <input name ='nb_calories' id ='nb_calories' type='number' value='0'>
            </div>
            <div class='input_container'>
            <label for='etat'>Etat de l'ingrédient </label>
            <input name ='etat' id ='etat' type='text'>
            </div>
            <label > Saisons </label>
            <div class='input_container'>
            <span>
            <input type='checkbox' id_saison='1' id='automne' name='saisons[]' value='automne'/>
            <label for='automne'>Automne</label>
            <input type='checkbox' id_saison='2' id='hiver' name='saisons[]' value='hiver'/>
            <label for='hiver'>Hiver</label>
            <input type='checkbox' id_saison='3'  id='printemps' name='saisons[]' value='printemps'/>
            <label for='printemps'>Printemps</label>
            <input type='checkbox' id_saison='4'  id='été' name='saisons[]' value='été'/>
            <label for='été'>Eté</label>
            </span>
            </div>
            
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Valeurs nutritionnelles(100g)</p>";
            echo "
            <table id='table_val' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Nutriment</th>
                    <th>Quantité(g)</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_val'>";
                
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Rajouter une valeur </p>
            <div class='input_container'>
            <label for='nutriments'> Liste des nutriments </label>
            <select id='nutriments' name='nutriments[]'>
            </select>
            </div>
            <p class='ins'>Spécifiez la quantité</p>
            <div class='input_container'>
            <span>
            <label> Quantité(g) </label>
            <input type='number' id='quantité'>
            </span>
            </div>
            <p class='ins'>Cliquez sur le + pour rajoutez cette valeur </p>
        </div>
        <input type='button' value='+' id ='add_val'/>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>";
        echo "
            
            $.ajax({
                type: 'GET',
                url: 'get_nutriments.php',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $.each(data, function(index, nutriment) {
                        var str  = '<option id_nut='+nutriment.Id_Valeurs_nutritionnelles+'>';
                        $('#nutriments').append(str + nutriment.Nom + '</option>');
                    });
                }
            });
            
            $('#add_val').click(function(){
                var quantité = $('#quantité').val();
                var nut_name = $('#nutriments option:selected').text();
                var id_nut = $('#nutriments option:selected').attr('id_nut');
                $('#myTable_val').append('<tr><td id_nut='+id_nut+'>'+nut_name+'</td><td>'+quantité+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' id_nut='+id_nut+'>Supprimer</button></td></tr>');
            });
            
            $('#publierbtn').click(function(){
                var val_nut = [];
                $(function () {
                $('#myTable_val tr').each(function () {
                var id_nut = $(this).find('td:nth-child(1)').attr('id_nut');
                var quantité = $(this).find('td:nth-child(2)').text();
                val_nut.push({ id_nut: id_nut, quantité: quantité});
                });
                    json_val_nut = JSON.stringify(val_nut);
                    });

                var saisons = [];
                $(function(){
                      $(':checkbox:checked').each(function(){
                        var id_saison = $(this).attr('id_saison');
                        saisons.push({ id_saison: id_saison});
                        });
                    json_saisons = JSON.stringify(saisons);
                    });    

                var ingData = {
                    titre: $('#titre').val(),
                    healthy: $('#healthy option:selected').val(),
                    taux: $('#taux').val(),
                    lien_image: $('#lien_image').val(),
                    description : $('#description').val(),
                    nbcalories :$('#nb_calories').val(),
                    etat: $('#etat').val(),
                    saisons: json_saisons,
                    val_nut:json_val_nut,
                    method : 'ajouter_ing_admin'
                };
                $.ajax({
                    type: 'POST',
                    url : 'traitements_ings.php',
                    data : ingData,
                    dataType : 'json',
                    error : function(ts){alert(ts.responseText);},
                    complete : function(){window.close();location.reload();}
                });
                console.log(ingData);
            });
                
        ";
        echo"</script>";
        
    }

    public function modifier_ingrédient($id_ing){
        $controller = new IngrédientController();
        $res = $controller->get_ingrédient_info($id_ing);
        $saisons=$controller->get_ingrédient_saison($id_ing);
        $vals=$controller->get_valeurs_nutritionnelles($id_ing);
        $ing =$res->fetch(PDO::FETCH_ASSOC);
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
        echo "
        <form id='publier_form'>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Informations génerales</p>
            <div class='input_container'>
            <label for='titre'>Nom de l'ingrédient </label>
            <input name ='titre' id ='titre' type='text'>
            </div>
            <div class='input_container'>
            <label for='description'> Description de la recette </label>
            <textarea name ='description' id ='description' form='publier_form'> </textarea>
            </div>
            <div class='input_container'>
            <span>
            <label for='healthy'> Healthy </label>
            <select id='healthy' name='healthy'>
                <option value='1'>Oui</option>
                <option value='0'>Non</option>
            </select>
            </span><span>
            <label> Taux </label>
            <input type='number' id='taux'>
            </span>
            </div>
            <div class='input_container'>
            <label for='lien_image'> Lien image (avec '/')</label>
            <input name ='lien_image' id ='lien_image' type='text'>
            </div>
            
            <div class='input_container'>
            <label for='nb_calories'> Nombre de calories(Kcal) </label>
            <input name ='nb_calories' id ='nb_calories' type='number'>
            </div>
            <div class='input_container'>
            <label for='etat'>Etat de l'ingrédient </label>
            <input name ='etat' id ='etat' type='text'>
            </div>
            <label > Saisons </label>
            <div class='input_container'>
            <span>
            <input type='checkbox' id_saison='1' id='automne' name='saisons[]' value='automne'/>
            <label for='automne'>Automne</label>
            <input type='checkbox' id_saison='2' id='hiver' name='saisons[]' value='hiver'/>
            <label for='hiver'>Hiver</label>
            <input type='checkbox' id_saison='3'  id='printemps' name='saisons[]' value='printemps'/>
            <label for='printemps'>Printemps</label>
            <input type='checkbox' id_saison='4'  id='été' name='saisons[]' value='été'/>
            <label for='été'>Eté</label>
            </span>
            </div>
            
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Valeurs nutritionnelles(100g)</p>";
            echo "
            <table id='table_val' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Nutriment</th>
                    <th>Quantité(g)</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_val'>";
                foreach($vals as $val){
                     echo "<tr>";
                        echo "<td id_nut='".$val['Id_Valeurs_nutritionnelles']."'>".$val['Nom']."</td>";
                        echo "<td>".$val['Taux']."</td>";
                        echo "<td><button onclick=this.closest('tr').remove() class='supprimer' id_nut='".$val['Id_Valeurs_nutritionnelles']."'>Supprimer</button></td>";
                    echo "</tr>";
                }
                echo"
                </tbody>
            </table>";
            
            echo"
            <p class='ins'>Rajouter une valeur </p>
            <div class='input_container'>
            <label for='nutriments'> Liste des nutriments </label>
            <select id='nutriments' name='nutriments[]'>
            </select>
            </div>
            <p class='ins'>Spécifiez la quantité</p>
            <div class='input_container'>
            <span>
            <label> Quantité(g) </label>
            <input type='number' id='quantité'>
            </span>
            </div>
            <p class='ins'>Cliquez sur le + pour rajoutez cette valeur </p>
        </div>
        <input type='button' value='+' id ='add_val'/>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>";
        echo "var ing = "; echo json_encode($ing); echo ";";
        echo "var saisons = "; echo json_encode($saisons); echo ";";
        echo "
            $('#titre').attr('value',ing.Nom_ingrédient);
            $('#description').val(ing.Description);
            $('#healthy').val(ing.Healthy);
            $('#lien_image').attr('value',ing.Lien_image);
            $('#taux').attr('value',ing.Taux_);
            $('#nb_calories').attr('value',ing.Calories);
            $('#etat').attr('value',ing.état);
             
                $.ajax({
                    type: 'GET',
                    url: 'get_nutriments.php',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        $.each(data, function(index, nutriment) {
                            var str  = '<option id_nut='+nutriment.Id_Valeurs_nutritionnelles+'>';
                            $('#nutriments').append(str + nutriment.Nom + '</option>');
                        });
                    }
                });
            
                $('#add_val').click(function(){
                    var quantité = $('#quantité').val();
                    var nut_name = $('#nutriments option:selected').text();
                    var id_nut = $('#nutriments option:selected').attr('id_nut');
                    $('#myTable_val').append('<tr><td id_nut='+id_nut+'>'+nut_name+'</td><td>'+quantité+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' id_nut='+id_nut+'>Supprimer</button></td></tr>');
                });

                $('#publierbtn').click(function(){
                    var val_nut = [];
                    $(function () {
                    $('#myTable_val tr').each(function () {
                    var id_nut = $(this).find('td:nth-child(1)').attr('id_nut');
                    var quantité = $(this).find('td:nth-child(2)').text();
                    val_nut.push({ id_nut: id_nut, quantité: quantité});
                    });
                        json_val_nut = JSON.stringify(val_nut);
                        });
    
                    var saisons = [];
                    $(function(){
                          $(':checkbox:checked').each(function(){
                            var id_saison = $(this).attr('id_saison');
                            saisons.push({ id_saison: id_saison});
                            });
                        json_saisons = JSON.stringify(saisons);
                        });    
    
                    var ingData = {
                        id : ing.Id_Ingrédient,
                        titre: $('#titre').val(),
                        healthy: $('#healthy option:selected').val(),
                        taux: $('#taux').val(),
                        lien_image: $('#lien_image').val(),
                        description : $('#description').val(),
                        nbcalories :$('#nb_calories').val(),
                        etat: $('#etat').val(),
                        saisons: json_saisons,
                        val_nut:json_val_nut,
                        method:'modifier_ing'
                    };
                    $.ajax({
                        type: 'POST',
                        url : 'traitements_ings.php',
                        data : ingData,
                        dataType : 'json',
                        error : function(ts){alert(ts.responseText);},
                        complete : function(){window.close();location.reload();}
                    });
                    console.log(ingData);
                });                
        ";
               
        echo"</script>";
        
    }
    public function filtrer(){
        echo"
        <div class='cadre'>
        <div class='sous_cadre'>
            <p class='cadre_titre'>Filtres des ingrédients</p>
            <p class='cadre_sous_titre'>Filtrer selon </p>
            <div id='filtre_bar'>
                    <form id='filtres_form' method='post' action=''>
                        <select name='Filtres' id='filtres'>
                            <option value='tous'>Afficher tous les ingrédients</option>
                            <option value='healthy'>Les ingrédients healthy</option>
                            <option value='calories'>Nombre de calories</option>
                        </select>
                        <div id='filtrer_selon'>
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
                    case 'calories':
                        $('#intervalle').show();
                        break; 
                }
            })
        </script>
        ";
        $ing_list=array();
        $controller = new IngrédientController();
        if(isset($_POST['submit_recherche'])){
            if(!empty($_POST['Filtres'])) {
                $selected = $_POST['Filtres'];
                switch($selected){
                    case 'tous':
                        $ing_list=$controller->get_all_ingrédients_ids();
                        break;
                    case 'healthy':
                        $ing_list=$controller->get_all_healthy_ing();
                        break;
                    case 'calories':
                        $min = $_POST['min'];
                        $max = $_POST['max'];
                        $ing_list=$controller->get_ing_in_calories_range($min,$max);
                        break; 
                }
            } 
            unset($_POST);
         }
         else{
            $ing_list=$controller->get_all_ingrédients_ids();
         }
         
         return $ing_list;
    }
 }
