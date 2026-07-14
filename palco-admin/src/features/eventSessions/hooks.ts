import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import type { AxiosError } from 'axios'
import { createEventSession, deleteEventSession, listEventSessions, updateEventSession } from './api'
import type { EventSession, EventSessionFormErrorResponse, EventSessionFormValues } from './types'

export function useEventSessions(eventId: number | undefined) {
  return useQuery({
    queryKey: ['admin', 'events', eventId, 'sessions'],
    queryFn: () => listEventSessions(eventId!),
    enabled: eventId !== undefined,
  })
}

export function useCreateEventSession(eventId: number) {
  const queryClient = useQueryClient()

  return useMutation<EventSession, AxiosError<EventSessionFormErrorResponse>, EventSessionFormValues>({
    mutationFn: (values) => createEventSession(eventId, values),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['admin', 'events', eventId, 'sessions'] })
    },
  })
}

export function useUpdateEventSession(eventId: number, id: number) {
  const queryClient = useQueryClient()

  return useMutation<EventSession, AxiosError<EventSessionFormErrorResponse>, EventSessionFormValues>({
    mutationFn: (values) => updateEventSession(id, values),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['admin', 'events', eventId, 'sessions'] })
    },
  })
}

export function useDeleteEventSession(eventId: number) {
  const queryClient = useQueryClient()

  return useMutation({
    mutationFn: deleteEventSession,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['admin', 'events', eventId, 'sessions'] })
    },
  })
}
