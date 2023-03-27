<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<a href="#import" data-bs-toggle="modal" data-bs-target="#import"><button class="btn btn-add mb-3">+</button></a>

<h1 class="mb-4">Openstaande mutaties</h1>

<form method="post">
    <?php if (count($data['open_mutations']) > 0) { ?>
    <div class="row">
        <div class="col d-flex justify-content-start align-items-center mb-2">
            <p class="mb-0">Aantal openstaande mutaties: <?php echo count($data['open_mutations']); ?></p>
        </div>
        <div class="col d-flex justify-content-end align-items-center mb-2">
            <?php if ($data['check_process'] === true) { ?>
            <input type="submit" onclick="showLoader()" class="btn btn-add me-2" name="process" value="Mutaties verwerken">
            <?php } else { ?>
            <input type="submit" class="btn btn-add me-2" name="process" value="Mutaties verwerken" disabled>
            <?php } ?>
            <input type="submit" class="btn btn-add me-2" name="save" value="Wijzigingen opslaan">
            <input type="submit" class="btn btn-add" name="delete" value="Alle mutaties verwijderen">
        </div>
    </div>
    <?php } ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th class="text-nowrap">Datum</th>
                    <th>Naam tegenrekening</th>
                    <th>Bedrag</th>
                    <th>Omschrijving</th>
                    <th>Mutatiecode</th>
                    <th>Rekening</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($data['open_mutations'] as $open_mutation) { $i++; ?>
                <?php if ($open_mutation->MUTATION_CODE_ID == "") { ?>
                <tr>
                <?php } else { ?>
                <tr class="table-success">
                <?php } ?>
                    <input type="hidden" name="open_mutation_id_<?php echo $i; ?>" value="<?php echo $open_mutation->ID; ?>">
                    <td class="text-nowrap"><?php echo date("d-m-Y", strtotime($open_mutation->DATE) ); ?></td>
                    <td><?php echo $open_mutation->NAME_COUNTER_ACCOUNT; ?></td>
                    <td><?php echo '€' . number_format($open_mutation->AMOUNT, 2, ',', '.'); ?></td>
                    <td><input type="text" value="<?php echo $open_mutation->DESCRIPTION; ?>" name="description_<?php echo $i; ?>" class="form-control"></td>
                    <td>
                        <select name="mutation_code_id_<?php echo $i; ?>" class="form-control">
                            <option value=""></option>
                            <?php foreach ($data['mutation_codes'] as $mutation_code) { ?>
                            <?php if ($open_mutation->MUTATION_CODE_ID == $mutation_code->ID) { ?>
                            <option value="<?php echo $mutation_code->ID; ?>" selected><?php echo $mutation_code->DESCRIPTION; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $mutation_code->ID; ?>"><?php echo $mutation_code->DESCRIPTION; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </td>
                    <td><?php echo $open_mutation->ACCOUNT_DESCRIPTION; ?></td>
                </tr>
                <?php } ?>
                <?php if (count($data['open_mutations']) == 0) { ?>
                <tr>
                    <td colspan="8">Er zijn geen openstaande mutaties.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if (count($data['open_mutations']) != 0) { ?>
    <?php if ($data['check_process'] === true) { ?>
    <input type="submit" onclick="showLoader()" class="btn btn-add mr-3" name="process" value="Mutaties verwerken">
    <?php } else { ?>
    <input type="submit" class="btn btn-add mr-3" name="process" value="Mutaties verwerken" disabled>
    <?php } ?>
    <input type="submit" class="btn btn-add mr-3" name="save" value="Wijzigingen opslaan">
    <input type="submit" class="btn btn-add float-right" name="delete" value="Alle mutaties verwijderen">
    <?php } ?>
</form>

<!-- Modal: Mutaties importeren -->
<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mutaties importeren</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="mb-2" for="file">Selecteer een bestand:</label><br>
                            <input type="file" id="file" name="file" required>
                        </div>
                        <div class="col-12 mb-4">
                            <label class="mb-2" for="bank">Selecteer een bank:</label><br>
                            <select name="bank" class="form-control" required>
                            <?php if ($user->BANK == "ingb") {
                                echo '<option value="ingb" selected>ING</option>';
                            } else {
                                echo '<option value="ingb">ING</option>';
                            }
                            if ($user->BANK == "rabo") {
                                echo '<option value="rabo" selected>Rabobank</option>';
                            } else {
                                echo '<option value="rabo">Rabobank</option>';
                            }
                            if ($user->BANK == "snsb") {
                                echo '<option value="snsb" selected>SNS Bank</option>';
                            } else {
                                echo '<option value="snsb">SNS Bank</option>';
                            } ?>
                        </select>
                        </div>
                        <div class="col-12">
                            <input type="submit" class="btn btn-add" value="Import starten" name="import">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>