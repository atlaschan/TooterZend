<?php

namespace stormpath\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tooter\Service;
use Tooter\Model\Dao\DefaultTootDao;
use Tooter\Model\Dao\DefaultCustomerDao;
use Tooter\Model\Toot;
use Tooter\Model\Status;
use Tooter\Model\Error; 
use Tooter\Model\User;
use Tooter\Form\TootForm;
use Tooter\Validator\TootValidator;
use Tooter\Util\PermissionUtil;

/*
	Login Controller and Logout Controller
*/
class TootController extends AbstractActionController
{
	private $stormpath;
	
    public function tooterAction()
    {
		require "stormpathconfig/vars.php";
		
		if(!isset($_SESSION["user"]))
		{
			return $this->redirect()->toRoute('login'); //redirect to login page if not authenticated yet
		} 
		
		$user = $_SESSION["user"];
		$error = null;
		
		$form = new TootForm();
		$request = $this->getRequest();
		
		$this->stormpath = $stormpath; //initializing the service
		if($request->isPost())
		{
			$toot = new Toot();
			
			$form->setData($request->getPost());
			if ($form->isValid()) {				
                $toot->exchangeArray($form->getData());
				
				$status = $this->submit($toot);
				
				if($status->getStatus() != Service::SUCCESS)
					$error = $status->getError();
            }
		} 
		else
		{
			$this->retrieveToots();
		}
		
		$permissionUtil = new PermissionUtil($application_property);
		$isAdmin = $permissionUtil->hasRole($user, "ADMINISTRATOR");
		$isPremium = $permissionUtil->hasRole($user, "PREMIUM_USER");

		return array('messages'=>$messages,	
					'base_directory'=>$base_directory,	
					'current_directory'=>$current_directory,	
					'application_property'=>$application_property,
					'user'=> $user,
					'isAdmin' => $isAdmin,
					'isPremium' => $isPremium, 
					'error' => $error);
    }
	
	private function submit($toot)
	{
		$tootValidator = new TootValidator;
		$status = new Status();
	
		$checked = $tootValidator->validate($toot);
		if(!empty($checked))
			return $checked;
		
		//act on the user stored in the session directly and so no reutrn value is needed for representing user
		$user = $_SESSION["user"];
		$persistCustomer = User::constructWithUser($user);
		
		$tootList;
		$persistToot = new Toot;
		$persistToot->setTootMessage($toot->getTootMessage());
        $persistToot->setCustomer($persistCustomer);
		
		$tootDao = new DefaultTootDao($this->stormpath->getConnector());
		
		try
		{
			$tootDao->saveToot($persistToot);
			$toot->setTootId($persistToot->getTootId());
			
			
			$tootList = $tootDao->getTootsByUserId($persistCustomer->getId());

			foreach ($tootList as $key=>$itemToot) {
				$itemToot->setCustomer($user);
			}

			krsort($tootList, \SORT_NUMERIC);
			$user->setTootList($tootList);
			
			$status->setStatus(Service::SUCCESS);
			
		}
		catch(\Exception $e)
		{
			$status->setStatus(Service::FAILED);
		}
		
		return $status;
	}

	private function retrieveToots()
	{
		$tootDao = new DefaultTootDao($this->stormpath->getConnector());
		$customerDao = new DefaultCustomerDao($this->stormpath->getConnector());
	
		$user = $_SESSION["user"];
		$customer = $customerDao->getCustomerByUserName($user->getUserName());
		
		$tootList = $tootDao->getTootsByUserId($customer->getId());

		foreach ($tootList as $key=>$itemToot) {
			$itemToot->setCustomer($user);
		}

		krsort($tootList, SORT_NUMERIC);
		$user->setTootList($tootList);
	}
	
}

?>