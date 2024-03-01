<?php 

namespace App\Controller ;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {

    #[Route("/", name:'home')]
    
    public function home(VehiculeRepository $vehiculeRepository){
        $vehicules= $vehiculeRepository->findAll();
       
        return $this->render("front/home.html.twig", ['vehicules'=> $vehicules]);
    }

    #[Route("/mention_legale", name:"mention_legale")]
    public function mention_legale(){
        $data["h1"] = "voici le titre de la page" ; 
        return $this->render("front/mention_legale.html.twig" ); 

}
}