<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    /**
     * @Route("/connexion", name="login")
     */
    public function login(){
        if($this->getUser()){
            return $this->redirectToRoute('homepage');
        }
        return $this->render('security/connexion.html.twig');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}
