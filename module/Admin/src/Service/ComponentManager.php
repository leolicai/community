<?php
/**
 * ComponentManager.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Service;


use Admin\Entity\Action;
use Admin\Entity\Component;
use Admin\Repository\ActionRepository;
use Admin\Repository\ComponentRepository;
use Application\Service\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;


class ComponentManager extends BaseManager
{

    /**
     * @return ComponentRepository | \Doctrine\ORM\EntityRepository
     */
    private function getComponentRepository()
    {
        return $this->getEntityManager()->getRepository(Component::class);
    }

    /**
     * @return ActionRepository | \Doctrine\ORM\EntityRepository
     */
    private function getActionRepository()
    {
        return $this->getEntityManager()->getRepository(Action::class);
    }

    /**
     * @return integer
     */
    public function getComponentsCount()
    {
        return $this->getComponentRepository()->getComponentsCount();
    }

    /**
     * @param int $page
     * @param int $size
     * @return Component[]
     */
    public function getComponentsByLimitPage($page = 1, $size = 10)
    {
        return $this->getComponentRepository()->getComponentsByLimitPage($page, $size);
    }

    /**
     * @param string $class
     * @return null|object|Component
     */
    public function getComponentByClass($class)
    {
        return $this->getComponentRepository()->find($class);
    }


    /**
     * @return Component[]
     */
    public function getAllComponent()
    {
        return $this->getComponentRepository()->findAll();
    }


    /**
     * @return Component[] | ArrayCollection
     */
    public function getMenuComponents()
    {
        return $this->getComponentRepository()->findBy(['componentMenu' => Component::MENU_VALID], ['componentRank' => 'DESC', 'componentName' => 'ASC']);
    }

    /**
     * @return Action[] | ArrayCollection
     */
    public function getMenuActions()
    {
        return $this->getActionRepository()->findBy(['actionMenu' => Action::MENU_VALID], ['actionRank' => 'DESC', 'actionName' => 'ASC']);
    }


    /**
     * @param $actionID
     * @return null|object|Action
     */
    public function getAction($actionID)
    {
        return $this->getActionRepository()->find($actionID);
    }


    /**
     * @param $class
     * @param $method
     * @return null|object|Action
     */
    public function getActionByClassAndMethod($class, $method)
    {
        $component = $this->getComponentByClass($class);
        if (!$component instanceof Component) {
            return null;
        }

        return $this->getActionRepository()->findOneBy(['actionComponent' => $component, 'actionMethod' => $method]);
    }


    /**
     * Remove a component will been remove:
     *
     * i: component's all action
     * ii: action's all acl
     * iii: component self
     *
     * @param Component $component
     */
    public function removeComponent(Component $component)
    {
        $this->getEntityManager()->remove($component);
        $this->getEntityManager()->flush();
    }


    /**
     * Remove a action will been remove:
     *
     * i: action with group acl many to many relation
     * ii: action self
     *
     * @param Action $action
     */
    public function removeAction(Action $action)
    {
        $this->getEntityManager()->remove($action);
        $this->getEntityManager()->flush();
    }


    /**
     * @param array $items
     */
    public function async($items = [])
    {
        $newItems = $items;

        // Registered components
        $registeredComponents = $this->getAllComponent();
        $needUpdatedComponents = [];

        // Remove all deleted components
        foreach ($registeredComponents as $component) {
            if (!array_key_exists($component->getComponentClass(), $items)) {
                $this->getEntityManager()->remove($component);
                $this->getEntityManager()->flush();
            } else {
                unset($newItems[$component->getComponentClass()]);
                $needUpdatedComponents[$component->getComponentClass()] = $component;
            }
        }

        // Register new components
        if (!empty($newItems)) {
            foreach ($newItems as $key => $item) {
                $this->registerNewComponent($item);
                unset($items[$key]);
            }
        }

        // Update registered components
        if (!empty($items)) {
            foreach ($items as $key => $item) {
                $this->updateComponent($item, $needUpdatedComponents[$key]);
            }
        }

    }

    /**
     * @param $item
     * @param Component $component
     */
    private function updateComponent($item, Component $component)
    {
        $newActionItems = $item['component_actions'];

        // Update component information
        $component->setComponentName($item['component_name']);
        $component->setComponentRoute($item['component_route']);
        $component->setComponentIcon($item['component_icon']);
        $component->setComponentMenu($item['component_menu']);
        $component->setComponentRank($item['component_rank']);

        // Update old actions
        $componentActions = $component->getComponentActions();
        foreach ($componentActions as $action) {
            $method = $action->getActionMethod();
            if (!array_key_exists($method, $item['component_actions'])) {
                $componentActions->removeElement($action);
                $this->getEntityManager()->remove($action);
            } else {
                unset($newActionItems[$method]);
                $action->setActionName($item['component_actions'][$method]['action_name']);
                $action->setActionIcon($item['component_actions'][$method]['action_icon']);
                $action->setActionMenu($item['component_actions'][$method]['action_menu']);
                $action->setActionRank($item['component_actions'][$method]['action_rank']);
            }
        }

        // Register new actions
        if (!empty($newActionItems)) {
            foreach ($newActionItems as $actionItem) {
                $action = new Action();
                $action->setActionID(Uuid::uuid1()->toString());
                $action->setActionMethod($actionItem['action_method']);
                $action->setActionName($actionItem['action_name']);
                $action->setActionIcon($actionItem['action_icon']);
                $action->setActionMenu($actionItem['action_menu']);
                $action->setActionRank($actionItem['action_rank']);
                $action->setActionComponent($component);
                $componentActions->add($action);
                //instead use: cascade={"persist","remove"} on Entity Column defined.
                //$this->getEntityManager()->persist($action);
            }
        }
        $component->setComponentActions($componentActions);

        $this->getEntityManager()->persist($component);
        $this->getEntityManager()->flush();
    }


    /**
     * @param array $item
     */
    private function registerNewComponent($item)
    {
        $component = new Component();
        $component->setComponentClass($item['component_class']);
        $component->setComponentName($item['component_name']);
        $component->setComponentRoute($item['component_route']);
        $component->setComponentIcon($item['component_icon']);
        $component->setComponentMenu($item['component_menu']);
        $component->setComponentRank($item['component_rank']);

        $componentActions = new ArrayCollection();
        foreach ($item['component_actions'] as $subItem) {
            $action = new Action();
            $action->setActionID(Uuid::uuid1()->toString());
            $action->setActionMethod($subItem['action_method']);
            $action->setActionName($subItem['action_name']);
            $action->setActionIcon($subItem['action_icon']);
            $action->setActionMenu($subItem['action_menu']);
            $action->setActionRank($subItem['action_rank']);
            $action->setActionComponent($component);
            $componentActions->add($action);
            //instead use: cascade={"persist","remove"} on Entity Column defined.
            //$this->getEntityManager()->persist($action);
        }
        $component->setComponentActions($componentActions);

        $this->getEntityManager()->persist($component);
        $this->getEntityManager()->flush();
    }
}