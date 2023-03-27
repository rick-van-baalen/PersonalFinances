<?php

require_once APP_ROOT . '/models/CategoriesBC.php';
require_once APP_ROOT . '/models/MutationCodesBC.php';
require_once APP_ROOT . '/models/AccountsBC.php';

class ExpensesOverview extends Controller {

    private $data;

    public function index() {
        $this->data['title'] = 'Overzicht uitgaven';
        
        $CategoriesBC = new CategoriesBC();
        $categories = $CategoriesBC->getCategories();
        $this->data['categories'] = $categories;

        $MutationCodesBC = new MutationCodesBC();
        $mutation_codes = $MutationCodesBC->getMutationCodes();
        $this->data['mutation_codes'] = $mutation_codes;

        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();
        $this->data['accounts'] = $accounts;

        $this->view('ExpensesOverview', $this->data);
    }

    public function filter() {
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;
        $order_by = isset($_POST['order_by']) ? $_POST['order_by'] : "total_amount";

        $MutationCodesBC = new MutationCodesBC();
        $expenses = $MutationCodesBC->getExpenses($start_date, $end_date, $account_id, $order_by);
        $this->data['expenses'] = $expenses;

        $this->index();
    }

}