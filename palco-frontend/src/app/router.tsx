import type { ReactNode } from 'react'
import { Navigate, Route, Routes } from 'react-router-dom'
import { LoginForm } from '../features/auth/components/LoginForm'
import { RegisterForm } from '../features/auth/components/RegisterForm'
import { EventsHomePage } from '../features/events/components/EventsHomePage'
import { useAuth } from './AuthContext'

function RequireAuth({ children }: { children: ReactNode }) {
  const { isAuthenticated } = useAuth()
  if (!isAuthenticated) {
    return <Navigate to="/login" replace />
  }
  return children
}

export function AppRoutes() {
  return (
    <Routes>
      <Route
        path="/login"
        element={
          <div className="flex min-h-screen items-center justify-center">
            <LoginForm />
          </div>
        }
      />
      <Route
        path="/registro"
        element={
          <div className="flex min-h-screen items-center justify-center">
            <RegisterForm />
          </div>
        }
      />
      <Route
        path="/"
        element={
          <RequireAuth>
            <EventsHomePage />
          </RequireAuth>
        }
      />
    </Routes>
  )
}
