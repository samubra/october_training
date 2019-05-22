<?php

namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\Organization;

/**
 * Class UserRepository.
 */
class OrganizationRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Organization::class;
    }
}
