<?php


namespace CusForm\Controller;


use CusForm\CusForm;
use CusForm\Model\FormModel;
use Gy_Library\GyListController;
use Qscmf\Builder\FormBuilder;
use Qscmf\Lib\DBCont;

class FormController extends GyListController
{

    public function index(){
        $get_data = I('get.');
        $map = array('deleted'=>DBCont::NO_BOOL_STATUS);
        $model = new FormModel();
        $count = $model->getListForCount($map);
        $per_page = C('ADMIN_PER_PAGE_NUM', null, false);
        if($per_page === false){
            $page = new \Gy_Library\GyPage($count);
        }
        else{
            $page = new \Gy_Library\GyPage($count, $per_page);
        }

        $data_list = $model->getListForPage($map, $page->nowPage, $page->listRows, 'create_date desc');


        $builder = new \Qscmf\Builder\ListBuilder();

        $builder = $builder->setMetaTitle('表单列表');

        $builder
            ->setNIDByNode()
            ->addTopButton('addnew')
            ->addTableColumn('title', '表单标题', '', '', false)
            ->addTableColumn('right_button', '操作', 'btn')
            ->addRightButton('self',array('title' => '编辑', 'href' => U('edit', array('id' => '__data_id__')),'class'=>'label label-primary ajax-get confirm','confirm-msg'=>'该操作会可能造成已填写的用户数据混乱，请慎重操作'))
            ->addRightButton('self',array('title' => '编辑表单项', 'href' => U('FormItem/index', array('id' => '__data_id__')), 'class' => 'label label-success'))
            ->addRightButton('delete',array('confirm-msg'=>'该操作会可能造成已填写的用户数据混乱，请慎重操作'))
            ->setTableDataList($data_list)
            ->setTableDataPage($page->show())
            ->display();
    }

    public function add(){
        $model = new FormModel();
        if (IS_POST){
            $data=I('post.');
            $data['create_date']=time();
            if ($model->createAdd($data)!==false){
                $this->success('新增成功',U('index'));
            }else{
                $this->error('新增失败'.D('Form')->getError(),U('index'));
            }
        }else{
            $builder=new FormBuilder();
            $builder=$builder->setMetaTitle('新建表单')
                ->setNIDByNode()
                ->addFormItem('title','text','标题');
            if (packageConfig('cusform','form_description')===true){
                $builder=$builder->addFormItem('description','ueditor','表单辅助说明');
            }else{
                $builder=$builder->addFormItem('description','hidden','');
            }
            $builder->display();
        }
    }
    public function edit(){
        $model = new FormModel();
        $id=I('get.id');

        if (IS_POST){
            $data=I('post.');
            $data['create_date']=time();
            if ($model->createSave($data)!==false){
                $this->success('保存成功',U('index'));
            }else{
                $this->error('保存失败'.D('Form')->getError(),U('index'));
            }
        }else{
            if(IS_AJAX){
                $this->success('',U('',['id'=>$id]));
            }
            $data=$model->getOne($id);
            $builder=new FormBuilder();
            $builder->setMetaTitle('编辑表单')
                ->setNIDByNode()
                ->setFormData($data)
                ->addFormItem('id','hidden','')
                ->addFormItem('title','text','标题');
            if (packageConfig('cusform','form_description')===true){
                $builder=$builder->addFormItem('description','ueditor','表单辅助说明');
            }else{
                $builder=$builder->addFormItem('description','hidden','');
            }
            $builder->display();
        }
    }
    public function delete(){
        $ids = I('ids');
        $model = new FormModel();
        if(!$ids){
            $this->error('请选择要删除的数据');
        }
        $r = $model->where(['id'=>['in',$ids]])->setField('deleted',\Qscmf\Lib\DBCont::YES_BOOL_STATUS);
        if ($r===false){
            $this->error($model->getError());
        }
        $this->success('删除成功');
    }
}