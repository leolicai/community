<?php
/**
 * Activity.php
 *
 * @author: Leo <camworkster@gmail.com>
 * @version: 1.0
 */

namespace WeChat\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Class Activity
 * 活动
 *
 * @package WeChat\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="activities")
 */
class Activity
{

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="activity_id", type="string", length=128, options={"fixed" = true})
     */
    protected $activityID;

    /**
     * @var string
     *
     * @ORM\Column(name="activity_name", type="string", length=255)
     */
    protected $activityName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activity_start", type="datetime")
     */
    protected $activityStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activity_end", type="datetime")
     */
    protected $activityEnd;


    /**
     * 本次选举的参选信息
     *
     * @var  Election[] An ArrayCollection of Bug objects
     *
     * @ORM\OneToMany(targetEntity="WeChat\Entity\Election", mappedBy="belongActivityID")
     */
    protected $activityElections;


    public function __construct()
    {
        $this->activityElections = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getActivityID()
    {
        return $this->activityID;
    }

    /**
     * @param string $activityID
     */
    public function setActivityID($activityID)
    {
        $this->activityID = $activityID;
    }

    /**
     * @return string
     */
    public function getActivityName()
    {
        return $this->activityName;
    }

    /**
     * @param string $activityName
     */
    public function setActivityName($activityName)
    {
        $this->activityName = $activityName;
    }

    /**
     * @return \DateTime
     */
    public function getActivityStart()
    {
        return $this->activityStart;
    }

    /**
     * @param \DateTime $activityStart
     */
    public function setActivityStart($activityStart)
    {
        $this->activityStart = $activityStart;
    }

    /**
     * @return \DateTime
     */
    public function getActivityEnd()
    {
        return $this->activityEnd;
    }

    /**
     * @param \DateTime $activityEnd
     */
    public function setActivityEnd($activityEnd)
    {
        $this->activityEnd = $activityEnd;
    }

    /**
     * @return Election[]
     */
    public function getActivityElections()
    {
        return $this->activityElections;
    }

    /**
     * @param Election[] $activityElections
     */
    public function setActivityElections($activityElections)
    {
        $this->activityElections = $activityElections;
    }


}