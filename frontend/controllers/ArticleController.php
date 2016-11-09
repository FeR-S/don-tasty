<?php

namespace frontend\controllers;

use Yii;
use common\models\Article;
use common\models\ArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\User;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use common\models\Category;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return (User::isLawyer(Yii::$app->user->identity->getId()) or User::isAdmin(Yii::$app->user->identity->getId())) ? true : false;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'categories' => $dataProvider
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->identity->getId();
            $model->status = Article::STATUS_MODERATION;
            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->validate() and $model->save()) {
                if ($model->upload()) {
                    // file is uploaded successfully
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                Yii::$app->getSession()->setFlash('danger', 'Возникла ошибка при сохранении статьи. Пожалуйста, свяжитесь с администратором.');
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->user_id == Yii::$app->user->identity->getId()) {
            if ($model->load(Yii::$app->request->post())) {
                $model->image = UploadedFile::getInstance($model, 'image');
                if ($model->validate() && $model->save()) {
                    if ($model->upload()) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                } else {
                    Yii::$app->getSession()->setFlash('danger', 'Возникла ошибка при изменении статьи. Пожалуйста, свяжитесь с администратором.');
                    return $this->redirect(['index']);
                }
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            $this->redirect(['index']);
        }
    }

    /**
     * @return string
     */
    public function actionList()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $categories = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'articles' => $dataProvider,
            'categories' => $categories,
        ]);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function actionCategory($id)
    {
        $model = Category::findOne($id);

        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('category_articles', [
            'model' => $model,
            'articles' => self::getCategoryArticles($id),
            'categories' => $dataProvider
        ]);
    }

    /**
     * @param $category_id
     * @return ActiveDataProvider
     */
    public static function getCategoryArticles($category_id)
    {
        $articles = Article::find()->where(['category_id' => $category_id]);
        return $dataProvider = new ActiveDataProvider([
            'query' => $articles,
        ]);
    }
}
