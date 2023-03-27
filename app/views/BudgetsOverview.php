<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Budgetten</h1>

<form method="post">
    <div class="row">
        <div class="col-2">
            <label class="mb-2">Kies een maand:</label>
            <?php if (isset($_POST['month'])) $value = $_POST['month']; ?>
            <input type="month" value="<?php echo $value; ?>" name="month" class="form-control">
        </div>
        <div class="col-1 d-flex align-items-end">
            <button type="submit" name="filter" class="btn btn-add w-100">Filter</button>
        </div>
    </div>
</form>

<?php if (isset($data['budgets'])) { ?>
    <div class="table-responsive mt-4">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Categorie</th>
                <th>Budget</th>
                <th>Uitgaven</th>
                <th>Restant bedrag</th>
                <th class="text-nowrap">Percentages</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['budgets'] as $budget) { ?>
            <tr>
                <td><?php echo $budget->DESCRIPTION; ?></td>
                <td>€<?php echo number_format($budget->BUDGET, 2, ',', '.'); ?></td>
                <td>€<?php echo number_format($budget->AMOUNT, 2, ',', '.'); ?></td>
                <td>
                    <?php $balance = $budget->BUDGET + $budget->AMOUNT;
                    if ($balance <= 0) {
                        echo '<span class="badge bg-danger">€' . number_format($balance, 2, ',', '.') . '</span>';
                    } else {
                        echo '<span class="badge bg-success">€' . number_format($balance, 2, ',', '.') . '</span>';
                    } ?>
                </td>
                <td>
                    <?php $pct_spent = (abs($budget->AMOUNT) / abs($budget->BUDGET)) * 100;
                    if (round($pct_spent) < 100) {
                        $total = 100 - round($pct_spent);
                    } ?>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo round($pct_spent); ?>%" aria-valuenow="<?php echo round($pct_spent); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($pct_spent); ?>%</div>
                        <?php if ($pct_spent < 100) { ?>
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $total; ?>%" aria-valuenow="<?php echo $total; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo $total; ?>%</div>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <?php } ?>

            <?php if (count($data['budgets']) == 0) { ?>
            <tr>
                <td colspan="5">Er zijn geen uitgaven gevonden.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>