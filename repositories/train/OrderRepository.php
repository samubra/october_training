<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Models\OrderBack;
use Samubra\Training\Repositories\BaseRepository;

/**
 * Class UserRepository.
 */
class OrderRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return OrderBack::class;
    }
}
