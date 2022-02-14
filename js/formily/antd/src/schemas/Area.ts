import { ISchema } from '@formily/react'

export const Area: ISchema = {
  type: 'object',
  properties: {
    'x-component-props': {
      type: 'object',
      properties: {
        allowClear: {
          type: 'boolean',
          'x-decorator': 'FormItem',
          'x-component': 'Switch',
        },
        placeholder: {
          type: 'string',
          'x-decorator': 'FormItem',
          'x-component': 'Input',
        },
        needAddress: {
          type: 'boolean',
          'x-decorator': 'FormItem',
          'x-component': 'Switch',
        },
      }
    }
  }
}
