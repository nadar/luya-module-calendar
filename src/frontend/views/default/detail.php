<?php
use luya\cms\helpers\Url;
use Yii;

$this->title = Yii::$app->formatter->asDate($from, 'php:F, Y');
?>
<div class="container content pt-5">
    <h1><?= Yii::$app->formatter->asDate($from, 'php:F, Y'); ?></h1>
    
    <div class="text-center">
        <a href="<?= Url::toRoute([
            '/calendarfrontend/default/index', 
            'year' => Yii::$app->formatter->asDate($from, 'php:Y')
        ]); ?>" 
        class="btn btn-primary btn-arrow-left">Zur Übersicht</a>
    </div>
    
    <table class="table table-bordered table-hover mt-3">
    <?php foreach ($days as $d => $items): ?>
        <tr <?php if (Yii::$app->formatter->asDate($items['timestamp'], 'php:N') == 7): ?>class="table-secondary"<?php endif; ?>>
            <td class="w-25"><?= Yii::$app->formatter->asDate($items['timestamp'], 'php:j. (D)'); ?></td>
            <td>
                <?php foreach ($items['items'] as $e): ?>
                <span class="badge rounded badge-pill mr-1" style="background-color:<?= htmlspecialchars($e->person->color, ENT_QUOTES, 'UTF-8'); ?>; color:white;">
                    <?= htmlspecialchars($e->person->name, ENT_QUOTES, 'UTF-8'); ?>
                    <?php if ($e->title): ?>: <?= htmlspecialchars($e->title, ENT_QUOTES, 'UTF-8'); ?><?php endif; ?>
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
