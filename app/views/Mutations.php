<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<a href="#import" data-bs-toggle="modal" data-bs-target="#add"><button class="btn btn-add mb-3">+</button></a>

<h1 class="mb-4">Mutaties</h1>

<form method="post">
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th class="text-nowrap">Datum</th>
                    <th class="text-nowrap">Naam tegenrekening</th>
                    <th>Bedrag</th>
                    <th>Omschrijving</th>
                    <th class="text-nowrap">Mutatiecode</th>
                    <th class="text-nowrap">Rekening</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($data['mutations'] as $mutation) { $i++; ?>
                <tr>
                    <td class="text-nowrap"><?php echo date("d-m-Y", strtotime($mutation->DATE) ); ?></td>
                    <td class="text-nowrap"><?php echo $mutation->NAME_COUNTER_ACCOUNT; ?></td>
                    <td><?php echo '€' . number_format($mutation->AMOUNT, 2, ',', '.'); ?></td>
                    <td><?php echo $mutation->DESCRIPTION; ?></td>
                    <td class="text-nowrap"><?php echo $mutation->MUTATION_CODE_DESCRIPTION; ?></td>
                    <td class="text-nowrap"><?php echo $mutation->ACCOUNT_DESCRIPTION; ?></td>
                    <td class="actions text-center">
                        <a class="view" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit_<?php echo $mutation->ID; ?>"><img src="<?php echo URL_ROOT; ?>/icons/pencil-square.svg"></a>
                    </td>
                </tr>

                <!-- Modal: Mutatie wijzigen -->
                <div class="modal fade" id="edit_<?php echo $mutation->ID; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Mutatie wijzigen</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post">
                                    <input type="hidden" name="mutation_id" value="<?php echo $mutation->ID; ?>">

                                    <label class="mb-2">Datum</label>
                                    <input type="text" class="form-control mb-3" name="date" value="<?php echo date("d-m-Y", strtotime($mutation->DATE) ); ?>" disabled>

                                    <label class="mb-2">Naam tegenrekening</label>
                                    <input type="text" class="form-control mb-3" name="name_counter_account" value="<?php echo $mutation->NAME_COUNTER_ACCOUNT; ?>" disabled>

                                    <label class="mb-2">Bedrag</label>
                                    <input type="text" class="form-control mb-3" name="amount" value="<?php echo '€' . number_format($mutation->AMOUNT, 2, ',', '.'); ?>" disabled>

                                    <label class="mb-2">Omschrijving</label>
                                    <input type="text" class="form-control mb-3" name="description" value="<?php echo $mutation->DESCRIPTION; ?>" disabled>

                                    <label class="mb-2">Mutatiecode</label>
                                    <select name="mutation_code_id" class="form-control mb-3">
                                        <option value=""></option>
                                        <?php foreach ($data['mutation_codes'] as $mutation_code) { ?>
                                        <?php if ($mutation->MUTATION_CODE_ID == $mutation_code->ID) { ?>
                                        <option value="<?php echo $mutation_code->ID; ?>" selected><?php echo $mutation_code->DESCRIPTION; ?></option>
                                        <?php } else { ?>
                                        <option value="<?php echo $mutation_code->ID; ?>"><?php echo $mutation_code->DESCRIPTION; ?></option>
                                        <?php } ?>
                                        <?php } ?>
                                    </select>

                                    <label class="mb-2">Rekening</label>
                                    <input type="text" class="form-control mb-3" name="account" value="<?php echo $mutation->ACCOUNT_DESCRIPTION; ?>" disabled>

                                    <input type="submit" class="btn btn-add" value="Opslaan" name="edit">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } ?>
                <?php if (count($data['mutations']) == 0) { ?>
                <tr>
                    <td colspan="8">Er zijn geen mutaties gevonden.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</form>

<!-- Modal: Mutatie toevoegen -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mutatie toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <label class="mb-2">Datum</label>
                    <input type="date" class="form-control mb-3" name="date" value="<?php echo date('Y-m-d'); ?>" required>

                    <label class="mb-2">Naam tegenrekening</label>
                    <input type="text" class="form-control mb-3" value="Handmatige mutatie" name="name_counter_account">

                    <label class="mb-2">Bedrag</label>
                    <input type="number" step=".01" class="form-control mb-3" name="amount" required>

                    <label class="mb-2">Omschrijving</label>
                    <input type="text" class="form-control mb-3" value="Correctie" name="description">
                    
                    <div class="row">
                        <div class="col">
                            <label class="mb-2">Mutatiecode van</label>
                            <select name="mutation_code_id_from" class="form-control mb-3" required>
                                <option value=""></option>
                                <?php foreach ($data['mutation_codes'] as $mutation_code) { ?>
                                <option value="<?php echo $mutation_code->ID; ?>"><?php echo $mutation_code->DESCRIPTION; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col">
                            <label class="mb-2">Mutatiecode naar</label>
                            <select name="mutation_code_id_to" class="form-control mb-3" required>
                                <option value=""></option>
                                <?php foreach ($data['mutation_codes'] as $mutation_code) { ?>
                                <option value="<?php echo $mutation_code->ID; ?>"><?php echo $mutation_code->DESCRIPTION; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>                    

                    <label class="mb-2">Rekening</label>
                    <select name="account_id" class="form-control mb-3" required>
                        <option value=""></option>
                        <?php foreach ($data['accounts'] as $account) { ?>
                        <?php if ($account->PRIMARY_ACCOUNT == 1) { ?>
                        <option value="<?php echo $account->ID; ?>" selected><?php echo $account->DESCRIPTION; ?> (<?php echo $account->ACCOUNT_NUMBER; ?>)</option>
                        <?php } else { ?>
                        <option value="<?php echo $account->ID; ?>"><?php echo $account->DESCRIPTION; ?> (<?php echo $account->ACCOUNT_NUMBER; ?>)</option>
                        <?php } ?>
                        <?php } ?>
                    </select>

                    <input type="submit" class="btn btn-add" value="Toevoegen" name="add">
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>