<?php

namespace backend\models\business;

use Yii;
use backend\models\BaseModel;
use yii\helpers\StringHelper;
use common\models\GlobalFunctions;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%artifact_responsibility_item}}".
 *
 * @property int $id
 * @property int $artifact_id
 * @property int $role_responsibility_item_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Artifact $artifact
 * @property RoleResponsibilityItem $roleResponsibilityItem

 */
class ArtifactResponsibilityItem extends BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%artifact_responsibility_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['artifact_id', 'role_responsibility_item_id'], 'required'],
            [['artifact_id', 'role_responsibility_item_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['artifact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Artifact::className(), 'targetAttribute' => ['artifact_id' => 'id']],
            [['role_responsibility_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => RoleResponsibilityItem::className(), 'targetAttribute' => ['role_responsibility_item_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('backend', 'ID'),
            'artifact_id' => Yii::t('backend', 'Artifact ID'),
            'role_responsibility_item_id' => Yii::t('backend', 'Role Responsibility Item ID'),
            'status' => Yii::t('backend', 'Estado'),
            'created_at' => Yii::t('backend', 'Fecha de creación'),
            'updated_at' => Yii::t('backend', 'Fecha de actualiación'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArtifact()
    {
        return $this->hasOne(Artifact::className(), ['id' => 'artifact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleResponsibilityItem()
    {
        return $this->hasOne(RoleResponsibilityItem::className(), ['id' => 'role_responsibility_item_id']);
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
        return "/artifact-responsibility-item";
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

}