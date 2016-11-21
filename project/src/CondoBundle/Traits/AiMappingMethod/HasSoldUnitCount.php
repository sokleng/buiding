<?php

namespace CondoBundle\Traits\AiMappingMethod;

use ProjectRealtyBundle\Repository\CondominiumProjectRepository;
use ProjectRealtyBundle\Repository\ProjectUnitRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Tgallice\Wit\Model\Context;

/**
 * Class HasAvailableUnitCount.
 *
 * @method CondominiumProjectRepository getCondominiumProjectRepository()
 * @method ProjectUnitRepository        getProjectUnitRepository()
 *
 * @property TokenStorage $tokenStorage
 */
trait HasSoldUnitCount
{
    /**
     * Gets available units count for a given AI entity result.
     *
     * @param string  $sessionId
     * @param Context $context
     * @param array   $entities
     *
     * @return Context
     */
    public function getSoldUnitsCount(
        /* @noinspection PhpUnusedParameterInspection */
        $sessionId,
        Context $context,
        array $entities
    ) {
        $filter = [];

        // Checking if a condominium was provided
        if (isset($entities['condominium'][0]['value'])) {
            $condoStr = strtolower($entities['condominium'][0]['value']);

            // Finding condo
            $project = $this->getCondominiumProjectRepository()
                ->searchByNameForUser(
                    $condoStr,
                    $this->tokenStorage->getToken()->getUser()
                );

            // Condo not found, returning error
            if (empty($project)) {
                $context->add('missing_project', 1);

                return $context;
            }

            // Updating condo name to it's actual display name.
            $context->add('condominium', $project->getName());

            // Adding project filter
            $filter['project'] = $project;
        }

        // Checking if a unit_type was provided (project is needed as well)
        if (
            isset($filter['project']) &&
            isset($entities['unit_type'][0]['value'])
        ) {
            $unitTypeStr = strtolower($entities['unit_type'][0]['value']);

            // Finding Unit Type in Condo
            $unitType = null;
            foreach ($filter['project']->getUnitTypes() as $type) {
                if (strtolower($type->getCode()) === $unitTypeStr) {
                    $unitType = $type;
                    break;
                }
            }

            // Unit Type Requested not found, returning missing unit error.
            if (empty($unitType)) {
                $context->add('missing_unit', 1);

                return $context;
            }

            $filter['unitType'] = $unitType;
        }

        if (isset($entities['floor'][0]['value'])) {
            $filter['floor'] = $entities['floor'][0]['value'];
        }

        // Check if we have a date limitation
        if (isset($entities['time_range'][0])) {
            $rangeData = $entities['time_range'][0];

            if ($rangeData['type'] === 'interval') {
                if (isset($rangeData['from'])) {
                    $filter['from'] = new \DateTime($rangeData['from']['value']);
                }
                if (isset($rangeData['to'])) {
                    $filter['to'] = new \DateTime($rangeData['to']['value']);
                }
            } elseif ($rangeData['type'] === 'value') {
                $filter['from'] = new \DateTime($rangeData['value']);
                $grain = $rangeData['grain'];
                $filter['to'] = (new \DateTime($rangeData['value']))->add(
                    \DateInterval::createFromDateString('1 '.$grain)
                );
            }
        }

        $count = $this->getProjectUnitRepository()
            ->getPaidCountByFilter($filter);

        $key = $count == 1 ? 'unit_count' : 'units_count';

        $context->add($key, $count);

        return $context;
    }
}
