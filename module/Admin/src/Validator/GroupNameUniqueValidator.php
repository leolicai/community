<?php
/**
 * GroupNameUniqueValidator.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/31
 * Version: 1.0
 */

namespace Admin\Validator;


use Admin\Entity\Group;
use Admin\Service\GroupManager;
use Zend\Validator\AbstractValidator;


class GroupNameUniqueValidator extends AbstractValidator
{


    const NAME_EXISTED = 'nameExisted';

    protected $options = [
        'groupManager' => null,
        'group' => null,
    ];

    protected $messageTemplates = [
        self::NAME_EXISTED => '这个名称已经被使用了',
    ];


    public function __construct($options = null)
    {
        if (is_array($options)) {
            if (isset($options['groupManager'])) {
                $this->options['groupManager'] = $options['groupManager'];
            }
            if (isset($options['group'])) {
                $this->options['group'] = $options['group'];
            }
        }

        parent::__construct($options);
    }


    public function isValid($value)
    {

        $groupManager = $this->options['groupManager'];
        $group = $this->options['group'];

        if (!$groupManager instanceof GroupManager) {
            $this->error(self::NAME_EXISTED);
            return false;
        }

        $existedGroup = $groupManager->getGroupByName($value);

        if (!$group instanceof Group) { // Created
            if (!$existedGroup instanceof Group) {
                return true;
            } else {
                $this->error(self::NAME_EXISTED);
                return false;
            }
        } else {
            $name = $group->getGroupName();
            if ($value == $name) { // No modified
                return true;
            } else {
                if (!$existedGroup instanceof Group) { // modified to new name. unique.
                    return true;
                } else { // The new name is existed.
                    $this->error(self::NAME_EXISTED);
                    return false;
                }
            }
        }
    }


}