<?php
/**
 * GroupManager.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/30
 * Version: 1.0
 */

namespace Admin\Service;


use Admin\Entity\Group;
use Application\Service\BaseManager;


class GroupManager extends BaseManager
{

    /**
     * @return Group|null|object
     */
    public function getDefaultGroup()
    {
        return $this->getEntityManager()->getRepository(Group::class)->findOneBy(['groupDefault' => Group::DEFAULT_GROUP]);
    }

}