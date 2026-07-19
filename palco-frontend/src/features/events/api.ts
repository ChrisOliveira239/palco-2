import { apiClient } from '../../shared/api/client'
import type { PaginatedResponse } from '../../shared/api/types'
import type { City } from '../cities/types'
import type { Event, EventFilters } from './types'

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
  }
}

export function listEvents(filters: EventFilters) {
  return apiClient
    .get<PaginatedResponse<EventApiResponse>>('/events', {
      params: {
        city_ids: filters.cityIds,
        page: filters.page,
      },
    })
    .then((response) => ({
      data: response.data.data.map(toEvent),
      meta: response.data.meta,
    }))
}