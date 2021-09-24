<?php

namespace CusForm\FormItem\RadioText;

use Illuminate\Support\Str;
use Qscmf\Builder\FormType\FormType;
use Think\View;

class RadioText implements FormType
{

    function build(array $form_type)
    {
        $view = new View();

        if ($form_type['value']){
            $form_type['value']=json_decode(htmlspecialchars_decode($form_type['value']),true);
        }
        $view->assign('form', $form_type);
        $view->assign('gid',md5(Str::uuid()));
        $content = $view->fetch(__DIR__ . '/radio_text.html');
        return $content;
    }
}