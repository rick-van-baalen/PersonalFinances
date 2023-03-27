<?php

class AccountsBC extends BusinessComponent {

    public function getAccounts() {
        try {
            $this->query("SELECT * FROM accounts");
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function getAccount($account_id) {
        try {
            $this->query("SELECT * FROM accounts WHERE ID = ?");
            $this->bind(1, $account_id);
            $this->execute();
            return $this->getResult();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function editAccount($account_id, $description, $account_number, $primary_account) {
        try {
            if ($primary_account == 1) {
                $this->query("UPDATE accounts SET PRIMARY_ACCOUNT = 0 WHERE NOT ID = ?");
                $this->bind(1, $account_id);
                $this->execute();
            }

            $this->query("UPDATE accounts SET DESCRIPTION = ?, ACCOUNT_NUMBER = ?, PRIMARY_ACCOUNT = ? WHERE ID = ?");
            $this->bind(1, $description);
            $this->bind(2, $account_number);
            $this->bind(3, $primary_account);
            $this->bind(4, $account_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function deleteAccount($account_id) {
        try {
            $this->query("DELETE FROM accounts WHERE ID = ?");
            $this->bind(1, $account_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function addAccount($description, $account_number, $balance, $primary_account) {
        try {
            if ($primary_account == 1) {
                $this->query("UPDATE accounts SET PRIMARY_ACCOUNT = 0");
                $this->execute();
            }

            $this->query("INSERT INTO accounts (DESCRIPTION, ACCOUNT_NUMBER, BALANCE, PRIMARY_ACCOUNT) VALUES (?, ?, ?, ?)");
            $this->bind(1, $description);
            $this->bind(2, $account_number);
            $this->bind(3, $balance);
            $this->bind(4, $primary_account);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function updateBalance($account_id, $balance_after) {
        try {
            $this->query("UPDATE accounts SET BALANCE = ? WHERE ID = ?");
            $this->bind(1, $balance_after);
            $this->bind(2, $account_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

}