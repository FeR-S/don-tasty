<?php

namespace frontend\controllers;

use common\models\Article;
use common\models\Category;
use yii\web\Controller;

class SitemapController extends Controller
{

    const ALWAYS = 'always';
    const HOURLY = 'hourly';
    const DAILY = 'daily';
    const WEEKLY = 'weekly';
    const MONTHLY = 'monthly';
    const YEARLY = 'yearly';
    const NEVER = 'never';

    public function actionIndex()
    {
        if (!$xml_sitemap = \Yii::$app->cache->get('sitemap')) {

            $urls = [];

            // categories
            $categories = Category::find()
                ->joinWith('articles')
                ->where([
                    'articles.status' => Article::STATUS_PUBLIC
                ])
                ->andFilterWhere([
                    '!=', 'categories.id', Article::CATEGORY_QUESTION
                ])->groupBy('categories.id')->all();
            foreach ($categories as $category) {
                $urls[] = [
                    'url' => \Yii::$app->urlManager->createUrl([$category->url]),
                    'change_freq' => self::DAILY
                ];
            }

            // articles
            $articles = Article::find()
                ->where([
                    'status' => Article::STATUS_PUBLIC
                ])
                ->andFilterWhere([
                    '!=', 'category_id', Article::CATEGORY_QUESTION
                ])
                ->all();
            foreach ($articles as $article) {
                $urls[] = [
                    'url' => \Yii::$app->urlManager->createUrl([$article->url]),
                    'change_freq' => self::WEEKLY
                ];
            }

            $xml_sitemap = $this->renderPartial('index', [ // записываем view на переменную для последующего кэширования
                'host' => \Yii::$app->request->hostInfo,         // текущий домен сайта
                'urls' => $urls,                                // с генерированные ссылки для sitemap
            ]);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = \Yii::$app->response->headers;
        $headers->add('Content-Type', 'text/xml');
        \Yii::$app->cache->set('sitemap', $xml_sitemap, 3600 * 12);
        return $xml_sitemap;

    }

}
