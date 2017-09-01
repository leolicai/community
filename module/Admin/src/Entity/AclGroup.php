<?php
/**
 * AclGroup.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Entity;


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

}