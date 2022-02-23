<?php

namespace CusForm;

use Bootstrap\LaravelProvider;
use Bootstrap\Provider;
use Bootstrap\RegisterContainer;
use CusForm\Controller\AreaController;
use CusForm\Controller\FormApplyController;
use CusForm\Controller\FormController;
use CusForm\FormItem\CheckBoxText\CheckBoxText;
use CusForm\FormItem\RadioText\RadioText;


class CusFormProvider implements Provider,LaravelProvider
{

    public function register()
    {
        RegisterContainer::registerController('admin','Form',FormController::class);
        RegisterContainer::registerController('extends','Area',AreaController::class);
        RegisterContainer::registerController('admin','FormApply',FormApplyController::class);

        RegisterContainer::registerSymLink(WWW_DIR . '/Public/cusform', __DIR__ . '/../js/formily/antd/build');
    }

    public function registerLara()
    {
        RegisterContainer::registerMigration(__DIR__.'/migrations');
    }
}