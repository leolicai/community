<?php
/**
 * ComponentRepository.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Repository;


use Admin\Entity\Component;
use Doctrine\ORM\EntityRepository;


class ComponentRepository extends EntityRepository
{

    /**
     * @return integer
     */
    public function getComponentsCount()
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->from(Component::class, 'c');
        $queryBuilder->select($queryBuilder->expr()->count('c.componentClass'));

        return $queryBuilder->getQuery()->getSingleScalarResult();
    }


    /**
     * @param int $page
     * @param int $size
     * @return Component[]
     */
    public function getComponentsByLimitPage($page = 1, $size = 100)
    {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
            ->from(Component::class, 'c')
            ->setMaxResults($size)
            ->setFirstResult(($page - 1) * $size)
            ->orderBy('c.componentRank', 'DESC')
            ->addOrderBy('c.componentName', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }


}