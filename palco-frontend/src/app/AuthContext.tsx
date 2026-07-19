import { createContext, useContext, useState, type ReactNode } from 'react'
import { AUTH_STORAGE_KEY } from '../shared/api/client'
import type { AuthUser } from '../features/auth/types'

type StoredAuth = {
  user: AuthUser
  token: string
}

type AuthContextValue = {
  user: AuthUser | null
  token: string | null
  isAuthenticated: boolean
  login: (user: AuthUser, token: string) => void
  logout: () => void
}

const AuthContext = createContext<AuthContextValue | null>(null)

function readStoredAuth(): StoredAuth | null {
  const raw = localStorage.getItem(AUTH_STORAGE_KEY)
  return raw ? (JSON.parse(raw) as StoredAuth) : null
}

export function AuthProvider({ children }: { children: ReactNode }) {
  const [auth, setAuth] = useState<StoredAuth | null>(() => readStoredAuth())

  function login(user: AuthUser, token: string) {
    const next = { user, token }
    localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(next))
    setAuth(next)
  }

  function logout() {
    localStorage.removeItem(AUTH_STORAGE_KEY)
    setAuth(null)
  }

  const value: AuthContextValue = {
    user: auth?.user ?? null,
    token: auth?.token ?? null,
    isAuthenticated: auth !== null,
    login,
    logout,
  }

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}

export function useAuth(): AuthContextValue {
  const context = useContext(AuthContext)
  if (!context) {
    throw new Error('useAuth precisa ser usado dentro de <AuthProvider>')
  }
  return context
}