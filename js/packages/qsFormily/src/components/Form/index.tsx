import React from 'react'
import { FormProps as FomilyFormProps, Form as FormilyForm  } from '@formily/antd'

export type FormLayoutType = 'vertical' | 'horizontal'

export interface FormProps extends FomilyFormProps {
  formLayout?: FormLayoutType
}

const mapProps = {
  vertical: {
    layout: 'vertical',
    labelCol: 24,
    wrapperCol: 24
  },
  horizontal:{
    layout: 'horizontal',
    labelCol: 6,
    wrapperCol: 18
  }
}

export const Form: React.FC<FormProps> = ({formLayout, ...props}: FormProps) => {

  const formProps = {...props, ...mapProps[formLayout]};

  return <FormilyForm {...formProps}></FormilyForm>
}

Form.defaultProps = {
  formLayout: 'horizontal',
}

export default Form
