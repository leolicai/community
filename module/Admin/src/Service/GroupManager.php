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

    /**
     * @param string $groupID
     * @return Group|null|object
     */
    public function getGroupByID($groupID)
    {
        return $this->getEntityManager()->getRepository(Group::class)->find($groupID);
    }

    /**
     * @param string $name
     * @return null|Group|object
     */
    public function getGroupByName($name)
    {
        return $this->getEntityManager()->getRepository(Group::class)->findOneBy(['groupName' => $name]);
    }

    /**
     * @return integer
     */
    public function getGroupsCount()
    {
        return $this->getEntityManager()->getRepository(Group::class)->getGroupsCount();
    }

    /**
     * @param int $page
     * @param int $size
     * @return Group[]
     */
    public function getGroupsByLimitPage($page = 1, $size = 10)
    {
        return $this->getEntityManager()->getRepository(Group::class)->getGroupsByLimitPage($page, $size);
    }


    /**
     * @return Group[]
     */
    public function getAllGroups()
    {
        return $this->getEntityManager()->getRepository(Group::class)->findAll();
    }


    /**
     * @param Group $group
     */
    public function saveModifiedGroup(Group $group)
    {
        $this->getEntityManager()->persist($group);
        $this->getEntityManager()->flush();
    }


    /**
     * @param Group $group
     */
    public function removeGroup(Group $group)
    {
        // Remove group
        $this->getEntityManager()->remove($group);
        $this->getEntityManager()->flush();
    }

}