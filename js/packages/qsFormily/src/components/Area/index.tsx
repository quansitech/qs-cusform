import {Cascader, Input} from "antd";
import React from "react";
import {
  isEmpty
} from '@formily/shared'
import { isFn } from "@designable/shared"
import './index.less';

const TextArea = Input.TextArea;

export const Area = (props) => {
  const {
    placeholder,
    allowClear,
    needAddress,
    url
  } = props;

  const level = 3;
  const defaultValue = {
    value: '',
    address: ''
  };
  const onChange = props.onChange;

  const [options, setOptions] = React.useState([]);
  const [innerValue, setInnerValue] = React.useState([]);
  const [innerAddress, setInnerAddress] = React.useState(defaultValue.address);

  React.useEffect(() => {
    const init = async () => {
      const innerUrl = defaultValue.value ? `${url}?value=${defaultValue.value}&limit_level=${level}` : url;
      const res = await fetch(innerUrl);
      const data = await res.json();
      setOptions(data.list);
      setInnerValue(data.default_value || []);
    }
    init();
  }, []);


  const loadData = async selectedOptions => {

    const targetOption = selectedOptions[selectedOptions.length - 1];
    if(targetOption.level >= level || targetOption.children){
      return
    }

    targetOption.loading = true;

    const res = await fetch(`${url}?id=${targetOption.value}&limit_level=${level}`);
    const data = await res.json();

    targetOption.loading = false;
    targetOption.children = data.list;
    setOptions([...options]);
  };

  function getValue(value, address){

    let lastVal;
    if(!Array.isArray(value)){
      lastVal = '';
    }
    else{
      lastVal = value[value.length - 1];
    }

    return {
      value: lastVal,
      address,
      getCurrentContent: () => {
        return {
          hasText: () => {
            return needAddress ? !isEmpty(lastVal) && !isEmpty(address) : !isEmpty(lastVal);
          }
        }
      }
    }
  }

  function handleCascaderChange(value) {
    setInnerValue(value);
    if(onChange){
      onChange(getValue(value, innerAddress))
    }
  }

  function handleAddressChange(e){
    setInnerAddress(e.target.value);
    if(onChange){
      onChange(getValue(innerValue, e.target.value))
    }
  }

  return <>
    <Cascader allowClear={allowClear || false} value={innerValue} options={options} onChange={handleCascaderChange} loadData={loadData} changeOnSelect  placeholder={placeholder} />
    {needAddress && <TextArea className='customform-area-textarea' value={innerAddress} onChange={handleAddressChange} allowClear={allowClear} />}
  </>
}

Area.defaultProps = {
  url: '/extends/area/getAreaById'
}
