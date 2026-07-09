import { useState, type FormEvent } from 'react'
import { useLogin } from '../hooks'

export function LoginForm() {
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const { mutate, isPending, error } = useLogin()

  function handleSubmit(event: FormEvent) {
    event.preventDefault()
    mutate({ email, password })
  }

  return (
    <form onSubmit={handleSubmit} className="flex w-full max-w-sm flex-col gap-4">
      <div className="flex flex-col gap-1">
        <label htmlFor="email" className="text-sm font-medium">
          E-mail
        </label>
        <input
          id="email"
          type="email"
          value={email}
          onChange={(event) => setEmail(event.target.value)}
          required
          className="rounded border border-gray-300 px-3 py-2"
        />
      </div>
      <div className="flex flex-col gap-1">
        <label htmlFor="password" className="text-sm font-medium">
          Senha
        </label>
        <input
          id="password"
          type="password"
          value={password}
          onChange={(event) => setPassword(event.target.value)}
          required
          className="rounded border border-gray-300 px-3 py-2"
        />
      </div>
      {error && (
        <p className="text-sm text-red-600">
          {error.response?.data?.message ?? 'Credenciais inválidas.'}
        </p>
      )}
      <button
        type="submit"
        disabled={isPending}
        className="rounded bg-black px-4 py-2 text-white disabled:opacity-50"
      >
        {isPending ? 'Entrando...' : 'Entrar'}
      </button>
    </form>
  )
}