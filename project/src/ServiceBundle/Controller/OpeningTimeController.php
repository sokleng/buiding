<?php

namespace ServiceBundle\Controller;

use CondoBundle\Entity\Service;
use CondoBundle\Entity\ServiceAvailability;
use CondoBundle\Entity\ServiceUnavailability;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ServiceBundle\Traits\HasServiceControllerUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * ShopItem controller.
 *
 * @Route("/{service}/opening-time")
 */
class OpeningTimeController extends Controller
{
    use HasServiceControllerUtils;

    /**
     * Lists the shop opening/closing times.
     *
     * @Route("/", name="service_openingTime_list")
     * @Method("GET")
     * @Template("ServiceBundle:OpeningTime:index.html.twig")
     *
     * @param Service $service
     *
     * @return array|Response
     */
    public function indexAction(Service $service)
    {
        $this->assertUserCanAccessService($service);

        $availabilities = $this->getServiceAvailabilityRepository()
            ->findBy(
                ['service' => $service],
                [
                    'dayOfTheWeekStart' => 'ASC',
                    'openingTime' => 'ASC',
                ]
            );

        $unavailabilities = $this->getServiceUnavailabilityRepository()
            ->findAllNotOutdatedForService($service)
            ->getQuery()
            ->getResult()
        ;

        return [
            'services' => $this->getManagerServices(),
            'service' => $service,
            'availabilities' => $availabilities,
            'unavailabilities' => $unavailabilities,
        ];
    }

    /**
     * Creates a new ShopItem entity.
     *
     * @Route("/new-availability", name="service_openingTime_availability_new")
     * @Method({"GET", "POST"})
     * @Template("ServiceBundle:OpeningTime/Availability:new.html.twig")
     *
     * @param Request $request
     * @param Service $service
     *
     * @return array|RedirectResponse
     */
    public function newAvailabilityAction(
        Request $request,
        Service $service
    ) {
        $this->assertUserCanAccessService($service);

        $availability = new ServiceAvailability();
        $availability->setService($service);
        $form = $this->createForm(
            'ServiceBundle\Form\ServiceAvailabilityType',
            $availability
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $availability->setOpeningTime($availability->getOpeningTime());
            $availability->setClosingTime($availability->getClosingTime());
            $this->persistAndFlush($availability);

            return $this->redirectToRoute(
                'service_openingTime_list',
                [
                    'service' => $service->getId(),
                ]
            );
        }

        return [
            'services' => $this->getManagerServices(),
            'service' => $service,
            'availability' => $availability,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new ShopItem entity.
     *
     * @Route("/new-unavailability", name="service_openingTime_unavailability_new")
     * @Method({"GET", "POST"})
     * @Template("ServiceBundle:OpeningTime/Unavailability:new.html.twig")
     *
     * @param Request $request
     * @param Service $service
     *
     * @return array|RedirectResponse
     */
    public function newUnavailabilityAction(
        Request $request,
        Service $service
    ) {
        $this->assertUserCanAccessService($service);

        $unavailability = new ServiceUnavailability();
        $timezone = $this->getParameter('app_display_timezone');
        $offset = (new \DateTimeZone($timezone))->getOffset(new \DateTime());
        $tzInterval = \DateInterval::createFromDateString("$offset seconds");
        $unavailability->getStartDateTime()->sub($tzInterval);
        $unavailability->getEndDateTime()->sub($tzInterval);
        $unavailability->setService($service);
        $form = $this->createForm(
            'ServiceBundle\Form\ServiceUnavailabilityType',
            $unavailability,
            ['view_timezone' => $timezone]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->persistAndFlush($unavailability);

            return $this->redirectToRoute(
                'service_openingTime_list',
                [
                    'service' => $service->getId(),
                ]
            );
        }

        return [
            'services' => $this->getManagerServices(),
            'service' => $service,
            'unavailability' => $unavailability,
            'form' => $form->createView(),
        ];
    }

    /**
     * Deletes a service availability.
     *
     * @Route("/availability/{availability}", name="service_availability_delete")
     * @Method("DELETE")
     *
     * @param Service             $service
     * @param ServiceAvailability $availability
     *
     * @return RedirectResponse
     */
    public function deleteAvailabilityAction(
        Service $service,
        ServiceAvailability $availability
    ) {
        $this->assertUserCanAccessService(
            $service,
            $availability
        );

        $this->removeAndFlush($availability);

        return $this->redirectToRoute(
            'service_openingTime_list',
            [
                'service' => $service->getId(),
            ]
        );
    }

    /**
     * Switches the enabled of the given availability.
     *
     * @Route("/availability/{availability}/enabled", name="service_availability_enable")
     * @Method("PATCH")
     *
     * @param Service             $service
     * @param ServiceAvailability $availability
     *
     * @return RedirectResponse
     */
    public function enableAvailabilityAction(
        Service $service,
        ServiceAvailability $availability
    ) {
        $this->assertUserCanAccessService(
            $service,
            $availability
        );

        $availability->setEnabled(!$availability->isEnabled());
        $this->persistAndFlush($availability);

        return $this->redirectToRoute(
            'service_openingTime_list',
            [
                'service' => $service->getId(),
            ]
        );
    }

    /**
     * Deletes a service unavailability.
     *
     * @Route("/unavailability/{unavailability}", name="service_unavailability_delete")
     * @Method("DELETE")
     *
     * @param Service               $service
     * @param ServiceUnavailability $unavailability
     *
     * @return RedirectResponse
     */
    public function deleteUnavailabilityAction(
        Service $service,
        ServiceUnavailability $unavailability
    ) {
        $this->assertUserCanAccessService(
            $service,
            $unavailability
        );

        $this->removeAndFlush($unavailability);

        return $this->redirectToRoute(
            'service_openingTime_list',
            [
                'service' => $service->getId(),
            ]
        );
    }

    /**
     * Switches the enabled of the given unavailability.
     *
     * @Route("/unavailability/{unavailability}/enabled", name="service_unavailability_enable")
     * @Method("PATCH")
     *
     * @param Service               $service
     * @param ServiceUnavailability $unavailability
     *
     * @return RedirectResponse
     */
    public function enableUnavailabilityAction(
        Service $service,
        ServiceUnavailability $unavailability
    ) {
        $this->assertUserCanAccessService(
            $service,
            $unavailability
        );

        $unavailability->setEnabled(!$unavailability->isEnabled());
        $this->persistAndFlush($unavailability);

        return $this->redirectToRoute(
            'service_openingTime_list',
            [
                'service' => $service->getId(),
            ]
        );
    }
}
