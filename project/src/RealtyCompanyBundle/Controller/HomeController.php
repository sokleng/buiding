<?php

namespace RealtyCompanyBundle\Controller;

use RealtyCompanyBundle\Entity\RealtyCompany;
use RealtyCompanyBundle\Traits\HasRealtyControllerUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class HomeController extends Controller
{
    use HasRealtyControllerUtils;
    /**
     * @Route("/", name="realty_company_home")
     */
    public function indexAction()
    {
        $user = $this->getUser();

        /** @var RealtyCompany[] $realtyCompanies */
        $realtyCompanies = $this->getRealtyCompanyRepository()
            ->findAllRealtyCompanyByManager($user);

        if (empty($realtyCompanies)) {
            throw new AccessDeniedException("User $user does not have any Realty Company");
        }

        /*redirect to manager page instead of blank*/
        return $this->redirectToRoute(
            'realty_managers_show',
            [
                'realtyCompany' => $realtyCompanies[0]->getId(),
            ]
        );
    }
}
