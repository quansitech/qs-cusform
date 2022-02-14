<?php
namespace CusForm\Controller;

use Think\Controller;

class AreaController extends Controller{

    public function getAreaById(){
        $id = I('get.id');
        $value = I('get.value');
        $limit_level = I('get.limit_level', 3);

        $parm = ['id' => $id, 'value' => $value, 'limit_level' => $limit_level];
        $key = json_encode($parm);
        if($cache_data = S($key)){
            $this->ajaxReturn($cache_data);
        }

        if($value){
            list($area_list, $default_value) = $this->getChildren($value, $limit_level, [], []);
            $default_value = array_reverse($default_value);
        }
        else{
            $area_list = $this->getAreaList($id, $limit_level);
        }

        $res = [
            'default_value' => $default_value,
            'list' => $area_list
        ];

        S($key, $res, 86400);

        $this->ajaxReturn($res);
    }


    protected function getAreaList($id, $limit_level){
        if($id){
            $map['upid'] = $id;

            $area_list = D("Area")->where($map)->field("id as `value`,case when cname1!='' then cname1 else cname end as label, level")->select();
        }
        else{
            $map['level'] = 1;

            $area_list = D('Area')->where($map)->field("id as `value`,case when cname1!='' then cname1 else cname end as label, level")->select();
        }
        foreach($area_list as &$vo){
            $vo['isLeaf'] = $vo['level'] < $limit_level ? false: true;
        }
        return $area_list;
    }

    protected function getChildren($value, $limit_level, $children, $default_value){
        $ent = D("Area")->where(['id' => $value])->find();
        $children_ents = D("Area")->where(['upid' => $ent['upid']])->select();

        $res_children = [];
        foreach($children_ents as $vo){
            $tmp = [
                'value' => $vo['id'],
                'label' => $vo['cname1'] ?: $vo['cname'],
                'level' => $vo['level'],
                'isLeaf' => $vo['level'] < $limit_level ? false: true,
            ];
            if($value === $vo['id'] && count($children) > 0){
                $tmp['children'] = $children;
            }
            array_push($res_children, $tmp);
        }

        $pent = D("Area")->where(['id' => $ent['upid']])->find();
        array_push($default_value, $value);
        if($pent['level'] > 0){
            return $this->getChildren($pent['id'], $limit_level, $res_children, $default_value);
        }
        else{
            return [$res_children, $default_value];
        }
    }
}