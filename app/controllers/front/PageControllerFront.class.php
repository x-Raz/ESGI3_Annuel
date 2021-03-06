<?php

class PageControllerFront extends Front
{

    public function indexAction($params = [])
    {
        parent::indexAction($params);

        if (empty($params[Routing::PARAMS_URL][0])) {
            $params[Routing::PARAMS_URL][0] = '/';
        }

        $page = new Page();
        $page->populate(['url' => $params[Routing::PARAMS_URL][0]]);

        if ($page->id() === null || !$page->publish()) {
            Helpers::error404();
        }

        $components = $page->getComponents();
        $componentsRendered = [];
        foreach ($components as $component) {
            $componentsRendered[] = $this->generateComponent($component);
        }

        $this->view->setView('page');
        $this->view->assign('components', $componentsRendered);
        $this->view->assign('title', $page->title());
        $this->view->assign('description', $page->description());
    }

    private function generateComponent($data)
    {
        if (!isset($data['template_id'])) {
            return false;
        }
        $templateId = $data['template_id'];

        ob_start();
        include 'themes/templates/default/components/template' . $templateId . '.php';
        $render = ob_get_clean();

        return $render;
    }
}
