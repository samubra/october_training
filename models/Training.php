<?php

namespace Samubra\Training\Models;

use October\Rain\Database\Model;
use Illuminate\Support\Facades\Cache;
use Mail;

class Training extends Model
{
    const ENABLE = 1;
    const DISABLE = 0;

    const YES = 1;
    const NO = 0;

    const SUCCESS = 1;
    const FAIL = 0;

    const MODEL_PROJECT = 1;
    const MODEL_CATEGORY = 2;

    const CACHE_ENABLE = 1;
    const CACHE_DISABLE = 0;

    const CACHE_DEFAULT_TIME = 1;

    const MAIL_SEND = 0;
    const MAIL_QUEUE = 1;

    const CREATE = 0;
    const CREATE_AND_CLOSE = 1;

    const IN_STOCK = 1;
    const OUT_OF_STOCK = 0;


    const PER_PAGE_DEFAULT = 10;

    /**
     * Convert object to Array
     */
    public static function objectToArray($object)
    {
        $json = json_encode($object);
        $array = json_decode($json, true);
        return $array;
    }

    /**
     * $data is object
     * Convert array to ['id'=>'name']
     */
    public static function convertArrayKeyValue($data, $key, $value)
    {
        $rs = [];
        if (!empty($data)) {
            foreach ($data as $row) {
                $rs[$row->$key] = $row->$value;
            }
        }
        return $rs;
    }

    /**
     * Get option field
     */
    public static function getOptionOfField($object,$key = 'id',$name = 'name')
    {
        $data = $object::select($key, $name)
            ->get()
            ->toArray();
        $rs = [];
        foreach ($data as $row) {
            $rs[$row[$key]] = $row[$name];
        }
        return $rs;
    }




    /**
     * Generate random string
     */
    public static function generateRandomString($length = 10)
    {
        $prefix = date('YmdHis');

        $characters = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $prefix . $randomString;
    }
    /**
     * Generate random number
     */
    public static function generateRandomNumber($time_prefix = true)
    {

        $prefix = date('YmdHis');
        
        $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return $no;
    }



    /**
     * Generate array to add 'first' class in first column of array
     */
    public static function generateArrayForFirstClass($min, $max, $numColumnPerRow)
    {
        $rs = [];
        for ($i=$min; $i<=$max; $i+=$numColumnPerRow) {
            $rs[] = $i;
        }
        return $rs;
    }


}