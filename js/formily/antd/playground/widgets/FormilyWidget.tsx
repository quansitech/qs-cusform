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
import {Card, Slider, Rate, message} from 'antd'
import {Form, Area, Upload} from "@quansitech/qs-formily"

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
    Area
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
  } = props;

  const handleSubmit = async (data) => {
    data.apply_id = applyId;
    const res = await fetch(postUrl, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
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

  return (
    <Form form={form} {...jsonSchema.form} onAutoSubmit={handleSubmit}>
      <SchemaField schema={jsonSchema.schema} />
      {mode === 'edit' && <Submit block size="large">提交</Submit>}
    </Form>
  )
}
