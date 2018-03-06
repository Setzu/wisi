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
            <?php if (isset($this->aAlertes)) { ?>
                <li class="tab-alert"><a id="link-tab3" href="#tabs-3">Alertes</a></li>
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
        <?php if (isset($this->aAlertes)) { ?>
            <!-- Contenu chargé en ajax (jobs.php)-->
            <div id="tabs-3"></div>
        <?php } ?>
        <div id="tabs-4">
            <div class="row" style="text-align: center">
                <h4>Paramétrage des jobs</h4>
                <a href="/job" class="btn btn-default">Suivre un job</a>
                <a href="/job/affichage" class="btn btn-danger">Affichage des jobs</a>
            </div>
            <hr>
            <div class="row" style="text-align: center">
                <h4>Paramétrage des messages QSYSOPR</h4>
                <a href="#" class="btn btn-danger">Messages</a>
            </div>
            <hr>
            <div class="row" style="text-align: center">
                <h4>Paramétrage des systèmes</h4>
                <a href="/system" class="btn btn-default">Ajouter un système</a>
                <a href="#" class="btn btn-danger">Modifier un système</a>
                <a href="#" class="btn btn-danger">Modifier les couleurs système</a>
                <a href="#" class="btn btn-danger">Modifier les priorités système</a>
            </div>
            <hr>
            <div class="row" style="text-align: center">
                <h4>Paramétrage utilisateurs</h4>
                <a href="#" class="btn btn-danger">Admin</a>
            </div>
        </div>
    </div>
</div>