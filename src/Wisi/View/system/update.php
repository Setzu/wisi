<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 08/03/18
 * Time: 12:22
 */
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3 cadre-form">
        <a href="/wisi/" class="btn btn-default"><span class="glyphicon glyphicon-home">&nbsp;</span>Retour à l'accueil</a>
        <h3 class="title-form">Modifier un système</h3>
        <hr>
        <form action="/wisi/system/update" role="form" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="NMSYS" class="col-sm-4 control-label">Système :</label>
                <div class="col-sm-4">
                    <select name="NMSYS" id="select-update" class="form-control" required="required">
                        <?php if (isset($this->aSystemsList) && is_array($this->aSystemsList)) { ?>
                            <?php foreach ($this->aSystemsList as $aSystem) { ?>
                                <option value="<?= urlencode($aSystem['NMSYS']); ?>"><?= $aSystem['NMSYS']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="SYSNAME" class="col-sm-4 control-label">Alias<span style="color: #a94442;">*</span> :</label>
                <div class="col-sm-4">
                    <input id="system-alias" type="text" name="SYSNAME" required="required" placeholder="DEV" maxlength="10" class="form-control">
                </div>
            </div>
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
                    <div class="input-group">
                        <div class="input-group-addon">#</div>
                        <input id="system-color" type="text" name="COLOR" maxlength="6" class="form-control">
                        <div class="input-group-addon" id="color">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                    </div>
                </div>
            </div>
            <span style="color: #a94442; float: left;">*&nbsp;</span>
            <p>Champs obligatoire</p>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4" style="text-align: center;">
                    <button type="submit" class="btn btn-primary btn-block">Valider</button>
                </div>
                <div class="col-sm-4">
                    <button id="delete-system" type="button" class="btn btn-danger" data-toggle="tooltip"
                            title="Supprimer le système ?" style="float: right;">Supprimer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="<?= '/wisi/js/add-system.js'; ?>"></script>
<script type="text/javascript" src="<?= '/wisi/js/update-system.js'; ?>"></script>
<script type="text/javascript" src="<?= '/wisi/js/delete-system.js'; ?>"></script>
