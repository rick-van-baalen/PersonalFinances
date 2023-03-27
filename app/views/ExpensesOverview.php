<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Uitgaven</h1>

<form method="post">
    <div class="row">
        <div class="col">
            <label class="mb-2">Startdatum</label>
            <?php if (isset($_POST['start_date'])) { ?>
            <input type="date" name="start_date" value="<?php echo $_POST['start_date']; ?>" class="form-control">
            <?php } else { ?>
            <input type="date" name="start_date" value="<?php echo date('Y-m' . '-01'); ?>" class="form-control">
            <?php } ?>
        </div>
        <div class="col">
            <label class="mb-2">Einddatum</label>
            <?php if (isset($_POST['end_date'])) { ?>
            <input type="date" name="end_date" value="<?php echo $_POST['end_date']; ?>" class="form-control">
            <?php } else { ?>
            <input type="date" name="end_date" value="<?php echo date("Y-m-d"); ?>" class="form-control">
            <?php } ?>
        </div>
        <div class="col">
            <label class="mb-2">Rekening</label>
            <select name="account_id" class="form-control">
                <option value=""></option>
                <?php foreach ($data['accounts'] as $account) { ?>
                    <?php if ($_POST['account_id'] == $account->ID) { ?>
                    <option value="<?php echo $account->ID; ?>" selected><?php echo $account->DESCRIPTION; ?></option>
                    <?php } else if ($account->PRIMARY_ACCOUNT == 1) { ?>
                    <option value="<?php echo $account->ID; ?>" selected><?php echo $account->DESCRIPTION; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $account->ID; ?>"><?php echo $account->DESCRIPTION; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="col">
            <label class="mb-2">Sorteer op</label>
            <select name="order_by" class="form-control">
                <?php if ($_POST['order_by'] == 'description') { ?>
                <option value="total_amount">Totaalbedrag</option>
                <option value="description" selected>Omschrijving</option>
                <?php } else { ?>
                <option value="description">Omschrijving</option>
                <option value="total_amount" selected>Totaalbedrag</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-1 d-flex align-items-end">
            <button type="submit" name="filter" class="btn btn-add w-100">Filter</button>
        </div>
    </div>
</form>

<?php if (isset($data['expenses'])) { ?>
<div class="row">
    <div class="col">
        <div class="table-responsive mt-4">
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Mutatiecode</th>
                        <th>Totaalbedrag</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 0; foreach ($data['expenses'] as $expense) { $i++; ?>

                    <?php $view_url = URL_ROOT . '/overzichten/mutaties?mutation_code_id=' . $expense->MUTATION_CODE_ID;
                    if (isset($_POST['start_date']) && $_POST['start_date'] != "") $view_url .= '&start_date=' . $_POST['start_date'];
                    if (isset($_POST['end_date']) && $_POST['end_date'] != "") $view_url .= '&end_date=' . $_POST['end_date'];
                    if (isset($_POST['account_id']) && $_POST['account_id'] != "") $view_url .= '&account_id=' . $_POST['account_id']; ?>
                    
                    <tr>
                        <td><?php echo $expense->DESCRIPTION; ?></td>
                        <td><?php echo '€' . number_format($expense->TOTAL_AMOUNT, 2, ',', '.'); ?></td>
                        <td class="actions text-center">
                            <a title="Bekijk mutaties." href="<?php echo $view_url; ?>"><img src="<?php echo URL_ROOT . '/icons/eye-fill.svg'; ?>"></a>
                        </td>
                    </tr>
                    <?php } ?>

                    <?php if (count($data['expenses']) == 0) { ?>
                    <tr>
                        <td colspan="3">Er zijn geen uitgaven gevonden.</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>