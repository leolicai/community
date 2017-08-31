<?php
/**
 * GroupForm.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/31
 * Version: 1.0
 */

namespace Admin\Form;


use Admin\Entity\Group;
use Admin\Service\GroupManager;
use Admin\Validator\GroupNameUniqueValidator;
use Form\Form\BaseForm;
use Form\Validator\Factory;


class GroupForm extends BaseForm
{
    const FIELD_NAME = 'name';

    /**
     * @var Group|null
     */
    private $group;

    /**
     * @var GroupManager
     */
    private $groupManager;


    public function __construct(GroupManager $groupManager, $group = null)
    {

        $this->groupManager = $groupManager;
        $this->group = $group;

        parent::__construct();
    }


    /**
     * 表单: 部门名称
     */
    private function addGroupName()
    {
        $validators = [
            Factory::StringLength(2, 45),
            [
                'name' => GroupNameUniqueValidator::class,
                'break_chain_on_failure' => true,
                'options' => [
                    'groupManager' => $this->groupManager,
                    'group' => $this->group,
                ],
            ],
        ];

        $this->addTextElement(self::FIELD_NAME, true, $validators);
    }


    public function addElements()
    {
        $this->addGroupName();
    }

}