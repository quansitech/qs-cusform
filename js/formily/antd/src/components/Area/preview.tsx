import React from 'react'
import { createBehavior, createResource } from '@designable/core'
import { Area as formilyArea } from '@quansitech/qs-formily'
import { createFieldSchema } from '../Field'
import { AllSchemas } from '../../schemas'
import { AllLocales } from '../../locales'

import { Config } from '../../models'
import { addInitComponent } from "../../common/registry"

export const Area = formilyArea

Area.init = () => {
  Area.Behavior = createBehavior({
    name: 'Area',
    extends: ['Field'],
    selector: (node) => node.props['x-component'] === 'Area',
    designerProps: {
      propsSchema: createFieldSchema(AllSchemas.Area),
    },
    designerLocales: AllLocales.Area,
  })

  Area.Resource = createResource({
    icon: 'AreaSource',
    elements: [
      {
        componentName: 'Field',
        props: {
          type: 'Array<string | number>',
          title: 'Area',
          'x-decorator': 'FormItem',
          'x-component': 'Area',
          'x-component-props': {
            needAddress: true,
            url: Config.area?.url || Config.urlPrefix + '/extends/area/getAreaById'
          }
        },
      },
    ],
  })
}

addInitComponent(Area);


