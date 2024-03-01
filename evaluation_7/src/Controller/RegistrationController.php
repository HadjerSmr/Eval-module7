<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }


    #[Route('/index_user', name: 'index_user')]
     public function index(UserRepository $userRepository): Response
     {
         $users= $userRepository->findAll();
         return $this->render('registration/index_user.html.twig', [
             'users'=> $users,
         ]);
     }


    #[Route("/update_user/{id}", name: "update_user")]
    public function update(int $id,
        UserRepository $UserRepository,
        Request $request,  //POST
        EntityManagerInterface $em){ // Objet permettant de faire UPDATE en sql
        	$user = $UserRepository->findOneBy(["id"=>$id]);
            // dd($categorie);
            $form=$this->createForm(RegistrationFormType::class, $user,["label"=>"modifier"]);

            $form->handleRequest($request);// récupére le POST  et rempli le formulaire
            if($form->isSubmitted()&&$form->isValid()){
                $em->persist($user); //UPDATE SQL
                $em->flush(); //UPDATE SQL
                return $this->redirectToRoute("index_user");
            }

             return $this->render("registration/update.html.twig",["form"=> $form]);
    }

    #[Route("/delete_user/{id}", name: "delete_user")]
    public function delete(
            EntityManagerInterface $em,
            UserRepository $UserRepository,
            int $id,
        ){
        $user = $UserRepository->FindOneBy(["id"=>$id]);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("index_user");
     }





}
