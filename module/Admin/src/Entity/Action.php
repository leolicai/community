<?php
/**
 * Action.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Entity;


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
    private $actionName;

    /**
     * @var string
     * @ORM\Column(name="action_icon", type="string", length=45)
     */
    private $actionIcon;

    /**
     * @var integer
     * @ORM\Column(name="action_rank", type="integer")
     */
    private $actionRank;

    /**
     * @var integer
     * @ORM\Column(name="action_menu", type="integer")
     */
    private $actionMenu;

    /**
     * @var Component
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Component", inversedBy="componentActions")
     * @ORM\JoinColumn(name="controller_class", referencedColumnName="component_class")
     */
    private $actionComponent;

}