<?php

require_once APP_ROOT . '/models/AccountsBC.php';
require_once APP_ROOT . '/models/BalanceHistoryBC.php';

class BalanceHistoryOverview extends Controller {

    private $data;

    public function index() {
        $this->data['title'] = 'Overzicht saldoverloop';
        
        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();
        $this->data['accounts'] = $accounts;

        $this->view('BalanceHistoryOverview', $this->data);
    }

    public function filter() {
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;

        $BalanceHistoryBC = new BalanceHistoryBC();
        $balance_history = $BalanceHistoryBC->getBalanceHistory($start_date, $end_date, $account_id);
        
        $balance_history_dates = "";
        $balance_history_balances = "";

        $i = 1;
        foreach ($balance_history as $record) {
            if ($i == count($balance_history)) {
                $balance_history_dates .= "'$record->DATE'";
                $balance_history_balances .= "'$record->BALANCE'";
            } else {
                $balance_history_dates .= "'$record->DATE', ";
                $balance_history_balances .= "'$record->BALANCE', ";
            }
            $i++;
        }

        $this->data['balance_history_dates'] = $balance_history_dates;
        $this->data['balance_history_balances'] = $balance_history_balances;

        $this->index();
    }

}