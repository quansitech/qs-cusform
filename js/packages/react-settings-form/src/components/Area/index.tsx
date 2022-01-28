import {Cascader} from "antd";
import {connect} from "@formily/react";
import React from "react";

const InternalArea = (props) => {
  const options = [
    {
      value: 'zhejiang',
      label: 'Zhejiang',
      children: [
        {
          value: 'hangzhou',
          label: 'Hangzhou',
          children: [
            {
              value: 'xihu',
              label: 'West Lake',
            },
          ],
        },
      ],
    },
    {
      value: 'jiangsu',
      label: 'Jiangsu',
      children: [
        {
          value: 'nanjing',
          label: 'Nanjing',
          children: [
            {
              value: 'zhonghuamen',
              label: 'Zhong Hua Men',
            },
          ],
        },
      ],
    },
  ];

  function onChange(value) {
    console.log(value);
  }

  return <Cascader options={options} onChange={onChange} placeholder="Please select" />
}

export const Area = connect(
  InternalArea
)
