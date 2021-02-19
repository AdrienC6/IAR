<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile():Response
    {
        return $this->render("user/profile.html.twig");
    }
    
    
    /**
     * @Route("/profile/edit", name="user_profile_edit")
     */
    public function profileEdit(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            
            $em->flush();
            $this->addFlash('success', 'Modifications enregistrées');
            return $this->redirectToRoute('user_profile');
            
        }

        return $this->render('user/profile_edit.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
