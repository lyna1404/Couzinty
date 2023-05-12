<?php
global $work_dir;
require_once $work_dir."Conrtollers\ContactController.php";
class ContactView
{
    public function Contact()
    {

        echo "<ul class='contact'>";
        $controller = new ContactController();
        echo "<li><a class='contactitem' href=".$controller->get_contact("facebook")."><img src ='Media\Images\Utilitaires\Facebook.svg'></a></li>";
        echo "<li><a class='contactitem' href=".$controller->get_contact("instagram")."><img src ='Media\Images\Utilitaires\instagram.svg'></a></li>";
        echo "<li><a class='contactitem' href=".$controller->get_contact("twitter")."><img src ='Media\Images\Utilitaires\Twitter.svg'></a></li>";
        echo "<li><a class='contactitem' href=".$controller->get_contact("youtube")."><img src ='Media\Images\Utilitaires\youtube.svg'></a></li>";
        echo "</ul>";
    }
    public function Contact_white()
    {

        echo "<ul class='contact'>";
        $controller = new ContactController();
        echo "<li><a class='contactitem' href=".$controller->get_contact("facebook")."><img src ='Media\Images\Utilitaires\Facebook_white.svg'></a></li>";
        echo "<li><a class='contactitem' href=".$controller->get_contact("instagram")."><img src ='Media\Images\Utilitaires\instagram_white.svg'></a></li>";
        echo "<li><a class='contactitem' href=".$controller->get_contact("twitter")."><img src ='Media\Images\Utilitaires\Twitter_white.svg'></a></li>";
        echo "<li><a class='contactitem' href=".$controller->get_contact("youtube")."><img src ='Media\Images\Utilitaires\youtube_white.svg'></a></li>";
        echo "</ul>";
    }
    public function ContactPage(){
        echo "<p class='contact_header' >Nos réseaux sociaux</p>";
        echo "<div class='contact'>";
        $controller = new ContactController();
        echo "<div><a class='contactitem' href=".$controller->get_contact("facebook").">Page Facebook</a><img src ='Media\Images\Utilitaires\Facebook.svg'></div>";
        echo "<div><a class='contactitem' href=".$controller->get_contact("instagram").">Page Instagram</a><img src ='Media\Images\Utilitaires\instagram.svg'></div>";
        echo "<div><a class='contactitem' href=".$controller->get_contact("twitter").">Page Twitter</a><img src ='Media\Images\Utilitaires\Twitter.svg'></div>";
        echo "<div><a class='contactitem' href=".$controller->get_contact("youtube").">Chaine Youtube</a><img src ='Media\Images\Utilitaires\youtube.svg'></div>";
        echo "</div>";
        echo "<p class='contact_header'  >Contactez-Nous</p>";
        echo "<div class='contact'>";
        $controller = new ContactController();
        echo "<div><a class='contactitem'>". $controller->get_contact("tel")."</a><img src ='Media\Images\Utilitaires\\tel.svg'></div>";
        echo "<div><a class='contactitem'>".$controller->get_contact("mail")."</a><img src ='Media\Images\Utilitaires\mail.svg'></div>";
       echo "</div>";

    }
}