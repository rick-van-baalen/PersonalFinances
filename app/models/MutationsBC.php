<?php

require_once APP_ROOT . '/models/AccountsBC.php';
require_once APP_ROOT . '/models/BalanceHistoryBC.php';

class MutationsBC extends BusinessComponent {

    public function getMutations() {
        try {
            $this->query("SELECT mutations.ID, mutations.DESCRIPTION, mutations.DATE, mutations.AMOUNT, mutations.BALANCE_AFTER, mutations.MUTATION_CODE_ID, mutations.NAME_COUNTER_ACCOUNT, mutationcodes.DESCRIPTION as MUTATION_CODE_DESCRIPTION, mutations.ACCOUNT_ID, accounts.DESCRIPTION as ACCOUNT_DESCRIPTION
            FROM mutations
            LEFT JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID
            LEFT JOIN accounts ON mutations.ACCOUNT_ID = accounts.ID
            ORDER BY DATE DESC LIMIT 250");
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function getMutationsForOverview($category_id, $mutation_code_id, $start_date, $end_date, $account_id) {
        $where = "";
    
        if ($category_id !== null && $category_id !== "") {
            $where .= $where === "" ? "WHERE categories.ID = $category_id" : " AND categories.ID = " . $category_id;
        }
        if ($mutation_code_id !== null && $mutation_code_id !== "") {
            $where .= $where === "" ? "WHERE mutations.MUTATION_CODE_ID = $mutation_code_id" : " AND mutations.MUTATION_CODE_ID = " . $mutation_code_id;
        }
        if ($start_date !== null && $start_date !== "") {
            $where .= $where == "" ? "WHERE mutations.DATE >= '$start_date'" : " AND mutations.DATE >= '$start_date'";
        }
        if ($end_date !== null && $end_date !== "") {
            $where .= $where == "" ? "WHERE mutations.DATE <= '$end_date'" : " AND mutations.DATE <= '$end_date'";
        }
        if ($account_id !== null && $account_id !== "") {
            $where .= $where == "" ? "WHERE mutations.ACCOUNT_ID = $account_id" : " AND mutations.ACCOUNT_ID = $account_id";
        }

        if ($where == "") {
            $sql = "SELECT mutations.DATE, mutations.AMOUNT, mutations.MUTATION_CODE_ID, mutationcodes.DESCRIPTION as MUTATION_CODE_DESCRIPTION, mutations.NAME_COUNTER_ACCOUNT, mutations.DESCRIPTION, mutations.BALANCE_AFTER, accounts.DESCRIPTION as ACCOUNT_DESCRIPTION FROM mutations LEFT JOIN accounts ON mutations.ACCOUNT_ID = accounts.ID LEFT JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID ORDER BY mutations.DATE ASC, mutations.ID ASC";
        } else {
            $sql = "SELECT mutations.DATE, mutations.AMOUNT, mutations.MUTATION_CODE_ID, mutationcodes.DESCRIPTION as MUTATION_CODE_DESCRIPTION, mutations.NAME_COUNTER_ACCOUNT, mutations.DESCRIPTION, mutations.BALANCE_AFTER, accounts.DESCRIPTION as ACCOUNT_DESCRIPTION FROM mutations LEFT JOIN accounts ON mutations.ACCOUNT_ID = accounts.ID LEFT JOIN mutationcodes ON mutations.MUTATION_CODE_ID = mutationcodes.ID LEFT JOIN categories ON mutationcodes.CATEGORY_ID = categories.ID $where ORDER BY mutations.DATE ASC, mutations.ID ASC";
        }

        try {
            $this->query($sql);
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function addManualMutation($date, $name_counter_account, $amount, $description, $mutation_code_id_from, $mutation_code_id_to, $account_id) {
        try {
            $this->query("INSERT INTO mutations (ID, DESCRIPTION, DATE, AMOUNT, BALANCE_AFTER, MUTATION_CODE_ID, NAME_COUNTER_ACCOUNT, ACCOUNT_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $this->bind(1, date("Ymdhis"));
            $this->bind(2, $description);
            $this->bind(3, $date);
            $this->bind(4, $amount);
            $this->bind(5, null);
            $this->bind(6, $mutation_code_id_from);
            $this->bind(7, $name_counter_account);
            $this->bind(8, $account_id);
            $this->execute();

            $this->query("INSERT INTO mutations (ID, DESCRIPTION, DATE, AMOUNT, BALANCE_AFTER, MUTATION_CODE_ID, NAME_COUNTER_ACCOUNT, ACCOUNT_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $this->bind(1, date("Ymdhis") + 1);
            $this->bind(2, $description);
            $this->bind(3, $date);
            $this->bind(4, 0 - $amount);
            $this->bind(5, null);
            $this->bind(6, $mutation_code_id_to);
            $this->bind(7, $name_counter_account);
            $this->bind(8, $account_id);
            $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function addMutation($mutation) {
        $AccountsBC = new AccountsBC();
        $BalanceHistoryBC = new BalanceHistoryBC();

        $account = $AccountsBC->getAccount($mutation->ACCOUNT_ID);
        $balance_after = $account->BALANCE + $mutation->AMOUNT;

        try {
            $this->query("INSERT INTO mutations (ID, DESCRIPTION, DATE, AMOUNT, BALANCE_AFTER, MUTATION_CODE_ID, NAME_COUNTER_ACCOUNT, ACCOUNT_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $this->bind(1, $mutation->ID);
            $this->bind(2, $mutation->DESCRIPTION);
            $this->bind(3, $mutation->DATE);
            $this->bind(4, $mutation->AMOUNT);
            $this->bind(5, $balance_after);
            $this->bind(6, $mutation->MUTATION_CODE_ID);
            $this->bind(7, $mutation->NAME_COUNTER_ACCOUNT);
            $this->bind(8, $mutation->ACCOUNT_ID);

            if ($this->execute()) {
                $BalanceHistoryBC->updateBalanceHistory($mutation->DATE, $balance_after, $mutation->ACCOUNT_ID);
                $AccountsBC->updateBalance($mutation->ACCOUNT_ID, $balance_after);
                return true;
            } else {
                return false;
            }
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function editMutation($mutation_id, $mutation_code_id) {
        try {
            $this->query("UPDATE mutations SET MUTATION_CODE_ID = ? WHERE ID = ?");
            $this->bind(1, $mutation_code_id);
            $this->bind(2, $mutation_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function checkMutation($open_mutation_id) {
        try {
            $this->query("SELECT * FROM mutations WHERE ID = ?");
            $this->bind(1, $open_mutation_id);
            $this->execute();

            $result = $this->rowCount();
            return $result > 0 ? true : false;
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

}