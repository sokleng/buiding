<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        date_default_timezone_set('Asia/Phnom_Penh');
        parent::__construct($environment, $debug);
    }

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new AppBundle\AppBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \WeBridge\UserBundle\WeBridgeUserBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \FOS\UserBundle\FOSUserBundle(),
            new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new \JMS\TranslationBundle\JMSTranslationBundle(),
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new CondoBundle\CondoBundle(),
            new GenericOrderingServiceBundle\GenericOrderingServiceBundle(),
            new PlatformBundle\PlatformBundle(),
            new ClientBundle\ClientBundle(),
            new ServiceBundle\ServiceBundle(),
            new CondominiumManagementBundle\CondominiumManagementBundle(),
            new ProjectRealtyBundle\ProjectRealtyBundle(),
            new Pinano\Select2Bundle\PinanoSelect2Bundle(),
            new DeveloperBundle\DeveloperBundle(),
            new RealtyCompanyBundle\RealtyCompanyBundle(),
            new FrontendBundle\FrontendBundle(),
            new blackknight467\StarRatingBundle\StarRatingBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(
            $this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml'
        );
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs/'.$this->getEnvironment();
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }
}
