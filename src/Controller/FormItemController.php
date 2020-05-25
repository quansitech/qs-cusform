<?php
/**
 * Created by PhpStorm.
 * User: Jack
 * Date: 2018/2/11
 * Time: 10:05
 */

namespace CusForm\Controller;

use CusForm\Model\FormItemModel;
use CusForm\Model\FormModel;
use Qscmf\Builder\FormBuilder;
use Gy_Library\DBCont;


class FormItemController extends \Qscmf\Core\QsListController
{
    public function index($id){
        $formModel=new FormModel();
        $form=$formModel->find($id);
        $get_data = I('get.');
        $map = array('form_id'=>$id,'deleted'=> \Qscmf\Lib\DBCont::NO_BOOL_STATUS);
        $model = new FormItemModel();
        $count = $model->getListForCount($map);
        $per_page = C('ADMIN_PER_PAGE_NUM', null, false);
        if($per_page === false){
            $page = new \Gy_Library\GyPage($count);
        }
        else{
            $page = new \Gy_Library\GyPage($count, $per_page);
        }

        $data_list = $model->getListForPage($map, $page->nowPage, $page->listRows, 'sort');

        $builder = new \Qscmf\Builder\ListBuilder();

        $builder = $builder->setMetaTitle('表单项列表-'.$form['title']);

        $builder
            ->setNIDByNode(MODULE_NAME,'Form')
            ->addTopButton('addnew',['href'=>U('add',['form_id'=>$id])])
            ->addTopButton('save')
            ->addTableColumn('title', '表单项标题')
            ->addTableColumn('type', '类型', 'fun', 'CusForm\\Model\\FormItemModel::getItemType(__data_id__)', false)
            ->addTableColumn('sort','排序','text','',true)
            ->addTableColumn('right_button', '操作', 'btn')
            ->addRightButton('delete')
            ->addRightButton('edit',['href'=>U('edit',['form_id'=>$id,'id'=>'__data_id__'])])
            ->setTableDataList($data_list)
            ->setTableDataPage($page->show())
            ->display();
    }

    public function add($form_id){
        if (IS_POST){
            $data=I('post.');

            if ($data['type']==FormItemModel::DESCRIPTION){
                $data['required']=DBCont::NO_BOOL_STATUS;
            }

            $model = new FormItemModel();
            if ($model->addItem($data)!==false){
                $this->success('新增成功',U('index',['id'=>I('form_id')]));
            }else{
                $this->error('新增失败:'.$model->getError(),U('index',['id'=>I('get.form_id')]));
            }
        }else{
            $form=D('Form')->find($form_id);
            $builder=new FormBuilder();
            $builder=$builder->setMetaTitle('新建表单项-'.$form['title'])
                ->setNIDByNode(MODULE_NAME,'Form')
                ->setFormData(['required'=>DBCont::NO_BOOL_STATUS])
                ->addFormItem('form_id','self','','','<input type="hidden" name="form_id" value="'.$form_id.'">')
                ->addFormItem('title','text','标题')
                ->addFormItem('type','select','类型','',FormItemModel::getItemTypeList())
                ->addFormItem('options','text','选项','格式为xxx,xx,xx')
                ->addFormItem('tips','text','提示','')
                ->addFormItem('sort','text','排序')
                ->addFormItem('required','select','是否必填','',DBCont::getBoolStatusList());
            foreach (FormItemModel::OTHER_LIMIT_LIST as $key=>$item) {
                $builder=$builder->addFormItem($key,'text',$item['title']);
            }
            $builder->display();
        }
    }
    public function edit($form_id,$id){
        if (IS_POST){
            $data=I('post.');
            $id=I('get.id');
            $formItem=D('FormItem')->find($id);
            $data=array_merge($formItem,$data);

            if ($data['type']==FormItemModel::DESCRIPTION){
                $data['required']=DBCont::NO_BOOL_STATUS;
            }

            $model = new FormItemModel();
            if ($model->editItem($data)!==false){
                $this->success('保存成功',U('index',['id'=>I('form_id')]));
            }else{
                $this->error('保存失败'.$model->getError(),U('index',['form_id'=>I('form_id')]));
            }
        }else{
            $model = new FormItemModel();
            $form=D('Form')->find($form_id);
            $formItem=$model->find($id);
            $formItem['other_limit']=$formItem['other_limit']?$formItem['other_limit']:'[]';
            $formItem=array_merge($formItem,json_decode($formItem['other_limit'],true));
            $builder=new FormBuilder();
            $builder=$builder->setMetaTitle('编辑表单项-'.$form['title'])
                ->setFormData($formItem)
                ->setNIDByNode(MODULE_NAME,'Form')
                ->addFormItem('form_id','self','','','<input type="hidden" name="form_id" value="'.$form_id.'">')
                ->addFormItem('title','text','标题')
                ->addFormItem('type','select','类型','',FormItemModel::getItemTypeList())
                ->addFormItem('options','text','选项','格式为xxx,xx,xx')
                ->addFormItem('sort','text','排序')
                ->addFormItem('tips','text','提示','')
                ->addFormItem('required','select','是否必填','',DBCont::getBoolStatusList());
            foreach (FormItemModel::OTHER_LIMIT_LIST as $key=>$item) {
                $builder=$builder->addFormItem($key,'text',$item['title']);
            }
            $builder->display();
        }
    }

    public function delete(){
        $model = new FormItemModel();
        $ids = I('ids');
        if(!$ids){
            $this->error('请选择要删除的数据');
        }
        $r = $model->where(['id'=>['in',$ids]])->setField('deleted',\Qscmf\Lib\DBCont::YES_BOOL_STATUS);
        if ($r===false){
            $this->error($model->getError());
        }
        $this->success('删除成功');
    }
    public function save(){
        if(IS_POST){
            $data = I('post.');
            foreach($data['id'] as $k=>$v){
                $save_data['sort'] = $data['sort'][$k];
                D('FormItem')->where('id=' . $v)->save($save_data);
            }
            $this->success('保存成功');
        }
    }
}