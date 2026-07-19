import { useState, type FormEvent } from 'react'
import { Link } from 'react-router-dom'
import { CityMultiSelect } from '../../cities/components/CityMultiSelect'
import type { City } from '../../cities/types'
import { useRegister } from '../hooks'

const EMPTY_VALUES = {
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
}

export function RegisterForm() {
  const [values, setValues] = useState(EMPTY_VALUES)
  const [selectedCities, setSelectedCities] = useState<City[]>([])
  const { mutate, isPending, error } = useRegister()

  const fieldErrors = error?.response?.data.errors
  const hasFieldErrors = fieldErrors !== undefined && Object.keys(fieldErrors).length > 0

  function fieldError(field: string) {
    return fieldErrors?.[field]?.[0]
  }

  function handleSubmit(event: FormEvent) {
    event.preventDefault()
    mutate({ ...values, city_ids: selectedCities.map((city) => city.id) })
  }

  return (
    <form onSubmit={handleSubmit} className="flex w-full max-w-sm flex-col gap-4">
      <div className="flex flex-col gap-1">
        <label htmlFor="name" className="text-sm font-medium">
          Nome
        </label>
        <input
          id="name"
          type="text"
          value={values.name}
          onChange={(event) => setValues({ ...values, name: event.target.value })}
          required
          className="rounded border border-brand-text-muted/30 bg-brand-surface px-3 py-2 text-brand-text"
        />
        {fieldError('name') && <p className="text-sm text-red-400">{fieldError('name')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="email" className="text-sm font-medium">
          E-mail
        </label>
        <input
          id="email"
          type="email"
          value={values.email}
          onChange={(event) => setValues({ ...values, email: event.target.value })}
          required
          className="rounded border border-brand-text-muted/30 bg-brand-surface px-3 py-2 text-brand-text"
        />
        {fieldError('email') && <p className="text-sm text-red-400">{fieldError('email')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="password" className="text-sm font-medium">
          Senha
        </label>
        <input
          id="password"
          type="password"
          value={values.password}
          onChange={(event) => setValues({ ...values, password: event.target.value })}
          required
          className="rounded border border-brand-text-muted/30 bg-brand-surface px-3 py-2 text-brand-text"
        />
        {fieldError('password') && <p className="text-sm text-red-400">{fieldError('password')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="password_confirmation" className="text-sm font-medium">
          Confirmar senha
        </label>
        <input
          id="password_confirmation"
          type="password"
          value={values.password_confirmation}
          onChange={(event) => setValues({ ...values, password_confirmation: event.target.value })}
          required
          className="rounded border border-brand-text-muted/30 bg-brand-surface px-3 py-2 text-brand-text"
        />
      </div>

      <div className="flex flex-col gap-1">
        <label className="text-sm font-medium">Cidades de interesse</label>
        <CityMultiSelect
          selected={selectedCities}
          onChange={setSelectedCities}
          placeholder="Buscar cidade..."
        />
        {selectedCities.length === 0 && (
          <p className="text-sm text-brand-text-muted">Selecione ao menos uma cidade.</p>
        )}
        {fieldError('city_ids') && <p className="text-sm text-red-400">{fieldError('city_ids')}</p>}
      </div>

      {!hasFieldErrors && error && (
        <p className="text-sm text-red-400">{error.response?.data.message ?? 'Erro ao cadastrar.'}</p>
      )}

      <button
        type="submit"
        disabled={isPending || selectedCities.length === 0}
        className="rounded bg-brand-accent px-4 py-2 font-medium text-brand-dark hover:bg-brand-accent-hover disabled:opacity-50"
      >
        {isPending ? 'Cadastrando...' : 'Cadastrar'}
      </button>
      <p className="text-sm text-brand-text-muted">
        Já tem conta?{' '}
        <Link to="/login" className="text-brand-accent hover:underline">
          Entrar
        </Link>
      </p>
    </form>
  )
}
