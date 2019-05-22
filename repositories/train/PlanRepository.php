<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\Plan;

/**
 * Class UserRepository.
 */
class PlanRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Plan::class;
    }
}
