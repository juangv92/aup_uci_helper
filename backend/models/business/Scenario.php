<?php

namespace backend\models\business;

use backend\models\knn\IaCase;
use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%scenario}}".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $views
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property IaCase[] $iaCases
 * @property ScenarioArtifact[] $scenarioArtifacts

 */
class Scenario extends BaseModel
{
    public $artifacts;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%scenario}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['description'], 'string'],
            [['status', 'views'], 'integer'],
            [['created_at', 'updated_at', 'artifacts'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name', 'description', 'status'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'name' => Yii::t('backend', 'Nombre'),
            'description' => Yii::t('backend', 'Descripción'),
            'views' => Yii::t('backend', 'Visitas'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
            'artifacts' => Yii::t('backend', 'Artefactos'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIaCases()
    {
        return $this->hasMany(IaCase::className(), ['scenario_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScenarioArtifacts()
    {
        return $this->hasMany(ScenarioArtifact::className(), ['scenario_id' => 'id']);
    }

    /** :::::::::::: START > Abstract Methods and Overrides ::::::::::::*/

    /**
    * @return string The base name for current model, it must be implemented on each child
    */
    public function getBaseName()
    {
        return StringHelper::basename(get_class($this));
    }

    /**
    * @return string base route to model links, default to '/'
    */
    public function getBaseLink()
    {
        return "/scenario";
    }

    /**
    * Returns a link that represents current object model
    * @return string
    *
    */
    public function getIDLinkForThisModel()
    {
        $id = $this->getRepresentativeAttrID();
        if (isset($this->$id)) {
            $name = $this->getRepresentativeAttrName();
            return Html::a($this->$name, [$this->getBaseLink() . "/view", 'id' => $this->getId()]);
        } else {
            return GlobalFunctions::getNoValueSpan();
        }
    }

    /** :::::::::::: END > Abstract Methods and Overrides ::::::::::::*/

    public function getDescription()
    {
        if(isset($this->description) && !empty($this->description)){
            return $this->description;
        }
        return GlobalFunctions::getNoValueSpan();
    }

    /**
     * Return a concatenated span labels with related Artifact links
     * @return string
     */
    public function getArtifactsLink()
    {
        $artifactsLink = [];
        foreach (ScenarioArtifact::getRelationsMapForScenario($this->id) as $id=>$name){
            $link = Html::a("<span class='label label-default'>{$name}</span>", ['/artifact/view', 'id'=>$id]);
            array_push($artifactsLink, $link);
        }

        if(empty($artifactsLink)){
            return GlobalFunctions::getNoValueSpan();
        }

        return implode(" ", $artifactsLink);
    }


    public function getModelAsJson($includeArtifacts = false)
    {
        $json = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'views' => GlobalFunctions::getFormattedViewsCount($this->views),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        if($includeArtifacts){
            $artifacts = [];
            foreach ($this->scenarioArtifacts as $relation){
                array_push($artifacts, $relation->artifact->getModelAsJson());
            }
            $json['artifacts'] = $artifacts;
        }
        return $json;
    }
}
