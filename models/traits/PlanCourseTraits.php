<?php
/**
 * Created by PhpStorm.
 * User: samubra
 * Date: 19-4-17
 * Time: 下午8:41
 */

namespace Samubra\Training\Models\Traits;


use Samubra\Training\Models\Course;
use Samubra\Training\Models\Plan;
use Samubra\Training\Models\ProjectCourse;
use Samubra\Training\Models\Train;

trait PlanCourseTraits
{

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */



    /**
     * @var string The database table used by the model.
     */





    public function getPlanTitleAttribute()
    {
        return $this->plan->title;
    }
    public function getCourseTitleAttribute()
    {
        return $this->course->title;
    }

    public function filterFields($fields, $context = null)
    {
        if ($course = $this->course) {
            $fields->hours->value = $course->default_hours;
        }
    }

    public function getTeachingFormOptions()
    {
        return Train::$teachingFormMap;
    }
}