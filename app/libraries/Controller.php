<?php

class Controller {

    public function __construct() {
        $this->checkActions();
    }

    public function model($model) {
        if (file_exists('../app/models/' . $model . '.php')) {
            require_once '../app/models/' . $model . '.php';
        } else {
            die('There is no model found for: ' . $model);
        }
        return new $model();
    }

    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die('There is no view found for: ' . $view);
        }
    }

    public function checkActions() {
        if (isset($_POST['add']) && method_exists($this, 'add')) {
            call_user_func_array([$this, 'add'], array());
        } else if (isset($_POST['delete']) && method_exists($this, 'delete')) {
            call_user_func_array([$this, 'delete'], array());
        } else if (isset($_POST['edit']) && method_exists($this, 'edit')) {
            call_user_func_array([$this, 'edit'], array());
        } else if (isset($_POST['save']) && method_exists($this, 'save')) {
            call_user_func_array([$this, 'save'], array());
        } else if (isset($_POST['import']) && method_exists($this, 'import')) {
            call_user_func_array([$this, 'import'], array());
        } else if (isset($_POST['process']) && method_exists($this, 'process')) {
            call_user_func_array([$this, 'process'], array());
        } else if (isset($_POST['backup']) && method_exists($this, 'backup')) {
            call_user_func_array([$this, 'backup'], array());
        } else if (isset($_POST['filter']) && method_exists($this, 'filter')) {
            call_user_func_array([$this, 'filter'], array());
        }
    }

}