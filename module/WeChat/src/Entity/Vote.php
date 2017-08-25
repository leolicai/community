<?php
/**
 * Vote.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */


namespace WeChat\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * Class Vote
 * 选民投票
 *
 * @package WeChat\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="votes")
 */
class Vote
{

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="vote_id", type="string", length=128, options={"fixed" = true})
     */
    protected $voteID;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * 参与投票的用户
     *
     * @var Member
     *
     * @ORM\ManyToOne(targetEntity="WeChat\Entity\Member", inversedBy="memberVotes")
     * @ORM\JoinColumn(name="vote_member_id", referencedColumnName="member_id")
     */
    protected $voteMemberID;

    /**
     * 被投票的选项
     *
     * @var Election
     *
     * @ORM\ManyToOne(targetEntity="WeChat\Entity\Election", inversedBy="electionVotes")
     * @ORM\JoinColumn(name="vote_election_id", referencedColumnName="election_id")
     */
    protected $voteElectionID;

}