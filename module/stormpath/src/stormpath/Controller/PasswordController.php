<?php

namespace stormpath\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Tooter\Service;
use Tooter\Model\Dao\DefaultCustomerDao;
use Tooter\Model\User;
use Tooter\Model\Status;
use Tooter\Model\Error; 
use Tooter\Form\ResetPasswordForm;
use Tooter\Validator\ResetPasswordValidator;

/*
	Login Controller and Logout Controller
*/
class PasswordController extends AbstractActionController
{
	private $stormpath;
	
    public function resetPasswordAction()
    {
		require "stormpathconfig/vars.php";
		
		$form = new ResetPasswordForm();
		$request = $this->getRequest();
		
		if($request->isPost())
		{	
			$user = new User();
			
			$form->setData($request->getPost());
			if ($form->isValid()) {
			
				$this->stormpath = $stormpath; //initializing the service
                $user->exchangeArray($form->getData());
				
				$status = $this->processResetPassword($user, $user->getEmail());
				if($status->getStatus() == Service::SUCCESS)
				{
					return $this->redirect()->toRoute('reset-password-msg');
				}
				else
				{
					$error = $status->getError();
					return array('form'=>$form, 
								'messages'=>$messages,	
								'base_directory'=>$base_directory,	
								'current_directory'=>$current_directory,	
								'application_property'=>$application_property,
								'error'=>$error);
				}
            }
		}
		
		return array('form'=>$form, 
					'messages'=>$messages,	
					'base_directory'=>$base_directory,	
					'current_directory'=>$current_directory,	
					'application_property'=>$application_property);
    }
	
	
	private function processResetPassword($customer, $email)
	{
		$resetPasswordValidator = new ResetPasswordValidator;
		$checked = $resetPasswordValidator->validate($email);
		if(!empty($checked))
			return $checked;
		
		$status = new Status();
		
		try
		{
			$this->stormpath->getApplication()->sendPasswordResetEmail($email);
			$status->setStatus(Service::SUCCESS);
		}
		catch(\Exception $e)
		{
			$status->setStatus(Service::FAILED);
			$status->setError(new Error("password.errors", "errorblock", $e->getMessage()));
		}
		
		return $status;
	}
	
	public function resetPasswordMsgAction()
	{
		require "stormpathconfig/vars.php";
		
		return array('messages'=>$messages,	
					'base_directory'=>$base_directory,	
					'current_directory'=>$current_directory,	
					'application_property'=>$application_property);
	}
	
	public function changePasswordAction()
	{
		require "stormpathconfig/vars.php";
		
		return array('messages'=>$messages,	'base_directory'=>$base_directory,	
			'current_directory'=>$current_directory,	'application_property'=>$application_property);
	}

}

?>