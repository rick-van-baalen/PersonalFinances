<?php

require_once APP_ROOT . '/models/OpenMutationsBC.php';
require_once APP_ROOT . '/models/MutationsBC.php';
require_once APP_ROOT . '/models/MutationCodesBC.php';
require_once APP_ROOT . '/models/AccountsBC.php';

class OpenMutations extends Controller {

    private $data;

    public function index() {
        $OpenMutationsBC = new OpenMutationsBC();
        $open_mutations = $OpenMutationsBC->getOpenMutations();
        $check_process = $OpenMutationsBC->checkProcess();

        $MutationCodesBC = new MutationCodesBC();
        $mutation_codes = $MutationCodesBC->getMutationCodes();

        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();

        $this->data['title'] = 'Openstaande mutaties';
        $this->data['open_mutations'] = $open_mutations;
        $this->data['mutation_codes'] = $mutation_codes;
        $this->data['accounts'] = $accounts;
        $this->data['check_process'] = $check_process;

        $this->view('OpenMutations', $this->data);
    }

    public function delete() {
        $OpenMutationsBC = new OpenMutationsBC();
        $result = $OpenMutationsBC->deleteOpenMutations();

        $this->data['alert'] = $result === true ? "success" : "danger";
        $this->data['message'] = $result === true ? "Alle openstaande mutaties zijn verwijderd." : $result;
    }

    public function save() {
        $OpenMutationsBC = new OpenMutationsBC();
        $open_mutations = $OpenMutationsBC->getOpenMutations();

        for ($i = 1; $i <= count($open_mutations); $i++) {
            $open_mutation_id = $_POST['open_mutation_id_' . $i];
            $description = $_POST['description_' . $i];
            if ($description == "") $description = null;
            $mutation_code_id = $_POST['mutation_code_id_' . $i];
            if ($mutation_code_id == "") $mutation_code_id = null;
            
            $OpenMutationsBC->editOpenMutation($open_mutation_id, $description, $mutation_code_id);
        }

        $this->data['alert'] = "success";
        $this->data['message'] = "De openstaande mutaties zijn bijgewerkt.";
    }

    public function process() {
        $OpenMutationsBC = new OpenMutationsBC();
        $open_mutations = $OpenMutationsBC->getOpenMutations();
        
        $MutationsBC = new MutationsBC();

        $mutations_processed = 0;

        foreach ($open_mutations as $open_mutation) {
            $result = $MutationsBC->addMutation($open_mutation);
            if ($result === true) {
                $mutations_processed++;
                $OpenMutationsBC->deleteOpenMutation($open_mutation->ID);
            }
        }

        $this->data['alert'] = $mutations_processed > 0 ? "success" : "danger";
        $message = $mutations_processed == 1 ? "Er is 1 openstaande mutatie succesvol verwerkt." : "Er zijn " . $mutations_processed . " openstaande mutaties succesvol verwerkt.";
        $this->data['message'] = $mutations_processed > 0 ? $message : $result;
    }

