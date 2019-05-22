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

    public function getTree (){
        $categories = $this->makeModel()->orderBy('_lft')->get()->toTree();
        return $this->traverse($categories);
    }

    protected function traverse($categories = null, $prefix = '|--'){
        $list = [];
        foreach ($categories as $category) {
            $list[$category->id] = PHP_EOL.$prefix.' '.$category->name;
            if($category->children)
                $list = array_merge($list , $this->traverse($category->children, $prefix.'--'));
        }
        return $list;
    }
}
