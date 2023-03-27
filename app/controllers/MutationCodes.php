<?php

require_once APP_ROOT . '/models/MutationCodesBC.php';
require_once APP_ROOT . '/models/CategoriesBC.php';

class MutationCodes extends Controller {

    private $data;

    public function index() {
        $MutationCodesBC = new MutationCodesBC();
        $mutation_codes = $MutationCodesBC->getMutationCodes();

        $CategoriesBC = new CategoriesBC();
        $categories = $CategoriesBC->getCategories();

        $this->data['title'] = 'Mutatiecodes';
        $this->data['mutation_codes'] = $mutation_codes;
        $this->data['categories'] = $categories;

        $this->view('MutationCodes', $this->data);
    }

    public function add() {
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $category_id = isset($_POST['category_id']) && $_POST['category_id'] != "" ? $_POST['category_id'] : null;
        $matching = isset($_POST['matching']) && $_POST['matching'] != "" ? $_POST['matching'] : null;
        $show_in_overview = isset($_POST['show_in_overview']) ? $_POST['show_in_overview'] : null;

        $MutationCodesBC = new MutationCodesBC();
        $result = $MutationCodesBC->addMutationCode($description, $category_id, $matching, $show_in_overview);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is toegevoegd aan je mutatiecodes." : $result;
    }

    public function delete() {
        $mutation_code_id = isset($_POST['mutation_code_id']) ? $_POST['mutation_code_id'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        if ($mutation_code_id === null || $description === null) return;

        $MutationCodesBC = new MutationCodesBC();
        $result = $MutationCodesBC->deleteMutationCode($mutation_code_id);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is verwijderd uit je mutatiecodes." : $result;
    }

    public function edit() {
        $mutation_code_id = isset($_POST['mutation_code_id']) ? $_POST['mutation_code_id'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $category_id = isset($_POST['category_id']) && $_POST['category_id'] != "" ? $_POST['category_id'] : null;
        $matching = isset($_POST['matching']) && $_POST['matching'] != "" ? $_POST['matching'] : null;
        $show_in_overview = isset($_POST['show_in_overview']) ? $_POST['show_in_overview'] : null;

        $MutationCodesBC = new MutationCodesBC();
        $result = $MutationCodesBC->editMutationCode($mutation_code_id, $description, $category_id, $matching, $show_in_overview);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is bijgewerkt." : $result;
    }

}

?>