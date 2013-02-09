<?php

namespace Tooter\Form;

use Zend\Form\Form;

class LoginForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('customer');
		$this->setAttribute('method', 'post');
		
		//add user name
		$this->add(array(
            'name' => 'userName',
            'attributes' => array(
                'type'  => 'text',
				'id'	=> 'userName',
            ),
        ));
		
		//add password
		$this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
				'id'	=> 'password',
            ),
        ));
		
		//add submit button
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
				'value' => 'Go',
				'id'	=> 'submitbutton',
				'class'	=> 'btn btn-primary'
            ),
        ));
	}
}