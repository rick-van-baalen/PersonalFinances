<?php

class BalanceHistoryBC extends BusinessComponent {

    public function getBalanceHistory($start_date, $end_date, $account_id) {
        $where = "WHERE DATE >= '$start_date' AND DATE <= '$end_date'";
        
        if ($account_id !== null && $account_id != "") {
            $where .= " AND ACCOUNT_ID = $account_id";
        }
        
        try {
            $this->query("SELECT DATE, BALANCE FROM balancehistory $where ORDER BY DATE ASC");
            $this->execute();
            return $this->getResults();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

    public function history_exists($date, $account_id) {
        try {
            $this->query("SELECT BALANCE FROM balancehistory WHERE DATE = ? AND ACCOUNT_ID = ?");
            $this->bind(1, $date);
            $this->bind(2, $account_id);
            $this->execute();

            $result = $this->rowCount();
            return $result > 0 ? true : false;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function updateBalanceHistory($date, $balance_after, $account_id) {
        $history_exists = $this->history_exists($date, $account_id);

        if ($history_exists === false) {
            try {
                $this->query("INSERT INTO balancehistory (DATE, BALANCE, ACCOUNT_ID) VALUES (?, ?, ?)");
                $this->bind(1, $date);
                $this->bind(2, $balance_after);
                $this->bind(3, $account_id);
                return $this->execute();
            } catch(PDOException $e) {
                return $e->getMessage();
            }
        } else {
            try {
                $this->query("UPDATE balancehistory SET BALANCE = ? WHERE DATE = ? AND ACCOUNT_ID = ?");
                $this->bind(1, $balance_after);
                $this->bind(2, $date);
                $this->bind(3, $account_id);
                return $this->execute();
            } catch(PDOException $e) {
                return $e->getMessage();
            }
        }
    }

}