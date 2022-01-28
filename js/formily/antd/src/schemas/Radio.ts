import { ISchema } from '@formily/react'
import {
  OptionSetter,
  GroupControlSetter,
  DefaultOptionSetter
} from '@designable/formily-setters'

export const Radio: ISchema & { Group?: ISchema } = {
}

Radio.Group = {
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
  },
}
