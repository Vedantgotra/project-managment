<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectRegistrationController extends AbstractController
{
    #[Route('/dashboard/registration', name: 'app_project_registration')]
    public function index(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectRegistrationType::class,$project);
        $form->handleRequest($request);
        return $this->render('dashboard/project_registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
