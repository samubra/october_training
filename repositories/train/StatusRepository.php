<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\Status;

/**
 * Class UserRepository.
 */
class StatusRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Status::class;
    }
}
