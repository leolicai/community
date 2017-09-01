<?php
/**
 * Component.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Component
 * @package Admin\Entity
 *
 * @ORM\Entity(repositoryClass="\Admin\Repository\ComponentRepository")
 * @ORM\Table(
 *     name="sys_component",
 *     indexes={
 *         @ORM\Index(name="component_name_idx", columns={"component_name"}),
 *         @ORM\Index(name="component_menu_idx", columns={"component_menu"}),
 *         @ORM\Index(name="component_rank_idx", columns={"component_rank"})
 *     }
 * )
 */
class Component
{
    const MENU_VALID = 1;
    const MENU_INVALID = 0;

    /**
     * @var string
     * @ORM\ID
     * @ORM\Column(name="component_class", type="string", length=45, options={"fixed" = true})
     */
    private $componentClass;

    /**
     * @var string
     * @ORM\Column(name="component_name", type="string", length=45, options={"fixed" = true})
     */
    private $componentName;

    /**
     * @var string
     * @ORM\Column(name="component_icon", type="string", length=45)
     */
    private $componentIcon;

    /**
     * @var string
     * @ORM\Column(name="component_route", type="string", length=45)
     */
    private $componentRoute;

    /**
     * @var integer
     * @ORM\Column(name="component_rank", type="integer")
     */
    private $componentRank = 1;

    /**
     * @var integer
     * @ORM\Column(name="component_menu", type="integer")
     */
    private $componentMenu =  self::MENU_INVALID;

    /**
     * @var Action[] | ArrayCollection
     * @ORM\OneToMany(targetEntity="Admin\Entity\Action", mappedBy="actionComponent")
     */
    private $componentActions;


    public function __construct()
    {
        $this->componentActions = new ArrayCollection();
    }

}