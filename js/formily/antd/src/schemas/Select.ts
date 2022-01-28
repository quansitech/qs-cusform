import { ISchema } from '@formily/react'
import {
  OptionSetter,
  GroupControlSetter,
  DefaultOptionSetter
} from '@designable/formily-setters'

export const Select: ISchema = {
  type: 'object',
  properties: {
    enum: {
      'x-decorator': 'FormItem',
      'x-component': OptionSetter,
    },
    'x-reactions': {
      'x-decorator': 'FormItem',
      'x-component': GroupControlSetter,
    },
    default: {
      'x-decorator': 'FormItem',
      'x-component': DefaultOptionSetter
    },
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
        }
      }
    }
  }
}
