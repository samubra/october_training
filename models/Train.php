<?php

namespace Samubra\Training\Models;

use Backend\Widgets\Table\ServerEventDataSource;
use October\Rain\Database\Model;
use Illuminate\Support\Facades\Cache;
use Mail;
use phpDocumentor\Reflection\Types\Self_;

class Train extends Model
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

    const JOB_TITLE_NONE = 0;
    const JOB_TITLE_ELEMENTARY = 1;
    const JOB_TITLE_INTERMEDIATE = 2;
    const JOB_TITLE_HIGH_GRADE = 3;

    public static $jobTitleMap = [
        self::JOB_TITLE_NONE => '无',
        self::JOB_TITLE_ELEMENTARY => '初级',
        self::JOB_TITLE_INTERMEDIATE => '中级',
        self::JOB_TITLE_HIGH_GRADE => '高级',
    ];
    const EDU_TYPE_ELEMENTAR_SCHOOL = 1;
    const EDU_TYPE_JUNIOR_HIGH_SCHOOL = 2;
    const EDU_TYPE_SENIOR_HIGH_SCHOOL = 3;
    const EDU_TYPE_TECHNICAL_SECONDARY_SCHOOL = 4;
    const EDU_TYPE_JUNIOR_SCHOOL = 5;
    const EDU_TYPE_UNDERGRADUATE = 6;
    const EDU_TYPE_POSTGRADUATE = 7;
    const EDU_TYPE_BACHELOR = 8;
    const EDU_TYPE_MASTER = 9;
    const EDU_TYPE_DOCTOR = 10;

    public static $eduTypeMap = [
        self::EDU_TYPE_ELEMENTAR_SCHOOL    => '小学',
        self::EDU_TYPE_JUNIOR_HIGH_SCHOOL    => '初中',
        self::EDU_TYPE_SENIOR_HIGH_SCHOOL    => '高中',
        self::EDU_TYPE_TECHNICAL_SECONDARY_SCHOOL    => '中等专业学校',
        self::EDU_TYPE_JUNIOR_SCHOOL    => '大学专科',
        self::EDU_TYPE_UNDERGRADUATE    => '本科',
        self::EDU_TYPE_POSTGRADUATE => '研究生',
        self::EDU_TYPE_BACHELOR    => '学士',
        self::EDU_TYPE_MASTER    => '硕士',
        self::EDU_TYPE_DOCTOR    => '博士',
    ];

    const COMPLETE_TYPE_GRADUATION = 1;
    const COMPLETE_TYPE_TRAINING_CERTIFICATE = 2;
    const COMPLETE_TYPE_OPERATIONS_CERTIFICATE = 3;

    public static $completeTypeMap = [
        self::COMPLETE_TYPE_GRADUATION => '培训结业证',
        self::COMPLETE_TYPE_TRAINING_CERTIFICATE => '培训合格证',
        self::COMPLETE_TYPE_OPERATIONS_CERTIFICATE => '特种作业操作证'
    ];

    const ID_TYPE_IDENTITY = 1;
    const ID_TYPE_OFFIER = 2;
    const ID_TYPE_OTHER = 3;

    public static $idTypeMap = [
        self::ID_TYPE_IDENTITY => '身份证',
        self::ID_TYPE_OFFIER => '军官证',
        self::ID_TYPE_OTHER => '港澳台证',
    ];
    const COURSE_TYPE_THEORY = 1;
    const COURSE_TYPE_OPERATE = 2;
    const COURSE_TYPE_SELF_STUDY = 3;

    static $courseTypeMap = [
        self::COURSE_TYPE_THEORY=>'理论课',
        self::COURSE_TYPE_OPERATE=>'操作课',
        self::COURSE_TYPE_SELF_STUDY =>'自学'
    ];

    const HEALTH_TYPE_NONE = 0;
    const HEALTH_TYPE_ELIGIBLE = 1;
    const HEALTH_TYPE_NOT_INDICATED = 2;

    static $healthTypeMap = [
        self::HEALTH_TYPE_NONE => '无需体检',
        self::HEALTH_TYPE_ELIGIBLE => '体检合格',
        self::HEALTH_TYPE_NOT_INDICATED => '无意见性结论',
    ];

    const TEACHING_FORM_TEACHING = 1;
    const TEACHING_FORM_DISCUSS = 2;
    const TEACHING_FORM_EXPERIMENT = 3;
    const TEACHING_FORM_DEMONSTRATION = 4;
    const TEACHING_FORM_EXERCISE = 5;
    const TEACHING_FORM_CLASS_TEACHING = 6;
    const TEACHING_FORM_GROUP_TEACHING = 7;
    const TEACHING_FORM_INDIVIDUAL_TEACHING = 8;

    static $teachingFormMap = [
        self::TEACHING_FORM_TEACHING => '讲授法',
        self::TEACHING_FORM_DISCUSS => '讨论法',
        self::TEACHING_FORM_EXPERIMENT => '实验法',
        self::TEACHING_FORM_DEMONSTRATION => '演示法',
        self::TEACHING_FORM_EXERCISE => '练习法',
        self::TEACHING_FORM_CLASS_TEACHING => '班级教学法',
        self::TEACHING_FORM_GROUP_TEACHING => '分组教学法',
        self::TEACHING_FORM_INDIVIDUAL_TEACHING => '个别教学法',
    ];


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
        $prefix = time();
        //$prefix = date('YmdHis');

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
