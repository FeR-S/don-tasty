<?php

namespace console\controllers;

use common\models\Place;
use yii\console\Controller;
use yii\console\Exception;
use common\components\ImageHandler;
use common\models\News;
use common\models\Subject;
use common\models\Categories;
use common\models\Telegram;
use yii\helpers\Console;
use yii\base\Model;
use yii\imagine\Image;

class NewsController extends Controller
{
    const LAST_NEWS_UPDATE_FILE_NAME = 'last_news_update.dat';

    /**
     *
     */
    public function actionIndex()
    {
        echo 'yii news/get-news' . PHP_EOL;
    }

    /**
     *
     */
    public function actionGetNews()
    {
        // новости за последние сутки
        if ($this->log(($xml = News::getSourceNews()), true, 'Получили данные от источника новостей!', 'Не получили данные от источника новостей!')) {
            $xml = simplexml_load_string($xml);

            $this->log($xml->channel->item, true, 'Получили список новостей для парсинга!', 'Не получили список новостей!');

            // смотрим время последнего обновления
            $last_news_update_file = __DIR__ . '/' . self::LAST_NEWS_UPDATE_FILE_NAME;

            // смотрим время последнего запроса на проверку
            if ($this->log(file_exists($last_news_update_file), false, 'Взяли время последнего обновления статей', 'Время последнего обновления статей недоступно!')) {
                $last_news_update = file_get_contents($last_news_update_file);
            } else {
                $last_news_update = 0;
            };

            foreach ($xml->channel->item as $i => $item) {

                // дата новости
                $current_news_date = strtotime(trim($item->pubDate));

                // если время новости больше последней свежей новости у нас,
                // значит эту новость мы запишем
                if ($current_news_date > $last_news_update) {
                    // превью новости
                    $news_description = trim($item->description);

                    // заголовок новости
                    $news_title = trim($item->title);

                    // текст новости
                    $namespaces = $item->getNameSpaces(true);
                    $yandex = $item->children($namespaces['yandex']);
                    $full_text = (string)$yandex->{'full-text'};
                    $full_text = mb_substr(strip_tags($full_text), 0, mb_strlen($full_text), 'UTF-8');
                    $news_text = trim($full_text);

                    // категория новости
                    $news_category = trim($item->category);
                    $news_category_id = $this->categoryCheck($news_category);

                    // источник новости
                    $news_source = trim((string)$item->link);

                    // Записываем новость
                    $news = new News();
                    $news->title = $news_title;
                    $news->announcement = $news_description;
                    $news->body = $news_text;
                    $news->category_id = $news_category_id;
                    $news->subject_id = Subject::SUBJECT_NEWS;
                    $news->source_link = $news_source;
                    $news->type = News::TYPE;
                    $news->status = News::STATUS_ACTIVE;
                    $news->content_type = News::CONTENT_TYPE_PHOTO;
                    $this->log($news->save(), false, 'Добавили новую новость!', 'Ошибка записи новости!');

                    // изображение
                    $news_image_url = trim((string)$item->enclosure['url']);
                    $imageHandler = new ImageHandler();
                    if (!empty($news_image_url) and $news_image = $imageHandler->load($news_image_url)) {
                        $image_name = $news->id . '.jpg';

                        // сохраняем большую картинку
                        $news_image->save(News::getFilePath($image_name, News::NEWS_FILES_PATH), false, 100);

                        // сохраняем thumb
                        $news_image->thumb(400, 400);
                        $news_image->crop(250, 150);
                        $news_image->save(News::getFilePath($image_name, News::NEWS_THUMBS_PATH), false, 100);
                    }

                }
            }

            // пишем время последнего запроса
            $this->log(file_put_contents($last_news_update_file, time()), false, 'Время запроса залогировали.', 'Ошибка записи время запроса!');

            // удаляем старые новости
            $this->log($this->removeOldNews(), false, 'Удалили старые новости!', 'Продолжаем копить новости! Ничего не удаляем!');
        }
    }


    /**
     * @return array|bool
     */
    private function removeOldNews()
    {
        $query = News::find()->where(['status' => News::STATUS_ACTIVE]);

        if ($query->count() > News::NEWS_COUNT_KEEP) {
            $removed_news_ids = [];
            $old_news = News::find()
//            ->where(['status' => News::STATUS_ACTIVE])
                ->limit($query->count() - News::NEWS_COUNT_KEEP)
                ->orderBy(['created_at' => SORT_ASC])
                ->all();

            foreach ($old_news as $news) {
                if ($news->delete()) {
                    $removed_news_ids[] = $news->id;
                    // todo: сделать этот метод динамическим, что бы удалять нйжные файлы данной модели
                    if ($news->content_type != News::CONTENT_TYPE_TEXT) {
                        News::removeFile($news->id . News::EXT_DEFAULT, News::NEWS_THUMBS_PATH);
                        News::removeFile($news->id . News::EXT_DEFAULT, News::NEWS_FILES_PATH);
                        if ($news->content_type == News::CONTENT_TYPE_VIDEO) {
                            // удаляем видео
                            News::removeFile($news->id . News::EXT_VIDEO_DEFAULT, News::NEWS_FILES_PATH);
                        }
                    }

                };
            }

            return $removed_news_ids;
        }

        return false;
    }


    /**
     * @param $category_title
     * @return mixed
     */
    private function categoryCheck($category_title)
    {
        // ищем такую категорию
        $query = Categories::find()->where([
            'subject_id' => Subject::SUBJECT_NEWS,
            'LOWER(title)' => mb_strtolower($category_title)
        ])->one();

        // если есть -> возвращаем ее id
        if ($query) {
            return $query->id;
        }

        // если нет -> записываем и возвращаем ее id
        $category = new Categories();
        $category->pid = 0; // ХЗ что это за поле такое вообще...
        $category->status = 1; // ХЗ что это за поле такое вообще...
        $category->title = $category_title; // приводим к верхнему регистру первый символ
        $category->subject_id = Subject::SUBJECT_NEWS;
        if ($this->log($category->save(), false, 'Добавили новую категорию новостей!', 'Ошибка записи новой категории новостей!')) {
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
    private function log($success, $telegram_message = false, $success_log_msg = null, $error_log_msg = null)
    {
        if ($success) {
            $this->stdout('Success!' . $success_log_msg, Console::FG_GREEN, Console::BOLD);
            echo PHP_EOL;
            if ($telegram_message === true) {
                Telegram::sendMessage($success_log_msg);
            }
            return true;
        }

        if ($error_log_msg === null) {
            $error_log_msg = 'Неизвестная ошибка: ' . $this->id . ' / ' . $this->action->id;
        }

        $this->stderr('Error!' . $error_log_msg, Console::FG_RED, Console::BOLD);
        echo PHP_EOL;

        if ($telegram_message === true) {
            Telegram::sendMessage($error_log_msg);
        }
        return false;
    }
}


