import { useMutation, useQuery } from '@tanstack/react-query'
import type { AxiosError } from 'axios'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../../app/AuthContext'
import { getCurrentUser, login, logout as logoutRequest } from './api'
import type { LoginErrorResponse, LoginPayload, LoginResponse } from './types'

export function useLogin() {
  const { login: setSession } = useAuth()
  const navigate = useNavigate()

  return useMutation<LoginResponse, AxiosError<LoginErrorResponse>, LoginPayload>({
    mutationFn: login,
    onSuccess: (data) => {
      setSession(data.user, data.token)
      navigate('/')
    },
  })
}

export function useCurrentUser() {
  return useQuery({
    queryKey: ['auth', 'me'],
    queryFn: getCurrentUser,
  })
}

export function useLogout() {
  const { logout: clearSession } = useAuth()
  const navigate = useNavigate()

  return useMutation({
    mutationFn: logoutRequest,
    onSettled: () => {
      clearSession()
      navigate('/login')
    },
  })
}
