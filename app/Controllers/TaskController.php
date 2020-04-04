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

    /**
     * @return void
     */
    public function createAction()
    {
        $checkedData = $this->taskModel->validateTaskForm($_POST);
        $newTaskID = Helpers::getFlash('newTaskID');
        Helpers::deleteFlash('newTaskID');

        // Check if submit and valid form
        if ($checkedData['isSubmitForm'] && $checkedData['isValidForm']) {
            $newTaskID = $this->taskModel->save($checkedData['formData']);
            if ($newTaskID) {
                Helpers::setFlash('newTaskID', $newTaskID);
                Helpers::redirectTo('task/create');
            } else {
                $checkedData['isValidForm'] = false;
                $checkedData['errorMessage'][] = 'Error!!! Can\'t save new Task. Please try again.';
            }
        }

        $this->view('task/create', [
            'newTaskID'    => $newTaskID,
            'isAdminAuth'  => Helpers::isAdminAuth(),
            'isValidForm'  => $checkedData['isValidForm'],
            'isSubmitForm' => $checkedData['isSubmitForm'],
            'errorMessage' => implode(' ', $checkedData['errorMessage']),
            'formData'     => $checkedData['formData'],
            'formErrors'   => $checkedData['formErrors'],
            'formAction'   => Helpers::path('task/create'),
            'submitAction' => 'createTask',
            'submitLabel'  => 'Create Task',
        ]);
    }

    /**
     * @param int $taskId
     *
     * @return void
     */
    public function editAction($taskId = 0)
    {
        $task = $this->taskModel->getById((int) $taskId);
        if (!$task) {
            Helpers::redirectTo('task/table');
        }

        $checkedData = $this->taskModel->validateTaskForm($_POST);
        $updated = Helpers::getFlash('updated');
        Helpers::deleteFlash('updated');

        // Check if submit and valid form
        if ($checkedData['isSubmitForm']) {
            if ($checkedData['isValidForm']) {
                $updated = $this->taskModel->update($checkedData['formData'], (int) $taskId);
                if ($updated) {
                    Helpers::setFlash('updated', (bool) $updated);
                    Helpers::redirectTo('task/edit/' . $taskId);
                } else {
                    $checkedData['isValidForm'] = false;
                    $checkedData['errorMessage'][] = 'Error!!! Can\'t update Task. Please try again.';
                }
            }
        } else {
            $checkedData['formData'] = $task;
        }

        $this->view('task/edit', [
            'updated'      => $updated,
            'isAdminAuth'  => Helpers::isAdminAuth(),
            'isValidForm'  => $checkedData['isValidForm'],
            'isSubmitForm' => $checkedData['isSubmitForm'],
            'errorMessage' => implode(' ', $checkedData['errorMessage']),
            'formData'     => $checkedData['formData'],
            'formErrors'   => $checkedData['formErrors'],
            'formAction'   => Helpers::path('task/edit/' . $taskId),
            'submitAction' => 'editTask',
            'submitLabel'  => 'Update Task',
        ]);
    }
}
