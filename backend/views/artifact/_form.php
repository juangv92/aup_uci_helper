<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use dosamigos\ckeditor\CKEditor;
use kartik\number\NumberControl;
use common\models\GlobalFunctions;
use backend\models\business\Process;
use kartik\select2\Select2;
use backend\models\business\Artifact;
use kartik\sortable\Sortable;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\models\business\Artifact */
/* @var $form yii\widgets\ActiveForm */
/* @var $items_selected array of selected Scenarios */
/* @var $items_scenarios array of Scenarios map */
/* @var $items_responsibilities_selected array of selected RoleResponsibilityItem */
/* @var $items_responsibilities array of RoleResponsibilityItem map */

?>

    <div class="box-body">
        <?php
        $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <?php
        $content1 = $this->render('_form_general_data', ['form'=> $form, 'model' => $model]);
        $content2 = $this->render('_form_scenarios', [
            'model' => $model,
            'form'=> $form,
            'items_selected' => $items_selected,
            'items_scenarios' => $items_scenarios,
        ]);
        $content3 = $this->render('_form_role_responsibility_items', [
            'model' => $model,
            'form'=> $form,
            'items_responsibilities_selected' => $items_responsibilities_selected,
            'items_responsibilities' => $items_responsibilities,
        ]);

        $items = [
            [
                'label'=> Yii::t('backend', 'Datos generales'),
                'content'=>$content1,
                'active'=>true
            ],
            [
                'label'=>Yii::t('backend', 'Escenarios'),
                'content'=>$content2,
            ],
            [
                'label'=>Yii::t('backend', 'Responsabilidades de Rol'),
                'content'=>$content3,
            ],
        ];

        echo TabsX::widget([
            'items' => $items,
            'position' => TabsX::POS_ABOVE,
            'encodeLabels' => false
        ]);
        ?>
    </div>


    <div class="box-footer">
        <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-plus"></i> ' . Yii::t('backend', 'Crear') : '<i class="fa fa-pencil"></i> ' . Yii::t('yii', 'Update'), ['class' => 'btn btn-default btn-flat']) ?>
        <?= Html::a('<i class="fa fa-remove"></i> ' . Yii::t('backend', 'Cancelar'), ['index'], ['class' => 'btn btn-default btn-flat margin', 'title' => Yii::t('backend', 'Cancelar')]) ?>
    </div>
<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
  // SCENARIO SORTABLE
  var orderScenario = [];
                                          
  //loop trought each li...
  $("#sortable-scenarios-select li").each( function(e) {
      //add each li position to the array...     
      orderScenario.push( $(this).attr("data-id") );
  });

  // join the array as single variable...
  var positionsScenarios = orderScenario.join(",");
  document.getElementById("artifact-aup_scenarios").value = positionsScenarios;
  
  // RESPONSIBILITY SORTABLE
  var orderResponsibilities = [];
  $("#sortable-responsibilities-select li").each( function(e) {
      //add each li position to the array...     
      orderResponsibilities.push( $(this).attr("data-id") );
  });

  // join the array as single variable...
  var positionsResponsibilities = orderResponsibilities.join(",");
  document.getElementById("artifact-role_responsibilities").value = positionsResponsibilities;
                 
JS;

$this->registerJs($script);
?>