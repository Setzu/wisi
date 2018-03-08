<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 05/03/18
 * Time: 16:06
 */
?>

<div class="row">
    <div class="col-md-12">
        <h4>Liste des alertes</h4>
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Système</th>
                <th>Nom</th>
                <th>Utilisateur</th>
                <th>Numéro</th>
                <th>Type</th>
                <th>Sous-système</th>
                <th>Status Actif</th>
                <th>Process unit</th>
            </tr>
            </thead>
            <tbody id="tbody-jobs">
            <?php foreach ($this->aAlertes as $machine => $aAlerte) { ?>
                <?php if (is_array($aAlerte) && array_key_exists('alerte', $aAlerte) && is_array($aAlerte['alerte'])) { ?>
                    <?php foreach ($aAlerte['alerte'] as $v) { ?>
                        <tr style="background-color: <?= '#' . $aJobs['COLOR']; ?>">
                            <td>
                                <span style="color: <?= '#' . $aJobs['COLOR']; ?>;">
                                    <?= $aAlerte['SYSPTY']; ?>
                                </span>
                                <?= $aAlerte['SYSNAME']; ?>
                            </td>
                            <td><?= $v['JOBNAME']; ?></td>
                            <td><?= $v['JOBUSER']; ?></td>
                            <td><?= $v['JOBNUMBER']; ?></td>
                            <td><?= \Wisi\Services\Job::getJobTypeField($v['JOBTYPE'], $v['JOBSUBTYPE']); ?></td>
                            <td><?= $v['SUBSYSTEM']; ?></td>
                            <td><?= $v['ACTJOBSTS']; ?></td>
                            <td><?= $v['PROCESSUNT']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>