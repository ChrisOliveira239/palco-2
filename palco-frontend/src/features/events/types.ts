import type { City } from '../cities/types'

export type Event = {
  id: number
  title: string
  synopsis: string | null
  type: string
  venueName: string
  city: City
  nextSessionAt: string | null
  ticketUrl: string | null
  posterPath: string | null
}

export type EventFilters = {
  cityIds: number[]
  page?: number
}