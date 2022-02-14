import { ISchema } from '@formily/react'

export const Upload: ISchema = {
  type: 'object',
  properties: {
    'x-component-props': {
      type: 'object',
      properties: {
        maxCount: {
          type: 'number',
          'x-decorator': 'FormItem',
          'x-component': 'NumberPicker',
        }
      }
    }
  }
}

// Upload.Dragger = Upload
