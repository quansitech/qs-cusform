import React, { useMemo } from 'react'
import { createForm } from '@formily/core'
import { createSchemaField } from '@formily/react'
import {
  FormItem,
  DatePicker,
  Checkbox,
  Cascader,
  Editable,
  Input,
  NumberPicker,
  Switch,
  Password,
  PreviewText,
  Radio,
  Reset,
  Select,
  Space,
  Submit,
  TimePicker,
  Transfer,
  TreeSelect,
  FormGrid,
  FormLayout,
  FormTab,
  FormCollapse,
  ArrayTable,
  ArrayCards,
} from '@formily/antd'
import {Card, Slider, Rate, message, Button} from 'antd'
import {Form, Area, Upload, Text} from "@quansitech/qs-formily"

const form = createForm()

const SchemaField = createSchemaField({
  components: {
    Space,
    FormGrid,
    FormLayout,
    FormTab,
    FormCollapse,
    ArrayTable,
    ArrayCards,
    FormItem,
    DatePicker,
    Checkbox,
    Cascader,
    Editable,
    Input,
    NumberPicker,
    Switch,
    Password,
    PreviewText,
    Radio,
    Reset,
    Select,
    Submit,
    TimePicker,
    Transfer,
    TreeSelect,
    Upload,
    Card,
    Slider,
    Rate,
    Area,
    Text
  },
})

export interface IJsonSchema{
  formProps: Object,
  schema: Object
}

export interface IFormilyWidgetProps{
  jsonSchema: IJsonSchema,
  mode: 'readonly' | 'edit',
  postUrl: string,
  applyId: Number,
}

export const FormilyWidget: React.FC<IFormilyWidgetProps> = (props) => {
  const {
    jsonSchema,
    mode,
    postUrl,
    applyId,
    hideButton,
    returnUrl
  } = props;

  const handleSubmit = async (data) => {
    data.apply_id = applyId;
    const res = await fetch(postUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X_REQUESTED_WITH': 'xmlhttprequest'
        },
        body: JSON.stringify(data)
      }
    );

    const resData = await res.json();
    if(resData.status === 1){
      message.success('保存成功');
    }
    else{
      message.error(resData.info);
    }
  }

  const handleReturn = () => {
    if(returnUrl){
      location.href = returnUrl;
    }
    else{
      window.history.back();
    }
  }

  const submitButton = () => {
    return <>
      {!hideButton && mode === 'edit' && <Submit>提交</Submit>}
    </>
  }

  const returnButton = () => {
    return <>
      {!hideButton  && <Button onClick={handleReturn}>返回</Button>}
    </>
  }

  return (
    <Form form={form} {...jsonSchema.form} onAutoSubmit={handleSubmit}>
      <SchemaField schema={jsonSchema.schema} />
      <div className="formily-button-group">
        {submitButton()}
        {returnButton()}
      </div>

    </Form>
  )
}

FormilyWidget.defaultProps = {
  hideButton: false
}
