import React from 'react'
import { Upload as FormilyUpload, UploadProps as FormilyUploadProps } from '@quansitech/qs-formily'
import { createBehavior, createResource } from '@designable/core'
import { DnFC } from '@designable/react'
import { createFieldSchema } from '../Field'
import { AllSchemas } from '../../schemas'
import { AllLocales } from '../../locales'
import { addInitComponent, IInitComponent } from "../../common/registry"
import { Config } from '../../models'

export const Upload: DnFC<React.FC<FormilyUploadProps>> & IInitComponent=
  FormilyUpload

Upload.init = () => {
  Upload.Behavior = createBehavior(
    {
      name: 'Upload',
      extends: ['Field'],
      selector: (node) => node.props['x-component'] === 'Upload',
      designerProps: {
        propsSchema: createFieldSchema(AllSchemas.Upload),
      },
      designerLocales: AllLocales.Upload,
    },
  )

  const uploadTo = Config.upload?.uploadTo || 'server';
  const hashCheck = Config.upload?.hashCheck || true;
  const osDefaultUrl = Config.urlPrefix + '/extends/ObjectStorage/policyGet';
  const uploadDefaultUrl = Config.urlPrefix + '/api/upload/upload?cate=file';
  const action = Config.upload?.action || uploadTo === 'server' ? uploadDefaultUrl : osDefaultUrl;

  Upload.Resource = createResource(
    {
      icon: 'UploadSource',
      elements: [
        {
          componentName: 'Field',
          props: {
            type: 'Array<object>',
            title: 'Upload',
            'x-validator': `{{(value, rule)=> {
                var error = false;
                value?.forEach(item => {
                  if(item.status === 'uploading'){
                    error = true;
                  }
                });
                return error ? {type: 'error',message: '文件上传中'} : '';
            }}}`,
            'x-decorator': 'FormItem',
            'x-component': 'Upload',
            'x-component-props': {
              textContent: 'Upload',
              uploadTo,
              hashCheck,
              accept: 'image/*,.doc,.docx,.xls,.xlsx,.pdf,.ppt,.txt,.rar',
              action
            },
          },
        },
      ],
    },
  )
}

addInitComponent(Upload);




