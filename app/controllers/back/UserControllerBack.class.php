<?php

class UserControllerBack
{

    public function indexAction()
    {
        $view = new View('back', 'user/index', 'admin');

        $user = new User();
        $view->assign('user', $user);
    }

    public function viewAction(){

    }

    public function newAction(){
        $view = new View('back', 'user/new', 'admin');

        $user = new User();
        $view->assign('user', $user);
    }

    public function addAction($params)
    {
        $user = new User();
        $errors = $user->validate($params);

        if (count($errors) > 0) {
            // Add errors to view
            // Redirect back
            die('error');
        }

        $user->fill($params);
        $user->setRole($params['role']);

        try {
            $user->save();
        } catch (Exception $ex) {
            // TODO Add error message (flash)
            die($ex->getMessage());
        }

        // TODO Add success message (flash)
        Helpers::redirect(Helpers::getAdminRoute('user'));
    }

    public function editAction($params)
    {
        if (!isset($params[0])) {
            die('Missing id');
        }
        $userId = $params[0];

        $view = new View('back', 'user/edit', 'admin');

        $user = new User();
        $user->populate(['id' => $userId]);

        if ($user->getId() < 0) {
            die('User not found');
        }

        $view->assign('user', $user);
    }

    public function deleteAction(){

    }
}
