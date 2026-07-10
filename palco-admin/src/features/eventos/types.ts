import type { City } from '../cities/types'

export type EventStatus = 'active' | 'inactive' | 'all'

export type EventType = 'show' | 'oficina' | 'exposicao'

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
  active: boolean
  sessionsCount: number | null
}

export type EventFilters = {
  search?: string
  cityId?: number
  status?: EventStatus
  page?: number
}

export type EventFormValues = {
  title: string
  synopsis: string
  type: EventType
  venueName: string
  cityId: number | undefined
  ticketUrl: string
}

export type EventFormErrorResponse = {
  message: string
  errors?: Record<string, string[]>
}