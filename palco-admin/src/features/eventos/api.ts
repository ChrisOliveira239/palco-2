import { apiClient } from '../../shared/api/client'
import type { PaginatedResponse } from '../../shared/api/types'
import type { City } from '../cities/types'
import type { Event, EventFilters, EventFormValues } from './types'

type EventApiResponse = {
  id: number
  title: string
  synopsis: string | null
  type: string
  venue_name: string
  city: City
  next_session_at: string | null
  ticket_url: string | null
  poster_path: string | null
  active: boolean
  sessions_count: number | null
}

function toEvent(raw: EventApiResponse): Event {
  return {
    id: raw.id,
    title: raw.title,
    synopsis: raw.synopsis,
    type: raw.type,
    venueName: raw.venue_name,
    city: raw.city,
    nextSessionAt: raw.next_session_at,
    ticketUrl: raw.ticket_url,
    posterPath: raw.poster_path,
    active: raw.active,
    sessionsCount: raw.sessions_count,
  }
}

export function listEvents(filters: EventFilters) {
  return apiClient
    .get<PaginatedResponse<EventApiResponse>>('/admin/events', {
      params: {
        search: filters.search,
        city_id: filters.cityId,
        status: filters.status,
        page: filters.page,
      },
    })
    .then((response) => ({
      data: response.data.data.map(toEvent),
      meta: response.data.meta,
    }))
}

export function getEvent(id: number) {
  return apiClient
    .get<{ data: EventApiResponse }>(`/admin/events/${id}`)
    .then((response) => toEvent(response.data.data))
}

function toPayload(values: EventFormValues) {
  return {
    title: values.title,
    synopsis: values.synopsis || null,
    type: values.type,
    venue_name: values.venueName,
    city_id: values.cityId,
    ticket_url: values.ticketUrl || null,
  }
}

export function createEvent(values: EventFormValues) {
  return apiClient
    .post<{ data: EventApiResponse }>('/admin/events', toPayload(values))
    .then((response) => toEvent(response.data.data))
}

export function updateEvent(id: number, values: EventFormValues) {
  return apiClient
    .put<{ data: EventApiResponse }>(`/admin/events/${id}`, toPayload(values))
    .then((response) => toEvent(response.data.data))
}