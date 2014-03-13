<?php

namespace UserAdmin\Controller;

use Base\Controller\BaseController;
use Zend\Session\Container;
use Zend\Form\Element;


class UserController extends BaseController
{
    
    protected $route = 'admin-internals';
    protected $controller = 'users';

    public function indexAction()
    {
        $users = $this->getServiceLocator()->get('UserAdmin\Service\User')->getAllUsers();

        $elementHidden = new Element\Hidden();
        $elementSubmit = new Element\Submit();
        
        $pars = array(
                'users' => $users,
                'form' => $this->getServiceLocator()->get('UserAdmin\Form\DeleteUser'),
                'elementHidden' => $elementHidden,
                'elementSubmit' => $elementSubmit
        );

        return $this->renderView($pars);
    }

    public function addAction()
    {
        $form = $this->getServiceLocator()->get('UserAdmin\Form\NewUser');
        $elementSelect = new Element\Select();

        $userService = $this->getServiceLocator()->get('UserAdmin\Service\User');
        $roleService = $this->getServiceLocator()->get('UserAdmin\Service\Role');
        $roles = $roleService->getAllRoles();
        
        $request = $this->getRequest();

        if ( $request->isPost() ){
            $form->setData($request->getPost()->toArray());

            if ( $form->isValid() ) {
                $userService->save($form->getData());

                // @todo - Adicionar mensagem flash
                $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
            }
        }

        return $this->renderView(array(
            'form' => $form,
            'elementSelect' => $elementSelect,
            'roles' => $roles)
        );
    }

    public function editAction()
    {
        $userService = $this->getServiceLocator()->get('UserAdmin\Service\User');

        $userId = $this->params()->fromRoute('id', 0);
        $user = $userService->read($userId);
        $roles = $this->getServiceLocator()->get('UserAdmin\Service\Role')->getAllRoles();

        $form = $this->getServiceLocator()->get('UserAdmin\Form\EditUser');
        $elementHidden = new Element\Hidden();
        $elementSelect = new Element\Select();

        if ( $user ) {
            $form->setdata($user->toArray());
            $request = $this->getRequest();

            if ( $request->isPost() ) {

                $form->setData($request->getPost()->toArray());
                if ( $form->isValid() ) {
                    // @todo - Adicionar mensagem flash
                    $userService->save($form->getData());
                }

                return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
            }

        } else {
            return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
        }

        return $this->renderView(array(
            'user' => $user,
            'roles' => $roles,
            'form' => $form,
            'elementHidden' => $elementHidden,
            'elementSelect' => $elementSelect)
        );
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $userService = $this->getServiceLocator()->get('UserAdmin\Service\User');

        if ( $request->isPost() ) {
            $requestedUser = $request->getPost()->toArray();

            // @todo - Adicionar mensagem flash
            $userService->delete( $requestedUser['id'] );
        }

        return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
    }

}
