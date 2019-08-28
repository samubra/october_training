<?php

namespace Samubra\Training\Repositories\Train;

use Samubra\Training\Models\Category;
use Illuminate\Support\Facades\DB;
use Samubra\Training\Repositories\BaseRepository;

/**
 * Class UserRepository.
 */
class CategoryRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Category::class;
    }

    public function getTree ($column, $key=null, $indent = '&nbsp;&nbsp;&nbsp;'){
        return $this->makeModel()->listsNested($column, $key,$indent);
    }

}
