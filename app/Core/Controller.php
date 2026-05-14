<?php

class Controller {
    public function model($model) {
        require_once '../Models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        if (file_exists('../Views/' . $view . '.php')) {
            require_once '../Views/' . $view . '.php';
        } else {
            die('View: ' . $view . ' does not exist.');
        }
    }
}
?>