    public function import() {
        $bank = isset($_POST['bank']) ? $_POST['bank'] : null;
        if ($bank === null) return;

        $target_dir = "../";
        $source = $_FILES["file"]["tmp_name"];
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Alleen CSV en ZIP zijn toegestaan
        if ($this->checkFileType($fileType) === false) return;

        // Controleren of de juiste bank/bestand combinatie gemaakt is
        if ($this->checkBank($bank, $target_file) === false) return;

        // ZIP openen en target file pakken
        $target_file = $this->getTargetFile($fileType, $source, $target_file, $target_dir);
        if ($target_file === false) return;

        $row = 1;
        $i = 0;
        $processed = 0;

        $MutationsBC = new MutationsBC();

        $MutationCodesBC = new MutationCodesBC();
        $mutation_codes = $MutationCodesBC->getMutationCodes();

        $AccountsBC = new AccountsBC();
        $accounts = $AccountsBC->getAccounts();

        if (($handle = fopen($target_file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Bij de CSV van de Rabobank en ING Bank zit de header ook in de CSV, dus row 1 moeten we overslaan.
                if (($row == 1) && ($bank == "rabo" || $bank == "ingb")) {
                    $row++;
                    continue;
                }

                $DataRow = $this->getDataRow($bank, $data);
                if (count($DataRow) < 1) continue;

                // Checken of mutatie al eerder ingelezen is.
                $result = $MutationsBC->checkMutation($DataRow['ID']);
                if ($result === true) continue;

                // Rekening koppelen.
                $DataRow = $this->getAccountNumber($DataRow, $accounts);
                if ($DataRow['ACCOUNT_ID'] == "") continue;

                // Mutatiecode koppelen.
                $DataRow = $this->getMutationCode($DataRow, $mutation_codes);

                // Open mutatie toevoegen.
                $OpenMutationsBC = new OpenMutationsBC();
                $result = $OpenMutationsBC->addOpenMutation($DataRow);
                if ($result === true) $processed++;
            }

            fclose($handle);
            unlink($target_file);
        }

        $this->data['alert'] = $processed > 0 ? "success" : "danger";
        $this->data['message'] = $processed > 0 ? "De mutaties zijn succesvol geïmporteerd." : "De mutaties zijn niet geïmporteerd.";
    }

    private function checkFileType($fileType) {
        if($fileType != "csv" && $fileType != "zip") {
            $this->data['alert'] = "danger";
            $this->data['message'] = "Importeren mislukt. Alleen CSV en ZIP bestanden zijn toegestaan.";
            return false;
        } else {
            return true;
        }
    }

    private function checkBank($bank, $target_file) {
        if ($bank == "rabo") {
            $bank_check = stripos($target_file, "CSV_A_");
            if ($bank_check === false) {
                $this->data['alert'] = "danger";
                $this->data['message'] = "Importeren mislukt. Dit is geen importbestand van de Rabobank.";
                return false;
            } else {
                return true;
            }
        } elseif($bank == "snsb") {
            $bank_check1 = stripos($target_file, "tr-info_");
            $bank_check2 = stripos($target_file, "transactie-historie_");
            if ($bank_check1 === false && $bank_check2 === false) {
                $this->data['alert'] = "danger";
                $this->data['message'] = "Importeren mislukt. Dit is geen importbestand van de SNS Bank.";
                return false;
            } else {
                return true;
            }
        } elseif($bank == "ingb") {
            $bank_check = stripos($target_file, "ingb");
            if ($bank_check === false) {
                $this->data['alert'] = "danger";
                $this->data['message'] = "Importeren mislukt. Dit is geen importbestand van de ING Bank.";
                return false;
            } else {
                return true;
            }
        } else {
            $this->data['alert'] = "danger";
            $this->data['message'] = "Importeren mislukt. Dit is een onbekende bank.";
            return false;
        }
    }

    private function getTargetFile($fileType, $source, $target_file, $target_dir) {
        if ($fileType == "zip") {
            if(move_uploaded_file($source, $target_file)) {
                $zip = new ZipArchive();
                $x = $zip->open($target_file);
                if ($x === true) {
                    $zip->extractTo($target_dir);
                    $fileinfo = pathinfo($target_file);
                    $zip->close();
                }
                unlink($target_file);
                return str_replace("_CSV.zip", ".CSV", $target_dir . basename($_FILES["file"]["name"]));
            } else {
                $this->data['alert'] = "danger";
                $this->data['message'] = "Importeren mislukt. Probeer het opnieuw.";
                return false;
            }
        } else if ($fileType == "csv") {
            return $_FILES["file"]["tmp_name"];
        } else {
            $this->data['alert'] = "danger";
            $this->data['message'] = "Importeren mislukt. Alleen CSV en ZIP bestanden zijn toegestaan.";
            return false;
        }
    }

    private function getDataRow($bank, $data) {
        $DataRow = array();

        switch($bank) {
            case "snsb":
                $DataRow['DATE'] = date("Y-m-d", strtotime($data[0]));
                $DataRow['ACCOUNT_NUMBER'] = $data[1];
                $DataRow['NAME_COUNTER_ACCOUNT'] = str_replace("'", "", $data[3]);
                $DataRow['AMOUNT'] = str_replace(",", ".", $data[10]);
                $DataRow['ID'] = $data[15];
                $DataRow['DESCRIPTION'] = str_replace("'", "", $data[17]);
                break;
            case "rabo":
                $DataRow['DATE'] = date("Y-m-d", strtotime(str_replace('"', '', $data[4])));
                $DataRow['ACCOUNT_NUMBER'] = $data[0];
                $DataRow['NAME_COUNTER_ACCOUNT'] = str_replace("'", "", str_replace('"', '', $data[9]));
                $DataRow['AMOUNT'] = str_replace('"', '', str_replace(",", ".", $data[6]));
                $DataRow['ID'] = str_replace('"', '', $data[3]);
                $DataRow['DESCRIPTION'] = str_replace("'", "", str_replace('"', '', $data[19]));
                break;
        }

        return $DataRow;
    }

    private function getMutationCode($DataRow, $mutation_codes) {
        $DataRow['MUTATION_CODE_ID'] = null;

        foreach ($mutation_codes as $mutation_code) {
            if ($mutation_code->MATCHING == "") continue;

            $matchings = explode(", ", $mutation_code->MATCHING);
            foreach ($matchings as $matching) {
                if (strpos($DataRow['DESCRIPTION'], $matching) != "") {
                    $DataRow['MUTATION_CODE_ID'] = $mutation_code->ID;
                    return $DataRow;
                } else if (strpos($DataRow['NAME_COUNTER_ACCOUNT'], $matching) != "") {
                    $DataRow['MUTATION_CODE_ID'] = $mutation_code->ID;
                    return $DataRow;
                }
            }
        }

        return $DataRow;
    }

    private function getAccountNumber($DataRow, $accounts) {
        $DataRow['ACCOUNT_ID'] = null;

        foreach ($accounts as $account) {
            if ($DataRow['ACCOUNT_NUMBER'] == $account->ACCOUNT_NUMBER) {
                $DataRow['ACCOUNT_ID'] = $account->ID;
                return $DataRow;
            }
        }

        return $DataRow;
    }

}

?>