<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Balans</h1>

<form method="post">
    <div class="row">
        <div class="col">
            <label class="mb-2">Startdatum</label>
            <?php if (isset($_POST['start_date'])) { ?>
            <input type="date" name="start_date" value="<?php echo $_POST['start_date']; ?>" class="form-control">
            <?php } else if (isset($_GET['start_date'])) { ?>
            <input type="date" name="start_date" value="<?php echo $_GET['start_date']; ?>" class="form-control">
            <?php } else { ?>
            <input type="date" name="start_date" class="form-control">
            <?php } ?>
        </div>
        <div class="col">
            <label class="mb-2">Einddatum</label>
            <?php if (isset($_POST['end_date'])) { ?>
            <input type="date" name="end_date" value="<?php echo $_POST['end_date']; ?>" class="form-control">
            <?php } else if (isset($GET['end_date'])) { ?>
            <input type="date" name="end_date" value="<?php echo $GET['end_date']; ?>" class="form-control">
            <?php } else { ?>
            <input type="date" name="end_date" value="<?php echo date("Y-m-d"); ?>" class="form-control">
            <?php } ?>
        </div>
        <div class="col">
            <label class="mb-2">Rekening</label>
            <select name="account_id" class="form-control">
                <option value=""></option>
                <?php foreach ($data['accounts'] as $account) { ?>
                    <?php if (($_POST['account_id'] == $account->ID) || (isset($_GET['account_id']) && $_GET['account_id'] == $account->ID)) { ?>
                    <option value="<?php echo $account->ID; ?>" selected><?php echo $account->DESCRIPTION; ?></option>
                    <?php } else if ($account->PRIMARY_ACCOUNT == 1) { ?>
                    <option value="<?php echo $account->ID; ?>" selected><?php echo $account->DESCRIPTION; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $account->ID; ?>"><?php echo $account->DESCRIPTION; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="col-1 d-flex align-items-end">
            <button type="submit" name="filter" id="filter" class="btn btn-add w-100">Filter</button>
        </div>
    </div>
</form>

<?php if (isset($data['balance_income']) && isset($data['balance_expenses'])) { ?>
<div class="table-responsive mt-4">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th class="text-nowrap">Inkomsten</th>
                <th class="text-nowrap"></th>
                <th>Uitgaven</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 0;
            $end_reached = false;
            $total_income = 0;
            $total_expenses = 0;
            ?>
            
            <?php while ($end_reached === false) { ?>
            <tr>
                <?php if (isset($data['balance_income'][$count])) { $total_income = $total_income + round($data['balance_income'][$count]->TOTAL_AMOUNT, 2); ?>
                <td><?php echo $data['balance_income'][$count]->DESCRIPTION; ?></td>
                <td><?php echo '€' . number_format($data['balance_income'][$count]->TOTAL_AMOUNT, 2, ',', '.'); ?></td>
                <?php } else { ?>
                <td></td>
                <td></td>
                <?php } ?>

                <?php if (isset($data['balance_expenses'][$count])) { $total_expenses = $total_expenses + round($data['balance_expenses'][$count]->TOTAL_AMOUNT, 2); ?>
                <td><?php echo $data['balance_expenses'][$count]->DESCRIPTION; ?></td>
                <td><?php echo '€' . str_replace("-", "", number_format($data['balance_expenses'][$count]->TOTAL_AMOUNT, 2, ',', '.')); ?></td>
                <?php } else { ?>
                <td></td>
                <td></td>
                <?php } ?>
            </tr>

            <?php if (!isset($data['balance_income'][$count + 1]) && !isset($data['balance_expenses'][$count + 1])) {
                $end_reached = true;
            } else {
                $count++;
            } ?>

            <?php } ?>

            <?php $total_expenses = (float) str_replace("-", "", $total_expenses);
            $balance = $total_income - $total_expenses; ?>

            <?php if ($balance > 0) { ?>
            <tr class="bg-success">
                <td></td>
                <td></td>
                <td class="text-white">Saldo</td>
                <td class="text-white"><?php echo '€' . number_format($balance, 2, ',', '.'); ?></td>
            </tr>
            <?php } ?>

            <tr>
                <td><b>Totaal</b></td>
                <td>
                    <?php echo $total_income !== null ? '€' . number_format($total_income, 2, ',', '.') : 0; ?>
                </td>
                <td><b>Totaal</b></td>
                <td>
                    <?php if ($balance > 0) { ?>
                    <?php echo $total_expenses !== null ? '€' . number_format($total_expenses + $balance, 2, ',', '.') : 0; ?>
                    <?php } else { ?>
                    <?php echo $total_expenses !== null ? '€' . number_format($total_expenses, 2, ',', '.') : 0; ?>
                    <?php } ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php } ?>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>