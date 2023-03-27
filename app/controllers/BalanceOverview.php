<?php

require_once APP_ROOT . '/models/MutationCodesBC.php';
require_once APP_ROOT . '/models/AccountsBC.php';

class BalanceOverview extends Controller {

    private $data;

    public function index() {
        $this->data['title'] = 'Overzicht balans';

        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();
        $this->data['accounts'] = $accounts;

        $this->view('BalanceOverview', $this->data);
    }

    public function filter() {
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;

        $MutationCodesBC = new MutationCodesBC();
        $balance_income = $MutationCodesBC->getBalanceIncome($start_date, $end_date, $account_id);
        $balance_expenses = $MutationCodesBC->getBalanceExpenses($start_date, $end_date, $account_id);
        $predictions = $MutationCodesBC->getPredictions($start_date, $end_date, $account_id);

        $this->data['balance_income'] = $balance_income;
        $this->data['balance_expenses'] = $balance_expenses;
        $this->data['predictions'] = $predictions;

        $this->index();
    }

}