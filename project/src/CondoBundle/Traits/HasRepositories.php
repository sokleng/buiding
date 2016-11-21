<?php

namespace CondoBundle\Traits;

use CondoBundle\Repository\CityRepository;
use CondoBundle\Repository\ClientUnitRepository;
use CondoBundle\Repository\CondominiumNewsRepository;
use CondoBundle\Repository\CondominiumRepository;
use CondoBundle\Repository\CountryRepository;
use CondoBundle\Repository\DatabaseFileRepository;
use CondoBundle\Repository\DistrictRepository;
use CondoBundle\Repository\FeedbackRepository;
use CondoBundle\Repository\IssueRepository;
use CondoBundle\Repository\NewsRepository;
use CondoBundle\Repository\ResidentRepository;
use CondoBundle\Repository\ServiceAvailabilityRepository;
use CondoBundle\Repository\ServiceProviderRepository;
use CondoBundle\Repository\ServiceRepository;
use CondoBundle\Repository\ServiceUnavailabilityRepository;
use CondoBundle\Repository\UnitRepository;
use CondoBundle\Repository\UnitTypeRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectRepository;
use GenericOrderingServiceBundle\Repository\GenericOrderRepository;
use GenericOrderingServiceBundle\Repository\ShoppingCartRepository;
use ProjectRealtyBundle\Repository\CondominiumProjectRepository;
use ProjectRealtyBundle\Repository\CondoProjectListingProfileRepository;
use ProjectRealtyBundle\Repository\ProjectPaymentRepository;
use ProjectRealtyBundle\Repository\ProjectUnitRepository;
use ProjectRealtyBundle\Repository\ProjectUnitTypeRepository;
use RealtyCompanyBundle\Repository\RealtyCompanyRepository;
use WeBridge\UserBundle\Entity\UserRepository;
use WeBridge\UserBundle\Repository\UserModuleRepository;
use WeBridge\UserBundle\Repository\RoleTypeRepository;

