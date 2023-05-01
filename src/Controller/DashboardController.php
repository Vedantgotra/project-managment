<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Teacher;
use App\Form\ProjectRegistrationType;
use App\Form\ProjectSelectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/dashboard/project', name: 'app_project')]
    public function project(Request $request): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectRegistrationType::class,$project);
        $form->handleRequest($request);
        return $this->render('dashboard/project.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('dashboard/project/select/{slug}', name: 'app_project_select')]
    public function teacherSelect(Request $request): Response
    {
        $form = $this->createForm(ProjectSelectType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted()){
            return $this->redirectToRoute('app_project_select_choose', ['slug' =>'teacher','id' => $form->getData()['Teacher']->getid()]);
        }
        return $this->render('dashboard/project_select.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('dashboard/project/select/{slug}/{id}', name: 'app_project_select_choose')]
    public function projectSelect(Request $request, Teacher $teacher, EntityManagerInterface $entityManager): Response
    {
        
        $project =  $entityManager->getRepository(Project::class)->findBy(['Teacher' => $teacher]);
        // dd($request->query->get('id') !== null);
        if($request->query->get('id') !== null){
            $id = $request->query->get('id');
            $project =  $entityManager->getRepository(Project::class)->findOneBy(['id' => $id ]);
            $project->setUser($this->getUser());
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute("app_dashboard");

        }
        return $this->render('dashboard/project_final.html.twig', [
            'projects' => $project
        ]);
    }
}
