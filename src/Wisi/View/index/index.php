<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 19/02/18
 * Time: 11:48
 */
?>

<div class="row" style="box-shadow: 5px 5px 15px #888888;">
    <div id="tabs">
        <ul>
            <li><a id="link-tab1" href="#tabs-1">GFP Power - Main</a></li>
            <li><a id="link-tab2" href="#tabs-2">GFP Power - Jobs</a></li>
            <li><a id="link-tab3" href="#tabs-3">GFP Power - Informations</a></li>
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
        <!-- Contenu chargé en ajax (informations.php)-->
        <div id="tabs-3"></div>
        <div id="tabs-4">
            <div class="row" style="text-align: center">
                <h3>Paramétrage des jobs</h3>
                <br>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <a href="/job" class="btn btn-success btn-block btn-lg">
                        <span class="glyphicon glyphicon-eye-open">&nbsp;</span>Suivre un job
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/job/display" class="btn btn-success btn-block btn-lg">
                        <span class="glyphicon glyphicon-th-list">&nbsp;</span>Affichage des jobs
                    </a>
                </div>
            </div>
            <br>
            <div class="row" style="text-align: center">
                <h3>Paramétrage des systèmes</h3>
                <br>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <a href="/system" class="btn btn-success btn-block btn-lg">
                        <span class="glyphicon glyphicon-plus">&nbsp;</span>Ajouter un système
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="/system/update" class="btn btn-success btn-block btn-lg">
                        <span class="glyphicon glyphicon-pencil">&nbsp;</span>Modifier un système
                    </a>
                </div>
            </div>
            <br>
            <div class="row" style="text-align: center">
                <h3>Paramétrage de l'application</h3>
                <br>
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <a href="/index/timer" class="btn btn-success btn-block btn-lg">
                        <span class="glyphicon glyphicon-time">&nbsp;</span>Modifier le cycle d'actualisation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= '/js/tab.js'; ?>"></script>
<script type="text/javascript" src="<?= '/js/load-messages.js'; ?>"></script>
<script type="text/javascript" src="<?= '/js/load-jobs.js'; ?>"></script>
<script type="text/javascript" src="<?= '/js/load-informations.js'; ?>"></script>

<!-- Rafraîchissement de la page si inactivité pendant N secondes-->
<script type="text/javascript">
        $(function (seconds) {
            var _timer = seconds -1;

            var refresh,
                intvrefresh = function () {
                    clearInterval(refresh);
                    refresh = setTimeout(function () {
                        location.href = location.href;
                    }, seconds * 1000);
                };

            $(document).on('keypress click mousemove', function () {
                intvrefresh();
                _timer = seconds -1;
            });

            intvrefresh();

            function Timer() {
                $('#timer').text(_timer --);
            }

            setInterval(Timer, 1000);

        }(<?php echo $this->iTimer; ?>));
</script>