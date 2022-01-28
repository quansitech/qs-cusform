export interface IGroupReaction {
  target?: {
    value?: {
      target?: string,
      fulfill?: {
        run?: string
      },
      groupSetting?: any
    }
  }

}

export interface IGroupValue {
  target?: string,
  fulfill?: {
    run?: string
  },
  groupSetting?: any
}
