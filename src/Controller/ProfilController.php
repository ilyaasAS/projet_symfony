<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\LoginType; 

class ProfilController extends AbstractController
{

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('pages/profile/index.html.twig', [
        ]);
    }
}
