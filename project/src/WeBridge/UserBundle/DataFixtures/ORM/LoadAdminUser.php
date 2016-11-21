<?php

namespace WeBridge\UserBundle\DataFixtures\ORM;

use CondoBundle\Constant\SecurityRole;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WeBridge\UserBundle\Entity\User;

class LoadAdminUser implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $admin = $manager->getRepository('WeBridgeUserBundle:User')
            ->findOneBy([
               'username' => 'admin@webridge.asia',
            ]);

        if (empty($admin)) {
            // Admin user does not exist yet
            $admin = new User();
        }

        // Setting expected values
        $admin->setUsername('admin@webridge.asia');
        $admin->setEmail('admin@webridge.asia');
        $admin->setName('Administrator');
        $admin->setPlainPassword('admin');
        $admin->setRoles([
            SecurityRole::ADMIN,
            SecurityRole::CONDOMINIUM,
            SecurityRole::PROJECT,
            SecurityRole::SERVICE,
            SecurityRole::USER,
            SecurityRole::REALTY_COMPANY,
            SecurityRole::DEVELOPER,
        ]);
        $admin->setEnabled(true);

        $manager->persist($admin);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
