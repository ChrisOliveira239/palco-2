import { apiClient } from '../../shared/api/client'
import type { City } from './types'

export function listCities(search?: string) {
  return apiClient
    .get<{ data: City[] }>('/cities', { params: { search } })
    .then((response) => response.data.data)
}

export function getInterestedCities() {
  return apiClient.get<{ data: City[] }>('/user/cities').then((response) => response.data.data)
}