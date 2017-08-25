<?php
/**
 * Organization.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class Organization
 * 组织结构数据
 *
 * @package WeChat\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="organization")
 */
class Organization
{

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="organization_id", type="string", length=128, options={"fixed" = true})
     */
    protected $organizationID;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=45)
     */
    protected $title = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="members", type="integer")
     */
    protected $members = 0;

    /**
     * @var Election
     *
     * @var  Election[] An ArrayCollection of Bug objects
     *
     * @ORM\OneToMany(targetEntity="WeChat\Entity\Election", mappedBy="applyOrganizationID")
     */
    protected $organizationElections;


    public function __construct()
    {
        $this->organizationElections = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getOrganizationID()
    {
        return $this->organizationID;
    }

    /**
     * @param string $organizationID
     */
    public function setOrganizationID($organizationID)
    {
        $this->organizationID = $organizationID;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * @param int $members
     */
    public function setMembers($members)
    {
        $this->members = $members;
    }

    /**
     * @return mixed
     */
    public function getOrganizationElections()
    {
        return $this->organizationElections;
    }

    /**
     * @param mixed $organizationElections
     */
    public function setOrganizationElections($organizationElections)
    {
        $this->organizationElections = $organizationElections;
    }



}