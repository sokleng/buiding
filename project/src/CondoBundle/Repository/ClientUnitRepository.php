<?php

namespace CondoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use CondoBundle\Entity\Condominium;
use WeBridge\UserBundle\Entity\User;
use CondominiumManagementBundle\Constant\ClientStatus;
use CondoBundle\Entity\Unit;
use DateTime;

/**
 * ClientUnitRepository.
 */
class ClientUnitRepository extends EntityRepository
{
    /**
     * Get client unit by user.
     *
     * @param User $user
     *
     * @return null|void|ClientUnit
     */
    public function findClientUnitByUser(User $user)
    {
        $result = $this->createQueryBuilder('clientUnit')
            ->join('clientUnit.unit', 'cuUnit')
            ->where('clientUnit.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        if (empty($result)) {
            return;
        }

        return $result[0];
    }

     /* Gets all users for a given condominium and date.
     *
     * @param Condominium $condo
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findAllUsersForCondominiumAndDate(
        Condominium $condo,
        DateTime $from,
        DateTime $to
    ) {
        $qb = $this->createQueryBuilder('clientUnit');
        $qb
            ->innerJoin('clientUnit.user', 'cuUser')
            ->innerJoin('clientUnit.unit', 'cuUnit')
            ->where('cuUnit.condominium = :condo')
            ->andWhere('clientUnit.startDate >= :from AND clientUnit.startDate <= :to')
            ->orWhere('clientUnit.endDate >= :from AND clientUnit.endDate <= :to')
            ->orWhere('clientUnit.startDate <= :from AND clientUnit.endDate >= :to')
            ->setParameter('condo', $condo)
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('clientUnit.startDate', 'ASC');

        return $qb;
    }

    /**
     * Gets all users for a given condominium and status.
     *
     * @param Condominium $condo
     * @param string      $clientStatus
     * @param string      $startDate
     * @param string      $endDate
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findClientUnitByStatusDateAndCondo(
        Condominium $condo,
        $clientStatus,
        $startDate,
        $endDate
    ) {
        $result = $this->createQueryBuilder('clientUnit')
            ->join('clientUnit.user', 'cuUser')
            ->join('clientUnit.unit', 'cuUnit')
            ->where('cuUnit.condominium = :condo')
            ->setParameter('condo', $condo);
        $result = $this->findByStatusAndDate(
                $clientStatus,
                $startDate,
                $endDate,
                $result
            );
        $result->orderBy('clientUnit.id', 'DESC');

        return $result;
    }

    private function findByStatusAndDate(
        $clientStatus,
        $startDate,
        $endDate,
        $result
    ) {
        if ($clientStatus === ClientStatus::STAYING) {
            return $this->staying($startDate, $endDate, $result);
        }
        if ($clientStatus === ClientStatus::LEAVE) {
            return $this->leave($startDate, $endDate, $result);
        }

        return $this->all($startDate, $endDate, $result);
    }

    private function staying(
        $startDate,
        $endDate,
        $result
    ) {
        if (!empty($startDate) && !empty($endDate)) {
            return $result
                ->andWhere('clientUnit.startDate >= :startDate AND clientUnit.startDate <= :endDate')
                ->orWhere('clientUnit.endDate >= :startDate AND clientUnit.endDate <= :endDate')
                ->orWhere('clientUnit.startDate <= :startDate AND clientUnit.endDate >= :endDate')
                ->setParameter('startDate', new DateTime($startDate))
                ->setParameter('endDate', new DateTime($endDate));
        }
        if (empty($startDate) || !empty($endDate)) {
            return $result
                ->andWhere('clientUnit.endDate > :endDate')
                ->setParameter('endDate', new Datetime($endDate));
        }

        return $result
            ->andWhere('clientUnit.endDate > :now')
            ->setParameter('now', new Datetime());
    }

    private function leave(
        $startDate,
        $endDate,
        $result
    ) {
        if (!empty($startDate) && !empty($endDate)) {
            return $result
                ->andWhere('clientUnit.startDate >= :startDate AND clientUnit.endDate < :endDate')
                ->setParameter('startDate', new DateTime($startDate))
                ->setParameter('endDate', new DateTime($endDate));
        }
        if (empty($startDate) || !empty($endDate)) {
            return $result
                ->andWhere('clientUnit.endDate < :endDate')
                ->setParameter('endDate', new Datetime($endDate));
        }

        return $result
            ->andWhere('clientUnit.endDate <= :now')
            ->setParameter('now', new Datetime());
    }

    private function all(
        $startDate,
        $endDate,
        $result
    ) {
        if (!empty($startDate) && !empty($endDate)) {
            return $result
                ->andWhere('clientUnit.startDate >= :startDate AND clientUnit.endDate <= :endDate')
                ->setParameter('startDate', new DateTime($startDate))
                ->setParameter('endDate', new DateTime($endDate));
        }
        if (empty($startDate) || !empty($endDate)) {
            return $result
                ->andWhere('clientUnit.endDate <= :endDate')
                ->setParameter('endDate', new DateTime($endDate));
        }
    }

    /**
     * Gets a specific clientUnit for given unit and date.
     *
     * @param Unit     $unit
     * @param DateTime $from
     * @param DateTime $to
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findSpecificClientUnitForGivenUnitAndDate(
        Unit $unit,
        DateTime $from,
        DateTime $to
    ) {
        $qb = $this->createQueryBuilder('clientUnit')
            ->where('clientUnit.unit = :unit')
            ->andWhere('clientUnit.endDate >= :from AND clientUnit.endDate <= :to')
            ->orWhere('clientUnit.startDate <= :from AND clientUnit.endDate >= :to')
            ->setParameter('unit', $unit)
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        return $qb;
    }

    /**
     * Gets user who created issues.
     *
     * @param Condominium $condominium
     * @param User        $user
     *
     * @return array
     */
    public function findUserByCondoAndUser(
        Condominium $condominium,
        User $user
    ) {
        $result = $this->createQueryBuilder('clientUnit')
            ->join('clientUnit.unit', 'cuUnit')
            ->where('cuUnit.condominium = :condo')
            ->andWhere('clientUnit.user = :user')
            ->setParameter('condo', $condominium)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $result;
    }

