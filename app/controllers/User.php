<?php

require_once APP_ROOT . '/models/UserBC.php';

class User extends Controller {

    private $data;

    public function index() {
        $UserBC = new UserBC();
        $user = $UserBC->getUser();

        $this->data['title'] = 'Instellingen';
        $this->data['user'] = $user;
        
        $this->view('User', $this->data);
    }

    public function edit() {
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : null;
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : null;
        $bank = isset($_POST['bank']) ? $_POST['bank'] : null;
        $categories_enabled = isset($_POST['categories_enabled']) ? $_POST['categories_enabled'] : null;
        $port = isset($_POST['port']) ? $_POST['port'] : null;
        $system = isset($_POST['system']) ? $_POST['system'] : null;
        $backup = isset($_POST['backup']) ? $_POST['backup'] : null;
        if ($user_id === null) return;
        
        $backup = str_replace("\\", "/", $backup);
        if ($backup != "" && substr($backup, -1) != "/") $backup = $backup . "/";
        
        $UserBC = new UserBC();
        $result = $UserBC->editUser($user_id, $first_name, $last_name, $bank, $categories_enabled, $port, $system, $backup);

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "De instellingen zijn bijgewerkt." : $result;
    }

    public function backup() {
        $UserBC = new UserBC();
        $user = $UserBC->getUser();

        if ($user->BACKUP != "") {
            $dir = $user->BACKUP . 'Financien_' . date("Y_m_d_H_i_s") . '.sql';
            $xampp = str_replace("\\", "/", $_SERVER['MYSQL_HOME']);

            $database = DB_NAME;
            $username = DB_USERNAME;
            $password = DB_PASSWORD;
            $host = DB_HOST;

            $output = null;
            $retval = null;
            exec($xampp . "/mysqldump.exe --user={$username} --password={$password} --host={$host} {$database} --result-file={$dir} 2>&1", $output, $retval);
            
            if (isset($output[0])) {
                $this->data['alert'] = "danger";
                if (strpos($output[0], 'Errcode: 13') !== false) $this->data['message'] = "Het maken van de back-up file is mislukt. De toegang tot deze map wordt geweigerd.";
                if (!isset($this->data['message'])) $this->data['message'] = $output[0];
            } else {
                $this->data['alert'] = "success";
                $this->data['message'] = "Er is successvol een back-up file gemaakt.";
            }
        } else {
            $this->data['alert'] = "danger";
            $this->data['message'] = "Het maken van de back-up file is mislukt. De back-up directory is leeg. Controleer je instellingen.";
        }

        $this->index();
    }

}

?>