<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Rekeningen</h1>

<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add" class="btn btn-add mb-3">+</a>

<div class="table-responsive">
    <table class="table table-striped table-bordered rekeningen">
        <thead class="thead-dark">
            <tr>
                <th>Naam</th>
                <th>Rekeningnummer</th>
                <th>Saldo</th>
                <th>Betaalrekening?</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $saldo_totaal = 0;
            $i = 0;
            foreach ($data['accounts'] as $account) { $i++; $saldo_totaal = $saldo_totaal + $account->BALANCE; ?>
            <tr>
                <td><?php echo $account->DESCRIPTION; ?></td>
                <td><?php echo $account->ACCOUNT_NUMBER; ?></td>
                <td>
                    <?php echo '€' . number_format($account->BALANCE, 2, ',', '.'); ?>
                </td>
                <td>
                    <?php echo $account->PRIMARY_ACCOUNT === 1 ? 'Ja' : 'Nee'; ?>
                </td>
                <td class="actions text-center">
                    <a class="view mr-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit_<?php echo $account->ID; ?>"><img class="me-2" src="<?php echo URL_ROOT; ?>/icons/pencil-square.svg"></a>
                    <a class="delete" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#delete_<?php echo $account->ID; ?>"><img class="trash" src="<?php echo URL_ROOT; ?>/icons/trash.svg"></a>
                </td>
            </tr>
            <!-- Modal: Rekening bewerken -->
            <div class="modal fade" id="edit_<?php echo $account->ID; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Rekening bewerken</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" name="account_id" value="<?php echo $account->ID; ?>">
                                
                                <label class="mb-2">Naam</label>
                                <input type="text" name="description" value="<?php echo $account->DESCRIPTION; ?>" class="form-control mb-3" required>

                                <label class="mb-2">Rekeningnummer</label>
                                <input type="text" name="account_number" value="<?php echo $account->ACCOUNT_NUMBER; ?>" class="form-control mb-3" required>

                                <label class="mb-2">Huidig saldo</label>
                                <input type="text" name="balance" value="<?php echo '€' . number_format($account->BALANCE, 2, ',', '.'); ?>" class="form-control mb-3" disabled>

                                <label class="mb-2">Is deze rekening je betaalrekening?</label>
                                <select name="primary_account" class="form-control" required>
                                    <?php if ($account->PRIMARY_ACCOUNT === 1) { ?>
                                    <option value="0">Nee</option>
                                    <option value="1" selected>Ja</option>
                                    <?php } else { ?>
                                    <option value="0" selected>Nee</option>
                                    <option value="1">Ja</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                                <input type="submit" name="edit" value="Opslaan" class="btn btn-add">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal: Rekening verwijderen -->
            <div class="modal fade" id="delete_<?php echo $account->ID; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Rekening verwijderen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" name="account_id" value="<?php echo $account->ID; ?>">
                                <input type="hidden" name="description" value="<?php echo $account->DESCRIPTION; ?>">
                                <p class="m-0">Weet je zeker dat je '<?php echo $account->DESCRIPTION; ?>' wilt verwijderen?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nee</button>
                                <input type="submit" name="delete" value="Ja" class="btn btn-add">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php if (count($data['accounts']) == 0) { ?>
            <tr>
                <td colspan="5">Er zijn geen rekeningen gevonden.</td>
            </tr>
            <?php } else { ?>
            <tr>
                <td></td>
                <td><b>Totaal</b></td>
                <td><b><?php echo '€' . number_format($saldo_totaal, 2, ',', '.'); ?></b></td>
                <td></td>
                <td></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal: Rekening toevoegen -->
<div class="modal fade" id="add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rekening toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <label class="mb-2">Naam</label>
                    <input type="text" name="description" class="form-control mb-3" required>

                    <label class="mb-2">Rekeningnummer</label>
                    <input type="text" name="account_number" class="form-control mb-3" required>

                    <label class="mb-2">Huidig saldo</label>
                    <input type="text" name="balance" class="form-control mb-2" required>

                    <label class="mb-2">Is deze rekening je betaalrekening?</label>
                    <select name="primary_account" class="form-control" required>
                        <option value="0" selected>Nee</option>
                        <option value="1">Ja</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                    <input type="submit" name="add" value="Toevoegen" class="btn btn-add">
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>