trait HasRepositories
{
    /**
     * @return CountryRepository|ObjectRepository
     */
    public function getCountryRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Country');
    }

    /**
     * @return CityRepository|ObjectRepository
     */
    public function getCityRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:City');
    }

    /**
     * @return ClientUnitRepository|ObjectRepository
     */
    public function getClientUnitRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:ClientUnit');
    }

    /**
     * @return DistrictRepository|ObjectRepository
     */
    public function getDistrictRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:District');
    }

    /**
     * @return UserRepository|ObjectRepository
     */
    public function getUserRepository()
    {
        return $this->getDoctrine()->getRepository('WeBridgeUserBundle:User');
    }

    /**
     * @return RoleTypeRepository|ObjectRepository
     */
    public function getRoleTypeRepository()
    {
        return $this->getDoctrine()->getRepository('WeBridgeUserBundle:RoleType');
    }

    /**
     * @return UserModuleRepository|ObjectRepository
     */
    public function getUserModuleRepository()
    {
        return $this->getDoctrine()->getRepository('WeBridgeUserBundle:UserModule');
    }

    /**
     * @return ServiceRepository|ObjectRepository
     */
    public function getServiceRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Service');
    }

    /**
     * @return UnitTypeRepository|ObjectRepository
     */
    public function getUnitTypeRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:UnitType');
    }

    /**
     * @return ServiceProviderRepository|ObjectRepository
     */
    public function getServiceProviderRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:ServiceProvider');
    }

    /**
     * @return ServiceAvailabilityRepository|ObjectRepository
     */
    public function getServiceAvailabilityRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:ServiceAvailability');
    }

    /**
     * @return ServiceUnavailabilityRepository|ObjectRepository
     */
    public function getServiceUnavailabilityRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:ServiceUnavailability');
    }

    /**
     * @return ResidentRepository|ObjectRepository
     */
    public function getResidentRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Resident');
    }

    /**
     * @return CondominiumRepository|ObjectRepository
     */
    public function getCondominiumRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Condominium');
    }

    /**
     * @return UnitRepository|ObjectRepository
     */
    public function getUnitRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Unit');
    }

    /**
     * @return ShoppingCartRepository|ObjectRepository
     */
    public function getShoppingCartRepository()
    {
        return $this->getDoctrine()->getRepository(
            'GenericOrderingServiceBundle:ShoppingCart'
        );
    }

    /**
     * @return GenericOrderRepository|ObjectRepository
     */
    public function getGenericOrderRepository()
    {
        return $this->getDoctrine()->getRepository(
            'GenericOrderingServiceBundle:GenericOrder'
        );
    }

    /**
     * @return DatabaseFileRepository|ObjectRepository
     */
    public function getDatabaseFileRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:DatabaseFile');
    }

    /**
     * @return CondominiumNewsRepository|ObjectRepository
     */
    public function getCondominiumNewsRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:CondominiumNews');
    }

    /**
     * @return NewsRepository|ObjectRepository
     */
    public function getNewsRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:News');
    }

    /**
     * @return IssueRepository|ObjectRepository
     */
    public function getIssueRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Issue');
    }

    /**
     * @return FeedbackRepository|ObjectRepository
     */
    public function getFeedbackRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Feedback');
    }

    /**
     * @return CondominiumProjectRepository|ObjectRepository
     */
    public function getCondominiumProjectRepository()
    {
        return $this->getDoctrine()->getRepository('ProjectRealtyBundle:CondominiumProject');
    }

    /**
     * @return ProjectUnitTypeRepository|ObjectRepository
     */
    public function getProjectUnitTypeRepository()
    {
        return $this->getDoctrine()->getRepository('ProjectRealtyBundle:ProjectUnitType');
    }

    /**
     * @return ProjectUnitRepository|ObjectRepository
     */
    public function getProjectUnitRepository()
    {
        return $this->getDoctrine()->getRepository('ProjectRealtyBundle:ProjectUnit');
    }

    /**
     * @return ProjectPaymentRepository|ObjectRepository
     */
    public function getProjectPaymentRepository()
    {
        return $this->getDoctrine()->getRepository('ProjectRealtyBundle:ProjectPayment');
    }

    /**
     * @return RealtyCompanyRepository|ObjectRepository
     */
    public function getRealtyCompanyRepository()
    {
        return $this->getDoctrine()->getRepository('RealtyCompanyBundle:RealtyCompany');
    }

    /**
     * @return \CondoBundle\Repository\DeveloperRepository|ObjectRepository
     */
    public function getDeveloperRepository()
    {
        return $this->getDoctrine()->getRepository('DeveloperBundle:Developer');
    }

    /**
     * @return DeveloperRepository|ObjectRepository
     */
    public function getDeveloperPaymentRepository()
    {
        return $this->getDoctrine()->getRepository('DeveloperBundle:DeveloperPayment');
    }

    /**
     * @return CondoProjectListingProfileRepository|ObjectRepository
     */
    public function getCondoProjectListingProfileRepository()
    {
        return $this->getDoctrine()->getRepository('ProjectRealtyBundle:CondoProjectListingProfile');
    }

    /**
     * @return IssueRepository|ObjectRepository
     */
    public function getExpendCategoryRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:ExpendCategory');
    }

    /**
     * @return IncomeCategoryRepository|ObjectRepository
     */
    public function getIncomeCategoryRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:IncomeCategory');
    }

    /**
     * @return IssueRepository|ObjectRepository
     */
    public function getExpendInvoiceRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Expend');
    }

    /**
     * @return IncomeRepository|ObjectRepository
     */
    public function getIncomeRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Income');
    }

    /**
     * @return IncomeRepository|ObjectRepository
     */
    public function getInvoiceRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Invoice');
    }

    /**
     * @return CurrencyRepository|ObjectRepository
     */
    public function getCurrencyRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Currency');
    }

    /**
     * @return SupplierRepository|ObjectRepository
     */
    public function getSupplierRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:Supplier');
    }

    /**
     * @return CompanySupplierRepository|ObjectRepository
     */
    public function getCompanySupplierRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:CompanySupplier');
    }

    /**
     * @return IndividualSupplierRepository|ObjectRepository
     */
    public function getIndividualSupplierRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:IndividualSupplier');
    }
    /**
     * @return ExchangeSettingRepository|ObjectRepository
     */
    public function getExchangeSettingRepository()
    {
        return $this->getDoctrine()->getRepository('CondoBundle:ExchangeSetting');
    }

    /**
     * @return Registry
     */
    abstract public function getDoctrine();
}
