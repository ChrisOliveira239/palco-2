import { useQuery } from '@tanstack/react-query'
import { listEvents } from './api'
import type { EventFilters } from './types'

export function useAdminEvents(filters: EventFilters) {
  return useQuery({
    queryKey: ['admin', 'events', filters],
    queryFn: () => listEvents(filters),
  })
}