<?php

require_once APP_ROOT . '/models/AccountsBC.php';

class Accounts extends Controller {

    private $data;

    public function index() {
        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();

        $this->data['title'] = 'Rekeningen';
        $this->data['accounts'] = $accounts;
        
        $this->view('Accounts', $this->data);
    }

    public function add() {
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : null;
        if ($account_number != null) $account_number = str_replace(" ", "", $account_number);
        $balance = isset($_POST['balance']) ? $_POST['balance'] : null;
        $primary_account = isset($_POST['primary_account']) ? $_POST['primary_account'] : null;
        if ($description === null || $account_number === null || $balance === null || $primary_account === null) return;

        $AccountsBC = new AccountsBC();
        $result = $AccountsBC->addAccount($description, $account_number, $balance, $primary_account);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is toegevoegd aan je rekeningen." : $result;
    }

    public function delete() {
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        if ($account_id === null || $description === null) return;

        $AccountsBC = new AccountsBC();
        $result = $AccountsBC->deleteAccount($account_id);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is verwijderd uit je rekeningen." : $result;
    }

    public function edit() {
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : null;
        if ($account_number != null) $account_number = str_replace(" ", "", $account_number);
        $primary_account = isset($_POST['primary_account']) ? $_POST['primary_account'] : null;
        if ($account_id === null || $description === null || $account_number === null || $primary_account === null) return;

        $AccountsBC = new AccountsBC();
        $result = $AccountsBC->editAccount($account_id, $description, $account_number, $primary_account);
        
        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is bijgewerkt." : $result;
    }

}

?>