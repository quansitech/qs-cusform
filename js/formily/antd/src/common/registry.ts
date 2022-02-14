const init: IInitComponent[] = [];

export interface IInitComponent{
  init: () => void
}

export const  addInitComponent = (component: IInitComponent) => {
    init.push(component);
}

export const initComponent = () => {
  init.forEach((component: IInitComponent) : void => {
    component.init();
  })
}
