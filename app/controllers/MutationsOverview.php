<?php

require_once APP_ROOT . '/models/CategoriesBC.php';
require_once APP_ROOT . '/models/MutationCodesBC.php';
require_once APP_ROOT . '/models/AccountsBC.php';
require_once APP_ROOT . '/models/MutationsBC.php';

class MutationsOverview extends Controller {

    private $data;

    public function index() {
        $this->data['title'] = 'Overzicht mutaties';
        
        $CategoriesBC = new CategoriesBC();
        $categories = $CategoriesBC->getCategories();
        $this->data['categories'] = $categories;

        $MutationCodesBC = new MutationCodesBC();
        $mutation_codes = $MutationCodesBC->getMutationCodes();
        $this->data['mutation_codes'] = $mutation_codes;

        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();
        $this->data['accounts'] = $accounts;

        $this->view('MutationsOverview', $this->data);
    }

    public function filter() {
        $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
        $mutation_code_id = isset($_POST['mutation_code_id']) ? $_POST['mutation_code_id'] : null;
        $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : null;
        $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null;
        $account_id = isset($_POST['account_id']) ? $_POST['account_id'] : null;

        $MutationsBC = new MutationsBC();
        $mutations = $MutationsBC->getMutationsForOverview($category_id, $mutation_code_id, $start_date, $end_date, $account_id);
        $this->data['mutations'] = $mutations;

        $this->index();
    }

}