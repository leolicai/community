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
 * @ORM\Entity
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
    protected $groupStatus = self::STATUS_INVALID;

    /**
     * 分组创建时间
     *
     * @var \DateTime
     * @ORM\Column(name="group_created", type="datetime")
     */
    protected $groupCreated;


    /**
     * @var Adminer[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Admin\Entity\Adminer", mappedBy="adminGroups")
     */
    protected $groupAdminers;


    public function __construct()
    {
        $this->groupAdminers = new ArrayCollection();
    }

}