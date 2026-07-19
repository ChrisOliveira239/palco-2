export type UserRole = 'user' | 'admin'

export type AuthUser = {
  id: number
  name: string
  email: string
  role: UserRole
}

export type LoginPayload = {
  email: string
  password: string
}

export type LoginResponse = {
  user: AuthUser
  token: string
}

export type LoginErrorResponse = {
  message: string
}

export type RegisterPayload = {
  name: string
  email: string
  password: string
  password_confirmation: string
  city_ids: number[]
}

export type RegisterErrorResponse = {
  message: string
  errors?: Record<string, string[]>
}