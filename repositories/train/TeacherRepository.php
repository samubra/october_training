<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\Teacher;

/**
 * Class UserRepository.
 */
class TeacherRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Teacher::class;
    }
}
