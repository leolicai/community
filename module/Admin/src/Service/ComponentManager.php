<?php
/**
 * ComponentManager.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/9/1
 * Version: 1.0
 */

namespace Admin\Service;


use Admin\Entity\Component;
use Application\Service\BaseManager;


class ComponentManager extends BaseManager
{


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
        $existedComponents = $this->getAllComponent();
    }
}