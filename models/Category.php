<?php namespace Samubra\Training\Models;

use \October\Rain\Database\Traits\NestedTree;
use \October\Rain\Database\Model;
use \October\Rain\Database\Traits\Validation;
use Validator;
use ValidationException;
use Event;

/**
 * Model
 */
class Category extends Model
{
    use Validation;
    use NestedTree;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'samubra_training_categories';

    public $rules = [
        'name' => 'required|min:2',
        'num_display' => 'numeric',
        'active' => 'boolean'
    ];

    public $attachOne = [
        'image' => 'System\Models\File'
    ];


    /**
     * Delete routes after delete category
     */
    public function afterDelete()
    {
        $checked = post('checked');
        Route::deleteRoutes($checked, Route::ROUTE_CATEGORY);
    }

    public function getActiveOptions()
    {
        return [
            Train::ENABLE => '启用',
            Train::DISABLE => '停用',
        ];
    }

    public function beforeSave()
    {
        $post = post();

        if(!$post['Category']['slug'])
            $this->slug = Train::generateRandomString(10);
    }
    /**
     * Save routes for category to table #_routes
     */
    public function afterSave()
    {
        $categorySaved = $this->attributes;
        $post = post();
        trace_log($_POST);
        $idEdit = isset($post['id']) ? $post['id']:'0';
        $slug = $categorySaved['slug'];
        $entityId = $categorySaved['id'];
        $type = Route::ROUTE_CATEGORY;
        Route::saveRoutes($idEdit, $slug, $entityId, $type);
        Event::fire('samubra.training.after_save_category', [$categorySaved, $post]);
    }

    /**
     * Get breadcrumb for category
     */
    public static function getBreadCrumb($id)
    {
        $model = self::find($id);
        $parents = $model->getParentsAndSelf();
        return $parents->toArray();
    }

}
