import React from 'react'
import {UploadProps as FormilyUploadProps, Upload as FormilyUpload} from "@formily/antd"

export interface UploadProps extends FormilyUploadProps {
  oss?: boolean //是否oss上传
}

export const Upload: React.FC<React.PropsWithChildren<UploadProps>> = (props: React.PropsWithChildren<UploadProps>) => {
  const {
    oss,
    ...restProps
  } = props;

  return <FormilyUpload {...restProps}></FormilyUpload>
}


