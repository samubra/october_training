<?php

namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\Course;

/**
 * Class UserRepository.
 */
class CourseRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Course::class;
    }
}
