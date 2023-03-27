<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Saldoverloop</h1>

<form method="post">
    <div class="row">
        <div class="col">
            <label class="mb-2">Startdatum</label>
            <?php if (isset($_POST['start_date'])) { ?>
            <input type="date" name="start_date" value="<?php echo $_POST['start_date']; ?>" class="form-control">
            <?php } else { ?>
            <input type="date" name="start_date" class="form-control">
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
                    <?php } else if ($account->PRIMARY_ACCOUNT != 1) { ?>
                    <option value="<?php echo $account->ID; ?>" selected><?php echo $account->DESCRIPTION; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $account->ID; ?>"><?php echo $account->DESCRIPTION; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="col-1 d-flex align-items-end">
            <button type="submit" name="filter" class="btn btn-add w-100">Filter</button>
        </div>
    </div>
</form>

<?php if (isset($data['balance_history_dates']) && isset($data['balance_history_balances'])) { ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<canvas style="max-width: 1500px; margin: 0 auto;" id="balance_history"></canvas>
<script type="text/javascript">
var ctx = document.getElementById('balance_history').getContext('2d');
var chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo $data['balance_history_dates']; ?>],
        datasets: [{
            label: 'Saldo',
            backgroundColor: 'rgb(240,248,255)',
            borderColor: 'rgb(48,153,199)',
            data: [<?php echo $data['balance_history_balances']; ?>]
        }]
    },
    options: {}
});
</script>
<?php } ?>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>