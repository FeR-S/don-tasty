<?php

namespace console\controllers;

use common\models\Category;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use yii\base\Model;
use yii\helpers\Url;


class ReceiptsController extends Controller
{

    const RECEIPTS_SOURCE = 'http://kedem.ru';

    /**
     *
     */
    public function actionIndex()
    {
        echo 'yii receipts/get-receipts' . PHP_EOL;
    }

    /**
     *
     */
    public function actionGetReceipts()
    {
        \Yii::$classMap['Parse'] = 'libraries/Parse.php';
        \Yii::$classMap['Curl'] = 'libraries/Curl.php';
        $parser = new \Parse();

        // определяем категорию
        $parent_category_1_url = 'http://kedem.ru/recipe/salads/';

        $data_1 = $parser->get($parent_category_1_url, [
            'title' => [
                'tag' => '#hypercontext > h1.h1',
                'attr' => 'innertext'
            ],
            'categories' => [
                'tag' => '#hypercontext > ul.pgsubmenulit > li > a.pgsubmenulink',
                'attr' => 'href'
            ],
        ]);

        $parent_category_title = trim($data_1['title'][0]);
        // Пишим категорию в базу, берем id
        $parent_category_id = $this->categoryCheck($parent_category_title);


        // ------------------------------


        // идем по суб категориям
        $sub_categories_url = $data_1['categories'];

        // Идем по каждой категории, собираем название и ссылки на рецепты
        foreach ($sub_categories_url as $sub_category_link) {

            $sub_category_url = self::RECEIPTS_SOURCE . $sub_category_link;
            $data_2 = $parser->get($sub_category_url, [
                'title' => [
                    'tag' => '#hypercontext > h1.h1',
                    'attr' => 'innertext'
                ],
                'items_url' => [
                    'tag' => '#hypercontext > .pgrdiv > a.pgrblock',
                    'attr' => 'href'
                ],
            ]);

            $sub_category_title = trim($data_2['title'][0]);

            // Пишем категорию в БД, берем id
            $sub_category_id = $this->categoryCheck($sub_category_title, $parent_category_id);


            // ------------------------------------------------------------------------------


            $items_url = $data_2['items_url'];
            // Идем по каждой категории, собираем рецепты
            foreach ($items_url as $item) {
                $item_url = self::RECEIPTS_SOURCE . $item;
                $data_3 = $parser->get($item_url, [
                    'title' => [
                        'tag' => '#hypercontext > h1.h1',
                        'attr' => 'innertext'
                    ],
//                    'items_url' => [
//                        'tag' => '#hypercontext > .pgrdiv > a.pgrblock',
//                        'attr' => 'href'
//                    ],
//        'items_thumbs' => [
//            'tag' => '#hypercontext > .pgrdiv > a.pgrblock > img.pgrblockimg',
//            'attr' => 'src'
//        ],
                ]);

            }

        }
    }

    /**
     * @param $category_title
     * @param null $parent_category_id
     * @return int|mixed
     */
    private function categoryCheck($category_title, $parent_category_id = null)
    {
        // ищем такую категорию
        $query = Category::find()->where([
            'LOWER(title)' => mb_strtolower($category_title)
        ])->one();

        // если есть -> возвращаем ее id
        if ($query) {
            return $query->id;
        }

        // если нет -> записываем и возвращаем ее id
        $category = new Category();
        $category->title = $category_title;
        $category->description = $category_title;
        if ($parent_category_id and is_int($parent_category_id)) {
            $category->parent_category_id = $parent_category_id;
        }
        if ($this->log($category->save(), 'New category has been added successfully!', 'New category has not been added!')) {
            return $category->id;
        };
    }

    /**
     *
     */
    public function actionTest()
    {
        $this->removeOldNews();
    }

    /**
     * @param $success
     * @param bool $telegram_message
     * @param null $success_log_msg
     * @param null $error_log_msg
     * @return bool
     */
    private function log($success, $success_log_msg = null, $error_log_msg = null)
    {
        if ($success) {
            $this->stdout('Success: ' . $success_log_msg, Console::FG_GREEN, Console::BOLD);
            echo PHP_EOL;
            return true;
        }

        if ($error_log_msg === null) {
            $error_log_msg = 'Unknown error: ' . $this->id . ' / ' . $this->action->id;
        }

        $this->stderr('Error: ' . $error_log_msg, Console::FG_RED, Console::BOLD);
        echo PHP_EOL;

        return false;
    }
}


