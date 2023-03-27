<?php require_once APP_ROOT . '/helpers/Header.php'; ?>

<div class="row">
    <div class="col-3 mb-3">
        <a class="btn btn-add btn-lg w-100 overzicht-item" href="<?php echo URL_ROOT; ?>/overzichten/mutaties/">
            <img src="<?php echo URL_ROOT; ?>/icons/coin.svg">
            <p>Mutaties</p>
        </a>
    </div>
    <div class="col-3 mb-3">
        <a class="btn btn-add btn-lg w-100 overzicht-item" href="<?php echo URL_ROOT; ?>/overzichten/uitgaven/">
            <img src="<?php echo URL_ROOT; ?>/icons/cash-coin.svg">
            <p>Uitgaven</p>
        </a>
    </div>
    <div class="col-3 mb-3">
        <a class="btn btn-add btn-lg w-100 overzicht-item" href="<?php echo URL_ROOT; ?>/overzichten/balans/">
            <img src="<?php echo URL_ROOT; ?>/icons/clipboard-data.svg">
            <p>Balans</p>
        </a>
    </div>
    <?php if ($user->CATEGORIES_ENABLED == 1) { ?>
    <div class="col-3 mb-3">
        <a class="btn btn-add btn-lg w-100 overzicht-item" href="<?php echo URL_ROOT; ?>/overzichten/categorieen/">
            <img src="<?php echo URL_ROOT; ?>/icons/layers.svg">
            <p>Categorieën</p>
        </a>
    </div>
    <div class="col-3 mb-3">
        <a class="btn btn-add btn-lg w-100 overzicht-item" href="<?php echo URL_ROOT; ?>/overzichten/budgetten/">
            <img src="<?php echo URL_ROOT; ?>/icons/wallet2.svg">
            <p>Budgetten</p>
        </a>
    </div>
    <?php } ?>
    <div class="col-3 mb-3">
        <a class="btn btn-add btn-lg w-100 overzicht-item" href="<?php echo URL_ROOT; ?>/overzichten/saldoverloop/">
            <img src="<?php echo URL_ROOT; ?>/icons/graph-up.svg">
            <p>Saldoverloop</p>
        </a>
    </div>
</div>

<?php require_once APP_ROOT . '/helpers/Footer.php'; ?>