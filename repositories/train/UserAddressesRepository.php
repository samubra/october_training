<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\UserAddress;

/**
 * Class UserRepository.
 */
class UserAddressesRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return UserAddress::class;
    }
}
