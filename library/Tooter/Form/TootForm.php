<?php

namespace Tooter\Form;

use Zend\Form\Form;

class TootForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('toot');
		$this->setAttribute('method', 'post');
		
		//add toot message
		$this->add(array(
            'name' => 'tootMessage',
            'attributes' => array(
                'type'  => 'text',
				'id'	=> 'tootMessage',
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