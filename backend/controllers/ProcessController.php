<?php

namespace backend\controllers;

use Yii;
use backend\models\business\Process;
use backend\models\business\ProcessSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\GlobalFunctions;
use yii\helpers\Url;
use yii\db\Exception;
use yii\web\Response;

/**
 * ProcessController implements the CRUD actions for Process model.
 */
class ProcessController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                    'multiple_delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Process models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcessSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Process model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Process model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Process(['status'=>Process::STATUS_ACTIVE]);

        if ($model->load(Yii::$app->request->post()))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                if($model->save())
                {
                    $transaction->commit();

                    GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento creado correctamente'));

                    return $this->redirect(['index']);
                }
                else
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error creando el elemento'));
                }
            }
            catch (Exception $e)
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción creando el elemento'));
                $transaction->rollBack();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing Process model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(isset($model) && !empty($model))
        {
            if ($model->load(Yii::$app->request->post()))
            {
                $transaction = \Yii::$app->db->beginTransaction();

                try
                {
                    if($model->save())
                    {
                        $transaction->commit();

                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento actualizado correctamente'));

                        return $this->redirect(['view', 'id'=>$model->id]);
                    }
                    else
                    {
                        GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error actualizando el elemento'));
                    }
                }
                catch (Exception $e)
                {
                    GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción actualizando el elemento'));
                    $transaction->rollBack();
                }
            }
        }
        else
        {
            GlobalFunctions::addFlashMessage('warning',Yii::t('backend','El elemento buscado no existe'));
        }

        return $this->render('update', [
            'model' => $model,
        ]);

    }

    /**
     * Deletes an existing Process model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $transaction = \Yii::$app->db->beginTransaction();

        try
        {
            if($model->delete())
            {
                $transaction->commit();

                GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
            }
            else
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento'));
            }

            return $this->redirect(['index']);
        }
        catch (Exception $e)
        {
            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
            $transaction->rollBack();
        }
    }

    /**
     * Finds the Process model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Process the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Process::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('backend','La página solicitada no existe'));
        }
    }

    /**
    * Bulk Deletes for existing Process models.
    * If deletion is successful, the browser will be redirected to the 'index' page.
    * @return mixed
    */
    public function actionMultiple_delete()
    {
        if(Yii::$app->request->post('row_id'))
        {
            $transaction = \Yii::$app->db->beginTransaction();

            try
            {
                $pk = Yii::$app->request->post('row_id');
                $count_elements = count($pk);

                $deleteOK = true;
                $nameErrorDelete = '';
                $contNameErrorDelete = 0;

                foreach ($pk as $key => $value)
                {
                    $model= $this->findModel($value);

                    if(!$model->delete())
                    {
                        $deleteOK=false;
                        $nameErrorDelete= $nameErrorDelete.'['.$model->name.'] ';
                        $contNameErrorDelete++;
                    }
                }

                if($deleteOK)
                {
                    if($count_elements === 1)
                    {
                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elemento eliminado correctamente'));
                    }
                    else
                    {
                        GlobalFunctions::addFlashMessage('success',Yii::t('backend','Elementos eliminados correctamente'));
                    }

                    $transaction->commit();
                }
                else
                {
                    if($count_elements === 1)
                    {
                        if($contNameErrorDelete===1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento').': <b>'.$nameErrorDelete.'</b>');
                        }
                    }
                    else
                    {
                        if($contNameErrorDelete===1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando el elemento').': <b>'.$nameErrorDelete.'</b>');
                        }
                        elseif($contNameErrorDelete>1)
                        {
                            GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error eliminando los elementos').': <b>'.$nameErrorDelete.'</b>');
                        }
                    }
                }
            }
            catch (Exception $e)
            {
                GlobalFunctions::addFlashMessage('danger',Yii::t('backend','Error, ha ocurrido una excepción eliminando el elemento'));
                $transaction->rollBack();
            }

            return $this->redirect(['index']);
        }
    }

    /**
     * Returns the last order Process for a selected Discipline
     * @param $disciplineId integer Discipline to identify the last order Process
     * @return array
     */
    public function actionGetLastOrder($disciplineId)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->getIsAjax()) {
            $lastOrder = Process::getLastOrderForDiscipline($disciplineId);
            return [
                'status' => 200,
                'order' => $lastOrder + 1
            ];
        }

        return [
            'status' => 404,
            'message' => Yii::t("backend", "Página no encontrada.")
        ];
    }
}
