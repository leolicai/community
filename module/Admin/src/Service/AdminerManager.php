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
use Application\Service\BaseManager;
use Doctrine\ORM\EntityManager;


class AdminerManager extends BaseManager
{

    /**
     * @param $adminID
     * @return Adminer|null|object
     */
    public function getAdminerByID($adminID)
    {
        return $this->getEntityManager()->getRepository(Adminer::class)->find($adminID);
    }


    /**
     * @param $email
     * @return Adminer|object|null
     */
    public function getAdminerByEmail($email)
    {
        return $this->getEntityManager()->getRepository(Adminer::class)->findOneBy(['adminEmail' => $email]);
    }


    /**
     * @return integer
     */
    public function getAdminerCount()
    {
        return $this->getEntityManager()->getRepository(Adminer::class)->getAdminerCount();
    }

    /**
     * @param int $page
     * @param int $size
     * @return Adminer[]
     */
    public function getAdminersByLimitPage($page = 1, $size = 100)
    {
        return $this->getEntityManager()->getRepository(Adminer::class)->getAdminersByLimitPage($page, $size);
    }
}