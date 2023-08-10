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

  const oss = Config.upload?.oss || false;
  const ossDefaultUrl = Config.urlPrefix + '/extends/aliyunOss/policyGet/type/file';
  const uploadDefaultUrl = Config.urlPrefix + '/api/upload/uploadFile';
  const action = Config.upload?.action || oss ? ossDefaultUrl : uploadDefaultUrl;
  const listType = Config.upload?.listType || 'picture-circle';

  Upload.Resource = createResource(
    {
      icon: 'UploadSource',
      elements: [
        {
          componentName: 'Field',
          props: {
            type: 'Array<object>',
            title: 'Upload',
            'x-decorator': 'FormItem',
            'x-component': 'Upload',
            'x-component-props': {
              textContent: 'Upload',
              oss,
              accept: 'image/*,.doc,.docx,.xls,.xlsx,.pdf,.ppt,.txt,.rar',
              action,
              listType
            },
          },
        },
      ],
    },
  )
}

addInitComponent(Upload);




