import { observable, define, action } from '@formily/reactive'

export interface IConfigProps{
  area?: IAreaDefaultProps,
  upload?: IUploadDefaultProps,
  urlPrefix?: string
}

export interface IAreaDefaultProps{
  url?: string
}

export interface IUploadDefaultProps{
  oss?: boolean,
  action?: string,
  listType?: string
}

class ConfigModal{

  area: IAreaDefaultProps
  urlPrefix: string
  formId: Number
  upload: IUploadDefaultProps
  pathPrefix: string


  constructor() {
    this.pathPrefix = '/Public/cusform';
    define(this, {
      area: observable.ref,
      urlPrefix: observable.ref,
      formId: observable.ref,
      setJsOptions: action,
      setArea: action,
      setFormId: action
    })
  }



  setJsOptions(props: IConfigProps){
    this.urlPrefix = props.urlPrefix;
    this.setArea(props.area);
    this.setUpload(props.upload);
  }

  setArea(props: IAreaDefaultProps){
    this.area = props;
  }

  setFormId(id: Number){
    this.formId = id;
  }

  setUpload(props: IUploadDefaultProps){
    this.upload = props
  }
}

export const Config = new ConfigModal();



