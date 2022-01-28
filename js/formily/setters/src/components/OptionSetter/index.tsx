import React, { Fragment, useMemo, useState } from 'react'
import cls from 'classnames'
import { Modal, Button } from 'antd'
import {
  FormItem,
  Editable,
  Input,
  ArrayItems,
  Space,
  FormButtonGroup,
  Submit
} from '@formily/antd'
import { createForm } from '@formily/core'
import { observer } from '@formily/reactive-react'
import { usePrefix, useTheme, TextWidget } from '@designable/react'
import { createSchemaField, FormProvider } from '@formily/react'
import { transformDataToValue, transformValueToData } from './shared'
import { IDataSourceItem } from './types'
import './styles.less'


export interface IOptioneSetterProps {
  className?: string
  onChange: (dataSource: IDataSourceItem[]) => void
  value: IDataSourceItem[]
}

const schema = {
  type: 'object',
  properties: {
    options: {
      type: 'array',
      'x-component': 'ArrayItems',
      items: {
        type: 'void',
        'x-component': 'Space',
        properties: {
          sort: {
            type: 'void',
            'x-decorator': 'FormItem',
            'x-component': 'ArrayItems.SortHandle',
          },
          input: {
            type: 'string',
            'x-decorator': 'FormItem',
            'x-component': 'Input',
          },
          remove: {
            type: 'void',
            'x-decorator': 'FormItem',
            'x-component': 'ArrayItems.Remove',
          },
        },
      },
      properties: {
        add: {
          type: 'void',
          title: '添加选项',
          'x-component': 'ArrayItems.Addition',
        },
      },
    },
  },
}

const SchemaField = createSchemaField({
  components: {
    FormItem,
    Editable,
    Space,
    Input,
    ArrayItems,
  },
})



export const OptionSetter: React.FC<IOptioneSetterProps> = observer(
  (props) => {
    const {
      className,
      value,
      onChange
    } = props
    const theme = useTheme()
    const prefix = usePrefix('option-setter')
    const [modalVisible, setModalVisible] = useState(false)

    const form = useMemo(
        () => createForm({
          values: {
            options: transformValueToData(value || [])
          }
        })
    , [value])

    const openModal = () => setModalVisible(true)
    const closeModal = () => setModalVisible(false)

    const handleSubmit = (val) => {
      onChange(transformDataToValue(val.options));
      closeModal()
    }

    return (
      <Fragment>
        <Button block onClick={openModal}>
          <TextWidget token="SettingComponents.DataSourceSetter.configureDataSource" />
        </Button>
        <Modal
          title={
            <TextWidget token="SettingComponents.DataSourceSetter.configureDataSource" />
          }
          width="17%"
          bodyStyle={{ padding: 10 }}
          transitionName=""
          maskTransitionName=""
          visible={modalVisible}
          footer={null}
        >
          <div
            className={`${cls(prefix, className)} ${prefix + '-' + theme} ${
              prefix + '-layout'
            }`}
          >
            <FormProvider form={form}>
              <SchemaField schema={schema} />
              <FormButtonGroup align='right'>
                <Button onClick={() => closeModal()}>取消</Button>
                <Submit onSubmit={handleSubmit}>提交</Submit>
              </FormButtonGroup>
            </FormProvider>
          </div>
        </Modal>
      </Fragment>
    )
  }
)
