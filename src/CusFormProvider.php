<?php

namespace CusForm;

use Bootstrap\LaravelProvider;
use Bootstrap\Provider;
use Bootstrap\RegisterContainer;
use CusForm\Controller\FormController;
use CusForm\Controller\FormItemController;


class CusFormProvider implements Provider,LaravelProvider
{

    public function register()
    {
        RegisterContainer::registerController('admin','Form',FormController::class);
        RegisterContainer::registerController('admin','FormItem',FormItemController::class);

        RegisterContainer::registerSymLink(WWW_DIR . '/Public/cusform', __DIR__ . '/../js/dist');
    }

    public function registerLara()
    {
        RegisterContainer::registerMigration(__DIR__.'/migrations');
    }
}