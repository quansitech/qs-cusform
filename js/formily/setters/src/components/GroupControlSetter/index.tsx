import { Modal, Button, Menu, Transfer } from 'antd'
import { TextWidget } from '@designable/react'
import { useCurrentNode } from '@designable/react'
import './styles.less'
import React, {useState, useEffect} from "react";
import { IGroupReaction, IGroupValue } from './types'

export interface IGroupControlSetterProps {
  value?: IGroupValue
  onChange?: (value: IGroupReaction) => void
}

const transformDataSource = (node) => {
  const items = [];
  let curNode = node.next;
  while(curNode){
    items.push({
      key: curNode.id,
      title: curNode.props.title
    });
    curNode = curNode.next;
  }
  return items;
}

const serialize = (data) => {
  let str = '';
  data.forEach((item) => {
    str += `'${item.key}':${JSON.stringify(item.targetKeys)},
`
  })

  return `{
    ${str}}`
}

const transformDataToValue = (data, dataSource): IGroupReaction => {
  const target = dataSource.map(item => item.key);
  return {
    target: {
      value: {
        target: `*(${target.join(',')})`,
        fulfill: {
          run: `{{
            const setting = ${serialize(data)}
            
            $target.visible = !setting[$self.value]?.includes($target.props.name)
          }}`
        },
        groupSetting: data
      }
    }

  }
}

const getCurTargetKeys = (menuKey, groupSetting) => {
  const menuTargetArr = groupSetting.filter(item => item.key === menuKey);
  if(menuTargetArr.length > 0 && menuTargetArr[0].targetKeys.length > 0 ){
    return menuTargetArr[0].targetKeys;
  }
  else{
    return [];
  }
}

export const GroupControlSetter: React.FC<IGroupControlSetterProps> = (props) => {
    const [modalVisible, setModalVisible] = useState(false)
    const [ selectedMenu, setSelectedMenu ] = useState();
    const [ groupSetting, setGroupSetting ] = useState()
    const baseNode = useCurrentNode()
    const dataSource = transformDataSource(baseNode)

    useEffect(() => {
      setGroupSetting(props.value?.groupSetting || []);
    }, [props.value])

    const openModal = () => setModalVisible(true)
    const closeModal = () => setModalVisible(false)

    const handleTransferChange = (nextTargetKeys) => {
      setGroupSetting(old => {
        const groupList = old.filter(item => item.key !== selectedMenu)
        groupList.push({ key: selectedMenu, targetKeys: nextTargetKeys});
        return groupList;
      })
    }

    return <>
      <Button block onClick={openModal}>
        <TextWidget>配置</TextWidget>
      </Button>
      <Modal
        title='跳题设置'
        width="50%"
        bodyStyle={{ padding: 0 }}
        visible={modalVisible}
        onCancel={()=> closeModal()}
        onOk={() => {
            props.onChange(transformDataToValue(groupSetting, dataSource));
            closeModal();
          }
        }
      >
        <div className='group-control-setter-body'>
          <Menu mode="vertical"
                style={{
                  width: 200,
                  height: 300,
                  overflowY: 'auto',
                  overflowX: 'hidden'}}
                onSelect={(item) => setSelectedMenu(item.key)}
          >
            { baseNode.props.enum?.map(item => {
              return <Menu.Item key={item.value}><TextWidget>{item.value}</TextWidget></Menu.Item>
            })}
          </Menu>
          <div>
            { selectedMenu &&
              <Transfer
                dataSource={dataSource}
                listStyle={{width: '320px', height: '90%'}}
                titles={['显示', '隐藏']}
                targetKeys={getCurTargetKeys(selectedMenu, groupSetting)}
                onChange={handleTransferChange}
                render={item => item.title}
              />
            }
          </div>
        </div>
      </Modal>
    </>
}
