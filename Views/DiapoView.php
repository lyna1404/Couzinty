<?php
global $work_dir;
require_once $work_dir."Conrtollers\DiapoController.php";
require_once $work_dir."Conrtollers\NewsController.php";
require_once $work_dir."Conrtollers\RecetteController.php";


class DiapoView
{
    public function diapo_principal()
    {   
        echo "<div class='diapo_text'>";
        $diapocontroller = new DiapoController();
        $newscontroller = new NewsController();
        $recettecontroller = new RecetteController();
        $items = $diapocontroller->get_diapo_principal();
        echo "<div><p id='diapo_title'></p></div>";
        echo "<div><p id='diapo_description'></p></div>";  
        echo "<div><a id='diapo_link' href='Newsdetails.php?Id=' target='_blank'><p>Lire la suite</p></a></div>";              
        echo "</div>";
        $img = array();
        $title = array();
        $desc = array();
        $links=array();
        foreach($items as $item){
            
            if($item['Type']==0){
                array_push($img,addslashes($newscontroller->get_news_image($item['id_news_recette'])));
                array_push($title,addslashes($newscontroller->get_news_title($item['id_news_recette'])));
                array_push($desc,addslashes($newscontroller->get_news_description($item['id_news_recette'])));
                array_push($links,$item['Lien']);
            }
            else{
                array_push($img,addslashes($recettecontroller->get_recette_image($item['id_news_recette'])));
                array_push($title,addslashes($recettecontroller->get_recette_title($item['id_news_recette'])));
                array_push($desc,addslashes($recettecontroller->get_recette_description($item['id_news_recette'])));
                array_push($links,$item['Lien']);
            }
        }   
            echo "<script type='text/javascript'>";
            echo "var images = "; echo json_encode($img); echo ";";
            echo "var titres  = "; echo json_encode($title); echo ";";
            echo "var desc  = "; echo json_encode($desc); echo ";";
            echo "var liens  = "; echo json_encode($links); echo ";";
            echo "var i = 0;";
            echo "function changediapo(){";
                echo "
                document.getElementById('diapo_img').style.backgroundImage = 'url('+images[i]+')';
                document.getElementById('diapo_title').innerHTML = titres[i];
                document.getElementById('diapo_description').innerHTML =desc[i];
                document.getElementById('diapo_link').href =liens[i];
                if(i<images.length -1) i++;
                else i=0;
                setTimeout('changediapo()',3000);
                }";
            echo "</script>";

    }

    public function gestion_diapo(){
        $controller = new DiapoController();
        $recettecontroller = new RecetteController();
        $newscontroller = new NewsController();
        $items = $controller->get_diapo_principal();
        echo "
        <div class='cadre'>
        <div class='sous_cadre'>
        <p class='cadre_titre'>Gestion du diaporama principal</p>
        <select id='ftype' name='ftype'>
            <option value='toutes'>Tous les éléments</option>
            <option value='1'>Recettes</option>
            <option value='0'>News</option>
        </select>
        <table id='table' class='table table-bordered table-striped'>
            <thead>
            <tr>
                <th>Type</th>
                <th>Titre</th>
                <th>Ordre</th>
                <th></th>
            </tr>
            </thead>
            <tbody id='myTable'>";
            foreach($items as $item){
                if($item['Type']==0){
                    echo "<tr>";
                        echo "<td type='0' ide='".$item['id_news_recette']."' lien='Newsdetails.php?Id=".$item['id_news_recette']."'>News</td>";
                        echo "<td>".$newscontroller->get_news_title($item['id_news_recette'])."</td>";
                        echo "<td>".$item['Ordre']."</td>";
                        echo "<td>";
                        echo "<button onclick=this.closest('tr').remove() class='val_enl'>Enlever</button>";
                        echo "</td>";
                    echo "</tr>";
                }
                else{
                    echo "<tr>";
                        echo "<td type='1' ide='".$item['id_news_recette']."' lien='Recette.php?Id=".$item['id_news_recette']."'>Recette</td>";
                        echo "<td>".$recettecontroller->get_recette_title($item['id_news_recette'])."</td>";
                        echo "<td>".$item['Ordre']."</td>";
                        echo "<td>";
                        echo "<button onclick=this.closest('tr').remove() class='val_enl'>Enlever</button>";
                        echo "</td>";
                    echo "</tr>";
                }
                
            }
            echo"
            </tbody>
        </table>
        ";
        echo"
        <form id='publier_form'>
        <div class='partie_form'>
        <p class='ins'>Rajouter une news</p>
            <div class='input_container'>
            <label for='news'> Liste des news </label>
            <select id='news' name='news[]'>
            </select>
            </div>
            <p class='ins'>Spécifiez l\'ordre de passage de cet élément dans le diapo</p>
            <div class='input_container'>
            <span>
            <label> Ordre </label>
            <input type='number' id='ordren'>
            </span>
            </div>
            <input type='button' value='+' id ='add_news'/>
        <p class='ins'>Rajouter une recette</p>
            <div class='input_container'>
            <label for='recettes'> Liste des recettes </label>
            <select id='recettes' name='recettes[]'>
            </select>
            </div>
            <p class='ins'>Spécifiez l\'ordre de passage de cet élément dans le diapo</p>
            <div class='input_container'>
            <span>
            <label> Ordre </label>
            <input type='number' id='ordrer'>
            </span>
            </div>
            <input type='button' value='+' id ='add_recette'/>
        </div>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>
        </div>

        ";
        

        echo "<script>
        $.ajax({
            type: 'GET',
            url: 'get_recettes.php',
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, recette) {
                    var str  = '<option id_recette='+recette.Id_Recette+'>';
                    $('#recettes').append(str + recette.Nom_Recette + '</option>');
                });
            }
        });
        $.ajax({
            type: 'GET',
            url: 'get_news.php',
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index,n) {
                    var str  = '<option id_news='+n.Id_News+'>';
                    $('#news').append(str + n.Titre + '</option>');
                });
            }
        });
        $('#add_news').click(function(){
            var id_news = $('#news option:selected').attr('id_news');
            var ordre = $('#ordren').val();
            var title = $('#news option:selected').text();
            $('#myTable').append('<tr><td type=\'0\' ide='+id_news+'  lien=Newsdetails.php?Id='+id_news+'>News</td><td>'+title+'</td><td>'+ordre+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'val_enl\'>Enlever</button></td></tr>');
        });
        $('#add_recette').click(function(){
            var id_rec = $('#recettes option:selected').attr('id_recette');
            var ordre = $('#ordrer').val();
            var title = $('#recettes option:selected').text();
            $('#myTable').append('<tr><td type=\'1\' ide='+id_rec+' lien=Recette.php?Id='+id_rec+'>Recette</td><td>'+title+'</td><td>'+ordre+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'val_enl\'>Enlever</button></td></tr>');
        });
        $('select#ftype').change(function(){
            var value = $('#ftype option:selected').val();
            $('#myTable tr').filter(function () {
                if(value=='toutes'){
                    $(this).show();                
                }
                else{
                    if(value=='1'){
                        $(this).toggle($(this.children[0]).text().indexOf('Recette') > -1) ;}
                    if(value=='0'){
                        $(this).toggle($(this.children[0]).text().indexOf('News') > -1) ;}
                }          
             });
        });
        
        $('#publierbtn').click(function(){

            var rows = [];
            $(function () {
            $('#myTable tr').each(function () {
            var lien = $(this).find('td:nth-child(1)').attr('lien');
            var type = $(this).find('td:nth-child(1)').attr('type');
            var id = $(this).find('td:nth-child(1)').attr('ide');
            var ordre = $(this).find('td:nth-child(3)').text();


            rows.push({ id: id, type: type, lien:lien,ordre:ordre});
            });
                json_rows = JSON.stringify(rows);
                });

            
            var Data = {
                rows:json_rows
            };
            $.ajax({
                type: 'POST',
                url : 'update_diapo.php',
                data : Data,
                dataType : 'json',
                error : function(ts){alert(ts.responseText);}
            });
            console.log(Data);
        });
          
        </script>"; 
    }
}