<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectRegistrationController extends AbstractController
{
    #[Route('/dashboard/registration', name: 'app_project_registration')]
    public function index(): Response
    {
        return $this->render('dashboard/project_registration.html.twig', [
            'controller_name' => 'ProjectRegistrationController',
        ]);
    }
}
