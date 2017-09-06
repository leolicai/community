<?php
/**
 * Action.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Action
 * @package Admin\Entity
 *
 * @ORM\Entity(repositoryClass="\Admin\Repository\ActionRepository")
 * @ORM\Table(
 *     name="sys_action",
 *     indexes={
 *         @ORM\Index(name="action_method_idx", columns={"action_method"}),
 *         @ORM\Index(name="action_name_idx", columns={"action_name"}),
 *         @ORM\Index(name="action_menu_idx", columns={"action_menu"}),
 *         @ORM\Index(name="action_rank_idx", columns={"action_rank"})
 *     }
 * )
 */
class Action
{
    const MENU_VALID = 1;
    const MENU_INVALID = 0;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="action_id", type="string", length=36, options={"fixed" = true})
     */
    private $actionID;

    /**
     * @var string
     * @ORM\Column(name="action_method", type="string", length=45, options={"fixed" = true})
     */
    private $actionMethod;

    /**
     * @var string
     * @ORM\Column(name="action_name", type="string", length=45, options={"fixed" = true})
     */
    private $actionName = '';

    /**
     * @var string
     * @ORM\Column(name="action_icon", type="string", length=45)
     */
    private $actionIcon = '';

    /**
     * @var integer
     * @ORM\Column(name="action_rank", type="integer")
     */
    private $actionRank = 1;

    /**
     * @var integer
     * @ORM\Column(name="action_menu", type="integer")
     */
    private $actionMenu = self::MENU_INVALID;

    /**
     * @var Component
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Component", inversedBy="componentActions")
     * @ORM\JoinColumn(name="controller_class", referencedColumnName="component_class")
     */
    private $actionComponent;

    /**
     * @var Group[] | ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Admin\Entity\Group", mappedBy="groupActions")
     */
    private $actionGroups;


    public function __construct()
    {
        $this->actionGroups = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getActionID()
    {
        return $this->actionID;
    }

    /**
     * @param string $actionID
     */
    public function setActionID($actionID)
    {
        $this->actionID = $actionID;
    }

    /**
     * @return string
     */
    public function getActionMethod()
    {
        return $this->actionMethod;
    }

    /**
     * @param string $actionMethod
     */
    public function setActionMethod($actionMethod)
    {
        $this->actionMethod = $actionMethod;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @param string $actionName
     */
    public function setActionName($actionName)
    {
        $this->actionName = $actionName;
    }

    /**
     * @return string
     */
    public function getActionIcon()
    {
        return $this->actionIcon;
    }

    /**
     * @param string $actionIcon
     */
    public function setActionIcon($actionIcon)
    {
        $this->actionIcon = $actionIcon;
    }

    /**
     * @return int
     */
    public function getActionRank()
    {
        return $this->actionRank;
    }

    /**
     * @param int $actionRank
     */
    public function setActionRank($actionRank)
    {
        $this->actionRank = $actionRank;
    }

    /**
     * @return int
     */
    public function getActionMenu()
    {
        return $this->actionMenu;
    }

    /**
     * @param int $actionMenu
     */
    public function setActionMenu($actionMenu)
    {
        $this->actionMenu = $actionMenu;
    }

    /**
     * @return Component
     */
    public function getActionComponent()
    {
        return $this->actionComponent;
    }

    /**
     * @param Component $actionComponent
     */
    public function setActionComponent($actionComponent)
    {
        $this->actionComponent = $actionComponent;
    }

    /**
     * @return Group[]|ArrayCollection
     */
    public function getActionGroups()
    {
        return $this->actionGroups;
    }

    /**
     * @param Group[]|ArrayCollection $actionGroups
     */
    public function setActionGroups($actionGroups)
    {
        $this->actionGroups = $actionGroups;
    }

}