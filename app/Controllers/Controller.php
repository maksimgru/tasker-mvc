<?php

namespace App\Controllers;

class Controller
{
    /**
     * @param string $modelName The name of the Model class
     *
     * @return object Model
     */
    protected function model($modelName)
    {
        $model = 'App\\Models\\' . $modelName;

        return new $model;
    }

    /**
     * @param string $viewName The name of the view. This is rel-path to the file in views folder.
     * @param array  $data The data that passed in view file.
     *
     * @return void
     */
    protected function view(string $viewName, array $data = [])
    {
        $viewFilePath = '../app/Views/' . strtolower($viewName) . '.php';
        if (file_exists($viewFilePath)) {
            require_once($viewFilePath);
        }
    }
}
