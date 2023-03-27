<?php

class OpenMutationsBC extends BusinessComponent {

    public function getOpenMutations() {
        try {
            $this->query("SELECT openmutations.ID, openmutations.DESCRIPTION, openmutations.DATE, openmutations.NAME_COUNTER_ACCOUNT, openmutations.AMOUNT, openmutations.MUTATION_CODE_ID, mutationcodes.DESCRIPTION as MUTATION_CODE_DESCRIPTION, openmutations.ACCOUNT_ID, accounts.DESCRIPTION as ACCOUNT_DESCRIPTION
            FROM openmutations
            LEFT JOIN mutationcodes ON openmutations.MUTATION_CODE_ID = mutationcodes.ID
            LEFT JOIN accounts ON openmutations.ACCOUNT_ID = accounts.ID
            ORDER BY openmutations.DATE ASC");
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function addOpenMutation(array $DataRow) {
        try {
            $this->query("INSERT INTO openmutations (ID, NAME_COUNTER_ACCOUNT, DATE, AMOUNT, MUTATION_CODE_ID, DESCRIPTION, ACCOUNT_ID) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $this->bind(1, $DataRow['ID']);
            $this->bind(2, $DataRow['NAME_COUNTER_ACCOUNT']);
            $this->bind(3, $DataRow['DATE']);
            $this->bind(4, $DataRow['AMOUNT']);
            $this->bind(5, $DataRow['MUTATION_CODE_ID']);
            $this->bind(6, $DataRow['DESCRIPTION']);
            $this->bind(7, $DataRow['ACCOUNT_ID']);
            return $this->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function editOpenMutation($open_mutation_id, $description, $mutation_code_id) {
        try {
            $this->query("UPDATE openmutations SET DESCRIPTION = ?, MUTATION_CODE_ID = ? WHERE ID = ?");
            $this->bind(1, $description);
            $this->bind(2, $mutation_code_id);
            $this->bind(3, $open_mutation_id);
            return $this->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function deleteOpenMutation($open_mutation_id) {
        try {
            $this->query("DELETE FROM openmutations WHERE ID = ?");
            $this->bind(1, $open_mutation_id);
            return $this->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function checkProcess() {
        try {
            $this->query("SELECT ID FROM openmutations WHERE MUTATION_CODE_ID IS NULL OR ACCOUNT_ID IS NULL LIMIT 1");
            $this->execute();

            $result = $this->rowCount();
            return $result == 0 ? true : false;
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function deleteOpenMutations() {
        try {
            $this->query("TRUNCATE TABLE openmutations");
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

}