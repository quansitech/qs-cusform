import { ISchema } from '@formily/react'
import {
  OptionSetter,
  DefaultOptionSetter
} from '@designable/formily-setters'

export const Checkbox: ISchema & { Group?: ISchema } = {
}

Checkbox.Group = {
  type: 'object',
  properties: {
    enum: {
      'x-decorator': 'FormItem',
      'x-component': OptionSetter,
    },
    default: {
      'x-decorator': 'FormItem',
      'x-component': DefaultOptionSetter,
      'x-component-props': {
        mode: 'multiple'
      }
    },
  },
}
