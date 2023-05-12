<?php
global $work_dir;
require_once $work_dir."Models\NewsModel.php";
require_once $work_dir."Views\NewsView.php";

class NewsController{
    public function get_news($id){
        $model = new NewsModel();
        $res = $model->get_news($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    public function get_news_title($id){
        $model = new NewsModel();
        $res = $model->get_news($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Titre'];
    }
    public function get_news_image($id){
        $model = new NewsModel();
        $res = $model->get_news($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Lien_Image'];
    }
   
    public function get_news_description($id){
        $model = new NewsModel();
        $res = $model->get_news($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Description'];
    }
    public function get_news_video($id){
        $model = new NewsModel();
        $res = $model->get_news($id);
        $row = $res->fetch(PDO::FETCH_ASSOC);
        return $row['Lien_Vidéo'];
    }
    public function get_news_paragraphs($id){
        $model = new NewsModel();
        $res = $model->get_news_details($id);
        return $res;
    }
    public function get_all_news(){
        $model = new NewsModel();
        $res = $model->get_all_news();
        return $res;
    }
    public function get_all_news_affichables(){
        $model = new NewsModel();
        $res = $model->get_all_news_affichables();
        return $res;
    }
    public function add_news($titre,$lien_image,$lien_video,$description,$afficher,$paragraphes){
        $model = new NewsModel();
        $id = $model->add_news_info($titre,$lien_image,$lien_video,$description,$afficher);
        $model->add_news_paragraphes($id,$paragraphes);
    }
    public function supprimer_news($id){
        $model = new NewsModel();
        $model->supprimer_news_info($id);
        $model->supprimer_news_paragraphes($id);
    }
}
?>