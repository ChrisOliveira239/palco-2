import axios from 'axios'

export const AUTH_STORAGE_KEY = 'palco_admin_auth'

type StoredAuth = {
  token: string
}

export const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
})

apiClient.interceptors.request.use((config) => {
  const raw = localStorage.getItem(AUTH_STORAGE_KEY)
  if (raw) {
    const { token } = JSON.parse(raw) as StoredAuth
    config.headers.set('Authorization', `Bearer ${token}`)
  }
  return config
})

apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem(AUTH_STORAGE_KEY)
      window.location.href = '/login'
    }
    return Promise.reject(error)
  },
)
