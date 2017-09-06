<?php
/**
 * Group.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 17/8/27
 * Version: 1.0
 */

namespace Admin\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class Group
 *
 * 管理员分组实体定义
 *
 * @package Admin\Entity
 *
 * @ORM\Entity(repositoryClass="\Admin\Repository\GroupRepository")
 * @ORM\Table(
 *     name="sys_group",
 *     indexes={
 *         @ORM\Index(name="group_name_idx", columns={"group_name"}),
 *         @ORM\Index(name="group_default_idx", columns={"group_default"}),
 *         @ORM\Index(name="group_status_idx", columns={"group_status"})
 *     }
 * )
 */
class Group
{
    const DEFAULT_GROUP = 1;
    const DEFAULT_OTHER = 0;

    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;

    /**
     * 分组编号
     *
     * @var string
     * @ORM\Id
     * @ORM\Column(name="group_id", type="string", length=36, options={"fixed" = true})
     */
    protected $groupID;

    /**
     * 分组名称
     *
     * @var string
     * @ORM\Column(name="group_name", type="string", length=45, options={"fixed" = true})
     */
    protected $groupName = '';

    /**
     * @var string
     * @ORM\Column(name="group_desc", type="text", length=65535)
     */
    protected $groupDesc = '';

    /**
     * 是否是默认分组
     *
     * @var integer
     * @ORM\Column(name="group_default", type="integer")
     */
    protected $groupDefault = self::DEFAULT_OTHER;

    /**
     * 分组状态
     *
     * @var integer
     * @ORM\Column(name="group_status", type="integer")
     */
    protected $groupStatus = self::STATUS_VALID;

    /**
     * 分组创建时间
     *
     * @var \DateTime
     * @ORM\Column(name="group_created", type="datetime")
     */
    protected $groupCreated;

    /**
     * Group Action Access Control Logic
     *
     * @var Action[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Admin\Entity\Action", inversedBy="actionGroups")
     * @ORM\JoinTable(
     *     name="sys_acl",
     *     joinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="group_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="action_id", referencedColumnName="action_id")}
     * )
     */
    protected $groupActions;

    /**
     * @var Adminer[] | ArrayCollection
     * @ORM\ManyToMany(targetEntity="Admin\Entity\Adminer", mappedBy="adminGroups")
     */
    protected $groupAdminers;


    public function __construct()
    {
        $this->groupAdminers = new ArrayCollection();
        $this->groupActions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getGroupID()
    {
        return $this->groupID;
    }

    /**
     * @param string $groupID
     */
    public function setGroupID($groupID)
    {
        $this->groupID = $groupID;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->groupName;
    }

    /**
     * @param string $groupName
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    /**
     * @return string
     */
    public function getGroupDesc()
    {
        return $this->groupDesc;
    }

    /**
     * @param string $groupDesc
     */
    public function setGroupDesc($groupDesc)
    {
        $this->groupDesc = $groupDesc;
    }

    /**
     * @return int
     */
    public function getGroupDefault()
    {
        return $this->groupDefault;
    }

    /**
     * @param int $groupDefault
     */
    public function setGroupDefault($groupDefault)
    {
        $this->groupDefault = $groupDefault;
    }

    /**
     * @return int
     */
    public function getGroupStatus()
    {
        return $this->groupStatus;
    }

    /**
     * @param int $groupStatus
     */
    public function setGroupStatus($groupStatus)
    {
        $this->groupStatus = $groupStatus;
    }

    /**
     * @return \DateTime
     */
    public function getGroupCreated()
    {
        return $this->groupCreated;
    }

    /**
     * @param \DateTime $groupCreated
     */
    public function setGroupCreated($groupCreated)
    {
        $this->groupCreated = $groupCreated;
    }

    /**
     * @return Adminer[]|ArrayCollection
     */
    public function getGroupAdminers()
    {
        return $this->groupAdminers;
    }

    /**
     * @param Adminer[]|ArrayCollection $groupAdminers
     */
    public function setGroupAdminers($groupAdminers)
    {
        $this->groupAdminers = $groupAdminers;
    }

    /**
     * @return Action[]|ArrayCollection
     */
    public function getGroupActions()
    {
        return $this->groupActions;
    }

    /**
     * @param Action[]|ArrayCollection $groupActions
     */
    public function setGroupActions($groupActions)
    {
        $this->groupActions = $groupActions;
    }

}