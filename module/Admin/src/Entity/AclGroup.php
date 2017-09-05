<?php
/**
 * AclGroup.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * Class AclGroup
 * @package Admin\Entity
 *
 * @ORM\Entity(repositoryClass="\Admin\Repository\AclGroupRepository")
 * @ORM\Table(name="sys_acl_group")
 */
class AclGroup
{

    const STATUS_ALLOWED = 1;
    const STATUS_FORBIDDEN = -1;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="acl_id", type="string", length=36, options={"fixed" = true})
     */
    private $aclID;

    /**
     * @var integer
     * @ORM\Column(name="acl_status", type="integer")
     */
    private $aclStatus;

    /**
     * @var Group
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Group", inversedBy="groupAcls")
     * @ORM\JoinColumn(name="acl_group_id", referencedColumnName="group_id")
     */
    private $aclGroup;

    /**
     * @var Action
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Action", inversedBy="actionGroupAcls")
     * @ORM\JoinColumn(name="acl_action_id", referencedColumnName="action_id")
     */
    private $aclAction;


    public static function AclStatusList()
    {
        return [
            self::STATUS_ALLOWED => '允许访问',
            self::STATUS_FORBIDDEN => '禁止访问',
        ];
    }


    /**
     * @return string
     */
    public function getAclID()
    {
        return $this->aclID;
    }

    /**
     * @param string $aclID
     */
    public function setAclID($aclID)
    {
        $this->aclID = $aclID;
    }

    /**
     * @return int
     */
    public function getAclStatus()
    {
        return $this->aclStatus;
    }

    /**
     * @param int $aclStatus
     */
    public function setAclStatus($aclStatus)
    {
        $this->aclStatus = $aclStatus;
    }

    /**
     * @return Group
     */
    public function getAclGroup()
    {
        return $this->aclGroup;
    }

    /**
     * @param Group $aclGroup
     */
    public function setAclGroup($aclGroup)
    {
        $this->aclGroup = $aclGroup;
    }

    /**
     * @return Action
     */
    public function getAclAction()
    {
        return $this->aclAction;
    }

    /**
     * @param Action $aclAction
     */
    public function setAclAction($aclAction)
    {
        $this->aclAction = $aclAction;
    }




}