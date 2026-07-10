import type { ReactNode } from 'react'
import { Navigate, Route, Routes } from 'react-router-dom'
import { HomePage } from '../features/auth/components/HomePage'
import { LoginForm } from '../features/auth/components/LoginForm'
import { EventFormPage } from '../features/eventos/components/EventFormPage'
import { EventsPage } from '../features/eventos/components/EventsPage'
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
        path="/"
        element={
          <RequireAuth>
            <HomePage />
          </RequireAuth>
        }
      />
      <Route
        path="/eventos"
        element={
          <RequireAuth>
            <EventsPage />
          </RequireAuth>
        }
      />
      <Route
        path="/eventos/novo"
        element={
          <RequireAuth>
            <EventFormPage />
          </RequireAuth>
        }
      />
      <Route
        path="/eventos/:id/editar"
        element={
          <RequireAuth>
            <EventFormPage />
          </RequireAuth>
        }
      />
    </Routes>
  )
}
