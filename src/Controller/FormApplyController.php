<?php
namespace CusForm\Controller;

use CusForm\CusForm;
use CusForm\Helper;
use Qscmf\Core\QsController;

class FormApplyController extends QsController{

    public function edit(){
        $data = Helper::iJson();
        $apply_id = (int)$data->apply_id;
        list($r, $errMsg) = CusForm::getInstance()->editApply($apply_id, $data);
        if($r === false){
            $this->ajaxReturn(['status' => 0, 'info' => $errMsg]);
        }
        else {
            $this->ajaxReturn(['status' => 1, 'info' => '成功']);
        }

    }
}