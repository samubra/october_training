<?php

namespace Samubra\Training\Models;

use October\Rain\Database\Model;

class Route extends Model
{

    protected $table = 'samubra_training_routes';
    
    public $timestamps = false;//disable 'created_at' and 'updated_at'

    const ROUTE_PROJECT = 1;
    const ROUTE_CATEGORY = 2;
    const ROUTE_CERTIFICATE = 3;

    /**
     * Save routes
     * $idEdit : to know if create or edit
     * $entityId : id of category (product) just saved
     */
    public static function saveRoutes($idEdit, $slug, $entityId, $type)
    {
        $model = new Route();
        if ($idEdit != 0) {//edit
            $model = self::where('entity_id', $entityId)
                ->where('type', $type)
                ->first();
        }
        $model->slug = $slug;
        $model->entity_id = $entityId;
        $model->type = $type;
        $model->save();
    }

    /**
     * Delete router
     */
    public static function deleteRoutes($arrayId, $type)
    {
        foreach ($arrayId as $row) {
            self::where('entity_id', $row)
                ->where('type', $type)
                ->delete();
        }
    }

}