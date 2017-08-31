<?php
/**
 * AdminerRepository.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/30
 * Version: 1.0
 */

namespace Admin\Repository;


use Admin\Entity\Adminer;
use Doctrine\ORM\EntityRepository;


class AdminerRepository extends EntityRepository
{

    /**
     * @return integer
     */
    public function getAdminerCount()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->from(Adminer::class, 'a');
        $queryBuilder->select($queryBuilder->expr()->count('a.adminID'));

        //$queryBuilder->where('a.adminStatus = ?1');
        $queryBuilder->where($queryBuilder->expr()->eq('a.adminStatus', '?1'));
        $queryBuilder->orderBy('a.adminLevel', 'DESC');
        $queryBuilder->setParameter(1, Adminer::STATUS_VALID);

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }


    /**
     * @param int $page
     * @param int $size
     * @return Adminer[]
     */
    public function getAdminersByLimitPage($page = 1, $size = 100)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('a')
            ->from(Adminer::class, 'a')
            ->where('a.adminStatus = ?1')
            ->setMaxResults($size)
            ->setFirstResult(($page - 1) * $size)
            ->orderBy('a.adminDefault', 'DESC')
            ->addOrderBy('a.adminLevel', 'DESC')
            ->addOrderBy('a.adminCreated', 'DESC')
            ->setParameter(1, Adminer::STATUS_VALID);

        return $queryBuilder->getQuery()->getResult();
    }

}