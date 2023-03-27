<?php

require_once APP_ROOT . '/models/CategoriesBC.php';

class BudgetsOverview extends Controller {

    private $data;

    public function index() {
        $this->data['title'] = 'Overzicht budgetten';
        $this->view('BudgetsOverview', $this->data);
    }

    public function filter() {
        $month = isset($_POST['month']) ? $_POST['month'] : null;

        $CategoriesBC = new CategoriesBC();
        $budgets = $CategoriesBC->getBudgets($month);
        $this->data['budgets'] = $budgets;

        $this->index();
    }

}