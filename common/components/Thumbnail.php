<?php

namespace common\components;

use Yii;
use yii\imagine\Image;

/**
 * Class Thumbnail
 * @package common\components
 */
class Thumbnail
{
    /**
     * @param $path_to_source_file
     * @param $width
     * @param $height
     * @return string
     */
    public static function get($path_to_source_file, $width, $height)
    {
        $path_to_img = substr($path_to_source_file, 0, -4) . '-' . $width . 'x' . $height . '.png';
        $img_update_time = Yii::$app->cache->get($path_to_img);
        $img_source_update_time = Yii::$app->cache->get($path_to_source_file);
        if ($img_update_time and $img_source_update_time and $img_update_time == $img_source_update_time) {
            // проверяем, есть ли вообще файл, потому  что картинка могла быть сгенерирована на зеркале
            if (file_exists($path_to_img)) {
                if (!($width == 60 and $height == 80 and filesize($path_to_img) == 1934)) {
                    die($path_to_img);
                    return $path_to_img . '?' . $img_update_time;
                }
            }
        }
        if (file_exists(Yii::getAlias('@frontend/web' . $path_to_source_file))) {
            $source_path = Yii::getAlias('@frontend/web' . $path_to_source_file);
        } else {
            return $path_to_img . '?' . time();
            // $source_path = Yii::getAlias('@frontend/web/uploads/offer/default.png');
        }
        Image::thumbnail($source_path, $width, $height)->save(Yii::getAlias('@frontend/web' . $path_to_img), ['quality' => 90]);
        Yii::$app->cache->set($path_to_img, $img_source_update_time);
        Yii::$app->cache->set($path_to_source_file, $img_source_update_time);
        return $path_to_img . '?' . $img_source_update_time;
    }

    /**
     * @param $path_to_source_file
     */
    public static function setCache($path_to_source_file)
    {
        Yii::$app->cache->set($path_to_source_file, time());
    }
}