<?php

namespace ProjectRealtyBundle\Controller;

use ProjectRealtyBundle\Entity\CondominiumProject;
use ProjectRealtyBundle\Traits\HasProjectControllerUtils;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class HomeController extends Controller
{
    use HasProjectControllerUtils;

    /**
     * @Route("/", name="project_home")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        /** @var CondominiumProject[] $projects */
        $projects = $this->getCondominiumProjectRepository()
            ->findManagerProjects($user)
            ->getQuery()
            ->getResult()
        ;

        if (empty($projects)) {
            throw new AccessDeniedException("User $user does not have any project");
        }

        return $this->redirectToRoute(
            'project_units_list',
            [
                'project' => $projects[0]->getId(),
            ]
        );
    }
}
