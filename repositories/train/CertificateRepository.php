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

    public function relateCertificates($user)
    {
        $certificates = $this->makeModel()->with('category')->where('id_num',$user->identity)
            ->whereNull('user_id')->get();

        $certificates->each(function($item) use($user){
            $item->user_id = $user->id;
            $item->save();
            trace_log($user->identity . '已关联证书'.$item->category->name);
        });
    }
}
