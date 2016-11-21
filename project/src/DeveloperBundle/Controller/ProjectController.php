<?php

namespace DeveloperBundle\Controller;

use CondoBundle\Traits\HasPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use DeveloperBundle\Entity\Developer;
use DeveloperBundle\Traits\HasDeveloperControllerUtils;
use ProjectRealtyBundle\Entity\CondominiumProject;

class ProjectController extends Controller
{
    use HasDeveloperControllerUtils;
    use HasPagination;

    /**
     * @Route("/", name="developer_home")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        /** @var Developer[] $developers */
        $developers = $this->getDeveloperRepository()
        ->findDevelopersByManager($user)
        ->getQuery()
        ->getResult();

        if (empty($developers)) {
            throw new AccessDeniedException("User $user does not have any developer");
        }

        return $this->redirectToRoute(
            'developer_projects_list',
            ['developer' => $developers[0]->getId()]
        );
    }

    /**
     * @Route("/{developer}/projects", name="developer_projects_list")
     * @Template("DeveloperBundle:Project:list.html.twig")
     * @Method("GET")
     *
     * @param Request   $request
     * @param Developer $developer
     *
     * @return array
     */
    public function listAction(Request $request, Developer $developer)
    {
        $this->assertCanAccessDeveloper($developer);

        /** @var CondominiumProject[] $projects */
        $projects = $this->getCondominiumProjectRepository()
            ->findProjectsByDeveloper($developer)
            ->getQuery()
            ->getResult();

        $projectsPagination = $this->createPagination(
            $projects,
            $request
        );

        return $this->getResponseParameters([
            'projects' => $projectsPagination,
            'developer' => $developer,
        ]);
    }

    /**
     * Creates a new Developer Project.
     *
     * @Route("/{developer}/project/new", name="developer_projects_new")
     * @Method({"GET", "POST"})
     * @Template("DeveloperBundle:Project:new.html.twig")
     *
     * @param Request   $request
     * @param Developer $developer
     *
     * @return array|RedirectResponse
     */
    public function newAction(Request $request, Developer $developer)
    {
        $this->assertCanAccessDeveloper($developer);

        $project = new CondominiumProject();
        $form = $this->createForm(
            'DeveloperBundle\Form\DeveloperProjectType',
            $project
        );
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters([
                'developer' => $developer,
                'form' => $form->createView(),
            ]);
        }

        $project->setDeveloper($developer);
        $this->persistAndFlush($project);

        return $this->redirectToRoute(
            'developer_projects_list',
            ['developer' => $developer->getId()]
        );
    }

    /**
     * Finds and displays a Developer Project.
     *
     * @Route("/{developer}/project/{project}", name="developer_project_show")
     * @Method("GET")
     * @Template("DeveloperBundle:Project:show.html.twig")
     *
     * @param Developer          $developer
     * @param CondominiumProject $project
     *
     * @return array|Response
     */
    public function showAction(Developer $developer, CondominiumProject $project)
    {
        $this->assertCanAccessDeveloper($developer, $project);

        return $this->getResponseParameters([
            'developer' => $developer,
            'project' => $project,
        ]);
    }

    /**
     * Deletes a Developer Project.
     *
     * @Route("/{developer}/project/delete/{project}", name="developer_project_delete")
     * @Method("DELETE")
     *
     * @param Developer          $developer
     * @param CondominiumProject $project
     *
     * @return RedirectResponse
     */
    public function deleteAction(Developer $developer, CondominiumProject $project)
    {
        $this->assertCanAccessDeveloper($developer, $project);

        $this->removeAndFlush($project);
    }

    /**
     * Displays a form to edit an existing developer project.
     *
     * @Route("/{developer}/project/{project}/edit", name="developer_project_edit")
     * @Method({"GET", "POST"})
     * @Template("DeveloperBundle:Project:edit.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     * @param Developer          $developer
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        Developer $developer,
        CondominiumProject $project
    ) {
        $this->assertCanAccessDeveloper($developer, $project);

        $editForm = $this->createForm(
            'DeveloperBundle\Form\DeveloperProjectType',
            $project
        );
        $editForm->handleRequest($request);

        if (!$editForm->isSubmitted() && !$editForm->isValid()) {
            return $this->getResponseParameters([
                'developer' => $developer,
                'project' => $project,
                'edit_form' => $editForm->createView(),
            ]);
        }

        $project->setDeveloper($developer);
        $this->persistAndFlush($project);

        return $this->redirectToRoute(
            'developer_projects_list',
            ['developer' => $developer->getId()]
        );
    }
}
