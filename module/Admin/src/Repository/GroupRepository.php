<?php
/**
 * GroupRepository.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/30
 * Version: 1.0
 */

namespace Admin\Repository;


use Admin\Entity\Group;
use Doctrine\ORM\EntityRepository;


class GroupRepository extends EntityRepository
{
    /**
     * @return integer
     */
    public function getGroupsCount()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->from(Group::class, 'g');
        $queryBuilder->select($queryBuilder->expr()->count('g.groupID'));

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }


    /**
     * @param int $page
     * @param int $size
     * @return Group[]
     */
    public function getGroupsByLimitPage($page = 1, $size = 100)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('g')
            ->from(Group::class, 'g')
            ->setMaxResults($size)
            ->setFirstResult(($page - 1) * $size)
            ->orderBy('g.groupDefault', 'DESC')
            ->addOrderBy('g.groupName', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }


}