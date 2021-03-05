<?php
use luya\cms\helpers\Url;
use luya\helpers\Html;
use yii\widgets\Pjax;

?>
<?php Pjax::begin(); ?>
<div class="container-fluid content pt-5">
    <h1><?= $year; ?></h1>
    <div class="text-center mb-3">
        <?= Html::a($prevYear, ['index', 'year' => $prevYear], ['class' => 'btn btn-arrow-left btn-primary float-left']); ?>
        <a href="<?= Url::toRoute(['/calendarfrontend/default/create']); ?>" class="btn btn-primary">Eintrag erstellen</a>
        <?= Html::a($nextYear, ['index', 'year' => $nextYear], ['class' => 'btn btn-arrow-right btn-primary float-right']); ?>
    </div>
    <div class="row">
        <?php foreach ($months as $month): ?>
            <div class="col-lg-2">
                <a href="<?= Url::toRoute(['/calendarfrontend/default/detail', 'from' => $month['from'], 'to' => $month['to']]); ?>" class="border rounded mb-5 p-2 calendar-month">
                <h2><?= strftime("%B", $month['from']); ?></h2>
                <?php if (empty($month['persons'])): ?>
                    <p class="text-muted">Keine Einträge</p>
                <?php else: ?>
                    <p>
                    <?php foreach ($month['persons'] as $p): ?>
                    <span class="badge rounded badge-pill mr-1" style="background-color:<?= $p['color']; ?>; color:white;"><?= $p['count']; ?>x <?= $p['name']; ?></span>
                    <?php endforeach; ?>
                    </p>
                <?php endif; ?>
                </a>
            </div>
        <?php endforeach; ?>
    </div>  
    <div class="mb-3">
        <input type="text" class="form-control" value="<?= Url::toRoute(['feed'], true); ?>" readonly />
    </div>
</div>
<?php Pjax::end(); ?>