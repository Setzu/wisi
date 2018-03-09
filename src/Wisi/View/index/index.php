<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 19/02/18
 * Time: 11:48
 */
?>

<div class="row">
    <div id="tabs">
        <ul>
            <li><a id="link-tab1" href="#tabs-1">GFP Power - Main</a></li>
            <li><a id="link-tab2" href="#tabs-2">GFP Power - Jobs</a></li>
            <?php if (isset($_SESSION['alerts']) && is_array($_SESSION['alerts'])) { ?>
                <li class="tab-alert">
                    <a id="alert" href="#tabs-3"><?= $_SESSION['alerts']['count']; ?>&nbsp;Alertes</a>
                </li>
            <?php } ?>
            <li><a href="#tabs-4">Paramétrage</a></li>
        </ul>
        <!-- Contenu chargé en ajax (messages.php)-->
        <div id="tabs-1">
        <span class="ajax-loader col-md-offset-5" style="display: none;">
            <img src="<?= '/img/ajax-loader.gif'; ?>" alt="loader">
        </span>
        </div>
        <!-- Contenu chargé en ajax (jobs.php)-->
        <div id="tabs-2">
        <span class="ajax-loader col-md-offset-5" style="display: none;">
            <img src="<?= '/img/ajax-loader.gif'; ?>" alt="loader">
        </span>
        </div>
        <?php if (isset($_SESSION['alerts']) && is_array($_SESSION['alerts'])) { ?>
            <div id="tabs-3">
                <h4>Liste des alertes</h4>
                <?= \Wisi\Stdlib\SessionManager::alerts(); ?>
            </div>
        <?php } ?>
        <div id="tabs-4">
            <div class="row" style="text-align: center">
                <h4>Paramétrage des jobs</h4>
                <a href="/job" class="btn btn-default">Suivre un job</a>
                <a href="/job/display" class="btn btn-default">Affichage des jobs</a>
            </div>
            <hr>
            <div class="row" style="text-align: center">
                <h4>Paramétrage des systèmes</h4>
                <a href="/system" class="btn btn-default">Ajouter un système</a>
                <a href="/system/update" class="btn btn-default">Modifier un système</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= '/js/tab.js'; ?>"></script>
<script type="text/javascript" src="<?= '/js/load-messages.js'; ?>"></script>
<script type="text/javascript" src="<?= '/js/load-jobs.js'; ?>"></script>

<!-- Rafraîchissement de la page si inictivité pendant 30 secondes-->
<script type="text/javascript">
    $(function(seconds) {
        var refresh,
            intvrefresh = function() {
                clearInterval(refresh);
                refresh = setTimeout(function() {
                    location.href = location.href;
                }, seconds * 1000);
            };

        $(document).on('keypress click mousemove', function() { intvrefresh() });
        intvrefresh();

    }(30));
</script>