<?php

namespace User\Controller;

use Base\Controller\BaseController;
use User\Form\EditProfile as EditForm;

class RegisterController extends BaseController
{
    
    public function indexAction()
    {
        $form = $this->getServiceLocator()->get('User\Form\Register');
        $service = $this->getServiceLocator()->get('User\Service\User');
         
        $request = $this->getRequest();
        
        if ( $request->isPost() ) {
            
            $form->setData($request->getPost()->toArray());
            
            if ( $form->isValid() ) {
                
                $service->save( $form->getData() );
                return $this->redirect()->toRoute('home');
                
            }
        }
        
        return $this->renderView(array('form' => $form));
    }
    
    public function editAction()
    {
        $userId = $this->params()->fromRoute('id', 0);
        
        if ( 0 == $userId ) {
            throw new \InvalidArgumentException('Parâmetro inválido recebido');
        }
        
        $form = new EditForm();
        $service = $this->getServiceLocator()->get('User\Service\User');
        $user = $service->read($userId);
        
        $dataToForm = $this->setDataToForm($user);
        $form->setData($dataToForm);
        
        $request = $this->getRequest();
        
        if ( $request->isPost() ) {
            $form->setData($request->getPost()->toArray());
            
            if ( $form->isValid() ) {
                $service->edit($form->getData());
                return $this->redirect()->toRoute('editar-perfil', array('action' => 'edit', 'id' => $user->getId()));
            }
            
        }
        
        return $this->renderView(array('form' => $form));
    }
    
    /**
     * 
     * @param \User\Entity\User $user
     * @return array
     */
    public function setDataToForm(\User\Entity\User $user)
    {
        $arrayUser = $user->toArray();
        $userDetailsService = $this->getServiceLocator()->get('User\Service\UserDetail');
        
        $userDetails = $userDetailsService->parseDetails($arrayUser['detail']);
        
        
        $userData = array(
            'id' => $arrayUser['id'],
            'name' => $arrayUser['name'],
            'email' => $arrayUser['email']
        );
        
        $dataToForm = $userData + $userDetails;
        
        return $dataToForm;
    }
    
}
