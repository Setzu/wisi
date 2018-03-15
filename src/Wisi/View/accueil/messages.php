<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 26/02/18
 * Time: 10:16
 */
?>
<div class="row">
    <div class="col-md-6">
        <h4>Utilisation des disques</h4>
        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th style="max-width: 16%">Machine</th>
                <th style="width: 28%">CPU</th>
                <th style="width: 28%">ASP 1</th>
                <th style="width: 28%">ASP 2</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($this->aMainInfos) && is_array($this->aMainInfos) && count($this->aMainInfos) > 0) { ?>
                <?php foreach ($this->aMainInfos as $system => $aInfos) { ?>
                    <tr>
                        <td><?= $aInfos['SYSNAME']; ?></td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar progress-bar-<?= \Wisi\Services\System::storageClassByUC($aInfos['UC']); ?> progress-bar-striped"
                                     role="progressbar" aria-valuenow="<?= $aInfos['UC']; ?>" aria-valuemin="0" aria-valuemax="100"
                                     style="color: #000000; width: <?= $aInfos['UC']; ?>%">
                                    <?= $aInfos['UC']; ?>&nbsp;%
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar progress-bar-<?= \Wisi\Services\System::storageClassByUC($aInfos['ASP'][1]); ?> progress-bar-striped"
                                     role="progressbar" aria-valuenow="<?= $aInfos['ASP'][1]; ?>" aria-valuemin="0" aria-valuemax="100"
                                     style="color: #000000; width: <?= $aInfos['ASP'][1]; ?>%">
                                    <?= $aInfos['ASP'][1]; ?>&nbsp;%
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar progress-bar-<?= \Wisi\Services\System::storageClassByUC($aInfos['ASP'][2]); ?> progress-bar-striped"
                                     role="progressbar" aria-valuenow="<?= $aInfos['ASP'][2]; ?>" aria-valuemin="0" aria-valuemax="100"
                                     style="color: #000000; width: <?= $aInfos['ASP'][2]; ?>%">
                                    <?= $aInfos['ASP'][2]; ?>&nbsp;%
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Etat des systèmes -->
    <div class="col-md-6">
        <div id="chartContainer" style="height: 280px; width: 100%;"></div>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">
        <h4>Messages QSYSOPR</h4>

        <table id="messages" class="table table-condensed">
            <thead>
            <tr>
                <th>Système</th>
                <th>ID</th>
                <th>Message</th>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Job</th>
                <th style="display: none;"></th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($this->aMainInfos) && is_array($this->aMainInfos) && count($this->aMainInfos) > 0) { ?>
                <?php foreach ($this->aMainInfos as $system => $aInfos) { ?>
                    <?php if (is_array($aInfos) && array_key_exists('MESSAGES', $aInfos) && is_array($aInfos['MESSAGES']) && count($aInfos['MESSAGES']) > 0) { ?>
                        <?php foreach ($aInfos['MESSAGES'] as $v) { ?>
                            <tr class="pointer" style="background-color: <?= '#' . $aInfos['COLOR']; ?>">
                                <td class="detail"><span style="color: <?= '#' . $aInfos['COLOR']; ?>"><?= $aInfos['SYSPTY']; ?></span><?= $aInfos['SYSNAME']; ?></td>
                                <td class="detail"><?= $v['MSGID']; ?></td>
                                <td class="detail"><?= utf8_encode($v['MSGTEXT']); ?></td>
                                <td class="detail"><?= \Wisi\Services\Utils::formatDateToEU($v['MESSAGESTP']); ?></td>
                                <td class="detail"><?= $v['FROMUSER']; ?></td>
                                <td class="detail"><?= $v['FROMJOB']; ?></td>
                                <td style="display: none;">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Type message</th>
                                            <th>Sous-système</th>
                                            <th>Message complémentaire</th>
                                            <th>Statut</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><?= $v['MSGTYPE']; ?></td>
                                            <td><?= $v['SUBSYSTEM']; ?></td>
                                            <td><?= utf8_encode(trim($v['MSGTEXT1'])); ?></td>
                                            <td><?= $v['JOBSTATUS']; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript" src="<?= '/wisi/js/messages-datatable.js'; ?>"></script>
<script type="text/javascript" src="<?= '/wisi/js/load-status.js'; ?>"></script>
