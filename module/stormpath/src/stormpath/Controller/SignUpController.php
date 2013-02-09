<?php

namespace stormpath\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tooter\Service;
use Tooter\Model\Dao\DefaultCustomerDao;
use Tooter\Model\User;
use Tooter\Model\Status;
use Tooter\Model\Error; 
use Tooter\Form\SignUpForm;
use Tooter\Validator\SignUpValidator;

/*
	Login Controller and Logout Controller
*/
class SignUpController extends AbstractActionController
{
	private $stormpath;
	
    public function signUpAction()
    {
		require "stormpathconfig/vars.php";
		
		$form = new SignUpForm();
		$request = $this->getRequest();
		
		if($request->isPost())
		{	
			$user = new User();
			
			$form->setData($request->getPost());
			if ($form->isValid()) {
				
				$this->stormpath = $stormpath; //initializing the service
				
                $user->exchangeArray($form->getData());
				
				$status = $this->processSubmit($user);
				
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
		}
		
		return array('messages'=>$messages,	
					'base_directory'=>$base_directory,	
					'current_directory'=>$current_directory,	
					'application_property'=>$application_property);
    }

	
	private function processSubmit($user)
	{
		$signUpValidator = new SignUpValidator;
		$checked = $signUpValidator->validate($user);
		if(!empty($checked))
			return $checked;
		
		$status = new Status();
		
		//$returnStatus = array();
		try{
			$userName = strtolower($user->getFirstName()) + strtolower($user->getLastName());
			
			// Create the account in the Directory where the Tooter application belongs.
			$directory = $this->stormpath->getDataStore()->getResource($this->stormpath->getDirectoryURL(), \Services_Stormpath::DIRECTORY);
			
			$account = $this->stormpath->getDataStore()->instantiate(\Services_Stormpath::ACCOUNT);
			$account->setEmail($user->getEmail());
			$account->setGivenName($user->getFirstName());
			$account->setPassword($user->getPassword());
			$account->setSurname($user->getLastName());
			$account->setUsername($userName);
			
			$account = $directory->createAccount($account);
			
			$user->setUserName($userName);
			$this->customerDao->save($user);
			
			$status->setStatus(Service::SUCCESS);
			$status->setObj(array("user"=>$user));
		}
		catch (\Exception $e)
		{
			$status->setStatus(Service::FAILED);
			$status->setError(new Error("signup.errors", "errorblock", $e->getMessage()));
		}
		
		return $status;
	}
	
}

?>