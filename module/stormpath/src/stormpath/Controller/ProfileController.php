<?php

namespace stormpath\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tooter\Service;
use Tooter\Model\Dao\DefaultCustomerDao;
use Tooter\Model\User;
use Tooter\Model\Status;
use Tooter\Model\Error; 

/*
	Login Controller and Logout Controller
*/
class ProfileController extends AbstractActionController
{
	private $stormpath;
	
    public function profileAction()
    {
		require "stormpathconfig/vars.php";
		
		if(!isset($_SESSION["user"]))
		{
			return $this->redirect()->toRoute('login'); //redirect to login page if not authenticated yet
		} 
		
		/*
		$form = new LoginForm();
		$request = $this->getRequest();
		
		if($request->isPost())
		{
			
			$user = new User();
			$form->setInputFilter($user->getInputFilter());
			
			$form->setData($request->getPost());
			if ($form->isValid()) {
				
				$this->stormpath = $stormpath; //initializing the service
				
                $user->exchangeArray($form->getData());
				
				if($status->getStatus() == Service::SUCCESS)
				{

					return $this->redirect()->toRoute('tooter');
				}
				else
				{
					$error = $status->getError();
					return array('form' => $form, 'messages'=>$messages,	'base_directory'=>$base_directory,	
						'current_directory'=>$current_directory,	'application_property'=>$application_property,
						'error'=>$error);
				}
            }
		}*/
		
		$user = (isset($_SESSION["user"])) ? $_SESSION["user"] : null;
		return array('messages'=>$messages,	
					'base_directory'=>$base_directory,	
					'current_directory'=>$current_directory,	
					'application_property'=>$application_property,
					'user'=> $user);
    }
	
	
	

}

?>