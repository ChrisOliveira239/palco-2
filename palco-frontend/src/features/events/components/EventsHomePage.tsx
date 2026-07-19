import { useEffect, useState } from 'react'
import { useAuth } from '../../../app/AuthContext'
import { Pagination } from '../../../shared/components/Pagination'
import { useLogout } from '../../auth/hooks'
import { CityMultiSelect } from '../../cities/components/CityMultiSelect'
import { useInterestedCities } from '../../cities/hooks'
import type { City } from '../../cities/types'
import { useEvents } from '../hooks'
import { EventsList } from './EventsList'

export function EventsHomePage() {
  const { user } = useAuth()
  const { mutate: logout, isPending: isLoggingOut } = useLogout()
  const { data: interestedCities, isLoading: isLoadingInterestedCities } = useInterestedCities()
  const [selectedCities, setSelectedCities] = useState<City[] | undefined>(undefined)
  const [page, setPage] = useState(1)

  useEffect(() => {
    if (selectedCities === undefined && interestedCities !== undefined) {
      setSelectedCities(interestedCities)
    }
  }, [interestedCities, selectedCities])

  useEffect(() => {
    setPage(1)
  }, [selectedCities])

  const cityIds = (selectedCities ?? []).map((city) => city.id)
  const { data, isLoading: isLoadingEvents } = useEvents({ cityIds, page })

  const isInitializing = selectedCities === undefined && isLoadingInterestedCities

  return (
    <div className="mx-auto flex max-w-5xl flex-col gap-6 p-6">
      <header className="flex items-center justify-between">
        <h1 className="text-xl font-semibold">Olá, {user?.name}</h1>
        <button
          onClick={() => logout()}
          disabled={isLoggingOut}
          className="rounded bg-brand-surface px-4 py-2 text-sm text-brand-text hover:bg-brand-text-muted/20 disabled:opacity-50"
        >
          {isLoggingOut ? 'Saindo...' : 'Sair'}
        </button>
      </header>

      <div className="flex flex-col gap-1">
        <label className="text-sm font-medium">Cidades</label>
        <CityMultiSelect
          selected={selectedCities ?? []}
          onChange={setSelectedCities}
          placeholder="Buscar cidade..."
        />
      </div>

      {isInitializing && <p className="text-brand-text-muted">Carregando...</p>}

      {!isInitializing && isLoadingEvents && <p className="text-brand-text-muted">Carregando eventos...</p>}

      {!isInitializing && !isLoadingEvents && (
        <EventsList events={data?.data ?? []} hasSelectedCities={cityIds.length > 0} />
      )}

      {data && data.meta.last_page > 1 && (
        <Pagination
          currentPage={data.meta.current_page}
          lastPage={data.meta.last_page}
          total={data.meta.total}
          onPageChange={setPage}
        />
      )}
    </div>
  )
}