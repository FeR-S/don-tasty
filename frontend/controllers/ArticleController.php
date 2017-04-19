<?php

namespace frontend\controllers;

use common\models\ArticleComments;
use common\models\ArticleCommentsSearch;
use frontend\models\QuestionForm;
use frontend\models\QuestionFormModerate;
use Yii;
use common\models\Article;
use common\models\ArticleSearch;
use yii\helpers\Html;
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
use common\components\LImageHandler;


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
                            return (User::isLawyer(Yii::$app->user->identity->role) or User::isAdmin(Yii::$app->user->identity->role)) ? true : false;
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
        $dataProvider = $searchModel->moderationSearch(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @param $category_slug
     * @param $article_slug
     * @return string
     */
    public function actionView($category_slug, $article_slug)
    {
        $model = $this->findModelBySlug($category_slug, $article_slug);
//        $article_comments_model = new ArticleCommentsSearch();

//        $article_comments = new ActiveDataProvider([
//            'query' => ArticleComments::find()->where([
//                'status' => ArticleComments::STATUS_PUBLIC
//            ]),
//        ]);

//        if ($article_comments_model->load(Yii::$app->request->post()) and $article_comments_model->safeNewComment($model->id)) {
//            $article_comments_model->validate();
//            var_dump($article_comments_model->getAttributes());die;
//        }

        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        $description = ($model->description) ? $model->description : ($model->announcement ? mb_substr($model->announcement, 0, 200) : mb_substr($model->body, 0, 200));
        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => strip_tags($description),
        ], "blog_view_description");

        return $this->render('view', [
            'model' => $model,
            'categories' => $dataProvider,
//            'article_comments_model' => $article_comments_model,
//            'article_comments' => $article_comments,
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
            $model->category_id = Article::CATEGORY_QUESTION;
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

        if ($model->user_id == Yii::$app->user->identity->getId() or User::isAdmin(Yii::$app->user->identity->role) or $model->status == Article::STATUS_QUESTION) {

            if ($model->load(Yii::$app->request->post())) {

                $model->image = UploadedFile::getInstance($model, 'image');
                $model->user_id = Yii::$app->user->identity->getId();
                if ($model->validate() && $model->save()) {
                    if (!is_null($model->image)) {
                        $model->upload();
                    } else {
                        $fontSize = 38;
                        $colorArray = [48, 48, 48];
                        $pattern = "/[\s,]+/";


                        $model->title = mb_strtoupper(trim('я по нах тут теперь еба'));
                        $words_array = preg_split($pattern, $model->title);
//                        var_dump($model->title);

                        // считаем количество символов строки

                        // есть ширина текста
                        // подгоняем вторую строку что бы была по длине с первой

                        $first_row = '';
                        $first_row_max2 = 600;
                        $first_row_max = 15;
                        $second_row = '';

                        // подгоняем размер шрифта первой строки
                        foreach ($words_array as $word) {
                            if (strlen($first_row) < $first_row_max) {
                                $first_row .= ' ' . $word;
                            } else {
                                $second_row .= ' ' . $word;
                            }
                        }

//                        if (mb_strlen($model->title) > 27) {
//                            $model->title = mb_substr($model->title, 0, 27) . '...';
//                        };

                        $first_row = trim($first_row);
                        $fs_1 = 10;
                        while (($first_row_max2 - 10 - LImageHandler::getTextWidth($fs_1, 0, Article::DEFAULT_IMG_FONT_PATH, $first_row)) >= 0) {
                            $fs_1 = $fs_1 + 1;
                        }

                        // берем высоту первой строки, что бы узнать на сколько опустить вторую строку
                        $first_row_height = LImageHandler::getTextHeight($fs_1, 0, Article::DEFAULT_IMG_FONT_PATH, $first_row);

                        // подгоняем размер шрифта первой строки
                        $second_row = trim($second_row);
                        $fs_2 = 5;
                        while (($first_row_max2 - 10 - LImageHandler::getTextWidth($fs_2, 0, Article::DEFAULT_IMG_FONT_PATH, $second_row)) >= 0) {
                            $fs_2 = $fs_2 + 1;
                        }

                        // берем высоту второй строки
                        $second_row_height = LImageHandler::getTextHeight($fs_2, 0, Article::DEFAULT_IMG_FONT_PATH, $second_row);

                        // создаем пустое изображение, куда напишем текст
                        $template = imageCreateTrueColor($first_row_max2, $first_row_height + $second_row_height + 15);

                        // устанавливаем цвет фона шаблона
                        $template_with_bg = imagecolorallocate($template, 255, 255, 255);
                        imagefill($template, 0, 0, $template_with_bg);

                        // Сохраняем шаблон
                        imagejpeg($template, Yii::getAlias('@frontend/web/uploads/article_images/temp.jpg'));
                        imagedestroy($template);


                        $ih = new LImageHandler();
//                        $imgObj = $ih->load(Article::DEFAULT_IMG_PATH);
                        $imgObj = $ih->load(Yii::getAlias('@frontend/web/uploads/article_images/temp.jpg'));
//                        $imgObj->crop(2000, 3000);
                        $imgObj->text($first_row, Article::DEFAULT_IMG_FONT_PATH, $fs_1, $colorArray, LImageHandler::CORNER_CENTER_TOP, 0, 5);
                        $imgObj->text($second_row, Article::DEFAULT_IMG_FONT_PATH, $fs_2, $colorArray, LImageHandler::CORNER_CENTER_TOP, 0, $first_row_height + 8);
//                        $imgObj->flip(LImageHandler::FLIP_HORIZONTAL);

//                        var_dump($imgObj->show(false, 100));

//                        var_dump($imgObj->textHeight);
//                        $imgObj->resize($imgObj->textWidth / 2 + 30, $imgObj->textHeight / 2 + 30);
//                        var_dump($imgObj->show(false, 100));
//                        die;

                        // сохраняем самую большую часть
                        $imgObj->save(Yii::getAlias('@frontend/web/uploads/article_images/') . $model->id . Article::DEFAULT_IMG_EXT, false, 100);


                        // открываем сохраненный шаблон
                        list($opened_template_width, $opened_template_height) = getimagesize(Yii::getAlias('@frontend/web/uploads/article_images/') . $model->id . Article::DEFAULT_IMG_EXT);
                        $opened_template = imagecreatefromjpeg(Yii::getAlias('@frontend/web/uploads/article_images/') . $model->id . Article::DEFAULT_IMG_EXT);

                        // чуть меньше
                        $imgObj->resize($opened_template_width / 2, $opened_template_height / 2);


                        // теперь этот шаблон надо вертеть и заполонять им большую картинку
                        $full_size_image_width = 1280;
                        $full_size_image_height = 800;

                        // создаем новое изображение
                        $new_empty_full_size_image = imagecreatetruecolor($full_size_image_width, $full_size_image_height);

                        // устанавливаем цвет фона
                        $new_empty_full_size_image_with_bg = imagecolorallocate($new_empty_full_size_image, 255, 255, 255);
                        imagefill($new_empty_full_size_image, 0, 0, $new_empty_full_size_image_with_bg);

                        // определяем позиции и копируем шаблоны, изменяя размеры...

                        // 1
                        $top_1 = ($full_size_image_height - $opened_template_height) / 2;
                        $left_1 = ($full_size_image_width - $opened_template_width) / 2;
                        imagecopy($new_empty_full_size_image, $opened_template, $left_1, $top_1, 0, 0, $opened_template_width, $opened_template_height);

                        // 2
                        $top_2 = $top_1 + $opened_template_height;
                        $left_2 = $left_1;
                        $imgObj->resize($imgObj->textWidth / 2 + 45, $imgObj->textHeight / 2 + 45);
                        imagecopy($new_empty_full_size_image, $imgObj->getImage(), $left_2, $top_2, 0, 0, $opened_template_width, $opened_template_height);


                        // смотрим результат
                        imagejpeg($new_empty_full_size_image, Yii::getAlias('@frontend/web/uploads/article_images/') . $model->id . '-qwe' . Article::DEFAULT_IMG_EXT, 100);
                        $imgObj = $ih->load(Yii::getAlias('@frontend/web/uploads/article_images/') . $model->id . '-qwe' . Article::DEFAULT_IMG_EXT);
                        var_dump($imgObj->show(false, 100));

                        die;
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
            Yii::$app->getSession()->setFlash('danger', 'Вы можете редактировать только свои статьи.');
            $this->redirect(['index']);
        }
    }

    /**
     * @return string
     */
    public
    function actionList()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        $categories = new ActiveDataProvider([
            'query' => Category::find()->joinWith('articles')->where(['articles.status' => Article::STATUS_PUBLIC]),
        ]);

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => Html::encode('Список статей - ответы на вопросы неприятных или непонятных с юридической точки зрения ситуаций, с которыми мы, порой, сталкиваемся в жизни.'),
        ], "blog_category_description");

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
    protected
    function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $category_slug
     * @param $article_slug
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected
    function findModelBySlug($category_slug, $article_slug)
    {
        if (($model = Article::find()->joinWith('category')->where([
                'articles.slug' => $article_slug,
                'categories.slug' => $category_slug,
            ])->one()) !== null
        ) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $category_slug
     * @return string
     */
    public
    function actionCategory($category_slug)
    {
        if ($model = Category::find()->where(['slug' => $category_slug])->one()) {
            $dataProvider = new ActiveDataProvider([
                'query' => Category::find()->joinWith('articles')->where(['articles.status' => Article::STATUS_PUBLIC])
            ]);

            $description = $model->description ? $model->description : $model->title;

            Yii::$app->view->registerMetaTag([
                'name' => 'description',
                'content' => strip_tags($description),
            ], "blog_category_description");

            return $this->render('category_articles', [
                'model' => $model,
                'articles' => self::getCategoryArticles($model->id),
                'categories' => $dataProvider
            ]);
        } else {
            $this->redirect('/articles');
        }
    }

    /**
     * @return bool|object
     */
    public
    function actionRemoveImage()
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
    public
    function actionSearch()
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
    public
    static function getCategoryArticles($category_id)
    {
        $articles = Article::find()->where([
            'category_id' => $category_id,
            'status' => Article::STATUS_PUBLIC
        ]);
        return $dataProvider = new ActiveDataProvider([
            'query' => $articles,
        ]);
    }

    /**
     *
     */
    public
    function actionQuestion()
    {
        $model = new QuestionForm();

        if ($model->load(Yii::$app->request->post()) and $model->saveQuestion()) {
            return "Спасибо.";
        }

        return $this->render('/article/_question-form', [
            'model' => $model
        ]);
    }

    /**
     *
     */
    public
    function actionQuestionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->questionsSearch(Yii::$app->request->queryParams);

        return $this->render('question-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public
    function actionQuestionCreate()
    {
        $model = new QuestionFormModerate();

        if ($model->load(Yii::$app->request->post()) and $model->saveQuestion()) {
            Yii::$app->session->setFlash('success', 'Новая тема успешно добавлена! Добавь еще одну!');
            return $this->redirect('question-index');
        }

        return $this->render('question-create', [
            'model' => $model,
        ]);
    }
}
