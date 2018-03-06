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
        <a href="/index" class="btn btn-default"><span class="glyphicon glyphicon-home">&nbsp;</span>Retour à l'accueil</a>
        <h3 class="title-form">Affichage des jobs :</h3>
        <hr>
        <form action="/job/affichage" role="form" method="post" class="form-horizontal">
            <div class="form-group">
                <label for="number" class="col-sm-4 control-label">Jobs à afficher par machines :</label>
                <div class="col-sm-4">
                    <input type="number" name="number" required="required" placeholder="3" value="3" min="1" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-4" style="text-align: center;">
                    <button type="submit" class="btn btn-primary">Valider</button>
                </div>
            </div>
        </form>
    </div>
</div>
