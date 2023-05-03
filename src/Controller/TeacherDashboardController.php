<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeacherDashboardController extends AbstractController
{
    #[Route('/teacher/', name: 'app_teacher')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $project = $entityManager->getRepository(Project::class)->findBy(['Teacher' =>$this->getUser()]);
        return $this->render('teacher_dashboard/index.html.twig', [
            'projects' => $project
        ]);
    }
    #[Route('/teacher/project/add', name: 'app_teacher_project_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class,$project);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $project->setTeacher($this->getUser());
            $entityManager->persist($project);
            $entityManager->flush(); 
            return $this->redirectToRoute('app_teacher');

        }
        return $this->render('teacher_dashboard/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
