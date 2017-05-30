<?php

namespace console\controllers;

use common\components\LImageHandler;
use common\models\Category;
use common\models\Recipe;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use yii\base\Model;
use yii\helpers\Url;


class Recipes2Controller extends Controller
{

    const RECEIPTS_SOURCE = 'https://www.edimdoma.ru';
    const MAIN_IMAGE_PATH = '';

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

        // категории
        $categories = $parser->get(self::RECEIPTS_SOURCE . '/retsepty', [
            'cats' => [
                'tag' => 'div.side-nav__section',
                'attr' => 'innertext'
            ]
        ]);

        $html = new \simple_html_dom();
        $html->load(trim($categories['cats'][2]));
        $cats = [];

        foreach ($html->find("div.side-nav__item > label > span") as $cat) {
            $cats[] = trim($cat->plaintext);
        }

        // парсим рецепты
        foreach ($cats as $category) {
            $category_title = mb_strtolower($category);
            echo 'Save new Category, return id...' . PHP_EOL;

            $parent_category_id = $this->categoryCheck($category_title);

            var_dump($parent_category_id);
            die;

            $category_title = urlencode($category_title);
            $current_page_url = self::RECEIPTS_SOURCE . '/retsepty/tags/' . $category_title;

            $pagination = $parser->get($current_page_url, [
                'pagination' => [
                    'tag' => 'div.paginator div.paginator__pages a',
                    'attr' => 'href'
                ],
            ]);

            //идем по страницам, находим самую большую
            $pages = [];
            foreach ($pagination['pagination'] as $row) {
                if ($row) {
                    $pages[] = (int)explode('?page=', $row)[1];
                }
            }
            $max_page = max($pages);

            for ($i = 1; $i <= $max_page; $i++) {
                $current_page_url = self::RECEIPTS_SOURCE . '/retsepty/tags/' . $category_title . '?page=' . $i;
                echo 'Page: ' . $i . PHP_EOL;

                $recipes_list = $parser->get($current_page_url, [
                    'links' => [
                        'tag' => 'article.card > a',
                        'attr' => 'href'
                    ],
                ]);


                $recipes_links = [];

                // обрабаиываем ссылки на рецепты
                echo 'Prepare links: ' . $i . PHP_EOL;
                foreach ($recipes_list['links'] as $link) {
                    if (explode('/', $link)[1] == 'users') continue;
                    $recipes_links[] = self::RECEIPTS_SOURCE . $link;
                }

                // идем по рецептам
                echo 'Parse recipe...' . $i . PHP_EOL;
                foreach ($recipes_links as $recipe) {
                    $recipe_data = $parser->get($recipe, [
                        'title' => [
                            'tag' => 'h1.recipe-header__name',
                            'attr' => 'innertext'
                        ],
                        'image' => [
                            'tag' => '.content-box__content .plain-text > .content-media > img',
                            'attr' => 'src'
                        ],
                        'cooktime' => [
                            'tag' => '.entry-stats__value[itemprop="totalTime"]',
                            'attr' => 'plaintext'
                        ],
                        'persons' => [
                            'tag' => '.entry-stats__value[itemprop="recipeYield"]',
                            'attr' => 'plaintext'
                        ],
                        'description' => [
                            'tag' => '[itemprop="description"]',
                            'attr' => 'plaintext'
                        ],
                        'steps' => [
                            'tag' => '[itemprop="recipeInstructions"]',
                            'attr' => 'innertext'
                        ],
                        'tags' => [
                            'tag' => '.tags > .tags__tags-cloud > a',
                            'attr' => 'plaintext'
                        ],
                        'ingredients' => [
                            'tag' => '[itemprop="ingredients"]',
                            'attr' => 'innertext'
                        ],
                    ]);

                    // ИНСТРУКЦИЯ
                    $html = new \simple_html_dom();
                    $html->load(trim($recipe_data['steps'][0]));
                    $steps = [];
                    $steps_without_images = [];

                    // идем по каждому шагу
                    foreach ($html->find("div.content-box > div.content-box__content") as $key => $step) {
                        $step_text = $step->find('div.plain-text')[0]->plaintext;
                        $steps[$key + 1]['text'] = trim($step_text);
                        $steps_without_images[$key + 1] = trim($step_text);
                        if ($step_img = $step->find('.field-row > img') and $step_img = $step->find('.field-row > img')[0]->getAttribute('data-on-error')) {
                            $steps[$key + 1]['img'] = self::RECEIPTS_SOURCE . trim($step_img);
                        }
                    }

                    // ИНГРЕДИЕНТЫ
                    $html = new \simple_html_dom();
                    $html->load(trim($recipe_data['ingredients'][0]));
                    $ingredients = [];

                    // идем по каждой группе
                    foreach ($html->find("div.recipe_ingredients") as $key => $ing_group) {
                        // заголовок группы
                        $group_title = $ing_group->find('div.section-title')[0]->plaintext;
                        $ingredients[$key]['group_title'] = trim($group_title);
                        foreach ($ing_group->find('table.definition-list-table > tbody > tr') as $ings) {
                            // названия ингредиентов
                            $ing_titles = $ings->find('td.definition-list-table__td', 0)->find('label > span')[0]->innertext;
                            $pos = strpos($ing_titles, " <div class=\"checkbox-info-container");
                            if ($pos) {
                                $ing_title = substr($ing_titles, 0, $pos);
                            } else {
                                $ing_title = $ing_titles;
                            }

                            // количество ингоедиентов
                            $ing_counts = $ings->find('td.definition-list-table__td', -1)->plaintext;

                            // заполняем массив ингредиентов
                            $ingredients[$key]['ingredients'][] = [
                                'title' => trim($ing_title),
                                'count' => trim($ing_counts),
                            ];
                        }
                    }

                    // Заголовок
                    $title = trim($recipe_data['title'][0]);

                    // Описание
                    $description = trim($recipe_data['description'][0]);
//                    var_dump(mb_detect_encoding($description));
//                    die;

                    // Теги
                    $tags = [];
                    foreach ($recipe_data['tags'] as $tag) {
                        $tags[$tag] = $tag;
                    }

                    // Главное изображение
                    $main_img = 'https:' . trim($recipe_data['image'][0]);

                    // Время приготовления
                    $cook_time = trim($recipe_data['cooktime'][0]);

                    // На сколько персон
                    $persons = trim($recipe_data['persons'][0]);

                    // сохраняем рецепт
                    $recipe_db = new Recipe();
                    $recipe_db->title = $title;
                    $recipe_db->user_id = null;
                    $recipe_db->category_id = $parent_category_id;
                    $recipe_db->status = Recipe::STATUS_MODERATION;
                    $recipe_db->description = $description;
                    $recipe_db->ingredients = json_encode($ingredients);
                    $recipe_db->cook_time = trim($cook_time);
                    $recipe_db->persons_count = trim($persons);
                    $recipe_db->instructions = json_encode($steps_without_images);
                    $recipe_db->source = $recipe;

                    if ($recipe_db->save()) {
                        $wm = \Yii::getAlias('@frontend/web/uploads/recipes_images/321849813.png');

                        // обрабатываем фото шагов
                        foreach ($steps as $key => $value) {
                            if (isset($value['img'])) {
                                $ih = new LImageHandler();
                                $ih->load($value['img']);
                                $ih->crop($ih->getWidth() - 5, $ih->getHeight() - 5, 0, 0);
                                $ih->flip(LImageHandler::FLIP_HORIZONTAL);
                                $ih->watermark($wm, 0, 0, LImageHandler::CORNER_CENTER, false);
                                $ih->save(\Yii::getAlias('@frontend/web/uploads/recipes_images/steps/') . $recipe_db->id . '-' . $key . '.jpg', false, 100);
                                $ih = null;
                            }
                        }

                        // обрабатываем главное фото
                        $ih = new LImageHandler();
                        $ih->load($main_img);
                        $ih->crop($ih->getWidth() - 50, $ih->getHeight() - 50, 0, 0);
                        $ih->flip(LImageHandler::FLIP_HORIZONTAL);
                        $ih->watermark($wm, 0, 0, LImageHandler::CORNER_CENTER, false);
                        $ih->save(\Yii::getAlias('@frontend/web/uploads/recipes_images/') . $recipe_db->id . '.jpg', false, 100);
                        var_dump($recipe_db->id);
                    } else {
                        var_dump($recipe_db->getErrors());
                    }
                    die;
                }
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
                'tag' => 'article.card > a',
                'attr' => 'gref'
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
        $category->title = mb_strtolower(ucfirst($category_title));
        $category->description = $category->title . ' - Рецепты';
        if ($parent_category_id and is_int($parent_category_id)) {
            $category->parent_category_id = $parent_category_id;
        }

        if ($category->save()) {
            $this->log(true, 'New category has been added successfully!', 'New category has not been added!');
            return $category->id;
        } else {
            var_dump($category->getErrors());
        }
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


