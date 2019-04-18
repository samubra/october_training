<?php
/**
 * Created by PhpStorm.
 * User: samub
 * Date: 2019/4/18
 * Time: 18:43
 */

namespace Samubra\Training\Controllers;


trait ShowActionTraits
{
    public function listExtendColumns($list)
    {
        if ($this->showPreviewButton || $this->showPrintButton || $this->showUpdateButton){
            $list->addColumns([
                'action' => [
                    'label' => '操作',
                    'type' => 'partial',
                    'invisible' => false,
                    'sortable' => false,
                    'path' => '$/samubra/training/controllers/baseview/list/_action.htm'
                ]
            ]);
        }
    }
}