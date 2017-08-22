<?php
/**
 * Election.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class Election
 * 参加选举数据
 *
 * @package WeChat\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="elections")
 */
class Election
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="election_id", type="string", length=128, options={"fixed" = true})
     */
    protected $electionID;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255)
     */
    protected $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * 申请参选的职位
     *
     * @var  Organization
     *
     * @ORM\ManyToOne(targetEntity="WeChat\Entity\Organization", inversedBy="organizationElections")
     * @ORM\JoinColumn(name="apply_organization_id", referencedColumnName="organization_id")
     */
    protected $applyOrganizationID;

    /**
     * 申请参选的用户
     *
     * @var Member
     *
     * @ORM\ManyToOne(targetEntity="WeChat\Entity\Member", inversedBy="memberElections")
     * @ORM\JoinColumn(name="apply_member_id", referencedColumnName="member_id")
     */
    protected $applyMemberID;

    /**
     * 活动
     *
     * @var Activity
     *
     * @ORM\ManyToOne(targetEntity="WeChat\Entity\Activity", inversedBy="activityElections")
     * @ORM\JoinColumn(name="belong_activity_id", referencedColumnName="activity_id")
     */
    protected $belongActivityID;

    /**
     * 选项被投票的信息
     *
     * @var  Vote[] An ArrayCollection of Bug objects
     *
     * @ORM\OneToMany(targetEntity="WeChat\Entity\Vote", mappedBy="voteElectionID")
     */
    protected $electionVotes;


    public function __construct()
    {
        $this->electionVotes[] = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getElectionID()
    {
        return $this->electionID;
    }

    /**
     * @param string $electionID
     */
    public function setElectionID($electionID)
    {
        $this->electionID = $electionID;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return Organization
     */
    public function getApplyOrganizationID()
    {
        return $this->applyOrganizationID;
    }

    /**
     * @param Organization $applyOrganizationID
     */
    public function setApplyOrganizationID($applyOrganizationID)
    {
        $this->applyOrganizationID = $applyOrganizationID;
    }

    /**
     * @return Member
     */
    public function getApplyMemberID()
    {
        return $this->applyMemberID;
    }

    /**
     * @param Member $applyMemberID
     */
    public function setApplyMemberID($applyMemberID)
    {
        $this->applyMemberID = $applyMemberID;
    }

    /**
     * @return Activity
     */
    public function getBelongActivityID()
    {
        return $this->belongActivityID;
    }

    /**
     * @param Activity $belongActivityID
     */
    public function setBelongActivityID($belongActivityID)
    {
        $this->belongActivityID = $belongActivityID;
    }

    /**
     * @return Vote[]
     */
    public function getElectionVotes()
    {
        return $this->electionVotes;
    }

    /**
     * @param Vote[] $electionVotes
     */
    public function setElectionVotes($electionVotes)
    {
        $this->electionVotes = $electionVotes;
    }



}