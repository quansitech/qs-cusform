import { Engine } from '@designable/core'
import {
  transformToSchema,
  transformToTreeNode,
} from '@designable/formily-transformer'
import { message } from 'antd'
import { Config } from '../../src/models'


const fetchSchema = async () => {
  const urlPrefix = !!Config.urlPrefix ? Config.urlPrefix : '';
  const url = urlPrefix + '/api/form/getFormSchema?id=' + Config.formId;
  const res = await fetch(url);
  const data = await res.json();
  return data;
}

const postSchema = async (designer) => {
  const urlPrefix = !!Config.urlPrefix ? Config.urlPrefix : '';
  const url = urlPrefix + '/admin/form/saveFormSchema?id=' + Config.formId;
  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(transformToSchema(designer.getCurrentTree()))
  });
  const data = await res.json();
  return data;
}

export const saveSchema = async (designer: Engine) => {
  const data = await postSchema(designer);
  if(data.status === 1){
    message.success('保存成功');
  }
  else{
    message.success('保存出错');
  }
}

export const loadInitialSchema = async (designer: Engine) => {
  try {
    designer.setCurrentTree(
      transformToTreeNode(await fetchSchema())
    )
  } catch {}
}
