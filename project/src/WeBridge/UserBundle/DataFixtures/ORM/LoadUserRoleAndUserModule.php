<?php

namespace WeBridge\UserBundle\DataFixtures\ORM;

use CondoBundle\Constant\RoleTypes;
use CondoBundle\Constant\Modules;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WeBridge\UserBundle\Entity\UserModule;
use WeBridge\UserBundle\Entity\RoleType;

class LoadUserRoleAndUserModule implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    private $userRoles = [];

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->loadUserRoles();
        $this->loadUserModules();
    }

    private function persistAndFlush($object)
    {
        $this->manager->persist($object);
        $this->manager->flush();
    }

    private function loadUserRoles()
    {
        $userRole1 = new RoleType();
        $userRole1->setName(RoleTypes::CONDOMINIUM);
        $this->persistAndFlush($userRole1);
        $this->userRoles[] = $userRole1;

        $userRole2 = new RoleType();
        $userRole2->setName(RoleTypes::SERVICE);
        $this->persistAndFlush($userRole2);
        $this->userRoles[] = $userRole2;
    }

    private function loadUserModules()
    {
        foreach (Modules::CONDO_MODULE as $key => $vaule) {
            $userModule = new UserModule();
            $userModule->setRoleType($this->userRoles[0])
                ->setName($vaule)
                ->setKey($key);
            $this->persistAndFlush($userModule);
        }

        foreach (Modules::SERVICE_MODULE as $key => $vaule) {
            $userModule = new UserModule();
            $userModule->setRoleType($this->userRoles[1])
                ->setName($vaule)
                ->setKey($key);
            $this->persistAndFlush($userModule);
        }
    }

    public function getOrder()
    {
        return 4;
    }
}
