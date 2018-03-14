<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 06/03/18
 * Time: 10:50
 */
?>

<div class="row">
    <div class="col-md-6 col-md-offset-3 cadre-form">
        <a href="/wisi/accueil" class="btn btn-default"><span class="glyphicon glyphicon-home">&nbsp;</span>Retour à l'accueil</a>
        <h3 class="title-form">Affichage des jobs</h3>
        <hr>
        <form action="/wisi/job/display" role="form" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="number" class="col-sm-6 control-label">Quantité de Jobs à récupérer par machines :</label>
                <div class="col-sm-2">
                    <input type="number" name="number" required="required" placeholder="3" value="<?= $this->iJobs; ?>" min="1" max="15" class="form-control">
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
