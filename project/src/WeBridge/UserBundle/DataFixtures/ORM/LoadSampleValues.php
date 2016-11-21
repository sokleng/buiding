<?php

namespace WeBridge\UserBundle\DataFixtures\ORM;

use CondoBundle\Constant\SecurityRole;
use CondoBundle\Constant\ServiceType;
use CondoBundle\Entity\City;
use CondoBundle\Entity\Condominium;
use CondoBundle\Entity\CondominiumNews;
use CondoBundle\Entity\Country;
use CondoBundle\Entity\District;
use CondoBundle\Entity\Resident;
use CondoBundle\Entity\Service;
use CondoBundle\Entity\ServiceAvailability;
use CondoBundle\Entity\ServiceProvider;
use CondoBundle\Entity\Unit;
use CondoBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use GenericOrderingServiceBundle\Entity\ShopItem;
use ProjectRealtyBundle\Entity\CondominiumProject;
use RealtyCompanyBundle\Entity\RealtyCompany;
use WeBridge\UserBundle\Entity\User;
use DeveloperBundle\Entity\Developer;

class LoadSampleValues extends AbstractFixture implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    private $districts = [];
    private $condos = [];
    private $condoManager;
    private $serviceManager;
    private $superAdmin;
    private $developers = [];

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->superAdmin = $manager->getRepository('WeBridgeUserBundle:User')
            ->findOneBy(['username' => 'admin@webridge.asia']);
        $this->loadPlatformAreaSettings();
        $this->loadCondominiums();
        $this->loadServices();
        $this->loadCondoNews();
        $this->loadDevelopers();
        $this->loadProjects();
        $this->loadResidences();
        $this->loadRealtyCompany();
    }

    private function persistAndFlush($object)
    {
        $this->manager->persist($object);
        $this->manager->flush();
    }

    private function loadPlatformAreaSettings()
    {
        $country = new Country();
        $country->setCode('km-KH')
            ->setName('Cambodia');
        $this->persistAndFlush($country);

        $city = new City();
        $city->setName('Phnom Penh')
            ->setCountry($country);
        $this->persistAndFlush($city);

        foreach (['BKK1', 'Toul Kouk'] as $districtName) {
            $district = new District();
            $district->setCity($city)
                ->setName($districtName);
            $this->persistAndFlush($district);
            $this->districts[] = $district;
        }
    }

    private function loadCondominiums()
    {
        $condoManager = new User();
        $condoManager->setName('Condo Manager')
            ->setEmail('condo@webridge.asia')
            ->setEnabled(true)
            ->setPlainPassword('condo')
            ->setRoles([SecurityRole::CONDOMINIUM, SecurityRole::USER])
            ->setUsername('condo@webridge.asia');
        $this->persistAndFlush($condoManager);
        $this->condoManager = $condoManager;

        $condo = new Condominium();
        $condo->setName('De Castle Royal')
            ->setAddress('st 288')
            ->setIsVat(false)
            ->setRate(11)
            ->setVatTin(123)
            ->setDistrict($this->districts[0])
            ->setCurrency($this->getReference('currency-usd'));
        $condo->addManager($condoManager);
        $condo->addManager($this->superAdmin);
        $this->persistAndFlush($condo);
        $this->condos[] = $condo;

        $condo2 = new Condominium();
        $condo2->setName('Noblesse Residences')
            ->setAddress('Somewhere')
            ->setIsVat(false)
            ->setRate(11)
            ->setVatTin(123)
            ->setDistrict($this->districts[1])
            ->setCurrency($this->getReference('currency-usd'));
        $condo2->addManager($condoManager);
        $condo2->addManager($this->superAdmin);
        $this->persistAndFlush($condo2);
        $this->condos[] = $condo2;
    }

    private function loadServices()
    {
        $serviceManager = new User();
        $serviceManager->setName('Service Manager')
            ->setEmail('service@webridge.asia')
            ->setEnabled(true)
            ->setPlainPassword('service')
            ->setRoles([SecurityRole::SERVICE, SecurityRole::USER])
            ->setUsername('service@webridge.asia');
        $this->persistAndFlush($serviceManager);
        $this->serviceManager = $serviceManager;

        $serviceProvider = new ServiceProvider();
        $serviceProvider->setCompanyName('DelivR Co Ltd')
            ->setDescription('We deliver stuff')
            ->setContactNumber('021312311')
            ->setIsVat(false)
            ->setRate(11)
            ->setVatTin(123)
            ->addManager($serviceManager)
            ->addManager($this->superAdmin);
        $this->persistAndFlush($serviceProvider);

        $this->loadWaterService($serviceProvider, $serviceManager);
        $this->loadGasService($serviceProvider, $serviceManager);
    }

    private function loadGasService(
        ServiceProvider $sp,
        User $sm
    ) {
        $service = new Service();
        $service->setTitle('Gas Delivery')
            ->setDescription('Deliver Gas bottles to you! (mostly) Safely')
            ->setServiceProvider($sp)
            ->addManager($sm)
            ->addManager($this->superAdmin)
            ->setType(ServiceType::GENERIC_ORDERING);
        foreach ($this->condos as $condo) {
            $service->addCondominium($condo);
        }
        $this->persistAndFlush($service);

        $shopItem = new ShopItem();
        $shopItem->setName('100L Gas Bottle')
            ->setDescription(
                'You can live forever with that, but don\'t smoke around it!'
            )
            ->setPrice(50)
            ->setReference('GASB100')
            ->setService($service)
            ->setEnabled(true);
        $this->persistAndFlush($shopItem);

        $shopItem = new ShopItem();
        $shopItem->setName('5L Gas Bottle')
            ->setDescription('Mini gas bottle, easy to carry')
            ->setPrice(15)
            ->setReference('GASB5')
            ->setService($service)
            ->setEnabled(true);
        $this->persistAndFlush($shopItem);

        $sa = new ServiceAvailability();
        $sa->setService($service);
        $sa->setDayRange(1, 7);
        $sa->setTimeRange(420 - 420, 1200 - 420);
        $sa->setEnabled(true);
        $this->persistAndFlush($sa);

        return $service;
    }

    private function loadWaterService(
        ServiceProvider $sp,
        User $sm
    ) {
        $service = new Service();
        $service->setTitle('Water Delivery')
            ->setDescription('Deliver water bottles to you! Amazing')
            ->setServiceProvider($sp)
            ->addManager($sm)
            ->addManager($this->superAdmin)
            ->setType(ServiceType::GENERIC_ORDERING);
        foreach ($this->condos as $condo) {
            $service->addCondominium($condo);
        }
        $this->persistAndFlush($service);

        $shopItem = new ShopItem();
        $shopItem->setName('7L Water Bottle')
            ->setDescription('7L of amazing spring water in a bottle')
            ->setPrice(2)
            ->setReference('WATB7')
            ->setService($service)
            ->setEnabled(true);
        $this->persistAndFlush($shopItem);

        $shopItem = new ShopItem();
        $shopItem->setName('5L Water Bottle')
            ->setDescription('5L of amazing spring water in a bottle')
            ->setPrice(1.5)
            ->setReference('WATB5')
            ->setService($service)
            ->setEnabled(true);
        $this->persistAndFlush($shopItem);

        $sa = new ServiceAvailability();
        $sa->setService($service);
        $sa->setDayRange(1, 7);
        $sa->setTimeRange(540 - 420, 960 - 420);
        $sa->setEnabled(true);
        $this->persistAndFlush($sa);

        return $service;
    }

    private function loadCondoNews()
    {
        foreach ($this->condos as $condo) {
            $news = new CondominiumNews();
            $news->setCondominium($condo)
                ->setAuthor($this->condoManager)
                ->setTitle('The first news of '.$condo->getName())
                ->setShortDescription(
                    'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin lobortis odio ipsum. Aenean mollis lorem vitae leo eleifend, ac placerat lectus mollis. Suspendisse potenti. Cras scelerisque at dolor eget porta. In euismod nibh et risus tristique fermentum. Nulla vel pellentesque magna. Vivamus eleifend tempus semper. Ut condimentum condimentum quam sit amet mollis. Vestibulum bibendum tortor a pulvinar dictum. Integer scelerisque arcu porta, blandit velit vitae, lacinia diam. Vestibulum lobortis porta ornare. Vestibulum id tortor fermentum, consectetur eros ut, iaculis nisi. Vestibulum sagittis suscipit est a congue. Integer id justo libero. '
                );
            $this->persistAndFlush($news);

            $news = new CondominiumNews();
            $news->setCondominium($condo)
                ->setAuthor($this->condoManager)
                ->setTitle('The second news of '.$condo->getName())
                ->setShortDescription(
                    'Sed vulputate felis ac faucibus faucibus. Maecenas ullamcorper, ipsum non ultricies egestas, ante justo vulputate velit, non venenatis metus turpis vel sem. In consequat, metus at pellentesque molestie, diam diam efficitur risus, at ultrices erat nisi nec est. Phasellus eget tristique quam.'
                );
            $this->persistAndFlush($news);
        }
    }

    private function loadProjects()
    {
        $manager = new User();
        $manager->setName('Project Manager')
            ->setEmail('project@webridge.asia')
            ->setEnabled(true)
            ->setPlainPassword('project')
            ->setRoles([SecurityRole::PROJECT, SecurityRole::USER])
            ->setUsername('project@webridge.asia');
        $this->persistAndFlush($manager);

        $project = new CondominiumProject();
        $project->setName('The View')
            ->setDescription('The view condominium project description')
            ->setContactNumber('012518111')
            ->setContactName('Someone1')
            ->setAddress('Somewhere in Bkk1')
            ->setDeveloper($this->developers[0])
            ->addManager($this->superAdmin)
            ->addManager($manager)
            ->setFloorCount(30)
            ->setDistrict($this->districts[0]);
        $this->persistAndFlush($project);
    }

    private function loadResidences()
    {
        $unit = new Unit();
        $unit->setCondominium($this->condos[0])
            ->setFloor('11')
            ->setRoomNumber('1101')
            ->setPrice('222')
        ;
        $this->persistAndFlush($unit);

        $resident = new Resident();
        $resident->setUnit($unit)
            ->setUser($this->superAdmin);
        $this->persistAndFlush($resident);

        $unit = new Unit();
        $unit->setCondominium($this->condos[1])
            ->setFloor('18')
            ->setRoomNumber('1802')
            ->setPrice('222')
        ;
        $this->persistAndFlush($unit);

        $resident = new Resident();
        $resident->setUnit($unit)
            ->setUser($this->superAdmin);
        $this->persistAndFlush($resident);
    }

    private function loadRealtyCompany()
    {
        $realtyCompanyNamager = new User();
        $realtyCompanyNamager->setName('RealtyCompany Manager')
            ->setEmail('realty@webridge.asia')
            ->setEnabled(true)
            ->setPlainPassword('realty')
            ->setRoles([SecurityRole::REALTY_COMPANY, SecurityRole::USER])
            ->setUsername('realty@webridge.asia');
        $this->persistAndFlush($realtyCompanyNamager);

        $realtyCompany = new RealtyCompany();
        $realtyCompany->setName('WE Bridge Technologies');
        $realtyCompany->setDescription('Soft ware company');
        $realtyCompany->addManager($this->superAdmin);
        $realtyCompany->addManager($realtyCompanyNamager);
        $realtyCompany->setContactNumber('23425424545');
        $this->persistAndFlush($realtyCompany);

        $realtyCompany = new RealtyCompany();
        $realtyCompany->setName('China company');
        $realtyCompany->setDescription('Urgency');
        $realtyCompany->addManager($this->superAdmin);
        $realtyCompany->addManager($realtyCompanyNamager);
        $realtyCompany->setContactNumber('23425424545');
        $this->persistAndFlush($realtyCompany);
    }

    private function loadDevelopers()
    {
        $developerManager = new User();
        $developerManager->setName('Developer Manager')
            ->setEmail('developer@webridge.asia')
            ->setEnabled(true)
            ->setPlainPassword('developer')
            ->setRoles([SecurityRole::DEVELOPER, SecurityRole::USER])
            ->setUsername('developer@webridge.asia');
        $this->persistAndFlush($developerManager);

        $developer = new Developer();
        $developer->setName('Developer 1');
        $developer->setDescription('Front End Developer');
        $developer->addManager($this->superAdmin);
        $developer->addManager($developerManager);
        $this->persistAndFlush($developer);
        $this->developers[] = $developer;

        $developer2 = new Developer();
        $developer2->setName('Developer 2');
        $developer2->setDescription('Backend Developer');
        $developer2->addManager($this->superAdmin);
        $developer2->addManager($developerManager);
        $this->persistAndFlush($developer2);
        $this->developers[] = $developer2;
    }

    public function getOrder()
    {
        return 3;
    }
}
