<?php

namespace Tooter\Form;

use Zend\Form\Form;

class ProfileForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('profile');
		$this->setAttribute('method', 'post');
		
		$this->add(array(
            'name' => 'firstName',
            'attributes' => array(
                'type'  => 'text',
				'id'	=> 'firstName',
            ),
        ));
		
		$this->add(array(
            'name' => 'lastName',
            'attributes' => array(
                'type'  => 'text',
				'id'	=> 'lastName',
            ),
        ));
		
		$this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
				'id'	=> 'email',
            ),
        ));
		
		$this->add(array(
            'name' => 'groupUrl',
            'attributes' => array(
                'type'  => 'text',
				'id'	=> 'groupUrl',
            ),
        ));
		
		//add submit button
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
				'id'	=> 'submitbutton',
				'class'	=> 'btn btn-primary'
            ),
        ));
	}
}