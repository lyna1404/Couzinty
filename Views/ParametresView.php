<?php
global $work_dir;
require_once $work_dir."Conrtollers\ParametresController.php";
class ParametresView
{
    public function Logo()
    {

        $controller = new ParametresController();
        $res = $controller->get_param();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    if ($row['nom_param']=='Logo'){
                        echo "<img class ='logo' src='".$row['contenu']."'>";
                    }
                }
    }
    public function Logo_white()
    {

        $controller = new ParametresController();
        $res = $controller->get_param();
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    if ($row['nom_param']=='Logo_white'){
                        echo "<img class ='logo' src='".$row['contenu']."'>";
                    }
                }
    }
    
    public function gestion_param(){
        $controller = new ParametresController();
        $logo = $controller->get_logo_src();
        $logow = $controller->get_logo_white_src();
        $pourcentage = number_format($controller->get_pourcentage());
        echo "
        <div class='cadre'>
        <div class='sous_cadre'>
        <p class='cadre_titre'>Paramètres</p>
        </div>
        <form id='publier_form'>
        <div class='partie_form'>
            <div class='input_container'>
            <label for='logo'> Logo </label>
            <input name ='logo' id ='logo' type='text'>
            </div>
            <div class='input_container'>
            <label for='logow'> Logo blanc </label>
            <input name ='logow' id ='logow' type='text'>
            </div>
            <div class='input_container'>
            <label for='prc'> Pourcentage (Idée de recettes) </label>
            <input name ='prc' id ='prc' type='number'>
            </div>
            <input name='submit' type='submit' value='Publier' id='publierbtn'/>
        </div>
        </form></br></br></br>";
        echo"<script>";
        echo "var logo = "; echo json_encode($logo); echo ";";
        echo "var logow = "; echo json_encode($logow); echo ";";
        echo "var prc = "; echo json_encode($pourcentage); echo ";";

        echo"
            $('#logo').attr('value',logo);
            $('#logow').attr('value',logow);
            $('#prc').attr('value',prc);

            $('#publierbtn').click(function(){
                var Data = {
                    logo: $('#logo').val(),
                    logow:$('#logow').val(),
                    prc:$('#prc').val()
                };
                $.ajax({
                    type: 'POST',
                    url : 'update_param.php',
                    data : Data,
                    dataType : 'json',
                    error : function(ts){alert(ts.responseText);}
                });
                console.log(Data);
            });
        </script>";

        
        
        
    }
}