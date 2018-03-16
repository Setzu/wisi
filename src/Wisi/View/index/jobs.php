<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 26/02/18
 * Time: 10:00
 */
?>

<div class="row">
    <div class="col-md-12">
        <?php if (isset($this->aJobs) && is_array($this->aJobs)) { ?>
            <h4>Liste des jobs</h4>

            <table id="jobs" class="table table-condensed">
                <thead>
                <tr>
                    <th>Priorité</th>
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
                <?php foreach ($this->aJobs as $aJobs) { ?>
                    <?php if (is_array($aJobs) && array_key_exists('jobs', $aJobs) && is_array($aJobs['jobs'])) { ?>
                        <?php foreach ($aJobs['jobs'] as $v) { ?>
                            <tr style="background-color: <?= $aJobs['COLOR']; ?>">
                                <td><span style="color: <?= $aJobs['COLOR']; ?>"><?= $aJobs['SYSPTY']; ?></span><?= $aJobs['SYSNAME']; ?></td>
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
        <?php } ?>
    </div>
</div>

<script type="text/javascript" src="<?= '/js/jobs-datatable.js'; ?>"></script>