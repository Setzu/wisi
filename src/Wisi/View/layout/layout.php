<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 19/02/18
 * Time: 12:10
 */
?>

<!DOCTYPE html>
<title>GFP Web Informations System IBMi</title>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="<?= '/img/favicon.png'; ?>"/>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= '/css/global.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?= '/css/lib/jquery.dataTables.min.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?= '/css/lib/jquery-ui.min.css'; ?>">
    <link rel="stylesheet" type="text/css" href="<?= '/css/lib/bootstrap.min.css'; ?>">

    <!-- JS -->
    <script type="text/javascript" src="<?= '/js/lib/jquery-3.3.1.min.js'; ?>"></script>
    <script type="text/javascript" src="<?= '/js/lib/jquery-ui.min.js'; ?>"></script>
    <script type="text/javascript" src="<?= '/js/lib/chart.min.js'; ?>"></script>
    <script type="text/javascript" src="<?= '/js/lib/jquery-dataTables.min.js'; ?>"></script>
</head>

<body>

<header>
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/index" style="float: left; padding-top: 5px;">
                <img src="/img/gfp_logo.png" alt="logo gfp">
            </a>
            <?= '<h4 class="header-date">' . date('d/m/Y H:i:s') . '</h4>';?>
        </div>
    </nav>
</header>

<div class="container-fluid" style="width: 1400px;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <?= \Wisi\Stdlib\SessionManager::flashMessages(); ?>
        </div>
    </div>
    <?php if (file_exists($this->content)) {
        include_once ($this->content);
    } ?>
</div>

<footer></footer>

</body>
</html>