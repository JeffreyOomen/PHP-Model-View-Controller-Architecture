<?php

class Controller {

    /**
     * This method will require in the model class
     * given as parameter and will instantiate it.
     * @param String $model the name of the model class
     * @return mixed an instance of the model class
     */
    protected function model($model) {

        if (file_exists("../app/models/" . $model . ".php")) {
            require_once "../app/models/" . $model . ".php";
            return new $model();
        }

    }

    /**
     * This method will require in the view class based on the first
     * parameter and the data will be automatically available.
     * @param String $view the controller name / the view name, example: home/index
     * @param array $data  the data which needs to be passed to the view
     */
    protected function view($view, $data) {
        require_once "../app/views/" . $view . ".php";

    }

}