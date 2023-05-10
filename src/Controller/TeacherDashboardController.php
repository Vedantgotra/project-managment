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
        $project = $entityManager->getRepository(Project::class)->findBy(['Teacher' => $this->getUser()]);
        return $this->render('teacher_dashboard/index.html.twig', [
            'projects' => $project
        ]);
    }
    #[Route('/teacher/project/add', name: 'app_teacher_project_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $project->setTeacher($this->getUser());
            $project->setApproved(false);
            $entityManager->persist($project);
            $entityManager->flush();
            return $this->redirectToRoute('app_teacher');
        }
        return $this->render('teacher_dashboard/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/teacher/project/approve', name: 'app_teacher_project_approve')]
    public function approvalList(EntityManagerInterface $entityManager, Request $request): Response
    {
        
        if (isset($_REQUEST['working'])) {
            $action = $request->get('working');
            $ids = $request->get('id');
            if ($action == 'submit') {
                foreach ($ids as $id) {
                    $project = $entityManager->getRepository(Project::class)->find($id);
                    $project->setApproved(true);
                    $entityManager->persist($project);
                    $entityManager->flush();
                }
            } else if ($action == 'decline') {
            }
        }
        //    dd($request->request->all());        }
        $projects = $entityManager->getRepository(Project::class)->findBy(['Teacher' => $this->getUser(), 'Approved' => false]);
        $projectWithoutUser = [];
        foreach ($projects as $tempproject) {
            if($tempproject->getUser()){
                array_push($projectWithoutUser,$tempproject);
            }
        }
        // dd($projectWithoutUser);
        return $this->render('teacher_dashboard/approvallist.html.twig', [
            'projects' => $projectWithoutUser
        ]);
    }

    #[Route('/teacher/project/all', name: 'app_teacher_project_all')]
    public function myProjects(EntityManagerInterface $entityManager): Response
    {
        $project = $entityManager->getRepository(Project::class)->findBy(['Teacher' => $this->getUser()]);
        return $this->render('teacher_dashboard/myprojects.html.twig', [
            'projects' => $project
        ]);
    }
}
