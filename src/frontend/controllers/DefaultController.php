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

            // when no password is given, we do not need protection!
            if (empty($this->module->password)) {
                return true;
            }

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

        for ($i = 1; $i <= 12; $i++) {
            $time = strtotime($year . "-" . $i . "-01");
            $first = strtotime('first hour', $time);
            $last = strtotime('first day of next month', $time) - 1;

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
            ->andWhere([
                'or',
                ['between', 'start_date', $from, $to],
                ['between', 'end_date', $from, $to]
            ])
            ->all();

        $days = [];

        $month = date("n", $from); // Numeric representation of a month, without leading zeros (1-12)
        $year = date("Y", $from);  // Four-digit year
        $numberOfDays = date("t", $to); // Number of days in the month of the 'to' timestamp

        for ($i = 1; $i <= $numberOfDays; $i++) {
            // Create a timestamp for the current day
            $dayString = sprintf('%02d-%02d-%04d', $i, $month, $year);
            $dayTimestamp = strtotime($dayString);

            // Calculate the day of the year (1-366)
            $yearDay = (int)date('z', $dayTimestamp) + 1; // 'z' is 0-365, so add 1

            // Initialize the day's data
            $days[$i] = [
                'items' => [],
                'timestamp' => $dayTimestamp,
            ];

            foreach ($dates as $d) {
                // Calculate the day of the year for the item's start and end dates
                $dStartYearDay = (int)date('z', $d->start_date) + 1;
                $dEndYearDay = (int)date('z', $d->end_date) + 1;

                // Check if the current day falls within the item's date range
                if ($dStartYearDay <= $yearDay && $dEndYearDay >= $yearDay) {
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
