<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<h1 class="mb-4">Instellingen</h1>

<form method="post">
    <div class="row">
        <div class="col-12 col-md-6 mb-3">
            <label class="mb-2">Wat is je voornaam?</label>
            <input type="text" name="first_name" class="form-control" value="<?php echo $data['user']->FIRST_NAME; ?>" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="mb-2">Wat is je achternaam?</label>
            <input type="text" name="last_name" class="form-control" value="<?php echo $data['user']->LAST_NAME; ?>" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="mb-2">Welke bank kunnen we als voorkeur gebruiken?</label>
            <select name="bank" class="form-control" required>
                <?php
                if ($data['user']->BANK == "") {
                    echo '<option value="" selected></option>';
                }
                if ($data['user']->BANK == "ingb") {
                    echo '<option value="ingb" selected>ING</option>';
                } else {
                    echo '<option value="ingb">ING</option>';
                }
                if ($data['user']->BANK == "rabo") {
                    echo '<option value="rabo" selected>Rabobank</option>';
                } else {
                    echo '<option value="rabo">Rabobank</option>';
                }
                if ($data['user']->BANK == "snsb") {
                    echo '<option value="snsb" selected>SNS Bank</option>';
                } else {
                    echo '<option value="snsb">SNS Bank</option>';
                }
                ?>
            </select>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="mb-2">Wil je gebruik maken van categorieën?</label>
            <select name="categories_enabled" class="form-control" required>
                <?php if ($data['user']->CATEGORIES_ENABLED != 0) { ?>
                <option value="0">Nee</option>
                <option value="1" selected>Ja</option>
                <?php } else { ?>
                <option value="0" selected>Nee</option>
                <option value="1">Ja</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="mb-2">Welke poort gebruikt dit programma?</label>
            <?php if ($data['user']->PORT == "" || $data['user']->PORT == 0) { ?>
            <input type="number" name="port" class="form-control" value="">
            <?php } else { ?>
            <input type="number" name="port" class="form-control" value="<?php echo $data['user']->PORT; ?>">
            <?php } ?>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="mb-2">Op welk besturingssysteem zit je?</label>
            <select name="system" class="form-control" required>
                <?php if ($data['user']->SYSTEM == "macos") { ?>
                <option value="windows">Windows</option>
                <option value="macos" selected>MacOS</option>
                <?php } else { ?>
                <option value="windows" selected>Windows</option>
                <option value="macos">MacOS</option>
                <?php } ?>
            </select>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label class="mb-2">Back-up directory</label>
            <input type="text" name="backup" class="form-control mb-3" value="<?php echo $data['user']->BACKUP; ?>">
            <?php if ($data['user']->BACKUP != "") { ?>
            <button type="submit" name="backup" class="btn btn-dark">Download back-up file</button>
            <?php } else { ?>
            <button type="button" class="btn btn-dark" disabled>Download back-up file</button>
            <?php } ?>
        </div>
        <div class="col-12">
            <input type="hidden" name="user_id" value="<?php echo $data['user']->ID; ?>">
            <input type="submit" id="edit" name="edit" class="btn btn-add" value="Opslaan">
        </div>
    </div>
</form>

<script>
    const input = document.querySelector('input')
    input.onkeydown = (e) => {
        if (e.keyCode === 13) {
            e.preventDefault()
            document.getElementById("edit").click();
        }
    };
</script>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>