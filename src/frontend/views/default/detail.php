<?php
use luya\cms\helpers\Url;
$this->title = strftime("%B, %Y", $from);
?>
<div class="container content pt-5">
    <h1><?= strftime("%B, %Y", $from); ?></h1>
    <div class="text-center">
        <a href="<?= Url::toRoute(['/calendarfrontend/default/index', 'year' => date("Y", $from)]); ?>" class="btn btn-primary btn-arrow-left">Zur Übersicht</a>
    </div>
    <table class="table table-bordered table-hover mt-3">
    <?php foreach ($days as $d => $items): ?>
    <tr <?php if (strftime('%u', $items['timestamp']) == 7): ?>class="table-secondary"<?php endif; ?>>
        <td class="w-25"><?= strftime('%e. (%a)', $items['timestamp']); ?></td>
        <td>
            <?php foreach ($items['items'] as $e): ?>
            <span class="badge rounded badge-pill mr-1" style="background-color:<?= $e->person->color; ?>; color:white;">
                <?= $e->person->name; ?><?php if ($e->title): ?>: <?= $e->title; ?><?php endif; ?>
            </span>
            <?php endforeach; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    </table>
    <div class="text-center mb-3">
        <a href="<?= Url::toRoute(['/calendarfrontend/default/index']); ?>" class="btn btn-primary btn-arrow-left">Zur Übersicht</a>
    </div>
</div>