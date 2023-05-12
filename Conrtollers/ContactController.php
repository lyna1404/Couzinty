<?php
global $work_dir;
require_once $work_dir."Views\ContactView.php";
require_once $work_dir."Models\ContactModel.php";
class ContactController{
    public function get_contact($socialmedia){
        $model = new ContactModel();
        $res = $model->get_contact();
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            if($row['Nom']==$socialmedia){
                $sm = $row['Lien_Contact'];
            }
        }
        return $sm;
    }
}
?>