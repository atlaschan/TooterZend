<?php

namespace stormpath\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tooter\Service;
use Tooter\Model\Dao\DefaultCustomerDao;
use Tooter\Model\User;
use Tooter\Model\Status;
use Tooter\Model\Error; 
use Tooter\Form\LoginForm;

/*
	Login Controller and Logout Controller
*/
class IndexController extends AbstractActionController
{
	private $stormpath;
	
    public function loginAction()
    {
		require "stormpathconfig/vars.php";
		
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
		
				$status = $this->submit($user);
				
				if($status->getStatus() == Service::SUCCESS)
				{
					$obj = $status->getObj();
					$_SESSION["user"] = $obj["user"];
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
		
		return array('form' => $form, 'messages'=>$messages,	'base_directory'=>$base_directory,	
					'current_directory'=>$current_directory,	'application_property'=>$application_property);
    }
	
	
	/*
	* Handle the main logic of authentication
	*/
	private function submit($customer)
	{
		$customerDao = new DefaultCustomerDao($this->stormpath->getConnector());
		
		$status = new Status();
		
		try
		{
			$request = new \Services_Stormpath_Authc_UsernamePasswordRequest($customer->getUserName(), $customer->getPassword());
			
			$authcResult = $this->stormpath->getApplication()->authenticateAccount($request);
			
			$account = $authcResult->getAccount();
			
			$user = User::constructWithAccount($account);
			
			$dbCustomer = $customerDao->getCustomerByUserName($customer->getUserName());
			if(empty($dbCustomer))
				$customerDao->saveCustomer($user);
			if($dbCustomer != null)
				$user->setId($dbCustomer->getId());
			
			if(!empty($user))
			{
				$status = new Status();
				$status->setStatus(Service::SUCCESS);
				$status->setObj(array("user"=>$user));
			} 
		} 
		catch(\Exception $e)
		{
			$status = new Status();
			$status->setStatus(Service::FAILED);
			$status->setError(new Error("customer.errors", "help-block", $e->getMessage()));
		}
		
		return $status;
		
	}
	
	public function logoutAction()
	{
		require "stormpathconfig/vars.php";
	
		if(isset($_SESSION["user"]))
			unset($_SESSION["user"]);
			
		return array('messages'=>$messages,	'base_directory'=>$base_directory,	
			'current_directory'=>$current_directory,	'application_property'=>$application_property);
	}
	
	/*
	public function tooterAction()
	{
		
	}*/
	
	
	/*
	
	public function profileAction()
	{
		
	}
	
	public function resetPasswordAction()
	{
		
	}
	
	public function resetPasswordMsgAction()
	{
		
	}
	
	public function signUpAction()
	{
		
	}
	
	public function tooterAction()
	{
		
	}
	
	public function changePasswordAction()
	{
		
	}
	*/
	/*
	public function viewAction()
	{
		$listingKey = $this->params()->fromRoute("key", 0);
		$sequence = $this->params()->fromRoute("sequence", 0);
		return new ViewModel(array(
            'listingKey' => $listingKey,
			'sequence' => $sequence,
        ));
	}*/
}

?>