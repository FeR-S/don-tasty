<?php

namespace console\controllers;

use common\models\Category;
use common\models\Recipe;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use yii\base\Model;
use yii\helpers\Url;


class RecipesController extends Controller
{

    const RECEIPTS_SOURCE = 'http://kedem.ru';

    /**
     *
     */
    public function actionIndex()
    {
        echo 'yii recipes/get-recipes' . PHP_EOL;
    }

    /**
     *
     */
    public function actionGetRecipes()
    {
        \Yii::$classMap['Parse'] = 'libraries/Parse.php';
        \Yii::$classMap['Curl'] = 'libraries/Curl.php';
        $parser = new \Parse();

        // определяем категорию
        $parent_category_1_url = 'http://kedem.ru/recipe/salads/';

        echo 'Main category...' . PHP_EOL;

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
        echo 'Save new Category, return id' . PHP_EOL;
        $parent_category_id = $this->categoryCheck($parent_category_title);


        // ------------------------------


        // идем по суб категориям
        $sub_categories_url = $data_1['categories'];
        echo 'For each sub category...' . PHP_EOL;

        // Идем по каждой категории, собираем название и ссылки на рецепты
        foreach ($sub_categories_url as $sub_category_link) {
            $page = 1;

            $sub_category_url = self::RECEIPTS_SOURCE . $sub_category_link . $page . '/';
            echo 'For each page of sub category...' . PHP_EOL;

            // идем по каждой странице, каждой подкатегории
            while ($data_2 = $this->parseSubCategoriesByPages($parser, self::RECEIPTS_SOURCE . $sub_category_link . $page . '/')) {

                $sub_category_title = trim($data_2['title'][0]);

                // Пишем категорию в БД, берем id
                echo 'Save sub category, return id' . PHP_EOL;
                $sub_category_id = $this->categoryCheck($sub_category_title, $parent_category_id);


                // ------------------------------------------------------------------------------

                // список ссылок на рецепты в рамках этой страницы, данной субкатегории
                $items_url = $data_2['items_url'];
                echo 'For each recipe...' . PHP_EOL;

                // Идем по рецептам
                foreach ($items_url as $item) {
                    $item_url = self::RECEIPTS_SOURCE . $item;
                    $data_3 = $parser->get($item_url, [
                        'title' => [
                            'tag' => '#hypercontext > h1.h1',
                            'attr' => 'innertext'
                        ],
                        'cook_time' => [
                            'tag' => '#hypercontext span[itemprop="cookTime"]',
                            'attr' => 'plaintext'
                        ],
                        'big_image' => [
                            'tag' => '#hypercontext img#rbimg',
                            'attr' => 'src'
                        ],
                        'ingredients' => [
                            'tag' => '#hypercontext .ringredients div[itemprop="ingredients"] > span',
                            'attr' => 'innertext'
                        ],
                        'ingredients_count' => [
                            'tag' => '#hypercontext .ringredients div[itemprop="ingredients"] > span > span[style="float:right;min-width:50px;"]',
                            'attr' => 'innertext',
                        ],
                        'recipe_instructions' => [
                            'tag' => '#hypercontext div[itemprop="recipeInstructions"] > p',
                            'attr' => 'innertext',
                        ],
                        'recipe_instructions_img' => [
                            'tag' => '#hypercontext div[itemprop="recipeInstructions"] > p > img',
                            'attr' => 'src',
                        ],
                    ]);

                    $ingredients = [];
                    $ingredients_with_count = [];

                    // обрабатываем игредиенты
                    echo 'ingredients...' . PHP_EOL;

                    foreach ($data_3['ingredients'] as $ingredient) {
                        $pos = strpos($ingredient, " <span style=\"display:none;position:absolute;right:30%;color:#e6e6e6;z-index:-1;\"");
                        if ($pos) {
                            $ingredient = substr($ingredient, 0, $pos);
                            $ingredients[] = trim($ingredient);
                        }
                    }

                    // готовим массив ингредиентов с количеством
                    foreach ($ingredients as $key => $_ingredient) {
                        $ingredients_with_count[] = [
                            'title' => $_ingredient,
                            'value' => trim($data_3['ingredients_count'][$key]),
                        ];
                    }


                    // работаем над инструкцией
                    $instructions = [];

                    $step = 1;
                    // создаем массив шагов с текстом и id картинки
                    echo 'Instructions...' . PHP_EOL;
                    foreach ($data_3['recipe_instructions'] as $key => $_step) {
                        if ($step > count($data_3['recipe_instructions_img'])) continue;
                        if ($key % 2 == 0) {
                            $instructions[$step] = $_step;
                        } else {
                            $step++;
                        }
                    }

                    // добавляем картинки в массив
//                    foreach ($data_3['recipe_instructions_img'] as $key => $inst_img) {
//                        $instructions[$key + 1]['img'] = self::RECEIPTS_SOURCE . $inst_img;
//                    }

                    $recipe_title = trim($data_3['title'][0]);
                    $recipe_big_image = str_replace("/rname/", "/rnamebig/", $data_3['big_image'][0]);
                    $recipe_ingredients = $ingredients_with_count;
                    $recipe_instructions = $instructions;

                    // сохраняем рецепт и сохраняем картинки
                    $recipe = new Recipe();
                    $recipe->title = $recipe_title;
                    $recipe->user_id = null;
                    $recipe->category_id = $sub_category_id;
                    $recipe->status = Recipe::STATUS_MODERATION;
                    $recipe->description = $recipe_title . ' - Рецепт приготовления';
                    $recipe->ingredients = json_encode($recipe_ingredients);
                    $recipe->instructions = json_encode($instructions);

                    if ($recipe->save()) {
                        var_dump($recipe->id);
                        // работаем с картинками
                    }
                    echo 'Saved new Recipe!' . PHP_EOL;
                    var_dump(json_decode($recipe->instructions));

                    die;
                }

                $page++;
            }
        }
    }

    /**
     * @param $parser
     * @param $url
     * @return mixed
     */
    private
    function parseSubCategoriesByPages($parser, $url)
    {
        return $parser->get($url, [
            'title' => [
                'tag' => '#hypercontext > h1.h1',
                'attr' => 'innertext'
            ],
            'items_url' => [
                'tag' => '#hypercontext > .pgrdiv > a.pgrblock',
                'attr' => 'href'
            ],
        ]);
    }

    /**
     * @param $parser
     * @param $url
     * @return mixed
     */
    private
    function parseItem($parser, $url)
    {
        return $parser->get($url, [
            'title' => [
                'tag' => '#hypercontext > h1.h1',
                'attr' => 'innertext'
            ],
//                    'items_url' => [
//                        'tag' => '#hypercontext > .pgrdiv > a.pgrblock',
//                        'attr' => 'href'
//                    ],
//                    'items_thumbs' => [
//                        'tag' => '#hypercontext > .pgrdiv > a.pgrblock > img.pgrblockimg',
//                        'attr' => 'src'
//                    ],
        ]);

    }

    /**
     * @param $category_title
     * @param null $parent_category_id
     * @return int|mixed
     */
    private
    function categoryCheck($category_title, $parent_category_id = null)
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
        $category->description = $category_title . ' - Рецепты';
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
    public
    function actionTest()
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
    private
    function log($success, $success_log_msg = null, $error_log_msg = null)
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


