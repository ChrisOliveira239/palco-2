import { useState, type FormEvent } from 'react'
import { CurrencyInput } from '../../../shared/components/CurrencyInput'
import type { EventSessionFormValues, PricingType } from '../types'

const EMPTY_VALUES: EventSessionFormValues = {
  startAt: '',
  endAt: '',
  pricingType: 'free',
  price: '',
  capacity: '',
}

const PRICING_LABELS: Record<PricingType, string> = {
  free: 'Gratuito',
  fixed: 'Pago',
}

type EventSessionFormProps = {
  initialValues?: EventSessionFormValues
  onSubmit: (values: EventSessionFormValues) => void
  onCancel?: () => void
  isSubmitting: boolean
  errorMessage?: string
  fieldErrors?: Record<string, string[]>
}

export function EventSessionForm({
  initialValues,
  onSubmit,
  onCancel,
  isSubmitting,
  errorMessage,
  fieldErrors,
}: EventSessionFormProps) {
  const [values, setValues] = useState<EventSessionFormValues>(initialValues ?? EMPTY_VALUES)

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
        <label htmlFor="startAt" className="text-sm font-medium">
          Início
        </label>
        <input
          id="startAt"
          type="datetime-local"
          value={values.startAt}
          onChange={(event) => setValues({ ...values, startAt: event.target.value })}
          className="rounded border border-gray-300 px-3 py-2"
        />
        {fieldError('start_at') && <p className="text-sm text-red-600">{fieldError('start_at')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="endAt" className="text-sm font-medium">
          Fim
        </label>
        <input
          id="endAt"
          type="datetime-local"
          value={values.endAt}
          onChange={(event) => setValues({ ...values, endAt: event.target.value })}
          className="rounded border border-gray-300 px-3 py-2"
        />
        {fieldError('end_at') && <p className="text-sm text-red-600">{fieldError('end_at')}</p>}
      </div>

      <div className="flex flex-col gap-1">
        <label htmlFor="pricingType" className="text-sm font-medium">
          Cobrança
        </label>
        <select
          id="pricingType"
          value={values.pricingType}
          onChange={(event) => setValues({ ...values, pricingType: event.target.value as PricingType })}
          className="rounded border border-gray-300 px-3 py-2"
        >
          {Object.entries(PRICING_LABELS).map(([value, label]) => (
            <option key={value} value={value}>
              {label}
            </option>
          ))}
        </select>
        {fieldError('pricing_type') && <p className="text-sm text-red-600">{fieldError('pricing_type')}</p>}
      </div>

      {values.pricingType === 'fixed' && (
        <div className="flex flex-col gap-1">
          <label htmlFor="price" className="text-sm font-medium">
            Preço
          </label>
          <CurrencyInput
            id="price"
            value={values.price}
            onChange={(price) => setValues({ ...values, price })}
          />
          {fieldError('price') && <p className="text-sm text-red-600">{fieldError('price')}</p>}
        </div>
      )}

      <div className="flex flex-col gap-1">
        <label htmlFor="capacity" className="text-sm font-medium">
          Capacidade
        </label>
        <input
          id="capacity"
          type="number"
          min="1"
          value={values.capacity}
          onChange={(event) => setValues({ ...values, capacity: event.target.value })}
          className="rounded border border-gray-300 px-3 py-2"
        />
        {fieldError('capacity') && <p className="text-sm text-red-600">{fieldError('capacity')}</p>}
      </div>

      {errorMessage && <p className="text-sm text-red-600">{errorMessage}</p>}

      <div className="flex gap-2">
        <button
          type="submit"
          disabled={isSubmitting}
          className="rounded bg-black px-4 py-2 text-white disabled:opacity-50"
        >
          {isSubmitting ? 'Salvando...' : 'Salvar'}
        </button>
        {onCancel && (
          <button type="button" onClick={onCancel} className="rounded border border-gray-300 px-4 py-2">
            Cancelar
          </button>
        )}
      </div>
    </form>
  )
}