    /**
     * Count all users for a given condominium.
     *
     * @param Condominium $condo
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function countAllUsersForCondominium(Condominium $condo)
    {
        return $this->createQueryBuilder('clientUnit')
            ->select('count(clientUnit.user)')
            ->join('clientUnit.unit', 'cuUnit')
            ->where('cuUnit.condominium = :condo')
            ->setParameter('condo', $condo);
    }

    /**
     * Get all unit in client unit that unit is not null for the condominium.
     *
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getUnitNotNullFromClient()
    {
        $qb = $this->createQueryBuilder('clientUnit');
        $qb->select('IDENTITY(clientUnit.unit)')
            ->andWhere(
                $qb->expr()
                ->isNotNull(
                    'clientUnit.unit'
                )
            )
            ->where('clientUnit.endDate > :now');

        return $qb;
    }

    /**
     * Gets client by day and hour.
     *
     * @param string $day
     * @param string $hour
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findClientByDayAndHour($day, $hour)
    {
        $lastDayOfMonth = date('t');
        $currentDayOfMonth = date('j');
        $result = $this->createQueryBuilder('clientUnit')
            ->where('clientUnit.isRunScheduleAuto = :isRunScheduleAuto')
            ->setParameter('isRunScheduleAuto', true);

        if($lastDayOfMonth === $currentDayOfMonth){

            //Last day of month
            return $result
                ->andWhere('clientUnit.day >= :currentDayOfMonth')
                ->setParameter('currentDayOfMonth', $currentDayOfMonth);
        }

        //Not last day of the month
        return $result
            ->andWhere('clientUnit.day = :day')
            ->andWhere('clientUnit.hour = :hour')
            ->setParameter('day', $day)
            ->setParameter('hour', $hour);
    }

    /**
     * Get a specific unit from client for given unit.
     *
     * @param Unit $unit
     *
     * @return array
     */
    public function findUnitClientByUnit(Unit $unit)
    {
        return $this->createQueryBuilder('clientUnit')
            ->where('clientUnit.unit = :unit')
            ->setParameter('unit', $unit)
            ->getQuery()
            ->getResult();
    }
}
