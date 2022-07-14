<?php
namespace App\Helpers;


use Illuminate\Support\Facades\Config;

class Helpers
{

    /**
     * Returns column to order by.
     *
     * @param string $item
     * @return string
     */
    public static function getOrderBy($item){
        return in_array($item, Config::get('tasks.order')) ? $item : 'id';
    }

    /**
     * Returns direction of ordering.
     *
     * @param string $item
     * @return string
     */
    public static function getOrderDirection($item){
        return in_array($item, Config::get('tasks.directions')) ? $item : 'ASC';
    }

    /**
     * Returns perpage number.
     *
     * @param int $item
     * @return int
     */
    public static function getPerPage($item)
    {
        return $item ?: Config::get('app.perpage');
    }

}
