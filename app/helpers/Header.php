<?php require_once APP_ROOT . '/models/UserBC.php';
$UserBC = new UserBC(); 
$user = $UserBC->getUser(); ?>

<!DOCTYPE html>
    <head>
        <title><?php echo $data['title']; ?> | <?php echo SITE_NAME; ?></title>
        <meta charset="UTF-8">
        <meta name="author" content="<?php echo SITE_NAME; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/x-icon" href="<?php echo LOGO; ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo URL_ROOT; ?>/css/style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Saira:ital,wght@0,400;0,700;1,300&display=swap" rel="stylesheet">
    </head>
    <body>
        <div id="sidenav" class="sidenav">
            <div class="user text-center">
                <p>Hi, <b><?php echo $user->FIRST_NAME; ?></b>!</p>
            </div>
            <div class="menu text-center mb-2">
                <ul>
                    <li>
                        <a title="Bekijk alle mutaties." href="<?php echo URL_ROOT; ?>/mutaties/">
                            <img src="<?php echo URL_ROOT; ?>/icons/coin.svg">
                            Mutaties
                        </a>
                    </li>
                    <li>
                        <a title="Bekijk alle mutatiecodes." href="<?php echo URL_ROOT; ?>/mutatiecodes/">
                        <img src="<?php echo URL_ROOT; ?>/icons/receipt.svg">
                            Mutatiecodes
                        </a>
                    </li>
                    <?php if ($user->CATEGORIES_ENABLED != false) { ?>
                    <li>
                        <a title="Bekijk alle categorieën." href="<?php echo URL_ROOT; ?>/categorieen/">
                            <img src="<?php echo URL_ROOT; ?>/icons/layers.svg">
                            Categorieën
                        </a>
                    </li>
                    <?php } ?>
                    <li>
                        <a title="Bekijk alle openstaande mutaties." href="<?php echo URL_ROOT; ?>/openstaande-mutaties/">
                            <img src="<?php echo URL_ROOT; ?>/icons/hourglass-split.svg">
                            Openstaande mutaties
                        </a>
                    </li>
                    <li>
                        <a title="Bekijk alle rekeningen." href="<?php echo URL_ROOT; ?>/rekeningen/">
                            <img src="<?php echo URL_ROOT; ?>/icons/piggy-bank.svg">
                            Rekeningen
                        </a>
                    </li>
                    <li>
                        <a title="Bekijk alle overzichten." href="<?php echo URL_ROOT; ?>/overzichten/">
                            <img src="<?php echo URL_ROOT; ?>/icons/binoculars.svg">
                            Overzichten
                        </a>
                    </li>
                    <li>
                        <a title="Bekijk persoonlijke instellingen." href="<?php echo URL_ROOT; ?>/instellingen/">
                            <img src="<?php echo URL_ROOT; ?>/icons/gear.svg">
                            Instellingen
                        </a>
                    </li>
                </ul>
            </div>
            <div class="versie">
                <p class="text-center">Versie: 6.0</p>
            </div>
        </div>
        <div class="main">
            <?php if (isset($data['alert']) && isset($data['message'])) { ?>
            <div class="alert alert-<?php echo $data['alert']; ?>" role="alert"><?php echo $data['message']; ?></div>
            <?php } ?>