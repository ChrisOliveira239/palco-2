import { useQuery } from '@tanstack/react-query'
import { listCities } from './api'

export function useCities(search?: string) {
  return useQuery({
    queryKey: ['cities', search],
    queryFn: () => listCities(search),
  })
}