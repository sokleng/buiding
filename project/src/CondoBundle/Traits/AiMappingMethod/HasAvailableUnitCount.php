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
trait HasAvailableUnitCount
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
    public function getAvailableUnitsCount(
        /* @noinspection PhpUnusedParameterInspection */
        $sessionId,
        Context $context,
        array $entities
    ) {
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

        // Initializing filter with mandatory filter params.
        $filter = [
            'project' => $project,
        ];

        // Checking if a unit_type was provided
        if (isset($entities['unit_type'][0]['value'])) {
            $unitTypeStr = strtolower($entities['unit_type'][0]['value']);

            // Finding Unit Type in Condo
            $unitType = null;
            foreach ($project->getUnitTypes() as $type) {
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

        $count = $this->getProjectUnitRepository()
            ->getCountByFilter($filter);

        $key = $count > 1 ? 'units_count' : 'unit_count';

        $context->add($key, $count);

        return $context;
    }
}
