<?php
/**
 * 
 * User : Harminanto Prastama
 * Mail: harminantoprastama@gmail.com
 * Time : 22.01
 */

namespace App\Helpers;

class QueryBuilderHelper
{
    /**
     * @param array $field
     * @return array
     */
    public static function include(array $field) :array
    {
        /**
         * Buat array kosong untuk menampung Statement Query yang dipersiapkan
         */
        if (!empty(request()->all('include'))) {
            /**
             * Pecah string menjadi sebuah array dengan pemisah ',' (Koma)
             */
            $exp_include = explode(',', request()->all('include'));
            /**
             * Membaca semua request include yang telah menjadi array
             * Cek apabila request include ada di array Field Translate dan Ambil Field DB nya
             */
            $result = array();
            foreach ($exp_include as $key_exp_include => $item_exp_include) {
                if ($fieldDB = array_search($item_exp_include, $field, true)) {
                    array_push($result, $fieldDB.' AS '.$item_exp_include);
                }
            }
            return $result;
        }
        if (empty(request()->all('include'))) {
            $result = array();
            foreach ($field as $fieldDB => $fieldTranslate) {
                array_push($result, $fieldDB.' AS '.$fieldTranslate);
            }
            return $result;
        }
    }
}