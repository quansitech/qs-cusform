import { ISchema } from '@formily/react'
import {
  ValidatorSetter,
} from '@designable/formily-setters'

export const Input: ISchema & { TextArea?: ISchema } = {
  type: 'object',
  properties: {
    default: {
      'x-decorator': 'FormItem',
      'x-component': 'Input',
    },
    'x-validator': {
      type: 'array',
      'x-component': ValidatorSetter,
    },
    'x-component-props': {
      type: 'object',
      properties: {
        allowClear: {
          type: 'boolean',
          'x-decorator': 'FormItem',
          'x-component': 'Switch',
        },
        maxLength: {
          type: 'number',
          'x-decorator': 'FormItem',
          'x-component': 'NumberPicker',
        },
        placeholder: {
          type: 'string',
          'x-decorator': 'FormItem',
          'x-component': 'Input',
        },
      }
    }
  },
}

Input.TextArea = {
  type: 'object',
  properties: {
    'x-component-props': {
      type: 'object',
      properties:{
        maxLength: {
          type: 'number',
          'x-decorator': 'FormItem',
          'x-component': 'NumberPicker',
        },
        placeholder: {
          type: 'string',
          'x-decorator': 'FormItem',
          'x-component': 'Input',
        },
        showCount: {
          'x-decorator': 'FormItem',
          'x-component': 'Switch',
        },
      }
    }

  },
}
