import { useQuery } from '@tanstack/react-query'
import { listEvents } from './api'
import type { EventFilters } from './types'

export function useEvents(filters: EventFilters) {
  return useQuery({
    queryKey: ['events', filters],
    queryFn: () => listEvents(filters),
    enabled: filters.cityIds.length > 0,
  })
}