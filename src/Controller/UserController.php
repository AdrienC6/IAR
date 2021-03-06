<?php

namespace App\Controller;

use App\Form\ChangePWType;
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
     * @Route("/profile/editpw", name="user_edit_pw" )
     */
    public function passwordEdit(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em):Response
    {
        $form = $this->createForm(ChangePWType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $user = $this->getUser();
            $currentPw = $form['currentPassword']->getData();
            $newPw = $encoder->encodePassword($user, $form['newPassword']->getData());

            if($encoder->isPasswordValid($user, $currentPw)){
                $user->setPassword($newPw);
                $user->setVerified(true);
                $em->flush();
                $this->addFlash('success', 'Mot de passe modifié avec succès');
                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('error', 'Le mot de passe saisi n\'est pas valide');
            }
        }

        return $this->render('user/edit_password.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
