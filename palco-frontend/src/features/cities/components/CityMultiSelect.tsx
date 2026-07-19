import { useState } from 'react'
import { MultiAutocomplete } from '../../../shared/components/MultiAutocomplete'
import { useCities } from '../hooks'
import type { City } from '../types'

type CityMultiSelectProps = {
  selected: City[]
  onChange: (cities: City[]) => void
  placeholder?: string
}

export function CityMultiSelect({ selected, onChange, placeholder }: CityMultiSelectProps) {
  const [search, setSearch] = useState('')
  const { data: cities, isLoading } = useCities(search)

  return (
    <MultiAutocomplete<City>
      items={cities ?? []}
      getLabel={(city) => `${city.name}/${city.state}`}
      getValue={(city) => city.id}
      selected={selected}
      onChange={onChange}
      onSearchChange={setSearch}
      isLoading={isLoading}
      placeholder={placeholder}
    />
  )
}