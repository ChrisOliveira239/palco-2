import { useEffect, useState } from 'react'
import { useDebouncedValue } from '../../../shared/hooks/useDebouncedValue'
import { CityAutocomplete } from '../../cities/components/CityAutocomplete'
import type { City } from '../../cities/types'
import type { EventStatus } from '../types'

const STATUS_LABELS: Record<EventStatus, string> = {
  active: 'Ativos',
  inactive: 'Inativos',
  all: 'Todos',
}

const MIN_SEARCH_LENGTH = 3
const SEARCH_DEBOUNCE_MS = 400

type EventsFiltersProps = {
  onSearchChange: (search: string) => void
  onCityIdChange: (cityId: number | undefined) => void
  status: EventStatus
  onStatusChange: (status: EventStatus) => void
}

export function EventsFilters({ onSearchChange, onCityIdChange, status, onStatusChange }: EventsFiltersProps) {
  const [selectedCity, setSelectedCity] = useState<City | undefined>(undefined)
  const [searchInput, setSearchInput] = useState('')
  const debouncedSearch = useDebouncedValue(searchInput, SEARCH_DEBOUNCE_MS)

  useEffect(() => {
    if (debouncedSearch.length === 0 || debouncedSearch.length >= MIN_SEARCH_LENGTH) {
      onSearchChange(debouncedSearch)
    }
  }, [debouncedSearch, onSearchChange])

  return (
    <div className="mb-4 flex gap-3">
      <input
        type="text"
        placeholder="Buscar por título (mín. 3 caracteres)..."
        value={searchInput}
        onChange={(event) => setSearchInput(event.target.value)}
        className="rounded border border-gray-300 px-3 py-2"
      />
      <CityAutocomplete
        value={selectedCity}
        onChange={(city) => {
          setSelectedCity(city)
          onCityIdChange(city?.id)
        }}
        placeholder="Todas as cidades"
      />
      <select
        value={status}
        onChange={(event) => onStatusChange(event.target.value as EventStatus)}
        className="rounded border border-gray-300 px-3 py-2"
      >
        {Object.entries(STATUS_LABELS).map(([value, label]) => (
          <option key={value} value={value}>
            {label}
          </option>
        ))}
      </select>
    </div>
  )
}