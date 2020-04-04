<?php

namespace App\Controllers;

use App\Core\Helpers;
use App\Models\TaskModel;

class TaskController extends Controller
{
    /** @var TaskModel */
    protected $taskModel;

    /**
     * Create new UsersController instance.
     */
    public function __construct()
    {
        $this->taskModel = $this->model('TaskModel');
    }

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->tableAction();
    }

    /**
     * @return void
     */
    public function tableAction() {
        $data = $this->taskModel->getTasks(Helpers::parseQueryString());

        $this->view('task/table', [
            'tasks'           => $data['tasks'],
            'paginationMeta'  => $data['paginationMeta'],
            'paginationLinks' => $data['paginationLinks'],
            'columnsMeta'     => $this->taskModel->getColumnsMeta(),
        ]);
    }

}
