import { useState, type FormEvent } from 'react'
import { Link } from 'react-router-dom'
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
          className="rounded border border-brand-text-muted/30 bg-brand-surface px-3 py-2 text-brand-text"
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
          className="rounded border border-brand-text-muted/30 bg-brand-surface px-3 py-2 text-brand-text"
        />
      </div>
      {error && (
        <p className="text-sm text-red-400">
          {error.response?.data?.message ?? 'Credenciais inválidas.'}
        </p>
      )}
      <button
        type="submit"
        disabled={isPending}
        className="rounded bg-brand-accent px-4 py-2 font-medium text-brand-dark hover:bg-brand-accent-hover disabled:opacity-50"
      >
        {isPending ? 'Entrando...' : 'Entrar'}
      </button>
      <p className="text-sm text-brand-text-muted">
        Não tem conta?{' '}
        <Link to="/registro" className="text-brand-accent hover:underline">
          Cadastre-se
        </Link>
      </p>
    </form>
  )
}
