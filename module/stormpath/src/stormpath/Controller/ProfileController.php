<?php

namespace stormpath\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tooter\Service;
use Tooter\Model\User;
use Tooter\Model\Status;
use Tooter\Model\Error; 
use Tooter\Util\PermissionUtil;
use Tooter\Form\ProfileForm;
use Tooter\Validator\ProfileValidator;

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
		
		$user = $_SESSION["user"];
		$updateProfile = false;
		$error = null;
				
		$form = new ProfileForm();
		$request = $this->getRequest();
		if($request->isPost())
		{
			$modifiedUser = new User();
			
			$form->setData($request->getPost());
			if ($form->isValid()) {
				
				$this->stormpath = $stormpath; //initializing the service
				
                $modifiedUser->exchangeArray($form->getData());
				
				$status = $this->submit($user, $modifiedUser);
				
				if($status->getStatus() == Service::SUCCESS)
					$updateProfile = true;
				else
					$error = $status->getError();
            }
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
					'updateProfile' => $updateProfile, 
					'error' => $error);
    }
	
	
	public function submit($user, $modifiedUser)
	{		
		$profileValidator = new ProfileValidator;
		$status = new Status();
		
		$checked = $profileValidator->validate($modifiedUser);
		if(!empty($checked))
			return $checked;
		
		try
		{
			$account = $user->getAccount();
			if($user->getFirstName() != $modifiedUser->getFirstName())
				$account->setGivenName($modifiedUser->getFirstName());
			if($user->getLastName() != $modifiedUser->getLastName())
				$account->setSurname($modifiedUser->getLastName());
			if($user->getEmail() != $modifiedUser->getEmail())
				$account->setEmail($modifiedUser->getEmail());
			
			$groupUrl = $modifiedUser->getGroupUrl();
			
			// remove the user's group memberships first.
			$dataStore = $this->stormpath->getDataStore();
			$groupExist = false; // possibly the group has been there for the account, set up one flag
			$memberships = $account->getGroupMemberships();
			foreach($memberships as $membership)
			{
				if(!empty($groupUrl))
				{
					// remove all groups that are not the target group
					$currentGroupName = $membership->getGroup()->getName();
					$group = $dataStore->getResource($groupUrl, \Services_Stormpath::GROUP);
					if($currentGroupName != $group->getName())
						$membership->delete();
					else
						$groupExist = true;
				} 
				else
					$membership->delete();
			}
			
			// if the Basic is selected, or the target group has been selected, the add action will not be added
			if(!empty($groupUrl) and $groupExist == false)
			{
				$group = $dataStore->getResource($groupUrl, \Services_Stormpath::GROUP);
				$account->addGroup($group);
			}
			
			$account->save();

			$status->setStatus(Service::SUCCESS);
			
		} 
		catch(Exception $e)
		{
			$status->setStatus(Service::FAILED);
			$status->setError(new Error("customer.errors", "help-block", $e->getMessage()));
		}
		
		return $status;
	}

}

?>