<?php

use backend\models\settings\Setting;
use common\models\GlobalFunctions;
use common\models\User;

/* @var $this yii\web\View */
/* @var $tree array with all information */

$this->title = Setting::getName();

$css = <<<CSS
.just-padding {
    padding: 15px;
}

.list-group.list-group-root {
    padding: 0;
    overflow: hidden;
}

.list-group.list-group-root .list-group {
    margin-bottom: 0;
}

.list-group.list-group-root .list-group-item {
    border-radius: 0;
    border-width: 1px 0 0 0;
}

.list-group.list-group-root > .list-group-item:first-child {
    border-top-width: 0;
}

.list-group.list-group-root > .list-group > .list-group-item {
    padding-left: 30px;
}

.list-group.list-group-root > .list-group > .list-group > .list-group-item {
    padding-left: 45px;
}

.list-group-item .glyphicon {
    margin-right: 5px;
}
CSS;

$this->registerCss($css);
?>

    <div class="site-index">
        <div class="row">
            <div class="col-md-4 col-xl-4 col-lg-4 col-sm-12 col-xs-12">
                <div class="treeview-animated" style="overflow: hidden scroll;">
                    <h5 class=""><?= Yii::t("backend", "Documentación") ?>
                        (<?= \yii\helpers\Html::a(Yii::t("backend", "Predecir Escenario"), ['/site/predictor']) ?>)</h5>
                    <hr>
                    <ul class="treeview-animated-list">
                        <li class="treeview-animated-items">
                            <a class="closed">
                                <i class="fa fa-angle-right" style="font-size: 1.5rem;"></i>
                                <span><i class="fa fa-folder-open"></i><b><?= Yii::t("backend", "Escenarios") ?></b></span>
                            </a>
                            <ul class="nested">
                                <?php foreach ($tree as $treeItem) { ?>
                                    <li class="treeview-animated-items">
                                        <a class="closed"><i class="fa fa-angle-right" style="font-size: 1.5rem;"></i>
                                            <span><i class="fa fa-folder-open"></i><?= $treeItem['scenario']['name']; ?></span></a>
                                        <ul class="nested">
                                            <?php if (isset($treeItem['scenario']['disciplines']) && !empty($treeItem['scenario']['disciplines'])) { ?>
                                                <?php foreach ($treeItem['scenario']['disciplines'] as $discipline): ?>
                                                    <?php if (isset($discipline['processes']) && !empty($discipline['processes'])) {
                                                        $processes = $discipline['processes'];
                                                        if (count($processes) > 0) { ?>
                                                            <li class="treeview-animated-items">
                                                                <a class="closed"><i class="fa fa-angle-right"
                                                                                     style="font-size: 1.5rem;"></i>
                                                                    <span><i class="fa fa-folder-open"></i><?= $discipline['name']; ?></span>
                                                                </a>
                                                                <ul class="nested">
                                                                    <?php foreach ($processes as $process): ?>
                                                                        <?php if (isset($process['artifacts']) && !empty($process['artifacts'])) {
                                                                            $artifacts = $process['artifacts'];
                                                                            if (count($artifacts) > 0) { ?>
                                                                                <li class="treeview-animated-items">
                                                                                    <a class="closed"><i
                                                                                                class="fa fa-angle-right"
                                                                                                style="font-size: 1.5rem;"></i>
                                                                                        <span><i class="fa fa-folder-open"></i><?= $process['name']; ?></span></a>
                                                                                    <ul class="nested">
                                                                                        <?php foreach ($artifacts as $artifact): ?>
                                                                                            <li>
                                                                                                <div class="treeview-animated-element artifact-link" data-link="<?= $artifact['api_url']?>">
                                                                                                    <i class="fa fa-file-o"></i><?= $artifact['name']; ?>
                                                                                            </li>
                                                                                        <?php endforeach; ?>

                                                                                    </ul>
                                                                                </li>
                                                                            <?php } ?>

                                                                        <?php } ?>
                                                                    <?php endforeach; ?>

                                                                </ul>
                                                            </li>
                                                        <?php }
                                                    } ?>

                                                <?php endforeach; ?>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            <div id="artifact_container" class="col-md-8 col-xl-8 col-lg-8 col-sm-12 col-xs-12">

            </div>
        </div>
    </div>


<?php

$js = <<<JS
    $('.treeview-animated').mdbTreeview();
    
    function attachArtifactListeners(){
        $('.artifact-link').on('click', function(e) {
            e.preventDefault();
            let apiUrl = $(this).data('link') || false;
            if(apiUrl){
                $.ajax({
                    type: 'GET',
                    url: apiUrl
                }).done(function(data){
                    $('#artifact_container').html(data);
                    
                    $('.list-group-item').on('click', function() {
                        $('.glyphicon', this)
                          .toggleClass('glyphicon-chevron-right')
                          .toggleClass('glyphicon-chevron-down');
                    });
                });
            }
            
        });    
    }
    
    attachArtifactListeners();
    
JS;

$this->registerJs($js);
?>