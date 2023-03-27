<?php

class CategoriesBC extends BusinessComponent {

    public function getCategories() {
        try {
            $this->query("SELECT * FROM categories");
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getCategoriesOverview($start_date, $end_date, $account_id, $order_by) {
        $where = "WHERE categories.ID IS NOT NULL AND mutations.DATE >= '$start_date' AND mutations.DATE <= '$end_date'";
        if ($account_id !== null && $account_id != "") {
            $where .= " AND mutations.ACCOUNT_ID = '$account_id'";
        }
        
        if ($order_by == "category") {
            $sql = "SELECT SUM(AMOUNT) as TOTAL_AMOUNT, categories.DESCRIPTION
            FROM mutations 
            LEFT JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID 
            LEFT JOIN categories ON mutationcodes.CATEGORY_ID = categories.ID
            $where
            GROUP BY mutationcodes.CATEGORY_ID
            ORDER BY categories.DESCRIPTION ASC";
        } else {
            $sql = "SELECT SUM(AMOUNT) as TOTAL_AMOUNT, categories.DESCRIPTION
            FROM mutations 
            LEFT JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID 
            LEFT JOIN categories ON mutationcodes.CATEGORY_ID = categories.ID
            $where
            GROUP BY mutationcodes.CATEGORY_ID
            ORDER BY TOTAL_AMOUNT ASC";
        }

        try {
            $this->query($sql);
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getBudgets($period) {
        $month = date("m", strtotime($period));
        $year = date("Y", strtotime($period));

        $sql = "SELECT SUM(mutations.AMOUNT) as AMOUNT, mutationcodes.CATEGORY_ID, categories.DESCRIPTION, categories.BUDGET
        FROM mutations
        INNER JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID
        INNER JOIN categories ON mutationcodes.CATEGORY_ID = categories.ID
        WHERE MONTH(mutations.DATE) = $month AND YEAR(mutations.DATE) = $year
        GROUP BY mutationcodes.CATEGORY_ID";

        try {
            $this->query($sql);
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function addCategory($description, $show_in_overview, $budget) {
        try {
            $this->query("INSERT INTO categories (DESCRIPTION, SHOW_IN_OVERVIEW, BUDGET) VALUES (?, ?, ?)");
            $this->bind(1, $description);
            $this->bind(2, $show_in_overview);
            $this->bind(3, $budget);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function editCategory($category_id, $description, $show_in_overview, $budget) {
        try {
            $this->query("UPDATE categories SET DESCRIPTION = ?, SHOW_IN_OVERVIEW = ?, BUDGET = ? WHERE ID = ?");
            $this->bind(1, $description);
            $this->bind(2, $show_in_overview);
            $this->bind(3, $budget);
            $this->bind(4, $category_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteCategory($category_id) {
        try {
            $this->query("DELETE FROM categories WHERE ID = ?");
            $this->bind(1, $category_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

}