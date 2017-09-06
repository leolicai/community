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
use Admin\Repository\GroupRepository;
use Application\Service\BaseManager;


class GroupManager extends BaseManager
{

    /**
     * @return GroupRepository | \Doctrine\ORM\EntityRepository
     */
    private function getGroupRepository()
    {
        return $this->getEntityManager()->getRepository(Group::class);
    }

    /**
     * @return Group|null|object
     */
    public function getDefaultGroup()
    {
        return $this->getGroupRepository()->findOneBy(['groupDefault' => Group::DEFAULT_GROUP]);
    }

    /**
     * @param string $groupID
     * @return Group|null|object
     */
    public function getGroupByID($groupID)
    {
        return $this->getGroupRepository()->find($groupID);
    }

    /**
     * @param string $name
     * @return null|Group|object
     */
    public function getGroupByName($name)
    {
        return $this->getGroupRepository()->findOneBy(['groupName' => $name]);
    }

    /**
     * @return integer
     */
    public function getGroupsCount()
    {
        return $this->getGroupRepository()->getGroupsCount();
    }

    /**
     * @param int $page
     * @param int $size
     * @return Group[]
     */
    public function getGroupsByLimitPage($page = 1, $size = 10)
    {
        return $this->getGroupRepository()->getGroupsByLimitPage($page, $size);
    }


    /**
     * @return Group[]
     */
    public function getAllGroups()
    {
        return $this->getGroupRepository()->findAll();
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
     * Remove a group will been remove
     *
     * i: group with administrator many to many relation
     * ii: group with action many to many relation
     * iii: group self
     *
     * @param Group $group
     */
    public function removeGroup(Group $group)
    {
        // Remove group
        $this->getEntityManager()->remove($group);
        $this->getEntityManager()->flush();
    }

}