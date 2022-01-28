import React from 'react'
import { Button } from 'antd'

export interface IDemoReaction {
  target?: string,
  fulfill?: {
    state?: {
      [key: string]: string | boolean
    }
  }
}

export interface IDemoSetterProps {
  value?: IDemoReaction
  onChange?: (value: IDemoReaction) => void
}

export const DemoSetter: React.FC<IDemoSetterProps> = (props) => {
  return (
    <>
      <Button onClick={() => props.onChange({ target: '123'})}>demo</Button>
    </>
  )
}
