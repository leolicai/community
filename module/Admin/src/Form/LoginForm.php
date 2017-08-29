<?php
/**
 * LoginForm.php
 *
 * Author: leo <camworkster@gmail.com>
 * Date: 2017/8/28
 * Version: 1.0
 */

namespace Admin\Form;


use Form\Form\BaseForm;
use Form\Validator\Factory as ValidatorFactory;


class LoginForm extends BaseForm
{

    /**
     * Form account
     */
    private function addFormAccount()
    {
        $this->addEmailElement('email');
    }

    /**
     * Form password
     */
    private function addFormPassword()
    {
        $this->addPasswordElement('password', [ValidatorFactory::StringLength(4, 20)]);
    }

    /**
     * Form elements
     */
    public function addElements()
    {
        $this->addFormAccount();
        $this->addFormPassword();
    }
}