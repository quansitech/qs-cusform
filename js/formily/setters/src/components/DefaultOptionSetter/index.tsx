import {Select} from 'antd'
import { useCurrentNode } from '@designable/react'
import React from "react";
import { observer } from '@formily/reactive-react'

export interface IDefaultOptionSetterProps {
  value?: string
  onChange?: (value: string) => void,
  mode?: 'multiple' | 'tags';
}

const { Option } = Select;

export const DefaultOptionSetter: React.FC<IDefaultOptionSetterProps> = observer((props) => {
  const baseNode = useCurrentNode()

  return <Select mode={props.mode} defaultValue={props.value} onChange={(val) => {props.onChange(val);}} allowClear onClear={() => props.onChange(null)}>
      { baseNode?.props?.enum?.map(item => {
          return <Option key={item.value} value={item.value}>{item.value}</Option>
        })}
    </Select>
})
