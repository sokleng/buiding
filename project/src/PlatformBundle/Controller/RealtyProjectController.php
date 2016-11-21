<?php

namespace PlatformBundle\Controller;

use CondoBundle\Traits\HasControllerUtils;
use ProjectRealtyBundle\Entity\CondominiumProject;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use WeBridge\UserBundle\Entity\User;

/**
 * @Route("/projects")
 */
class RealtyProjectController extends Controller
{
    use HasControllerUtils;

    /**
     * Lists all Realty Projects.
     *
     * @Route("/", name="platform_project_list")
     * @Method("GET")
     * @Template("PlatformBundle:project:list.html.twig")
     */
    public function indexAction()
    {
        $projects = $this->getCondominiumProjectRepository()
            ->findAll();

        return [
            'projects' => $projects,
        ];
    }

    /**
     * Creates a new Realty Project.
     *
     * @Route("/new", name="platform_project_new")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:project:new.html.twig")
     *
     * @param Request $request
     *
     * @return array|RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $project = new CondominiumProject();
        $form = $this->createForm(
            'PlatformBundle\Form\CondominiumProjectType',
            $project
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($project);

            return $this->redirectToRoute(
                'platform_project_show',
                ['project' => $project->getId()]
            );
        }

        return [
            'project' => $project,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ServiceProvider entity.
     *
     * @Route("/{project}", name="platform_project_show")
     * @Method("GET")
     * @Template("PlatformBundle:project:show.html.twig")
     *
     * @param CondominiumProject $project
     *
     * @return array|Response
     */
    public function showAction(CondominiumProject $project)
    {
        return [
            'project' => $project,
        ];
    }

    /**
     * Displays a form to edit an existing project entity.
     *
     * @Route("/{project}/edit", name="platform_project_edit")
     * @Method({"GET", "POST"})
     * @Template("PlatformBundle:project:edit.html.twig")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     *
     * @return array|RedirectResponse|Response
     */
    public function editAction(Request $request, CondominiumProject $project)
    {
        $editForm = $this->createForm(
            'PlatformBundle\Form\CondominiumProjectType',
            $project
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute(
                'platform_project_edit',
                ['project' => $project->getId()]
            );
        }

        return [
            'project' => $project,
            'form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ServiceProvider entity.
     *
     * @Route("/{project}", name="platform_project_delete")
     * @Method("DELETE")
     *
     * @param CondominiumProject $project
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        CondominiumProject $project
    ) {
        $this->removeAndFlush($project);

        return $this->redirectToRoute('platform_serviceProvider_list');
    }

    /**
     * @Route("/{project}/managers", name="platform_project_managers_new")
     * @Method("POST")
     *
     * @param Request            $request
     * @param CondominiumProject $project
     *
     * @return RedirectResponse
     */
    public function addNewMember(Request $request, CondominiumProject $project)
    {
        $managerUsername = $request->get('username');

        /* @var User $member */
        $manager = $this->getUserRepository()
            ->findOneUserByPhoneOrEmail($managerUsername);

        if (!empty($manager)) {
            $project->addManager($manager);
            $this->persistAndFlush($project);

            // Checks memberships AFTER ADD too add/remove service role if needed.
            $this->getUserRepository()
                ->updateProjectRoleForUser(
                    $manager,
                    $this->get('security.token_storage')
                );
        }

        return $this->redirectToRoute(
            'platform_project_show',
            [
                'project' => $project->getId(),
            ]
        );
    }

    /**
     * @Route("/{project}/managers/{manager}", name="platform_project_managers_delete")
     * @Method("DELETE")
     *
     * @param CondominiumProject $project
     * @param User               $manager
     *
     * @return RedirectResponse
     */
    public function deleteManager(CondominiumProject $project, User $manager)
    {
        $project->removeManager($manager);
        $this->persistAndFlush($project);

        // Checks memberships AFTER DELETE too add/remove service role if needed.
        $this->getUserRepository()
            ->updateProjectRoleForUser(
                $manager,
                $this->get('security.token_storage')
            );

        return $this->redirectToRoute(
            'platform_project_show',
            [
                'project' => $project->getId(),
            ]
        );
    }
}
