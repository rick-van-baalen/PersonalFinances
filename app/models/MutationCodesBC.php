<?php

class MutationCodesBC extends BusinessComponent {

    public function getMutationCodes() {
        try {
            $this->query("SELECT mutationcodes.ID, mutationcodes.DESCRIPTION, mutationcodes.CATEGORY_ID, categories.DESCRIPTION as CATEGORY_DESCRIPTION, mutationcodes.MATCHING, mutationcodes.SHOW_IN_OVERVIEW
            FROM mutationcodes
            LEFT JOIN categories ON mutationcodes.CATEGORY_ID = categories.ID
            ORDER BY mutationcodes.DESCRIPTION ASC");
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getExpenses($start_date, $end_date, $account_id, $order_by) {
        $where = "WHERE mutations.DATE >= '$start_date' AND mutations.DATE <= '$end_date'";
        if ($account_id !== null && $account_id != "") {
            $where .= " AND mutations.ACCOUNT_ID = '$account_id'";
        }

        if ($order_by == 'description') {
            $sql = "SELECT mutations.MUTATION_CODE_ID, mutationcodes.SHOW_IN_OVERVIEW, mutationcodes.DESCRIPTION, SUM(AMOUNT) as TOTAL_AMOUNT FROM mutations INNER JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID $where AND mutationcodes.SHOW_IN_OVERVIEW = 1 GROUP BY mutations.MUTATION_CODE_ID ORDER BY mutationcodes.DESCRIPTION ASC";
        } else if ($order_by == 'total_amount') {
            $sql = "SELECT mutations.MUTATION_CODE_ID, mutationcodes.SHOW_IN_OVERVIEW, mutationcodes.DESCRIPTION, SUM(AMOUNT) AS TOTAL_AMOUNT FROM mutations INNER JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID $where AND mutationcodes.SHOW_IN_OVERVIEW = 1 GROUP BY mutations.MUTATION_CODE_ID ORDER BY TOTAL_AMOUNT ASC";
        }

        try {
            $this->query($sql);
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
            return $e->getMessage();
        }
    }

    public function getBalanceIncome($start_date, $end_date, $account_id) {
        $where = "WHERE mutations.DATE >= '$start_date' AND mutations.DATE <= '$end_date'";
        if ($account_id !== null && $account_id != "") {
            $where .= " AND mutations.ACCOUNT_ID = '$account_id'";
        }

        $sql = "SELECT mutations.MUTATION_CODE_ID, mutationcodes.DESCRIPTION, SUM(AMOUNT) AS TOTAL_AMOUNT FROM mutations INNER JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID $where AND mutationcodes.SHOW_IN_OVERVIEW = 1 GROUP BY mutations.MUTATION_CODE_ID HAVING SUM(AMOUNT) > 0 ORDER BY TOTAL_AMOUNT DESC";
        
        try {
            $this->query($sql);
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
            return $e->getMessage();
        }
    }

    public function getBalanceExpenses($start_date, $end_date, $account_id) {
        $where = "WHERE mutations.DATE >= '$start_date' AND mutations.DATE <= '$end_date'";
        if ($account_id !== null && $account_id != "") {
            $where .= " AND mutations.ACCOUNT_ID = '$account_id'";
        }

        $sql = "SELECT mutations.MUTATION_CODE_ID, mutationcodes.DESCRIPTION, SUM(AMOUNT) AS TOTAL_AMOUNT FROM mutations INNER JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID $where AND mutationcodes.SHOW_IN_OVERVIEW = 1 GROUP BY mutations.MUTATION_CODE_ID HAVING SUM(AMOUNT) < 0 ORDER BY TOTAL_AMOUNT ASC";
        
        try {
            $this->query($sql);
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
            return $e->getMessage();
        }
    }

    public function getPredictions($start_date, $end_date, $account_id) {
        
    }

    public function addMutationCode($description, $category_id, $matching, $show_in_overview) {
        try {
            $this->query("INSERT INTO mutationcodes (DESCRIPTION, CATEGORY_ID, MATCHING, SHOW_IN_OVERVIEW) VALUES (?, ?, ?, ?)");
            $this->bind(1, $description);
            $this->bind(2, $category_id);
            $this->bind(3, $matching);
            $this->bind(4, $show_in_overview);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function editMutationCode($mutation_code_id, $description, $category_id, $matching, $show_in_overview) {
        try {
            $this->query("UPDATE mutationcodes SET DESCRIPTION = ?, CATEGORY_ID = ?, MATCHING = ?, SHOW_IN_OVERVIEW = ? WHERE ID = ?");
            $this->bind(1, $description);
            $this->bind(2, $category_id);
            $this->bind(3, $matching);
            $this->bind(4, $show_in_overview);
            $this->bind(5, $mutation_code_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteMutationCode($mutation_code_id) {
        try {
            $this->query("DELETE FROM mutationcodes WHERE ID = ?");
            $this->bind(1, $mutation_code_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

}