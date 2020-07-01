<?php

/* @var $this yii\web\View */
/* @var $model backend\models\business\Discipline */

$this->title = Yii::t('backend', 'Crear').' '. Yii::t('backend', 'Disciplina');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Disciplinas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="discipline-create">

    <?= $this->render('_form', [
    'model' => $model,
    ]) ?>

</div>
