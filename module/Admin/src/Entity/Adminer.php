<?php
/**
 * Adminer.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 17/8/27
 * Version: 1.0
 */

namespace Admin\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class Adminer
 *
 * 系统管理员实体定义
 *
 * @package Admin\Entity
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="sys_admin",
 *     indexes={
 *         @ORM\Index(name="admin_default_idx", columns={"admin_default"}),
 *         @ORM\Index(name="admin_status_idx", columns={"admin_status"}),
 *         @ORM\Index(name="admin_locked_idx", columns={"admin_locked"}),
 *         @ORM\Index(name="admin_activated_idx", columns={"admin_activated"}),
 *         @ORM\Index(name="admin_level_idx", columns={"admin_level"}),
 *         @ORM\Index(name="admin_active_code_idx", columns={"admin_active_code"})
 *     }
 * )
 */
class Adminer
{
    const DEFAULT_ADMIN = 1;
    const DEFAULT_OTHER = 0;

    const STATUS_VALID = 1;
    const STATUS_INVALID = 0;

    const LOCKED_VALID = 1;
    const LOCKED_INVALID = 0;

    const ACTIVATED_VALID = 1;
    const ACTIVATED_INVALID = 0;

    const LEVEL_SUPPER = 999;
    const LEVEL_DEFAULT = 1;

    /**
     * 管理员编号
     *
     * @var string
     * @ORM\Id
     * @ORM\Column(name="admin_id", type="string", length=36, options={"fixed" = true})
     */
    protected $adminID;

    /**
     * 管理员登录邮箱
     *
     * @var string
     * @ORM\Column(name="admin_email", type="string", length=45, options={"fixed" = true})
     */
    protected $adminEmail;

    /**
     * 管理员登录密码
     *
     * @var string
     * @ORM\Column(name="admin_passwd", type="string", length=32, options={"fixed" = true})
     */
    protected $adminPasswd;

    /**
     * 默认管理员身份
     *
     * @var integer
     * @ORM\Column(name="admin_default", type="integer")
     */
    protected $adminDefault = self::DEFAULT_OTHER;

    /**
     * 管理员账户状态
     *
     * @var integer
     * @ORM\Column(name="admin_status", type="integer")
     */
    protected $adminStatus = self::ACTIVATED_INVALID;

    /**
     * 管理员账户锁定状况
     *
     * @var integer
     * @ORM\Column(name="admin_locked", type="integer")
     */
    protected $adminLocked = self::LOCKED_VALID;

    /**
     * 管理员账户是否被激活
     *
     * @var integer
     * @ORM\Column(name="admin_activated", type="integer")
     */
    protected $adminActivated = self::ACTIVATED_INVALID;

    /**
     * 管理员等级
     *
     * @var integer
     * @ORM\Column(name="admin_level", type="integer")
     */
    protected $adminLevel;

    /**
     * 管理员名字
     *
     * @var string
     * @ORM\Column(name="admin_name", type="string", length=45)
     */
    protected $adminName = '';

    /**
     * 管理员账户激活 CODE
     *
     * @var string
     * @ORM\Column(name="admin_active_code", type="string", length=32, options={"fixed" = true})
     */
    protected $adminActiveCode = '';

    /**
     * 管理员账户失效时间
     *
     * @var \DateTime
     * @ORM\Column(name="admin_expired", type="datetime")
     */
    protected $adminExpired;

    /**
     * 管理员创建时间
     *
     * @var \DateTime
     * @ORM\Column(name="admin_created", type="datetime")
     */
    protected $adminCreated;

    /**
     * 管理员所属分组
     *
     * @var Group[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Admin\Entity\Group", inversedBy="groupAdminers")
     * @ORM\JoinTable(
     *     name="sys_relation_admin_group",
     *     joinColumns={@ORM\JoinColumn(name="relation_admin_id", referencedColumnName="admin_id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="relation_group_id", referencedColumnName="group_id")}
     * )
     */
    protected $adminGroups;


    public function __construct()
    {
        $this->adminGroups = new ArrayCollection();
    }

}