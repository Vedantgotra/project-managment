<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherDashboardController extends AbstractController
{
    #[Route('/teacher/dashboard', name: 'app_teacher_dashboard')]
    public function index(): Response
    {
        return $this->render('teacher_dashboard/index.html.twig', [
            'controller_name' => 'TeacherDashboardController',
        ]);
    }
}
