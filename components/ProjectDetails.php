<?php


namespace Samubra\Training\Components;


use Cms\Classes\ComponentBase;
use Samubra\Training\Repositories\Train\CertificateRepository;
use Samubra\Training\Repositories\Train\ProjectRepository;
use Flash;
use Validator;
use ValidationException;
use ApplicationException;
use Cart;
use Auth;

class ProjectDetails extends ComponentBase
{
    protected $projectRepository;
    protected $projectModel;
    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => '培训项目详情',
            'description' => '培训项目的详细信息',
        ];
    }

    public function init()
    {
        $this->projectRepository = new ProjectRepository();
    }

    public function onRun()
    {
        $this->projectModel = $this->page['project'] = $this->loadProject();
    }


    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'rainlab.blog::lang.settings.post_slug',
                'description' => 'rainlab.blog::lang.settings.post_slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string',
            ]
        ];
    }

    public function loadProject()
    {
        $slug = post('slug',$this->property('slug'));
        try {
            $projectModel = $this->projectRepository->with('plan','plan.category','status','records_count','courses','courses.course','courses.teacher')->getByColumn($slug,'slug' );
        } catch (ModelNotFoundException $ex) {
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }

        return $projectModel;
    }

}
