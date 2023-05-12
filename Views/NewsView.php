<?php
global $work_dir;
require_once $work_dir."Conrtollers\NewsController.php";
class NewsView
{
    public function afficher_news()
    {   
        $controller = new NewsController();
        $res=$controller->get_all_news_affichables();
        echo "<div class='cadre'><div class='sous_cadre'>
            <p class='cadre_titre'>News</p>
        </div></div>";
        echo "<div class='cadre_news'><div class='sous_cadre_recette'>";
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            $this->créer_cadre_news($row['Id_News']);
        }
        echo "</div></div>";
    
    }
    public function créer_cadre_news($id_news){
        $controller = new NewsController();
        $img = $controller->get_news_image($id_news);
        $titre = $controller->get_news_title($id_news);
        echo "
        <div class = 'news_cadre' style='background-image: url(".$img.")'>
        <a href='Newsdetails.php?Id=".$id_news."' ></a>
            <div class = 'news_info'>
                <p class = 'news_titre'>".$titre."</p>
            </div>
        </div>
        ";
    }
    public function News_details($id_news){
        $controller = new NewsController();
        $titre =$controller->get_news_title($id_news);
        $img =$controller->get_news_image($id_news);
        $desc=$controller->get_news_description($id_news);
        $paragraphs = $controller->get_news_paragraphs($id_news);
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
                echo "<div class=news_paragraphs>";
                while($prg=$paragraphs->fetch(PDO::FETCH_ASSOC)){
                    echo "<div class='paragraph'>";
                    if($prg['titre']!=null){
                        echo "<p class='titre_paragraph'>".$prg['titre']."</p>";

                    }
                    echo "<p class='contenu_paragraph'>".$prg['Contenu']."</p>";
                    echo "</div>";}
                
                echo "</div>";
                $video=$controller->get_news_video($id_news); 
                if($video!=null){
                    echo "<video width='1240px' height='600px' autoplay muted><source src='".$video."' type='video/mp4'></video> ";
                }
        echo "</div>";

    }
    public function gestion_news(){
        $controller = new NewsController();
        $news = $controller->get_all_news();
        echo "
        <div class='cadre'>
        <div class='sous_cadre'>
        <p class='cadre_titre'>Gestion des news (toutes les news)</p>
        <select id='fnews' name='fnews'>
            <option value='toutes'>Toutes les actualités</option>
            <option value='1'>Actualités affichées</option>
            <option value='0'>Actualités non affichées</option>
        </select>
        <button value='Ajouter news' id='ajouterbtn'>Ajouter news</button>
        </div>
        <table id='table' class='table table-bordered table-striped'>
            <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Afficher</th>
                <th>Lien Image</th>
                <th>Lien Vidéo</th>
                <th>Description</th>
                <th></th>
            </tr>
            </thead>
            <tbody id='myTable'>";
            foreach($news as $new){
                echo "<tr>";
                    echo "<td>".$new['Id_News']."</td>";
                    echo "<td>".$new['Titre']."</td>";
                    echo "<td>".$new['Afficher']."</td>";
                    echo "<td>".$new['Lien_Image']."</td>";
                    echo "<td>".$new['Lien_Vidéo']."</td>";
                    echo "<td>".$new['Description']."</td>";
                    echo "<td><button class='modifier' id_news='".$new['Id_News']."'>Modifier</button>
                    <button class='supprimer' id_news='".$new['Id_News']."'>Supprimer</button>
                    </td>";
                echo "</tr>";
            }
            echo"
            </tbody>
        </table>
        ";
        

        echo "<script>
        $('select#fnews').change(function(){
            var value = $('#fnews option:selected').val();
            $('#myTable tr').filter(function () {
                if(value=='toutes'){
                    $(this).show();                
                }
                else{
                    $(this).toggle($(this.children[2]).text().indexOf(value) > -1) ;  }          
             });
        });
        $('button.modifier').click(function(){
            var id = $(this).attr('id_news');
            window.open('Modifier_news.php?Id='+id);
            
        });

        $('button.supprimer').click(function(){
            var id = $(this).attr('id_news');

            $.ajax({
                type: 'POST',
                url : 'traitements_news.php',
                data : {id: id,method:'supprimer_news'},
                dataType : 'json',
                error : function(ts){alert(ts.responseText);},
                complete : function(){location.reload();}
            });
        });
        $('button#ajouterbtn').click(function(){
            window.open('Ajouter_news.php');
        });
        </script>";
    }
    public function ajouter_news(){
        $controller = new NewsController();
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
        echo "
        <form id='publier_form'>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Informations génerales</p>
            <div class='input_container'>
            <label for='titre'> Titre </label>
            <input name ='titre' id ='titre' type='text'>
            </div>
            <div class='input_container'>
            <label for='description'> Description </label>
            <textarea name ='description' id ='description' form='publier_form'> </textarea>
            </div>
            <div class='input_container'>
            <label for='afficher'> Afficher </label>
            <select id='afficher' name='afficher'>
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
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Paragraphes</p>";
            echo "
            <table id='table_prg' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_prg'>";
                
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Ajouter un paragraphe </p>
            <div class='input_container'>
            <span>
            <label> Titre du paragraphe </label>
            <input type='text' id='titre_prg'>
            </span>
            <label> Contenu du paragraphe </label>
            <textarea name='contenu_prg' form='publier_form' id='contenu_prg'></textarea>
            </span>
            <span>
            <label> Ordre </label>
            <input type='number' id='ordre_prg'>
            </span>
            </div>
            <p class='ins'>Cliquez sur le + pour rajoutez le paragraphe</p>
            <input type='button' value='+' id ='add_prg'/>
        </div>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>";
        echo "
            $('#add_prg').click(function(){
                var ordre = $('#ordre_prg').val();
                var titre = $('#titre_prg').val();
                var contenu = $('#contenu_prg').val();
                $('#myTable_prg').append('<tr><td ordre='+ordre+'>'+ordre+'</td><td>'+titre+'</td><td>'+contenu+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' ordre='+ordre+'>Supprimer</button></td></tr>');

            });
    
            $('#publierbtn').click(function(){

                var paragraphes = [];
                $(function () {
                $('#myTable_prg tr').each(function () {
                var ordre = $(this).find('td:nth-child(1)').text();
                var titre = $(this).find('td:nth-child(2)').text();
                var contenu = $(this).find('td:nth-child(3)').text();


                paragraphes.push({ ordre: ordre, titre: titre, contenu:contenu});
                });
                    json_paragraphes = JSON.stringify(paragraphes);
                    });

                if(jQuery.isEmptyObject(json_paragraphes)) {
                    alert('veuillez introduire les paragraphes');
                    return;
                } else {
                        if($('#titre').val()==''){
                            alert('veuillez introduire le titre du news');
                            return;
                        }
                    
                }
                
                var newsData = {
                    titre: $('#titre').val(),
                    afficher: $('#afficher option:selected').val(),
                    lien_image: $('#lien_image').val(),
                    lien_video: $('#lien_vidéo').val(),
                    description : $('#description').val(),
                    paragraphes:json_paragraphes,
                    method : 'ajouter_news'
                };
                $.ajax({
                    type: 'POST',
                    url : 'traitements_news',
                    data : newsData,
                    dataType : 'json',
                    error : function(ts){alert(ts.responseText);},
                    complete:function(){window.close();}
                });
                console.log(newsData);
            });
                
        ";
        echo"</script>";
        
    }
    public function modifier_news($id_news){
        $controller = new NewsController();
        $news = $controller->get_news($id_news);
        $prgs=$controller->get_news_paragraphs($id_news);
        echo "<div class='cadre'><div class='sous_cadre_recette'>";
        echo "
        <form id='publier_form'>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Informations génerales</p>
            <div class='input_container'>
            <label for='titre'> Titre </label>
            <input name ='titre' id ='titre' type='text'>
            </div>
            <div class='input_container'>
            <label for='description'> Description </label>
            <textarea name ='description' id ='description' form='publier_form'> </textarea>
            </div>
            <div class='input_container'>
            <label for='afficher'> Afficher </label>
            <select id='afficher' name='afficher'>
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
        </div>
        <div class='partie_form'>
            <p class='publier_sous_titre'>Paragraphes</p>";
            echo "
            <table id='table_prg' class='table table-bordered table-striped'>
                <thead>
                <tr>
                    <th>Ordre</th>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id='myTable_prg'>";
                foreach($prgs as $prg){
                    echo "<tr>";
                        echo "<td ordre='".$prg['Ordre']."'>".$prg['Ordre']."</td>";
                        echo "<td>".$prg['titre']."</td>";
                        echo "<td>".$prg['Contenu']."</td>";
                        echo "<td><input onclick=this.closest('tr').remove() value='supprimer' class='supprimer' ordre='".$prg['Ordre']."' type='button'/></td>";
                    echo "</tr>";
                }
                echo"
                </tbody>
            </table>";
            echo"
            <p class='ins'>Ajouter un paragraphe </p>
            <div class='input_container'>
            <span>
            <label> Titre du paragraphe </label>
            <input type='text' id='titre_prg'>
            </span>
            <label> Contenu du paragraphe </label>
            <textarea name='contenu_prg' form='publier_form' id='contenu_prg'></textarea>
            </span>
            <span>
            <label> Ordre </label>
            <input type='number' id='ordre_prg'>
            </span>
            </div>
            <p class='ins'>Cliquez sur le + pour rajoutez le paragraphe</p>
        </div>
        <input type='button' value='+' id ='add_prg'/>
        <input type='button' value='Publier' id='publierbtn'/>
        </form>";
        echo "</div></div>"; 
        echo "<script>";
        echo "var news = "; echo json_encode($news); echo ";";
        echo "
            $('#titre').attr('value',news.Titre);
            $('#description').val(news.Description);
            $('#afficher').val(news.Afficher);
            $('#lien_image').attr('value',news.Lien_Image);
            $('#lien_vidéo').attr('value',news.Lien_Vidéo);
            
            $('#add_prg').click(function(){
                var ordre = $('#ordre_prg').val();
                var titre = $('#titre_prg').val();
                var contenu = $('#contenu_prg').val();
                $('#myTable_prg').append('<tr><td ordre='+ordre+'>'+ordre+'</td><td>'+titre+'</td><td>'+contenu+'</td><td><button onclick=this.closest(\'tr\').remove() class=\'supprimer\' ordre='+ordre+'>Supprimer</button></td></tr>');

            });

            $('#publierbtn').click(function(){

                var paragraphes = [];
                $(function () {
                $('#myTable_prg tr').each(function () {
                var ordre = $(this).find('td:nth-child(1)').text();
                var titre = $(this).find('td:nth-child(2)').text();
                var contenu = $(this).find('td:nth-child(3)').text();


                paragraphes.push({ ordre: ordre, titre: titre, contenu:contenu});
                });
                    json_paragraphes = JSON.stringify(paragraphes);
                    });

                if(jQuery.isEmptyObject(json_paragraphes)) {
                    alert('veuillez introduire les paragraphes');
                    return;
                } else {
                        if($('#titre').val()==''){
                            alert('veuillez introduire le titre du news');
                            return;
                        }
                    
                }
                
                var newsData = {
                    id : news.Id_News,
                    titre: $('#titre').val(),
                    afficher: $('#afficher option:selected').val(),
                    lien_image: $('#lien_image').val(),
                    lien_video: $('#lien_vidéo').val(),
                    description : $('#description').val(),
                    paragraphes:json_paragraphes,
                    method:'modifier_news'
                };
                $.ajax({
                    type: 'POST',
                    url : 'traitements_news.php',
                    data : newsData,
                    dataType : 'json',
                    error : function(ts){alert(ts.responseText);},
                    complete:function(){window.close();}

                });
                console.log(newsData);
            });
               
                

                
        ";
               
        echo"</script>";
         
    }
}