<?php

namespace CusForm;

use Bootstrap\LaravelProvider;
use Bootstrap\Provider;
use Bootstrap\RegisterContainer;
use CusForm\Controller\FormController;
use CusForm\Controller\FormItemController;
use CusForm\FormItem\CheckBoxText\CheckBoxText;
use CusForm\FormItem\RadioText\RadioText;
use CusForm\Model\FormItemModel;


class CusFormProvider implements Provider,LaravelProvider
{

    public function register()
    {
        RegisterContainer::registerController('admin','Form',FormController::class);
        RegisterContainer::registerController('admin','FormItem',FormItemController::class);

        RegisterContainer::registerSymLink(WWW_DIR . '/Public/cusform', __DIR__ . '/../js/formily/antd/build');

        RegisterContainer::registerFormItem(FormItemModel::RADIO_TEXT,RadioText::class);
        RegisterContainer::registerFormItem(FormItemModel::CHECKBOX_TEXT,CheckBoxText::class);
    }

    public function registerLara()
    {
        RegisterContainer::registerMigration(__DIR__.'/migrations');
    }
}