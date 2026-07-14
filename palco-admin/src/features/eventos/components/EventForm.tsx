import { useState, type FormEvent } from 'react'
import { CityAutocomplete } from '../../cities/components/CityAutocomplete'
import type { City } from '../../cities/types'
import type { EventFormValues, EventType } from '../types'

const EMPTY_VALUES: EventFormValues = {
  title: '',
  synopsis: '',
  type: 'show',
  venueName: '',
  cityId: undefined,
  ticketUrl: '',
}

const TYPE_LABELS: Record<EventType, string> = {
  show: 'Show',
  oficina: 'Oficina',
  exposicao: 'Exposição',
}

type EventFormProps = {
  initialValues?: EventFormValues
  initialCity?: City
  onSubmit: (values: EventFormValues) => void
  isSubmitting: boolean
  errorMessage?: string
  fieldErrors?: Record<string, string[]>
}

export function EventForm({
  initialValues,
  initialCity,
  onSubmit,
  isSubmitting,
  errorMessage,
  fieldErrors,
}: EventFormProps) {
  const [values, setValues] = useState<EventFormValues>(initialValues ?? EMPTY_VALUES)
  const [selectedCity, setSelectedCity] = useState<City | undefined>(initialCity)

  function fieldError(field: string) {
    return fieldErrors?.[field]?.[0]
  }

  function handleSubmit(event: FormEvent) {
    event.preventDefault()
    onSubmit(values)
  }

  return (
    <form onSubmit={handleSubmit} className="flex max-w-lg flex-col gap-4">
      <div className="flex flex-col gap-1">
        <label htmlFor="title" className="text-sm font-medium">
          Título
        </label>
        <input
          id="title"
          type="text"
          value={values.title}
          onChange={(event) => setValues({ ...values, title: event.target.value })}
          className="rounded border border-gray-300 px-3 py-2"
        />
        {fieldError('title') && <p className="text-sm text-red-600">{fieldError('title')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="synopsis" className="text-sm font-medium">
          Sinopse
        </label>
        <textarea
          id="synopsis"
          value={values.synopsis}
          onChange={(event) => setValues({ ...values, synopsis: event.target.value })}
          className="rounded border border-gray-300 px-3 py-2"
        />
        {fieldError('synopsis') && <p className="text-sm text-red-600">{fieldError('synopsis')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="type" className="text-sm font-medium">
          Tipo
        </label>
        <select
          id="type"
          value={values.type}
          onChange={(event) => setValues({ ...values, type: event.target.value as EventType })}
          className="rounded border border-gray-300 px-3 py-2"
        >
          {Object.entries(TYPE_LABELS).map(([value, label]) => (
            <option key={value} value={value}>
              {label}
            </option>
          ))}
        </select>
        {fieldError('type') && <p className="text-sm text-red-600">{fieldError('type')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="venueName" className="text-sm font-medium">
          Local
        </label>
        <input
          id="venueName"
          type="text"
          value={values.venueName}
          onChange={(event) => setValues({ ...values, venueName: event.target.value })}
          className="rounded border border-gray-300 px-3 py-2"
        />
        {fieldError('venue_name') && <p className="text-sm text-red-600">{fieldError('venue_name')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="cityId" className="text-sm font-medium">
          Cidade
        </label>
        <CityAutocomplete
          value={selectedCity}
          onChange={(city) => {
            setSelectedCity(city)
            setValues({ ...values, cityId: city?.id })
          }}
          placeholder="Selecione..."
        />
        {fieldError('city_id') && <p className="text-sm text-red-600">{fieldError('city_id')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="ticketUrl" className="text-sm font-medium">
          Link de ingressos
        </label>
        <input
          id="ticketUrl"
          type="text"
          value={values.ticketUrl}
          onChange={(event) => setValues({ ...values, ticketUrl: event.target.value })}
          className="rounded border border-gray-300 px-3 py-2"
        />
        {fieldError('ticket_url') && <p className="text-sm text-red-600">{fieldError('ticket_url')}</p>}
      </div>

      {errorMessage && <p className="text-sm text-red-600">{errorMessage}</p>}

      <button
        type="submit"
        disabled={isSubmitting}
        className="rounded bg-black px-4 py-2 text-white disabled:opacity-50"
      >
        {isSubmitting ? 'Salvando...' : 'Salvar'}
      </button>
    </form>
  )
}