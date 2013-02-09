<?php

namespace Tooter\Form;

use Zend\Form\Form;

class ResetPasswordForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('user');
		$this->setAttribute('method', 'post');
		
		//add email
		$this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
				'id'	=> 'email',
            ),
        ));
		
		//add submit button
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
				'class'	=> 'btn btn-primary'
            ),
        ));
	}
}