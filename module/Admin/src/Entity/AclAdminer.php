<?php
/**
 * AclAdminer.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * Class AclAdminer
 * @package Admin\Entity
 *
 * @ORM\Entity(repositoryClass="\Admin\Repository\AclAdminerRepository")
 * @ORM\Table(name="sys_acl_admin")
 */
class AclAdminer
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
     * @var Adminer
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Adminer", inversedBy="adminAcls")
     * @ORM\JoinColumn(name="acl_admin_id", referencedColumnName="admin_id")
     */
    private $aclAdminer;

    /**
     * @var Action
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Action", inversedBy="actionAdminerAcls")
     * @ORM\JoinColumn(name="acl_action_id", referencedColumnName="action_id")
     */
    private $aclAction;

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
     * @return Adminer
     */
    public function getAclAdminer()
    {
        return $this->aclAdminer;
    }

    /**
     * @param Adminer $aclAdminer
     */
    public function setAclAdminer($aclAdminer)
    {
        $this->aclAdminer = $aclAdminer;
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