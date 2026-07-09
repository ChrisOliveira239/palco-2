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
