<?php


namespace Samubra\Training\Components;


use Cms\Classes\ComponentBase;
use Samubra\Training\Repositories\Train\RecordRepository;
use Flash;
use Validator;
use ValidationException;
use SystemException;
use ApplicationException;
use Auth;
use Log;


class AddAttach extends ComponentBase
{

    protected $recordRepository;
    protected $recordModel;
    /**
     * @return array
     */

    public function init()
    {
        $this->recordRepository = new RecordRepository();
    }
    public function componentDetails()
    {
        return [
            'name'        => '上传图片',
            'description' => '上传培训所需的图片',
        ];
    }

    public function onRun()
    {
       $this->loadRecord();
    }
    public function defineProperties()
    {
        return [
            'record_id' => [
                'title'       => 'url中的ID',
                'description' => 'rainlab.blog::lang.settings.post_slug_description',
                'default'     => '{{ :id }}',
                'type'        => 'string',
            ]
        ];
    }



    public function onSaveAttach()
    {
        //trace_log(request()->file('photo'));

        /**
         * photo' => 'image|max:1000|dimensions:min_width=100,min_height=100',
        'id_card_front' => 'image|max:1000|dimensions:min_width=100,min_height=100',
        'id_card_back' => 'image|max:1000|dimensions:min_width=100,min_height=100',
        'edu_one' => 'image|max:1000|dimensions:min_width=100,min_height=100',
        'edu_two' => 'image|max:1000|dimensions:min_width=100,min_height=100',
         */
        if(request()->hasFile('photo')){
            $rules = [
                'photo' => 'required|image|max:1000|dimensions:min_width=100,min_height=100',
            ];
            $postData['photo'] = request()->file('photo');
        }
        if(request()->hasFile('id_card')){
            $rules = [
                'id_card' => 'required|image|max:1000|dimensions:min_width=100,min_height=100',
            ];
            $postData['id_card'] = request()->file('id_card');
        }
        if(request()->hasFile('edu')){
            $rules = [
                'edu' => 'required|image|max:1000|dimensions:min_width=100,min_height=100',
            ];
            $postData['edu'] = request()->file('edu');
        }
        $messages = [
            'photo.required' => '寸照必须上传!',
            'id_card.required' => '身份证正反面照片必须上传!',
            'edu.required' => '学历证明照片必须上传!',
            'photo.image' => '上传的照片必须是图片！',
            'id_card.image' => '上传的身份证照片必须是图片！',
            'edu.image' => '上传的学历证明必须是图片！',
            'edu.max' => '上传的学历证明图片不得超过1M！',
            'id_card.max' => '上传的身份证图片不得超过1M！',
            'photo.max' => '上传的寸照不得超过1M！',
            'photo.dimensions' => '上传的寸照尺寸不得小于100*100！',
            'id_card.dimensions' => '上传的身份证照片尺寸不得小于100*100！',
            'edu.dimensions' => '上传的学历证明照片尺寸不得小于100*100！',
        ];
        if(request()->hasFile('edu') || request()->hasFile('id_card') ||  request()->hasFile('id_card')){
            $validation = Validator::make($postData, $rules,$messages);
            if ($validation->fails()) {
                throw new ValidationException($validation);
            }
        }

        $this->loadRecord(post('record_id'));

        $this->recordModel->photo = request()->file('photo');
        $this->recordModel->id_card = request()->file('id_card');
        $this->recordModel->edu = request()->file('edu');
        $this->recordModel->save();

        if($this->recordModel->photo && $this->recordModel->id_card->count() ==2 && $this->recordModel->edu->count() ==2)
        {
            return redirect()->to('/user/carts');
        }else{
            return redirect()->refresh();
        }

    }
    protected function loadRecord($record_id= null)
    {
        $record_id = is_null($record_id) ? post('record_id',$this->property('record_id')):$record_id;
        $this->recordModel = $this->page['record'] = $this->recordRepository->with('project','certificate','project.plan','photo','edu','id_card')->getById($this->property('record_id'));
        $this->page['project'] = $this->recordModel->project;
    }


}
