<?php

class Overviews extends Controller {

    private $data;
    
    public function index() {
        $this->data['title'] = 'Overzichten';
        $this->view('Overviews', $this->data);
    }

}