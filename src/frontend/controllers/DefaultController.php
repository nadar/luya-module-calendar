<?php

namespace nadar\calendar\frontend\controllers;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Yii;
use luya\web\Controller;
use nadar\calendar\models\Item;
use nadar\calendar\models\LoginModelForm;

class DefaultController extends Controller
{
    const IS_AUTHED = 'isAuthed';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if ($action->id == 'login' || $action->id == 'feed') {
                return true;
            }

            if (Yii::$app->session->get(self::IS_AUTHED)) {
                return true;
            }

            return $this->redirect(['login']);
        }

        return false;
    }

    public function actionFeed()
    {
        $cal = new Calendar(Yii::$app->siteTitle);
        $cal->setName('Kalender');

        $tz = new \DateTimeZone('Europe/Zurich');

        $dates = Item::find()
            ->with(['person'])
            ->where([
                'and',
                ['<=', 'end_date', time() + (60 * 60 * 24 * 120)],
            ])
            ->all();

        foreach ($dates as $d) {
            $vEvent = new Event();
            $vEvent->setUseTimezone($tz);
            $vEvent->setDtStart((new \DateTime())->setTimestamp($d->start_date));
            $vEvent->setDtEnd((new \DateTime())->setTimestamp($d->end_date));
            $vEvent->setSummary($d->person->name . ' ' . $d->title);
            $vEvent->setDescription($d->comment);
            if ($this->module->calendarLocation) {
                $vEvent->setLocation($this->module->calendarLocation);
            }

            $cal->addComponent($vEvent);
        }

        return Yii::$app->response->sendContentAsFile($cal->render(), 'calendar-feed.ics', ['mimeType' => 'text/calendar']);
    }

    public function actionLogin()
    {
        $model = new LoginModelForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->password == $this->module->password) {
                Yii::$app->session->set(self::IS_AUTHED, true);
                return $this->redirect(['index']);
            } else {
                $model->addError('password', 'Das Passwort ist falsch.');
            }
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionIndex($year = null)
    {
        $year = empty($year) ? date("Y") : $year;

        $firstDay = strtotime("first day of January {$year}");
        $lastDay = strtotime("last day of December {$year}");

        $dates = Item::find()
        ->with(['person'])
        ->andWHere([
            'and',
            ['>=', 'start_date', $firstDay],
            ['<=', 'end_date', $lastDay],
        ])
        ->all();

        $months = [];

        for ($i = 1 ; $i <= 12; $i++) {
            $time = strtotime($year."-".$i."-01");
            $first = strtotime('first hour', $time);
            $last = strtotime('last day of this month', $time);

            $items = [];
            $persons = [];
            foreach ($dates as $key => $date) {
                if (date("n", $date->start_date) == $i || date("n", $date->end_date) == $i) {
                    $items[] = $date;
                    if (isset($persons[$date->person_id])) {
                        $persons[$date->person_id]['count']++;
                    } else {
                        $persons[$date->person_id] = [
                            'name' => $date->person->name,
                            'color' => $date->person->color,
                            'count' => 1,
                        ];
                    }
                }
            }

            $months[] = [
                'from' => $first,
                'to' => $last,
                'items' => $items,
                'count' => count($items),
                'persons' => $persons,
            ];
        }

        return $this->render('index', [
            'months' => $months,
            'year' => $year,
            'prevYear' => $year - 1,
            'nextYear' => $year + 1,
        ]);
    }

    public function actionDetail($from, $to)
    {
        $dates = Item::find()
            ->with(['person'])
            ->andWHere([
                'or',  
                ['between', 'start_date', $from, $to],
                ['between', 'end_date', $from, $to]
            ])
            ->all();

        $i = 0;
        $days = [];
        for ($i = 1; $i <= date("t", $to); $i++) {
            $dayTimestamp = strtotime($i . '-'.date("n", $from).'-'.date("Y", $from));

            $yearDay = strftime('%j', $dayTimestamp);
            $endOfDay = strtotime('midnight', $dayTimestamp);
            $days[$i] = [
                'items' => [],
                'timestamp' => $dayTimestamp,
            ];
            foreach ($dates as $d) {
                if ((int) strftime("%j", $d->start_date) <= $yearDay && (int) strftime("%j", $d->end_date)  >= $yearDay) {
                    $days[$i]['items'][$d->id] = $d;
                }
            }
        }

        return $this->render('detail', [
            'from' => $from,
            'to' => $to,
            'days' => $days,
        ]);
    }

    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}