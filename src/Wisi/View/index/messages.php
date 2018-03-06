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
            <?php if (isset($this->aUCList) && is_array($this->aUCList)) { ?>
                <tbody>
                <?php foreach($this->aUCList as $system => $aUCValues) { ?>
                    <?php if (is_array($aUCValues)) { ?>
                            <tr>
                                <td><?= $aUCValues['SYSNAME']; ?></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="<?= $aUCValues[0]['PCPROCUNTU']; ?>" aria-valuemin="0" aria-valuemax="100" style="color: #000000; width: <?= $aUCValues[0]['PCPROCUNTU']; ?>%">
                                            <?= $aUCValues[0]['PCPROCUNTU']; ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?= $aUCValues['sASPUtilisation']; ?>" aria-valuemin="0" aria-valuemax="100" style="color: #000000; width: <?= $aUCValues['sASPUtilisation']; ?>%">
                                            <?= $aUCValues['sASPUtilisation']; ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?= $aUCValues['sASPUtilisation']; ?>" aria-valuemin="0" aria-valuemax="100" style="color: #000000; width: <?= $aUCValues['sASPUtilisation']; ?>%">
                                            <?= $aUCValues['sASPUtilisation']; ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    <?php } ?>
                <?php } ?>
                </tbody>
            <?php } ?>
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
        <?php if (isset($this->aMessagesList)) { ?>
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
                </tr>
                </thead>
                <tbody>
                <?php if (is_array($this->aMessagesList)) { ?>
                    <?php foreach ($this->aMessagesList as $machine => $aMessages) { ?>
                        <?php if (is_array($aMessages) && array_key_exists('messages', $aMessages) && is_array($aMessages['messages'])) { ?>
                            <?php foreach ($aMessages['messages'] as $v) { ?>
                                <tr class="pointer" style="background-color: <?= '#' . $aMessages['COLOR']; ?>">
                                    <td class="detail">
                                        <span style="color: <?= '#' . $aMessages['COLOR']; ?>;">
                                            <?= $aMessages['SYSPTY']; ?>
                                        </span>
                                        <?= $aMessages['SYSNAME']; ?>
                                    </td>
                                    <td class="detail"><?= $v['MSGID']; ?></td>
                                    <td class="detail"><?= $v['MSGTEXT']; ?></td>
                                    <td class="detail"><?= $v['MESSAGESTP']; ?></td>
                                    <td class="detail"><?= $v['FROMUSER']; ?></td>
                                    <td class="detail"><?= $v['FROMJOB']; ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

<script type="text/javascript" src="<?= '/js/lib/bootstrap.min.js'; ?>"></script>
<script type="text/javascript" src="<?= '/js/messages-datatable.js'; ?>"></script>
<script type="text/javascript" src="<?= '/js/load-status.js'; ?>"></script>