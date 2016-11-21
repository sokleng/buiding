<?php

namespace RealtyCompanyBundle\Controller;

use RealtyCompanyBundle\Entity\CompanyContact;
use RealtyCompanyBundle\Entity\RealtyCompany;
use RealtyCompanyBundle\Traits\HasRealtyControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use CondoBundle\Entity\DatabaseFile;

/**
 * @Route("/{company}/contacts")
 */
class ContactController extends Controller
{
    use HasRealtyControllerUtils;

    /**
     * @Route("/", name="company_contacts_list")
     * @Method("GET")
     * @Template("RealtyCompanyBundle:Contact:list.html.twig")
     *
     * @param RealtyCompany $company
     *
     * @return array
     */
    public function listAction(
        RealtyCompany $company
    ) {
        $this->assertCanAccessRealtyCompany($company);

        return $this->getResponseParameters(
            [
                'company' => $company,
            ]
        );
    }

    /**
     * @Route("/new", name="company_contacts_new")
     * @Method({"GET", "POST"})
     * @Template("RealtyCompanyBundle:Contact:new.html.twig")
     *
     * @param Request       $request
     * @param RealtyCompany $company
     *
     * @return array|RedirectResponse
     */
    public function newAction(
        Request $request,
        RealtyCompany $company
    ) {
        $this->assertCanAccessRealtyCompany($company);

        $contact = new CompanyContact();

        $form = $this->createForm(
            'RealtyCompanyBundle\Form\CompanyContactType',
            $contact
        );
        $form->handleRequest($request);

        if (!$form->isSubmitted() && !$form->isValid()) {
            return $this->getResponseParameters(
                [
                    'company' => $company,
                    'contact' => $contact,
                    'form' => $form->createView(),
                ]
            );
        }
        $files = $form->get('databaseFiles')->getData();
        $description = $form->get('description')->getData();

        $this->persistAndFlush($contact);
        if (!empty($files)) {
            foreach ($files as $file) {
                if ($file === null) {
                    continue;
                }
                $databaseFile = new DatabaseFile($file);
                $databaseFile->setContact($contact);
                $databaseFile->setDescription($description);
                $this->persistAndFlush($databaseFile);
            }
        }

        $company->getContacts()->add($contact);
        $this->persistAndFlush($company);

        return $this->redirectToRoute(
            'company_contacts_list',
            ['company' => $company->getId()]
        );
    }

    /**
     * Finds and displays a Company Contact entity.
     *
     * @Route("/{contact}", name="company_contacts_show")
     * @Method("GET")
     * @Template("RealtyCompanyBundle:Contact:show.html.twig")
     *
     * @param RealtyCompany  $company
     * @param CompanyContact $contact
     *
     * @return array
     */
    public function showAction(
        RealtyCompany $company,
        CompanyContact $contact
    ) {
        $this->assertCanAccessRealtyCompany($company, $contact);

        return $this->getResponseParameters([
            'company' => $company,
            'contact' => $contact,
        ]);
    }

    /**
     * Displays a form to edit an existing Company Contact entity.
     *
     * @Route("/{contact}/edit", name="company_contacts_edit")
     * @Method({"GET", "POST"})
     * @Template("RealtyCompanyBundle:Contact:edit.html.twig")
     *
     * @param Request        $request
     * @param RealtyCompany  $company
     * @param CompanyContact $contact
     *
     * @return array|RedirectResponse
     */
    public function editAction(
        Request $request,
        RealtyCompany $company,
        CompanyContact $contact
    ) {
        $this->assertCanAccessRealtyCompany($company, $contact);

        $editForm = $this->createForm(
            'RealtyCompanyBundle\Form\CompanyContactType',
            $contact
        );
        $editForm->handleRequest($request);

        if (!$editForm->isSubmitted() && !$editForm->isValid()) {
            return $this->getResponseParameters([
                'company' => $company,
                'contact' => $contact,
                'edit_form' => $editForm->createView(),
            ]);
        }

        $this->persistAndFlush($company);

        return $this->redirectToRoute(
            'company_contacts_edit',
            [
                'company' => $company->getId(),
                'contact' => $contact->getId(),
            ]
        );
    }

    /**
     * Deletes a contact.
     *
     * @Route("/{contact}", name="company_contacts_delete")
     * @Method("DELETE")
     *
     * @param RealtyCompany  $company
     * @param CompanyContact $contact
     *
     * @return RedirectResponse
     */
    public function deleteAction(
        RealtyCompany $company,
        CompanyContact $contact
    ) {
        $this->assertCanAccessRealtyCompany($company, $contact);

        $this->removeAndFlush($contact);

        return $this->redirectToRoute(
            'company_contacts_list',
            ['company' => $company->getId()]
        );
    }
}
