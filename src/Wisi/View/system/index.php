<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 26/02/18
 * Time: 15:45
 */
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3 cadre-form">
        <a href="/index" class="btn btn-default"><span class="glyphicon glyphicon-home">&nbsp;</span>Retour à l'accueil</a>
        <h3 class="title-form">Ajouter un système</h3>
        <p style="border: 2px solid #ac2925; padding: 5px; text-align: center;">
            <strong>Faire une demande d'ouverture de flux à l'infra avant d'ajouter un système.</strong>
        </p>
        <hr>
        <form action="/system" method="post" role="form" id="addsystem" class="form-horizontal">
            <div class="form-group">
                <label for="NMSYS" class="col-sm-4 control-label">Nom du système<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-name" type="text" name="NMSYS" required="required" placeholder="S65ff17d" maxlength="10" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="SYSNAME" class="col-sm-4 control-label">Alias<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-alias" type="text" name="SYSNAME" required="required" placeholder="DEV" maxlength="10" class="form-control">
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label for="DBNAME" class="col-sm-4 control-label">Nom BDD<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-bdd" type="text" name="DBNAME" required="required" placeholder="D6022b34" maxlength="20" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="IPADR" class="col-sm-4 control-label">IP<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-host" type="text" name="IPADR" required="required" placeholder="192.168.24.4" maxlength="15" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="NMUSR" class="col-sm-4 control-label">Utilisateur<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-user" type="text" name="NMUSR" required="required" placeholder="QPGMR" maxlength="10" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="PWUSR" class="col-sm-4 control-label">Mot de passe<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-password" type="password" name="PWUSR" required="required" placeholder="********" maxlength="10" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4" style="text-align: center;">
                    <button type="button" id="test-connection" class="btn btn-default btn-block">
                        <span id="system-con" class="glyphicon glyphicon-refresh"></span>&nbsp;Tester la connexion
                    </button>
                </div>
            </div>
            <span id="connect-error" style="text-align: center;"></span>
            <hr>
            <div class="form-group">
                <label for="SYSTEMTYP" class="col-sm-4 control-label">Type de données :</label>
                <div class="col-sm-4">
                    <input id="system-type" type="text" name="SYSTEMTYP" maxlength="3" placeholder="DEV" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="SYSPTY" class="col-sm-4 control-label">Priorité<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-priority" type="number" name="SYSPTY" min="1" placeholder="1" class="form-control" required="required">
                </div>
            </div>
            <div class="form-group">
                <label for="COLOR" class="col-sm-4 control-label">Couleur :</label>
                <div class="col-sm-4">
                    <input id="system-color" type="color" name="COLOR" value="#FFFFFF" style="width: 100%; height: 35px;">
                </div>
            </div>
            <span style="color: #a94442; float: left;">*&nbsp;</span>
            <p>Champs obligatoire</p>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4" style="text-align: center;">
                    <button id="add-system" disabled="disabled" type="submit" class="btn btn-primary btn-block">Ajouter le système</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="<?= '/js/add-system.js' ?>"></script>
<script type="text/javascript" src="<?= '/js/test-connection.js' ?>"></script>
