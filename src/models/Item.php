<?php

namespace nadar\calendar\models;

use Yii;
use luya\admin\ngrest\base\NgRestModel;
use yii\behaviors\TimestampBehavior;
use luya\admin\ngrest\plugins\SelectRelationActiveQuery;

/**
 * Item.
 * 
 * File has been created with `crud/create` command. 
 *
 * @property integer $id
 * @property integer $person_id
 * @property integer $start_date
 * @property integer $end_date
 * @property string $title
 * @property text $comment
 * @property string $email
 * @property string $phone
 * @property tinyint $is_fix
 * @property integer $created_at
 * @property integer $updated_at
 */
class Item extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%calendar_item}}';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-calendar-item';
    }

    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::class],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'person_id' => Yii::t('app', 'Person'),
            'start_date' => Yii::t('app', 'Start-Datum'),
            'end_date' => Yii::t('app', 'End-Datum'),
            'title' => Yii::t('app', 'Zusätzlicher Name'),
            'comment' => Yii::t('app', 'Kommentar'),
            'email' => Yii::t('app', 'E-Mail'),
            'phone' => Yii::t('app', 'Telefon'),
            'is_fix' => Yii::t('app', 'Definitiv'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function attributeHints()
    {
        return [
            'person_id' => 'Wenn es kein Familien Mitglied ist, wähle <b>Gäste</b> und trage den Name unter <b>Zusätzlicher Name</b> ein.',
            'title' => 'Zusätzliche Information zur Person oder Gast der Person.',
            'is_fix' => 'Indikator ob dieser Termin als Fix oder Temporär hinterlegt werden soll.',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['start_date', 'date', 'format' => 'php:Y-m-d', 'timestampAttribute' => 'start_date'],
            ['end_date', 'date', 'format' => 'php:Y-m-d', 'timestampAttribute' => 'end_date'],
            [['person_id', 'start_date', 'end_date'], 'required'],
            [['person_id', 'is_fix', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['title', 'email', 'phone'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'person_id' => ['class' => SelectRelationActiveQuery::class, 'query' => $this->getPerson(), 'labelField' => 'name', 'relation' => 'person'],
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'title' => 'text',
            'comment' => 'textarea',
            'email' => 'text',
            'phone' => 'text',
            'is_fix' => 'toggleStatus',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['person_id', 'start_date', 'end_date', 'title', 'comment', 'is_fix']],
            [['create', 'update'], ['person_id', 'start_date', 'end_date', 'title', 'comment', 'email', 'phone', 'is_fix', 'created_at', 'updated_at']],
            ['delete', true],
        ];
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class, ['id' => 'person_id']);
    }
}