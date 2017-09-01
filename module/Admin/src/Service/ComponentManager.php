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
use Application\Service\BaseManager;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;


class ComponentManager extends BaseManager
{

    /**
     * @param string $class
     * @return null|object|Component
     */
    public function getComponentByClass($class)
    {
        return $this->getEntityManager()->getRepository(Component::class)->find($class);
    }


    /**
     * @return Component[]
     */
    public function getAllComponent()
    {
        return $this->getEntityManager()->getRepository(Component::class)->findAll();
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