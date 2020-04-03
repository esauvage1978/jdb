<?php

namespace App\Controller\Project;

use App\Controller\AppControllerAbstract;
use App\Entity\Project;
use App\Form\Project\ProjectType;
use App\Manager\ProjectManager;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/project")
 */
class ProjectController extends AppControllerAbstract
{
    CONST ENTITYS = 'projects';
    CONST ENTITY = 'project';

    /**
     * @Route("/", name="project_index", methods={"GET"})
     * @return Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function indexAction(projectRepository $projectRepository): Response
    {
        return $this->render(self::ENTITY . '/index.html.twig', [
            self::ENTITYS => $projectRepository->findAll(),
        ]);
    }

    /**
     * @Route("/add", name="project_add", methods={"GET","POST"})
     * @return Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function newAction(Request $request, ProjectManager $manager): Response
    {
        return $this->editAction($request, new Project(), $manager, self::MSG_CREATE);
    }

    /**
     * @Route("/{id}", name="project_show", methods={"GET"})
     * @return Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function showAction(Project $project): Response
    {
        return $this->render(self::ENTITY . '/show.html.twig', [
            self::ENTITY => $project,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="project_edit", methods={"GET","POST"})
     * @return Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function editAction(
        Request $request,
        Project $entity,
        ProjectManager $manager,
        string $message = self::MSG_MODIFY): Response
    {
        return $this->edit(
            $request,
            $entity,
            $manager,
            self::ENTITY,
            ProjectType::class,
            $message
        );
    }

    /**
     * @Route("/{id}", name="project_delete", methods={"DELETE"})
     * @return Response
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function deleteAction(
        Request $request,
        Project $project,
        ProjectManager $projectManager): Response
    {
        return $this->delete($request, $project, $projectManager, self::ENTITY);
    }
}
