<?php
/**
 * Created by PhpStorm.
 * User: david b. <dav.bla28@gmail.com>
 * Date: 21/03/18
 * Time: 14:52
 */
?>

<div class="row">
    <h4 style="text-align: center;">Informations sur les systèmes</h4>
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <?php if (isset($this->aInfos) && is_array($this->aInfos)) { ?>
            <table class="table">
                <thead>
                <tr>
                    <th>Système</th>
                    <th>ASP</th>
                    <th>Espace libre</th>
                    <th>Espace utilisé</th>
                    <th>Espace total</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->aInfos as $system => $aInfos) { ?>
                    <?php if (is_array($aInfos) && array_key_exists('ASP', $aInfos) && is_array($aInfos['ASP'])) { ?>
                        <?php foreach ($aInfos['ASP'] as $v) { ?>
                            <tr style="background-color: <?= $aInfos['COLOR']; ?>">
                                <td><span style="color: <?= $aInfos['COLOR']; ?>"><?= $aInfos['SYSPTY']; ?></span><?= $aInfos['SYSNAME']; ?></td>
                                <td><?= $v['ASPNUMBER']; ?></td>
                                <td><?= $v['DSKSTGAVA'] . ' Go'; ?></td>
                                <td><?= $v['USED'] . ' Go'; ?></td>
                                <td><?= $v['DSKCAPTY'] . ' Go'; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>