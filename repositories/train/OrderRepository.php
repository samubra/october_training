<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Models\Order;
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
        return Order::class;
    }
}
