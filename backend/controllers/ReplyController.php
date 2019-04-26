<?php

namespace backend\controllers;

use common\models\Common;
use common\models\Reply;
use Yii;
use yii\helpers\Json;
use common\models\Question;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use common\models\Answer;

/**
 * PostController implements the CRUD actions for AdminPost model.
 */
class ReplyController extends Controller
{
    /**
     * @inheritdoc
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','view'],
                        'allow' => true ,
                        'roles' => ['replyList'],
                    ],
                    [
                        'actions' => ['create','update','delete','deletes','status'],
                        'allow' => true,
                        'roles' => ['replyCud']
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST','GET'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Reply::find();
        $querys = Yii::$app->request->get('query');
        if (is_array($querys)) {
            if (count($querys) > 0) {
                if ($querys['posts_id']) {
                    $query = $query->andWhere(['{{%reply}}.posts_id' => $querys['posts_id']]);
                }
                if ($querys['user_id']) {
                    $query = $query->andWhere(['{{%reply}}.user_id' => $querys['user_id']]);
                }
                if ($querys['content']) {
                    $query = $query->andWhere(['like', '{{%reply}}.content', $querys['content']]);
                }
            }
        }
        $pagination = new Pagination([
                'totalCount' => $query->count(),
                'pageSize' => '10',
                'pageParam' => 'page',
                'pageSizeParam' => 'per-page']
        );
        $products = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('created_at desc')
            ->all();

        return $this->render('index', [
            'model' => $products,
            'pages' => $pagination,
            'query' => $querys,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);

    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->answer = Common::filter($model->answer);
            if($model->save()){
                return $this->redirect(['index']);
            }else{
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * ajax 单次删除
     * @param $id
     * @return array
     */
    public function actionDelete($id)
    {
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        Yii::$app->response->format = 'json';
        if (Reply::deleteAll(['in', 'reply_id', Json::decode($id)])) {
            $response['status'] = 1;
            $response['message'] = '操作成功';
        }

        return $response;
    }

    /**
     * ajax 批量删除
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletes()
    {
        $response = ['status' => 0, 'message' => '操作失败, 请重试'];
        Yii::$app->response->format = 'json';
        if (Reply::deleteAll(['in', 'reply_id', Json::decode(Yii::$app->request->post('ids'))])) {
            $response['status'] = 1;
            $response['message'] = '操作成功';
        } else {
            $response['message'] = '操作失败';
        }
        return $response;
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Reply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
