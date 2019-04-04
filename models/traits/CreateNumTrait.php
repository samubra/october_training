<?php
namespace Samubra\Training\Models\Traits;
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-4-4
 * Time: 下午8:33
 */
use Samubra\Training\Models\Train;
trait CreateNumTrait
{
    public function beforeCreate()
    {
        $this->num = $this->getRateRandom();
    }

    protected function getRateRandom($length = 10)
    {
        $num = Train::generateRandomString();
        if(\DB::table($this->table)->where('num',$num)->count())
            $this->getRateRandom($length);
        else
            return $num;
    }
}