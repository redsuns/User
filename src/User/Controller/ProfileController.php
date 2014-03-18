<?php

namespace User\Controller;

use Base\Controller\BaseController;
use User\Form\EditProfile as EditForm;


class ProfileController extends BaseController
{
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel | HttpRedirect
     * @throws \InvalidArgumentException
     */
    public function indexAction()
    {
        $sessionStorage = $this->getServiceLocator()->get('SessionStorage');
        if ( !$sessionStorage->read() ) {
            return $this->redirect()->toRoute('login');
        }
        
        $user = $this->getServiceLocator()->get('User\Service\User')->read($sessionStorage->read()->getId());
        
        if ( !$user ) {
            throw new \InvalidArgumentException('Sua sessão expirou');
        }
        
        $form = new EditForm();
        $service = $this->getServiceLocator()->get('User\Service\User');
        
        $dataToForm = $this->_setDataToForm($user);
        
        $form->setData($dataToForm);
        
        $request = $this->getRequest();
        
        if ( $request->isPost() ) {
            $form->setData($request->getPost()->toArray());
            
            if ( $form->isValid() ) {
                $service->edit($form->getData());
                return $this->redirect()->toRoute('meu-perfil');
            }
        }
        
        return $this->renderView(array('form' => $form, 'user' => $user));
    }
    
    /**
     * 
     * @param \User\Entity\User $user
     * @return array
     */
    protected  function _setDataToForm(\User\Entity\User $user)
    {
        $arrayUser = $user->toArray();
        $userDetailsService = $this->getServiceLocator()->get('User\Service\UserDetail');
        
        $userDetails = $userDetailsService->parseDetails($arrayUser['details']);
        
        $userData = array(
            'id' => $arrayUser['id'],
            'name' => $arrayUser['name'],
            'email' => $arrayUser['email']
        );
        
        $dataToForm = $userData + $userDetails;
        
        return $dataToForm;
    }
    
}
