<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Mutatiecodes</h1>

<?php if (count($data['mutation_codes']) > 0) { ?>
<input class="form-control mb-4" id="search" type="text" placeholder="Zoek op mutatiecode...">
<?php } ?>

<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add" class="btn btn-add mb-3">+</a>

<div class="table-responsive">
    <table class="table table-striped table-bordered mutatiecodes">
        <thead class="thead-dark">
            <tr>
                <th class="text-nowrap">Omschrijving</th>
                <th>Matching</th>
                <th class="text-nowrap">Tonen in overzicht</th>
                <?php if ($user->CATEGORIES_ENABLED !== false) { ?>
                <th class="text-nowrap">Categorie</th>
                <?php } ?>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody id="grid">
            <?php foreach ($data['mutation_codes'] as $mutation_code) { ?>
            <tr>
                <td class="text-nowrap"><?php echo $mutation_code->DESCRIPTION; ?></td>
                <td><?php echo $mutation_code->MATCHING; ?></td>
                <td>
                    <?php echo $mutation_code->SHOW_IN_OVERVIEW == 1 ? 'Ja' : 'Nee'; ?>
                </td>
                <?php if ($user->CATEGORIES_ENABLED !== false) { ?>
                <td class="text-nowrap"><?php echo $mutation_code->CATEGORY_DESCRIPTION; ?></td>
                <?php } ?>
                <td class="actions text-center">
                    <a class="view mr-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit_<?php echo $mutation_code->ID; ?>"><img class="me-2" src="<?php echo URL_ROOT; ?>/icons/pencil-square.svg"></a>
                    <a class="delete" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#delete_<?php echo $mutation_code->ID; ?>"><img class="trash" src="<?php echo URL_ROOT; ?>/icons/trash.svg"></a>
                </td>
            </tr>
            <!-- Modal: Mutatiecode bewerken -->
            <div class="modal fade" id="edit_<?php echo $mutation_code->ID; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Mutatiecode bewerken</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" name="mutation_code_id" value="<?php echo $mutation_code->ID; ?>">
                                
                                <label class="mb-2">Omschrijving</label>
                                <input type="text" name="description" value="<?php echo $mutation_code->DESCRIPTION; ?>" class="form-control mb-3" required>

                                <label class="mb-2">Matching</label>
                                <textarea name="matching" class="form-control mb-3"><?php echo $mutation_code->MATCHING; ?></textarea>

                                <label class="mb-2">Wil je deze mutatiecode tonen in overzichten?</label>
                                <select name="show_in_overview" class="form-control mb-3" required>
                                    <?php if ($mutation_code->SHOW_IN_OVERVIEW == 1) {
                                        echo '<option value="1" selected>Ja</option>';
                                        echo '<option value="0">Nee</option>';
                                    } else {
                                        echo '<option value="1">Ja</option>';
                                        echo '<option value="0" selected>Nee</option>';
                                    } ?>
                                </select>
                                
                                <?php if ($user->CATEGORIES_ENABLED !== false) { ?>
                                <label class="mb-2">Bij welke categorie hoort deze mutatiecode?</label>
                                <select name="category_id" class="form-control mb-3">
                                    <option value=""></option>
                                    <?php foreach ($data['categories'] as $category) { ?>
                                    <?php if ($category->ID == $mutation_code->CATEGORY_ID) { ?>
                                    <option value="<?php echo $category->ID; ?>" selected><?php echo $category->DESCRIPTION; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $category->ID; ?>"><?php echo $category->DESCRIPTION; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <input type="submit" name="edit" value="Opslaan" class="btn btn-add btn-block">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal: Mutatiecode verwijderen -->
            <div class="modal fade" id="delete_<?php echo $mutation_code->ID; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Mutatiecode verwijderen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" name="mutation_code_id" value="<?php echo $mutation_code->ID; ?>">
                                <input type="hidden" name="description" value="<?php echo $mutation_code->DESCRIPTION; ?>">
                                <p class="m-0">Weet je zeker dat je '<?php echo $mutation_code->DESCRIPTION; ?>' wilt verwijderen?
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
            <?php if (count($data['mutation_codes']) == 0) { ?>
            <tr>
                <td colspan="7">Er zijn geen mutatiecodes gevonden.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal: Mutatiecode toevoegen -->
<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mutatiecode toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <label class="mb-2">Omschrijving</label>
                    <input type="text" name="description" class="form-control mb-3" required>
                    
                    <label class="mb-2">Matching</label>
                    <textarea name="matching" class="form-control mb-3"></textarea>
                    
                    <label class="mb-2">Wil je deze mutatiecode tonen in overzichten?</label>
                    <select name="show_in_overview" class="form-control mb-3">
                        <option value="1" selected>Ja</option>
                        <option value="0">Nee</option>
                    </select>
                    
                    <?php if ($user->CATEGORIES_ENABLED !== false) { ?>
                    <label class="mb-2">Bij welke categorie hoort deze mutatiecode?</label>
                    <select name="category_id" class="form-control mb-3">
                        <option value=""></option>
                        <?php foreach ($data['categories'] as $category) { ?>
                        <option value="<?php echo $category->ID; ?>"><?php echo $category->DESCRIPTION; ?></option>
                        <?php } ?>
                    </select>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="add" value="Toevoegen" class="btn btn-add btn-block">
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>