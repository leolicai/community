<?php
/**
 * Member.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class Member
 * @package WeChat\Entity
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="members",
 *     indexes={
 *         @ORM\Index(name="openid_idx", columns={"openid"})
 *     }
 * )
 */
class Member
{
    const STATUS_PROFILE_FINISHED = 1;
    const STATUS_PROFILE_EMPTY = 0;

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="member_id", type="string", length=128, options={"fixed" = true})
     */
    protected $memberID;

    /**
     * @var string
     *
     * @ORM\Column(name="openid", type="string", length=128, options={"fixed" = true})
     */
    protected $openID;

    /**
     * @var string
     *
     * @ORM\Column(name="member_name", type="string", length=45)
     */
    protected $memberName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="member_avatar", type="string", length=255)
     */
    protected $memberAvatar = '';

    /**
     * @var string
     *
     * @ORM\Column(name="member_phone", type="string", length=20)
     */
    protected $memberPhone = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="member_building", type="integer")
     */
    protected $memberBuilding = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_floor", type="integer")
     */
    protected $memberFloor = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_room", type="integer")
     */
    protected $memberRoom = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="member_status", type="integer")
     */
    protected $memberStatus = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="member_created", type="datetime")
     */
    protected $memberCreated;

    /**
     * 我参加的选举
     *
     * @var  Election[] An ArrayCollection of Bug objects
     *
     * @ORM\OneToMany(targetEntity="WeChat\Entity\Election", mappedBy="applyMemberID")
     */
    protected $memberElections;

    /**
     * 我参加的投票
     *
     * @var  Vote[] An ArrayCollection of Bug objects
     *
     * @ORM\OneToMany(targetEntity="WeChat\Entity\Vote", mappedBy="voteMemberID")
     */
    protected $memberVotes;


    public function __construct()
    {
        $this->memberElections = new ArrayCollection();
        $this->memberVotes = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getMemberID()
    {
        return $this->memberID;
    }

    /**
     * @param string $memberID
     */
    public function setMemberID($memberID)
    {
        $this->memberID = $memberID;
    }

    /**
     * @return string
     */
    public function getOpenID()
    {
        return $this->openID;
    }

    /**
     * @param string $openID
     */
    public function setOpenID($openID)
    {
        $this->openID = $openID;
    }

    /**
     * @return string
     */
    public function getMemberName()
    {
        return $this->memberName;
    }

    /**
     * @param string $memberName
     */
    public function setMemberName($memberName)
    {
        $this->memberName = $memberName;
    }

    /**
     * @return string
     */
    public function getMemberAvatar()
    {
        return $this->memberAvatar;
    }

    /**
     * @param string $memberAvatar
     */
    public function setMemberAvatar($memberAvatar)
    {
        $this->memberAvatar = $memberAvatar;
    }

    /**
     * @return string
     */
    public function getMemberPhone()
    {
        return $this->memberPhone;
    }

    /**
     * @param string $memberPhone
     */
    public function setMemberPhone($memberPhone)
    {
        $this->memberPhone = $memberPhone;
    }

    /**
     * @return int
     */
    public function getMemberBuilding()
    {
        return $this->memberBuilding;
    }

    /**
     * @param int $memberBuilding
     */
    public function setMemberBuilding($memberBuilding)
    {
        $this->memberBuilding = $memberBuilding;
    }

    /**
     * @return int
     */
    public function getMemberFloor()
    {
        return $this->memberFloor;
    }

    /**
     * @param int $memberFloor
     */
    public function setMemberFloor($memberFloor)
    {
        $this->memberFloor = $memberFloor;
    }

    /**
     * @return int
     */
    public function getMemberRoom()
    {
        return $this->memberRoom;
    }

    /**
     * @param int $memberRoom
     */
    public function setMemberRoom($memberRoom)
    {
        $this->memberRoom = $memberRoom;
    }

    /**
     * @return int
     */
    public function getMemberStatus()
    {
        return $this->memberStatus;
    }

    /**
     * @param int $memberStatus
     */
    public function setMemberStatus($memberStatus)
    {
        $this->memberStatus = $memberStatus;
    }

    /**
     * @return \DateTime
     */
    public function getMemberCreated()
    {
        return $this->memberCreated;
    }

    /**
     * @param \DateTime $memberCreated
     */
    public function setMemberCreated($memberCreated)
    {
        $this->memberCreated = $memberCreated;
    }

    /**
     * @return Election[]
     */
    public function getMemberElections()
    {
        return $this->memberElections;
    }

    /**
     * @param Election[] $memberElections
     */
    public function setMemberElections($memberElections)
    {
        $this->memberElections = $memberElections;
    }

    /**
     * @return Vote[]
     */
    public function getMemberVotes()
    {
        return $this->memberVotes;
    }

    /**
     * @param Vote[] $memberVotes
     */
    public function setMemberVotes($memberVotes)
    {
        $this->memberVotes = $memberVotes;
    }

}