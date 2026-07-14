import { apiClient } from '../../shared/api/client'
import type { EventSession, EventSessionFormValues, PricingType } from './types'

type EventSessionApiResponse = {
  id: number
  start_at: string
  end_at: string | null
  pricing_type: string
  price: string | null
  capacity: number | null
}

function toEventSession(raw: EventSessionApiResponse): EventSession {
  return {
    id: raw.id,
    startAt: raw.start_at,
    endAt: raw.end_at,
    pricingType: raw.pricing_type as PricingType,
    price: raw.price,
    capacity: raw.capacity,
  }
}

function toPayload(values: EventSessionFormValues) {
  return {
    start_at: values.startAt,
    end_at: values.endAt || null,
    pricing_type: values.pricingType,
    price: values.pricingType === 'fixed' ? values.price : null,
    capacity: values.capacity ? Number(values.capacity) : null,
  }
}

export function listEventSessions(eventId: number) {
  return apiClient
    .get<{ data: EventSessionApiResponse[] }>(`/admin/events/${eventId}/sessions`)
    .then((response) => response.data.data.map(toEventSession))
}

export function createEventSession(eventId: number, values: EventSessionFormValues) {
  return apiClient
    .post<{ data: EventSessionApiResponse }>(`/admin/events/${eventId}/sessions`, toPayload(values))
    .then((response) => toEventSession(response.data.data))
}

export function updateEventSession(id: number, values: EventSessionFormValues) {
  return apiClient
    .put<{ data: EventSessionApiResponse }>(`/admin/event-sessions/${id}`, toPayload(values))
    .then((response) => toEventSession(response.data.data))
}

export function deleteEventSession(id: number) {
  return apiClient.delete(`/admin/event-sessions/${id}`)
}
