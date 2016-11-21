<?php

namespace RealtyCompanyBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use RealtyCompanyBundle\Entity\RealtyCompany;
use RealtyCompanyBundle\Traits\HasRealtyControllerUtils;

/**
 * CompanyController controller.
 *
 * @Route("/company")
 */
class CompanyController extends Controller
{
    use HasRealtyControllerUtils;

    /**
     * Displays a form to edit an existing RealtyCompany entity.
     *
     * @Route("/{company}/edit", name="realty_company_edit")
     * @Method({"GET", "POST"})
     * @Template("RealtyCompanyBundle:Company:edit.html.twig")
     *
     * @param Request       $request
     * @param RealtyCompany $company
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        RealtyCompany $company
    ) {
        $this->assertCanAccessRealtyCompany($company);

        $editForm = $this->createForm(
            'RealtyCompanyBundle\Form\RealtyCompanyType',
            $company
        );
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->persistAndFlush($company);

            return $this->redirectToRoute(
                'realty_company_home'
            );
        }

        return $this->getResponseParameters([
            'company' => $company,
            'edit_form' => $editForm->createView(),
        ]);
    }
}
