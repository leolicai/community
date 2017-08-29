<?php
/**
 * AdminerManager.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/29
 * Version: 1.0
 */

namespace Admin\Service;


use Admin\Entity\Adminer;
use Doctrine\ORM\EntityManager;


class AdminerManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->setEntityManager($entityManager);
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param $adminID
     * @return Adminer|null|object
     */
    public function getAdminerByID($adminID)
    {
        return $this->entityManager->getRepository(Adminer::class)->find($adminID);
    }


    /**
     * @param $email
     * @return Adminer|object|null
     */
    public function getAdminerByEmail($email)
    {
        return $this->entityManager->getRepository(Adminer::class)->findOneBy(['adminEmail' => $email]);
    }
}