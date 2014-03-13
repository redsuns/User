<?php

namespace User\Controller;

use Base\Controller\BaseController;


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
    
}
