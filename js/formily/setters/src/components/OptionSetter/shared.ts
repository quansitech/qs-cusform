import { IDataSourceItem } from './types'

export const transformValueToData = (value: IDataSourceItem[]): string[] => {
  return value.map(i => i.label)
}

export const transformDataToValue = (data: string[]): IDataSourceItem[] => {
  return data.map(i => {
    return {
      label: i,
      value: i
    }
  })
}
