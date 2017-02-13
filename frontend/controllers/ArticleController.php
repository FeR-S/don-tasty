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
use yii\web\Response;
use kartik\form\ActiveForm;


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
     * @param $article_slug
     * @return string
     */
    public function actionView($article_slug)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('view', [
            'model' => $this->findModelBySlug($article_slug),
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

            if ($model->validate() && $model->save()) {
                if (!is_null($model->image)) {
                    $model->upload();
                }
                return $this->redirect($model->url);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
                    if (!is_null($model->image)) {
                        $model->upload();
                    }
                    return $this->redirect($model->url);
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
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        $categories = new ActiveDataProvider([
            'query' => Category::find()->joinWith('articles')->where(['articles.status' => Article::STATUS_PUBLIC]),
        ]);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'articles' => $dataProvider,
            'categories' => $categories,
        ]);
    }

    /**
     * @param $id
     * @return static
     * @throws NotFoundHttpException
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
     * @param $slug
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModelBySlug($slug)
    {
        if (($model = Article::find()->where(['slug' => $slug])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $category_slug
     * @return string
     */
    public function actionCategory($category_slug)
    {
        $model = Category::find()->where(['slug' => $category_slug])->one();

        $dataProvider = new ActiveDataProvider([
            'query' => Category::find()->joinWith('articles')->where(['articles.status' => Article::STATUS_PUBLIC])
        ]);

        return $this->render('category_articles', [
            'model' => $model,
            'articles' => self::getCategoryArticles($model->id),
            'categories' => $dataProvider
        ]);
    }

    /**
     * @return bool|object
     */
    public function actionRemoveImage()
    {
        if (Yii::$app->request->isAjax) {
            $article_id = Yii::$app->request->post()['article_id'];
            return Article::removeImageStatic($article_id);
        }

        return false;
    }

    /**
     *
     */
    public function actionSearch()
    {
        $searchModel = new ArticleSearch();
        $searchModel->scenario = ArticleSearch::SCENARIO_PUBLIC_SEARCH;

        if ($searchModel->load(Yii::$app->request->post())) {
            return $this->renderAjax('_article-search-form', [
                'model' => $searchModel,
                'dataProvider' => $searchModel->searchInstant()
            ]);
        }
    }

    /**
     * @param $category_id
     * @return ActiveDataProvider
     */
    public static function getCategoryArticles($category_id)
    {
        $articles = Article::find()->where([
            'category_id' => $category_id,
            'status' => Article::STATUS_PUBLIC
        ]);
        return $dataProvider = new ActiveDataProvider([
            'query' => $articles,
        ]);
    }
}
