<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Teacher;
use App\Form\ProjectRegistrationType;
use App\Form\ProjectSelectType;
use App\Form\ProjectType;
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
        $form = $this->createForm(ProjectRegistrationType::class, $project);
        $form->handleRequest($request);
        return $this->render('dashboard/project.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('dashboard/project/select/{slug}', name: 'app_project_select')]
    public function teacherSelect(Request $request, EntityManagerInterface $entityManager, $slug): Response
    {
        if ($slug == "teacher") {
            $teacher = $entityManager->getRepository(Teacher::class)->findAll();
            $item = $teacher;
            if ($request->request->all()) {
                $id = $request->request->all()['id'];
                return $this->redirectToRoute('app_project_select_choose', ['slug' => 'teacher', 'id' => $id]);
            }
        } elseif ($slug == "technology") {
            $projects = $entityManager->getRepository(Project::class)->findAll();
            $technologies = [];
            foreach ($projects as $project) {
                $technologies[] =  $project->getTechnology();
            }
            $technologies = array_unique($technologies);
            $item = $technologies;
            if ($request->request->all()) {
                $val = $request->request->all()['technology'];
                return $this->redirectToRoute('app_project_select_choose_by_tech', ['slug' => $val]);
            }
        } elseif ($slug == "self") {

            $project = new Project();
            $form = $this->createForm(ProjectType::class, $project);
            $form->handleRequest($request);
            if ($form->isSubmitted()) {
                if ($this->getUser()->getProject()) {
                    $this->addFlash("danger", "YOu have already assigned a project");
                    return $this->redirectToRoute('app_project_select', ['slug' => $slug]);
                }
                if ($project->getUser()) {
                    $this->addFlash("danger", "this project is already Registered With Some User");
                    return $this->redirectToRoute('app_project_select', ['slug' => $slug]);
                } else {

                    $project->setUser($this->getUser());
                    $project->setApproved(false);
                    $entityManager->persist($project);
                    $entityManager->flush();

                    $this->addFlash("success", "Your Project is sent TO be Approved");
                    return $this->redirectToRoute("app_dashboard");
                }
            }
            $item = $form->createView();
        }

        return $this->render('dashboard/project_select.html.twig', [
            'slug' => $slug,
            'item' => $item
        ]);
    }
    #[Route('dashboard/project/select/technology/{slug}', name: 'app_project_select_choose_by_tech')]
    public function projectSelectByTech(Request $request, EntityManagerInterface $entityManager, $slug): Response
    {
        $projects = $entityManager->getRepository(Project::class)->findBy(['Technology' => $slug]);
        if ($request->query->get('id') !== null) {
            $id = $request->query->get('id');
            $project =  $entityManager->getRepository(Project::class)->findOneBy(['id' => $id]);
            if ($this->getUser()->getProject()) {
                $this->addFlash("danger", "YOu have already assigned a project");
                return $this->redirectToRoute('app_project_select_choose_by_tech', ['slug' => $slug]);
            }
            if ($project->getUser()) {
                $this->addFlash("danger", "this project is already Registered With Some User");
                return $this->redirectToRoute('app_project_select_choose_by_tech', ['slug' => $slug]);
            } else {

                $project->setUser($this->getUser());
                $project->setApproved(false);
                $entityManager->persist($project);
                $entityManager->flush();

                $this->addFlash("success", "Your Project is sent TO be Approved");
                return $this->redirectToRoute("app_dashboard");
            }
        }

        return $this->render('dashboard/project_final.html.twig', [
            'projects' => $projects
        ]);
    }
    #[Route('dashboard/project/select/{slug}/{id}', name: 'app_project_select_choose')]
    public function projectSelect(Request $request, Teacher $teacher, EntityManagerInterface $entityManager): Response
    {

        $project =  $entityManager->getRepository(Project::class)->findBy(['Teacher' => $teacher]);
        // dd($request->query->get('id') !== null);
        if ($request->query->get('id') !== null) {
            $id = $request->query->get('id');
            $project =  $entityManager->getRepository(Project::class)->findOneBy(['id' => $id]);
            if ($this->getUser()->getProject()) {
                $this->addFlash("danger", "YOu have already assigned a project");
                return $this->redirectToRoute('app_project_select_choose', ['slug' => 'teacher', 'id' => $teacher->getId()]);
            }
            if ($project->getUser()) {
                $this->addFlash("danger", "this project is already Registered With Some User");
                return $this->redirectToRoute('app_project_select_choose', ['slug' => 'teacher', 'id' => $teacher->getId()]);
            } else {

                $project->setUser($this->getUser());
                $project->setApproved(false);
                $entityManager->persist($project);
                $entityManager->flush();

                $this->addFlash("success", "Your Project is sent TO be Approved");
                return $this->redirectToRoute("app_dashboard");
            }
        }
        return $this->render('dashboard/project_final.html.twig', [
            'projects' => $project
        ]);
    }
}
