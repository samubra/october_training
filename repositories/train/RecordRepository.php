<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\Record;

/**
 * Class UserRepository.
 */
class RecordRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Record::class;
    }
}
