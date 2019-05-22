<?php
namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Repositories\BaseRepository;
use Samubra\Training\Models\Certificate;

/**
 * Class UserRepository.
 */
class CertificateRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Certificate::class;
    }
}
