<?php

class Page extends BaseSql implements Listable, Editable
{
    const TEMPLATE_ID = 'id';
    const TEMPLATE_NAME = 'name';
    const TEMPLATE_PREVIEW = 'preview';

    protected $id;
    protected $title;
    protected $description;
    protected $url;
    protected $visibility;
    protected $publish;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getVisibility()
    {
        return $this->visibility;
    }

    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

    public function getPublish()
    {
        return $this->publish;
    }

    public function setPublish($publish)
    {
        $this->publish = $publish;
    }

    public function getListConfig()
    {
        return [
            Listable::LIST_STRUCT => [
                Listable::LIST_TITLE => 'Pages',
                Listable::LIST_NEW_LINK => Helpers::getAdminRoute('page/new'),
                Listable::LIST_EDIT_LINK => Helpers::getAdminRoute('page/edit'),
                Listable::LIST_HEADER => [
                    '',
                    'ID',
                    'Title',
                    'Last update',
                    'Visible',
                    'Action'
                ]
            ],
            Listable::LIST_ROWS => $this->getListData()
        ];
    }

    public function getListData()
    {
        $pages = $this->getAll();

        $listData = [];

        /** @var Page $page */
        foreach ($pages as $page) {
            $pageData = [
                [
                    'type' => 'checkbox',
                    'value' => ''
                ],
                [
                    'type' => 'text',
                    'value' => $page->getId()
                ],
                [
                    'type' => 'text',
                    'value' => $page->getTitle()
                ],
                [
                    'type' => 'text',
                    'value' => 'TODO'
                ],
                [
                    'type' => 'text',
                    'value' => $page->getVisibility()
                ],
                [
                    'type' => 'action',
                    'id' => $page->getId()
                ]
            ];

            $listData[] = $pageData;
        }

        return $listData;
    }

    public function getFormConfig()
    {
        return [
            Editable::FORM_STRUCT => [
                Editable::FORM_METHOD => 'post',
                Editable::FORM_ACTION => Helpers::getAdminRoute('page/add'),
                Editable::FORM_SUBMIT => 'Save'
            ],
            Editable::FORM_GROUPS => [
                [
                    Editable::GROUP_LABEL => 'Search Engine Optimisation',
                    Editable::GROUP_FIELDS => [
                        'title' => [
                            'type' => 'text',
                            'label' => 'Title',
                            'required' => 1
                        ],
                        'url' => [
                            'type' => 'text',
                            'label' => 'URL',
                            'required' => 1
                        ],
                        'description' => [
                            'type' => 'textarea',
                            'label' => 'Description',
                            'required' => 1
                        ],
                        'publish' => [
                            'type' => 'checkbox',
                            'label' => 'Publié',
                            'value' => 1
                        ],
                        'visibility' => [
                            'type' => 'checkbox',
                            'label' => 'Visible',
                            'value' => 1
                        ]
                    ]
                ],
                [
                    Editable::GROUP_LABEL => 'Content',
                    Editable::GROUP_FIELDS => [
                        'preview' => [
                            'type' => 'widget',
                            'id' => 'page/new'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getConstraints()
    {
        return [
            'title' => [
                'required' => 1,
                'min' => 4
            ],
            'url' => [
                'unique' => 1,
                'require' => 1,
                'min' => 3
            ],
            'description' => [
                'min' => 5
            ]
        ];
    }
}