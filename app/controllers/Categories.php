<?php

require_once APP_ROOT . '/models/CategoriesBC.php';

class Categories extends Controller {

    private $data;

    public function index() {
        $CategoriesBC = new CategoriesBC();
        $categories = $CategoriesBC->getCategories();

        $this->data['title'] = 'Categorieën';
        $this->data['categories'] = $categories;
        
        $this->view('Categories', $this->data);
    }

    public function add() {
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $show_in_overview = isset($_POST['show_in_overview']) ? $_POST['show_in_overview'] : null;
        $budget = isset($_POST['budget']) ? $_POST['budget'] : null;
        if ($description === null || $show_in_overview === null) return;

        $CategoriesBC = new CategoriesBC();
        $result = $CategoriesBC->addCategory($description, $show_in_overview, $budget);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is toegevoegd aan je categorieën." : $result;
    }

    public function delete() {
        $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        if ($category_id === null || $description === null) return;

        $CategoriesBC = new CategoriesBC();
        $result = $CategoriesBC->deleteCategory($category_id);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is verwijderd uit je categorieën." : $result;
    }

    public function edit() {
        $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $show_in_overview = isset($_POST['show_in_overview']) ? $_POST['show_in_overview'] : null;
        $budget = isset($_POST['budget']) ? $_POST['budget'] : null;
        if ($category_id === null || $description === null || $show_in_overview === null) return;

        $CategoriesBC = new CategoriesBC();
        $result = $CategoriesBC->editCategory($category_id, $description, $show_in_overview, $budget);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "'" . $description . "' is bijgewerkt." : $result;
    }

}

?>