<?php

namespace User\Controller;

use Base\Controller\BaseController;
use User\Form\EditProfile as EditForm;


class ProfileController extends BaseController
{
    
    public function indexAction()
    {
        $userId = $this->params()->fromRoute('id', 0);
        
        if ( 0 == $userId ) {
            throw new \InvalidArgumentException('Parâmetro inválido recebido');
        }
        
        $form = new EditForm();
        $service = $this->getServiceLocator()->get('User\Service\User');
        $user = $service->read($userId);
        
        $dataToForm = $this->_setDataToForm($user);
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
    protected  function _setDataToForm(\User\Entity\User $user)
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
