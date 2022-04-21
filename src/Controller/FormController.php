<?php


namespace CusForm\Controller;


use CusForm\CusForm;
use CusForm\Helper;
use CusForm\Model\FormApplyModel;
use CusForm\Model\FormModel;
use Gy_Library\GyListController;
use Illuminate\Support\Str;
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

        $this->getFormSubmitNum($data_list);

        $builder = new \Qscmf\Builder\ListBuilder();

        $builder = $builder->setMetaTitle('表单列表');

        $builder
            ->setNIDByNode()
            ->addTopButton('addnew')
            ->addTableColumn('title', '表单标题', '', '', false)
            ->addTableColumn('create_date', '创建时间', 'time')
            ->addTableColumn('updated_date', '修改时间', 'time')
            ->addTableColumn('submit_num', '提交数', '', '', false)
            ->addTableColumn("status", "状态", "status")
            ->addTableColumn('right_button', '操作', 'btn')
            ->addRightButton('self',array('title' => '编辑', 'href' => U('edit', array('id' => '__data_id__')),'class'=>'label label-primary ajax-get confirm','confirm-msg'=>'该操作会可能造成已填写的用户数据混乱，请慎重操作'))
            ->addRightButton('self',array('title' => '编辑表单项', 'href' => U('editForm', array('id' => '__data_id__')), 'class' => 'label label-success'))
            ->addRightButton('self',array('title' => '复制', 'href' => U('copy', array('id' => '__data_id__')), 'class' => 'label label-success ajax-get confirm', 'confirm-msg'=>'确定执行该操作吗?'))
            ->addRightButton('forbid')
            ->addRightButton('delete',array('confirm-msg'=>'该操作会可能造成已填写的用户数据混乱，请慎重操作'))
            ->setTableDataList($data_list)
            ->setTableDataPage($page->show())
            ->display();
    }

    protected function getFormSubmitNum(array &$data_list){
        $form_ids = collect($data_list)->pluck('id')->all();
        $apply_model = new FormApplyModel();
        $map = array('form_id'=>array('in',$form_ids));
        $apply_nums = $apply_model->where($map)->group('form_id')->getField('form_id,count(id) as num');
        foreach($data_list as &$data){
            $data['submit_num'] = isset($apply_nums[$data['id']])?$apply_nums[$data['id']]:0;
        }
    }

    public function copy($id){
        $model = new FormModel();
        $ent = $model->where(['id' => $id])->find();

        unset($ent['id']);
        $ent['title'] = $ent['title'] . '_副本';
        $ent['create_date'] = time();
        $ent['status'] = DBCont::NORMAL_STATUS;
        $r = $model->createAdd($ent);
        if($r !== false){
            $this->success('复制成功', 'javascript:location.reload();');
        }
        else{
            $this->error($model->getError());
        }
    }

    protected function _forbid($id){
        $model = new FormModel();
        $r = $model->forbid($id);
        if($r === false){
            $this->_error = $model->getError();
        }
        return $r;
    }

    protected function _resume($id){
        $model = new FormModel();
        $r = $model->resume($id);
        if($r === false){
            $this->_error = $model->getError();
        }
        return $r;
    }

    public function forbid(){
        $ids = I('ids');
        if(!$ids){
            $this->error('请选择要禁用的数据');
        }
        $r = $this->_forbid($ids);
        if($r !== false){
            $this->success('禁用成功', 'javascript:location.reload();');
        }
        else{
            $this->error($this->_getError());
        }
    }

    public function resume(){
        $ids = I('ids');
        if(!$ids){
            $this->error('请选择要启用的数据');
        }
        $r = $this->_resume($ids);
        if($r !== false){
            $this->success('启用成功', 'javascript:location.reload();');
        }
        else{
            $this->error($this->_getError());
        }

    }

    public function add(){
        $model = new FormModel();
        if (IS_POST){
            $data=I('post.');
            $data['create_date']=time();
            $data['json_schema'] = '';
            $data['status'] = DBCont::NORMAL_STATUS;
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

    public function editForm(){
        $jsOptions = packageConfig('cusform','jsOptions') ?: [];
        $this->assign('meta_title','表单定制');
        $this->assign('nid',Helper::getNidBy());
        $this->assign('jsOptions', json_encode($jsOptions, JSON_PRETTY_PRINT));
        $this->display(__DIR__ . '/../View/Form/editForm.html');
    }

    public function getFormSchema($id){
        $ent = D("Form")->where(['id' => $id])->find();
        Helper::responseJson($ent['json_schema']);
    }

    public function saveFormSchema($id){
        $data = Helper::iJson();
        $ent = D("Form")->where(['id' => $id])->find();
        if($ent){
            $old = $ent['json_schema'];
            $ent['json_schema'] = json_encode($data);
            if($this->isChange($this->normalizeSchemaObject($old), $data)){
                $ent['updated_date'] = time();
            }

            $r = D("Form")->save($ent);
            if($r !== false){
                $this->ajaxReturn(['status' => 1]);
            }
        }

        $this->ajaxReturn(['status' => 0]);

    }

    protected function normalizeSchemaObject($obj){
        $obj_decode = json_decode($obj);
        if(!$obj_decode){
            $new = new \StdClass();
            $new->schema = new \StdClass();
            return json_encode($new);
        }
        else{
            return $obj;
        }
    }

    protected function isChange($old, $new){
        $old_object = json_decode($old);
        $new_object = $new;
        Helper::wrapComponentIllegalProp($old_object->schema, 'x-designable-id', '');
        Helper::wrapComponentIllegalProp($new_object->schema, 'x-designable-id', '');
        return strcmp(json_encode($old_object), json_encode($new_object)) !== 0;
    }

    public function delete(){
        $ids = I('ids');
        $model = new FormModel();
        if(!$ids){
            $this->error('请选择要删除的数据');
        }

        if(!packageConfig('cusform','force_delete')){
            $apply_model = new FormApplyModel();
            $count = $apply_model->where(['form_id'=>['in',$ids]])->count();

            if($count > 0){
                $this->error('该表单存在用户提交数据，禁止删除');
            }
        }

        $r = $model->where(['id'=>['in',$ids]])->setField('deleted',\Qscmf\Lib\DBCont::YES_BOOL_STATUS);

        if ($r===false){
            $this->error($model->getError());
        }
        $this->success('删除成功');
    }
}