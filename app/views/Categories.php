<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Categorieën</h1>

<button class="btn btn-add mb-3" data-bs-toggle="modal" data-bs-target="#add">+</button>

<div class="table-responsive">
    <table class="table table-striped table-bordered mutatiecodes">
        <thead class="thead-dark">
            <tr>
                <th>Omschrijving</th>
                <th class="text-nowrap">Tonen in overzicht</th>
                <th class="text-nowrap">Budget</th>
                <th class="text-center"></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['categories'] as $category) { ?>
            <tr>
                <td><?php echo $category->DESCRIPTION; ?></td>
                <td>
                    <?php
                    if ($category->SHOW_IN_OVERVIEW == 1) {
                        echo 'Ja';
                    } else {
                        echo 'Nee';
                    }
                    ?>
                </td>
                <td><?php echo '€' . number_format($category->BUDGET, 2, ',', '.'); ?></td>
                <td class="actions text-center">
                    <a class="view mr-2" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#edit_<?php echo $category->ID; ?>"><img class="me-2" src="<?php echo URL_ROOT; ?>/icons/pencil-square.svg"></a>
                    <a class="delete" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#delete_<?php echo $category->ID; ?>"><img class="trash" src="<?php echo URL_ROOT; ?>/icons/trash.svg"></a>
                </td>
            </tr>
            <!-- Modal: Categorie bewerken -->
            <div class="modal fade" id="edit_<?php echo $category->ID; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Categorie bewerken</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" name="category_id" value="<?php echo $category->ID; ?>">
                                
                                <label class="mb-2">Omschrijving</label>
                                <input type="text" name="description" value="<?php echo $category->DESCRIPTION; ?>" class="form-control mb-3" required>

                                <label class="mb-2">Wil je deze categorie tonen in overzichten?</label>
                                <select name="show_in_overview" class="form-control mb-2" required>
                                    <?php
                                    if ($category->SHOW_IN_OVERVIEW == 1) {
                                        echo '<option value="1" selected>Ja</option>';
                                        echo '<option value="0">Nee</option>';
                                    } else {
                                        echo '<option value="1">Ja</option>';
                                        echo '<option value="0" selected>Nee</option>';
                                    }
                                    ?>
                                </select>
                                
                                <label class="mb-2">Budget</label>
                                <input type="number" name="budget" value="<?php echo $category->BUDGET; ?>" class="form-control mb-2">
                            </div>
                            <div class="modal-footer">
                                <input type="submit" name="edit" value="Opslaan" class="btn btn-add btn-block">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal: Categorie verwijderen -->
            <div class="modal fade" id="delete_<?php echo $category->ID; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Categorie verwijderen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="post">
                            <div class="modal-body">
                                <input type="hidden" name="category_id" value="<?php echo $category->ID; ?>">
                                <input type="hidden" name="description" value="<?php echo $category->DESCRIPTION; ?>">
                                <p class="m-0">Weet je zeker dat je '<?php echo $category->DESCRIPTION; ?>' wilt verwijderen?
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
            <?php if (count($data['categories']) == 0) { ?>
            <tr>
                <td colspan="7">Er zijn geen categorieën gevonden.</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Modal: Categorie toevoegen -->
<div class="modal fade" id="add" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Categorie toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <label class="mb-2">Omschrijving</label>
                    <input type="text" name="description" class="form-control mb-3" required>
                    
                    <label class="mb-2">Wil je deze categorie tonen in overzichten?</label>
                    <select name="show_in_overview" class="form-control mb-3">
                        <option value="1" selected>Ja</option>
                        <option value="0">Nee</option>
                    </select>
                    
                    <label class="mb-2">Budget</label>
                    <input type="number" name="budget" class="form-control mb-2">
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