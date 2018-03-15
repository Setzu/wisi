<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 15/03/18
 * Time: 09:58
 */
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3 cadre-form">
        <a href="/index" class="btn btn-default"><span class="glyphicon glyphicon-home">&nbsp;</span>Retour à l'accueil</a>
        <h3 class="title-form">Timer de rafraichissement</h3>
        <hr>
        <form action="/index/timer" role="form" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="timer" class="col-sm-6 control-label">Timer (en secondes) :</label>
                <div class="col-sm-2">
                    <input type="number" name="timer" required="required" placeholder="30" value="<?= $this->iTimer; ?>" min="10" max="90" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4" style="text-align: center;">
                    <button type="submit" class="btn btn-primary btn-block">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>
