<?php

require_once APP_ROOT . '/models/MutationsBC.php';
require_once APP_ROOT . '/models/MutationCodesBC.php';
require_once APP_ROOT . '/models/AccountsBC.php';

class Mutations extends Controller {

    private $data;
    
    public function index() {
        $MutationsBC = new MutationsBC();
        $mutations = $MutationsBC->getMutations();
        $this->data['mutations'] = $mutations;

        $MutationCodesBC = new MutationCodesBC();
        $mutation_codes = $MutationCodesBC->getMutationCodes();
        $this->data['mutation_codes'] = $mutation_codes;

        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();
        $this->data['accounts'] = $accounts;

        $this->data['title'] = 'Mutaties';
        $this->view('Mutations', $this->data);
    }

    public function add() {
        $date = isset($_POST['date']) ? $_POST['date'] : null;
        $name_counter_account = isset($_POST['name_counter_account']) ? $_POST['name_counter_account'] : null;
        $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $mutation_code_id_from = isset($_POST['mutation_code_id_from']) ? $_POST['mutation_code_id_from'] : null;
        $mutation_code_id_to = isset($_POST['mutation_code_id_to']) ? $_POST['mutation_code_id_to'] : null;
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;
        if ($date === null || $amount === null || $mutation_code_id_from === null || $mutation_code_id_to === null || $account_id === null) return;

        $MutationsBC = new MutationsBC();
        $result = $MutationsBC->addManualMutation($date, $name_counter_account, $amount, $description, $mutation_code_id_from, $mutation_code_id_to, $account_id);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "Mutatie is succesvol toegevoegd." : $result;
    }

    public function edit() {
        $mutation_id = isset($_POST['mutation_id']) ? $_POST['mutation_id'] : null;
        $mutation_code_id = isset($_POST['mutation_code_id']) ? $_POST['mutation_code_id'] : null;
        if ($mutation_id === null || $mutation_code_id === null) return;

        $MutationsBC = new MutationsBC();
        $result = $MutationsBC->editMutation($mutation_id, $mutation_code_id);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "Mutatie is succesvol bijgewerkt." : $result;
    }

}