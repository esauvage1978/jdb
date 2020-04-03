<?php

namespace App\Controller;

use App\Dto\ProjectDto;
use App\Repository\ProjectDtoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(ProjectDtoRepository $dtoRepository)
    {
        $dto=new ProjectDto();

        $dto
            ->setUser($this->getUser())
            ->setEnable(ProjectDto::TRUE);

        return $this->render('home/index.html.twig', [
            'projects' => $dtoRepository->findAllForDto($dto),
        ]);
    }
}
