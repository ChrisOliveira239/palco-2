import type { ChangeEvent } from 'react'
import { formatBRL } from '../utils/currency'

function digitsToDecimalString(digits: string): string {
  const cents = digits === '' ? 0 : parseInt(digits, 10)
  return (cents / 100).toFixed(2)
}

type CurrencyInputProps = {
  id?: string
  value: string
  onChange: (value: string) => void
}

export function CurrencyInput({ id, value, onChange }: CurrencyInputProps) {
  function handleChange(event: ChangeEvent<HTMLInputElement>) {
    const digits = event.target.value.replace(/\D/g, '')
    onChange(digitsToDecimalString(digits))
  }

  return (
    <input
      id={id}
      type="text"
      inputMode="numeric"
      value={formatBRL(value)}
      onChange={handleChange}
      className="rounded border border-gray-300 px-3 py-2"
    />
  )
}
