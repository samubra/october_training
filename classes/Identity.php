<?php namespace Samubra\Training\Classes;

use Illuminate\Contracts\Validation\Rule;
use Samubra\Training\Classes\Idcard;

class Identity implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $idcard = new Idcard($value);
        //return preg_match('/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/', $value);
        return $idcard->isChinaIDCard();
    }
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '身份证号码格式错误。';
    }
}