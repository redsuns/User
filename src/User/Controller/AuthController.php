<?php

namespace User\Controller;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Base\Controller\BaseController;
use User\Form\Login as LoginForm;


class AuthController extends BaseController
{
 
    /**
     * 
     * @return HttpRedirect|array
     */
    public function indexAction()
    {
        $error = false;
        $form = new LoginForm();
        $request = $this->getRequest();
        
        if ( $request->isPost() ){
            $data = $request->getPost()->toArray();
            $form->setData($data);
            
            if ( $form->isValid() ) {
                
                $auth = new AuthenticationService();
                
                $sessionStorage = new SessionStorage('User');
                
                $auth->setStorage($sessionStorage);
                
                $authAdapter = $this->getServiceLocator()->get('User\Auth\Adapter');
                $authAdapter->setUsername($data['email'])->setPassword($data['password']);
                  
                $result = $auth->authenticate($authAdapter);
                
                if ( $result->isValid() ) {
                    $identity = $auth->getIdentity();
                    $sessionStorage->write($identity['user']);
                    return $this->redirect()->toRoute('home');
                }
                
                $error = true;
            }
        }
        
        return $this->renderView(array('form' => $form, 'error' => $error));
    }
    
    /**
     * 
     * @return HttpRedirect
     */
    public function logoutAction()
    {
        $auth = new AuthenticationService();
        $auth->setStorage(new SessionStorage('User'));
        $auth->clearIdentity();
        
        return $this->redirect()->toRoute('login');
    }
    
}
