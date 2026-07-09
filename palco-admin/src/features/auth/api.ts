import { apiClient } from '../../shared/api/client'
import type { AuthUser, LoginPayload, LoginResponse } from './types'

export function login(payload: LoginPayload) {
  return apiClient.post<LoginResponse>('/login', payload).then((response) => response.data)
}

export function getCurrentUser() {
  return apiClient.get<AuthUser>('/user').then((response) => response.data)
}

export function logout() {
  return apiClient.post('/logout')
}