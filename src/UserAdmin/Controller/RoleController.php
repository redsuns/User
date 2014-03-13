<?php

namespace UserAdmin\Controller;

use Base\Controller\BaseController;
use UserAdmin\Form\EditRole;
use Zend\Session\Container;
use UserAdmin\Form\NewRole;
use UserAdmin\Form\DeleteRole;
use Zend\Form\Element;


class RoleController extends BaseController
{
    
    protected $route = 'admin-internals';
    protected $controller = 'roles';
    
    public function indexAction()
    {
        $rs = $this->getServiceLocator()->get('UserAdmin\Service\Role');

        $roles = $rs->getAllRoles();
        $form = new DeleteRole();
        $elementHidden = new Element\Hidden();
        $elementSubmit = new Element\Submit();
        
        $pars = array(
                'roles' => $roles,
                'form' => $form,
                'elementHidden' => $elementHidden,
                'elementSubmit' => $elementSubmit
        );

        return $this->renderView($pars);
    }
    
    
    public function addAction()
    {
        $form = new NewRole();
        
        $request = $this->getRequest();

        if ( $request->isPost() ){
            $form->setData($request->getPost()->toArray());
            
            if ( $form->isValid() ) {
                $rs = $this->getServiceLocator()->get('UserAdmin\Service\Role');
                $rs->save($form->getData());

                // @todo - Adicionar mensagem flash
                $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
            }
        }
        
        return $this->renderView(array('form' => $form));
    }


    public function editAction()
    {
        $rs = $this->getServiceLocator()->get('UserAdmin\Service\Role');
        $roleId = $this->params()->fromRoute('id', 0);
        $role = $rs->read($roleId);

        $form = new EditRole();

        if ( 0 !== $roleId && $role ) {

            $form->setdata($role->toArray());

            $request = $this->getRequest();

            if ( $request->isPost() ) {

                $form->setData($request->getPost()->toArray());
                if ( $form->isValid() ) {
                    $rs->save($form->getData());
                    // @todo - Adicionar mensagem flash
                }

                $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
            }

        } else {
            $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }

        return $this->renderView(array('role' => $role, 'form' => $form));
    }


    public function deleteAction()
    {
        $rs = $this->getServiceLocator()->get('UserAdmin\Service\Role');
        $request = $this->getRequest();

        if ( $request->isPost() ) {
            $requestedRole = $request->getPost()->toArray();

            $rs->delete( $requestedRole['id'] );
            //TODO Adicionar mensagem de sucesso
        }

        return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
    }

}
