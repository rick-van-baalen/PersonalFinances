<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Mutaties</h1>

<form method="post">
    <div class="row">
        <div class="col">
            <label class="mb-2">Categorie</label>
            <select name="category_id" class="form-control">
                <option value=""></option>
                <?php foreach ($data['categories'] as $category) { ?>
                <?php if (($_POST['category_id'] == $category->ID) || (isset($_GET['category_id']) && $_GET['category_id'] == $category->ID)) { ?>
                <option value="<?php echo $category->ID; ?>" selected><?php echo $category->DESCRIPTION; ?></option>
                <?php } else { ?>
                <option value="<?php echo $category->ID; ?>"><?php echo $category->DESCRIPTION; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
        </div>
        <div class="col">
            <label class="mb-2">Mutatiecode</label>
            <select name="mutation_code_id" class="form-control">
                <option value=""></option>
                <?php foreach ($data['mutation_codes'] as $mutation_code) { ?>
                    <?php if (($_POST['mutation_code_id'] == $mutation_code->ID) || (isset($_GET['mutation_code_id']) && $_GET['mutation_code_id'] == $mutation_code->ID)) { ?>
                    <option value="<?php echo $mutation_code->ID; ?>" selected><?php echo $mutation_code->DESCRIPTION; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $mutation_code->ID; ?>"><?php echo $mutation_code->DESCRIPTION; ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
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

<?php if (isset($data['mutations'])) { ?>
<div class="table-responsive mt-4">
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th class="text-nowrap">Datum</th>
                <th class="text-nowrap">Naam tegenrekening</th>
                <th>Bedrag</th>
                <th>Omschrijving</th>
                <th class="text-nowrap">Mutatiecode</th>
                <th class="text-nowrap">Rekening</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; $totaal = 0; foreach ($data['mutations'] as $mutation) { $i++; $totaal =  $totaal + $mutation->AMOUNT; ?>
            <tr>
                <td class="text-nowrap"><?php echo date("d-m-Y", strtotime($mutation->DATE) ); ?></td>
                <td class="text-nowrap"><?php echo $mutation->NAME_COUNTER_ACCOUNT; ?></td>
                <td><?php echo '€' . number_format($mutation->AMOUNT, 2, ',', '.'); ?></td>
                <td><?php echo $mutation->DESCRIPTION; ?></td>
                <td class="text-nowrap"><?php echo $mutation->MUTATION_CODE_DESCRIPTION; ?></td>
                <td class="text-nowrap"><?php echo $mutation->ACCOUNT_DESCRIPTION; ?></td>
            </tr>
            <?php } ?>

            <?php if (count($data['mutations']) > 0) { ?>
            <tr>
                <td></td>
                <td><b>Totaalbedrag</b></td>
                <td><b><?php echo '€' . number_format($totaal, 2, ',', '.'); ?></b></td>
                <td colspan="4"></td>
            </tr>
            <?php } else { ?>
            <tr>
                <td colspan="7">Er zijn geen mutaties gevonden.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>

<?php if(isset($_GET['mutation_code_id'])) { ?>
<script>
    window.history.replaceState({}, document.title, "/financien/overzichten/mutaties");
    document.getElementById("filter").click();
</script>
<?php } ?>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>