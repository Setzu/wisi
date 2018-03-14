<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 28/02/18
 * Time: 09:25
 */
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3 cadre-form">
        <a href="/wisi/accueil" class="btn btn-default"><span class="glyphicon glyphicon-home">&nbsp;</span>Retour à l'accueil</a>
        <h3 class="title-form">Suivre un job</h3>
        <hr>
        <form action="/wisi/job" role="form" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="system" class="col-sm-4 control-label">Système :</label>
                <div class="col-sm-4">
                    <select name="system" class="form-control" required="required">
                        <?php if (isset($this->aSystems) && is_array($this->aSystems)) { ?>
                            <?php foreach ($this->aSystems as $name) {?>
                                <option value="<?= $name; ?>"><?= $name; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="sub-system" class="col-sm-4 control-label">Sous-système :</label>
                <div class="col-sm-4">
                    <input id="subsystem" type="text" name="sub-system" maxlength="10" required="required" placeholder="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="name" class="col-sm-4 control-label">Nom du job :</label>
                <div class="col-sm-4">
                    <input id="jobname" type="text" name="name" maxlength="10" required="required" placeholder="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="user" class="col-sm-4 control-label">Utilisateur du job :</label>
                <div class="col-sm-4">
                    <input id="jobuser" type="text" name="user" maxlength="10" required="required" placeholder="" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4" style="text-align: center;">
                    <button type="submit" class="btn btn-primary btn-block">Ajouter le suivi</button>
                </div>
            </div>
        </form>
        <p style="border: 1px solid #ac2925; padding: 5px;">Une fois le suivi d'un job validé, une alerte sera
            déclenchée si le job n'est PAS présent dans le fichier <strong>SSYJBSP0</strong> du système sélectionné.</p>
    </div>
</div>

<script type="text/javascript" src="<?= '/wisi/js/job-follow.js' ;?>"></script>