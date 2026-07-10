import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query'
import type { AxiosError } from 'axios'
import { createEvent, getEvent, listEvents, updateEvent } from './api'
import type { Event, EventFilters, EventFormErrorResponse, EventFormValues } from './types'

export function useAdminEvents(filters: EventFilters) {
  return useQuery({
    queryKey: ['admin', 'events', filters],
    queryFn: () => listEvents(filters),
  })
}

export function useEvent(id: number | undefined) {
  return useQuery({
    queryKey: ['admin', 'events', id],
    // id só é undefined quando enabled é false — TanStack Query nunca chama
    // queryFn nesse caso, então o "!" abaixo é seguro.
    queryFn: () => getEvent(id!),
    enabled: id !== undefined,
  })
}

export function useCreateEvent() {
  const queryClient = useQueryClient()

  return useMutation<Event, AxiosError<EventFormErrorResponse>, EventFormValues>({
    mutationFn: createEvent,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['admin', 'events'] })
    },
  })
}

export function useUpdateEvent(id: number) {
  const queryClient = useQueryClient()

  return useMutation<Event, AxiosError<EventFormErrorResponse>, EventFormValues>({
    mutationFn: (values) => updateEvent(id, values),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['admin', 'events'] })
    },
  })
}