import { useQuery } from '@tanstack/react-query'
import { getInterestedCities, listCities } from './api'

export function useCities(search?: string) {
  return useQuery({
    queryKey: ['cities', search],
    queryFn: () => listCities(search),
  })
}

export function useInterestedCities() {
  return useQuery({
    queryKey: ['cities', 'interested'],
    queryFn: getInterestedCities,
  })
}