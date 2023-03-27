<?php

require_once APP_ROOT . '/models/CategoriesBC.php';
require_once APP_ROOT . '/models/AccountsBC.php';

class CategoriesOverview extends Controller {

    private $data;

    public function index() {
        $this->data['title'] = 'Overzicht uitgaven';

        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();
        $this->data['accounts'] = $accounts;

        $this->view('CategoriesOverview', $this->data);
    }

    public function filter() {
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;
        $order_by = isset($_POST['order_by']) ? $_POST['order_by'] : "total_amount";

        $CategoriesBC = new CategoriesBC();
        $categories = $CategoriesBC->getCategoriesOverview($start_date, $end_date, $account_id, $order_by);
        $this->data['categories'] = $categories;

        $this->index();
    }

}