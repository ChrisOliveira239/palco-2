import { useState } from 'react'
import { Autocomplete } from '../../../shared/components/Autocomplete'
import { useCities } from '../hooks'
import type { City } from '../types'

type CityAutocompleteProps = {
  value: City | undefined
  onChange: (city: City | undefined) => void
  placeholder?: string
}

export function CityAutocomplete({ value, onChange, placeholder }: CityAutocompleteProps) {
  const [search, setSearch] = useState('')
  const { data: cities, isLoading } = useCities(search)

  return (
    <Autocomplete<City>
      items={cities ?? []}
      getLabel={(city) => `${city.name}/${city.state}`}
      getValue={(city) => city.id}
      value={value}
      onChange={onChange}
      onSearchChange={setSearch}
      isLoading={isLoading}
      placeholder={placeholder}
    />
  )
}