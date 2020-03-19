<?php namespace Samubra\Training\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Samubra\Training\Models\Settings;
use Samubra\Training\Models\Record as RecordModel;

class Records extends ComponentBase
{

    /**
     * A collection of records to display
     * @var Collection
     */
    public $records;

    /**
     * Message to display when there are no products.
     * @var string
     */
    public $noRecordsMessage;

    /**
     * Reference to the page name for linking to records.
     * @var string
     */
    public $recordDisplayPage;

    public function componentDetails()
    {
        return [
            'name'        => '申请记录',
            'description' => '所有申请记录列表'
        ];
    }

    public function defineProperties()
    {
        return [
            'noRecordsMessage' => [
                'title'        => '无记录内容',
                'description'  => '没有申请记录时的显示内容',
                'type'         => 'string',
                'default'      => 'No records found',
                'showExternalParam' => false
            ]
        ];
    }

    public function onRun()
    {
        $this->prepareVars();
        $this->records = $this->page['records'] = $this->loadRecords();
    }

    protected function prepareVars()
    {
        $this->noRecordsMessage = $this->page['noRecordsMessage'] = $this->property('noRecordsMessage');

        /*
         * Page links
         */
        $this->recordDisplayPage = $this->page['recordDisplayPage'] = Settings::get('record_display_page', 'records');
    }

    protected function loadRecords()
    {
        $user = Auth::getUser();
        if (!isset($user)) {
            return array();
        }
        else {
            $records = $user->records;
            /*
             * Add a "url" helper attribute for linking to each category
             */
            return $records->each(function($record) {
                $record->setUrl($this->recordDisplayPage, $this->controller);
            });


        }
    }

}
