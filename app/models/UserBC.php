<?php

class UserBC extends BusinessComponent {

    public function getUser() {
        try {
            $this->query("SELECT * FROM user LIMIT 1");
            $this->execute();
            return $this->getResult();
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }

    public function editUser($user_id, $first_name, $last_name, $bank, $categorie_enabled, $port, $system, $backup) {
        try {
            $this->query("UPDATE user SET FIRST_NAME = ?, LAST_NAME = ?, BANK = ?, PORT = ?, SYSTEM = ?, CATEGORIES_ENABLED = ?, BACKUP = ? WHERE ID = ?");
            $this->bind(1, $first_name);
            $this->bind(2, $last_name);
            $this->bind(3, $bank);
            $this->bind(4, $port);
            $this->bind(5, $system);
            $this->bind(6, $categorie_enabled);
            $this->bind(7, $backup);
            $this->bind(8, $user_id);
            return $this->execute();
        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

}