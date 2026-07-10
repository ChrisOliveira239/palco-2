import { useEffect, useState } from 'react'
import { Pagination } from '../../../shared/components/Pagination'
import { useAdminEvents } from '../hooks'
import type { EventStatus } from '../types'
import { EventsFilters } from './EventsFilters'
import { EventsTable } from './EventsTable'

export function EventsPage() {
  const [search, setSearch] = useState('')
  const [cityId, setCityId] = useState<number | undefined>(undefined)
  const [status, setStatus] = useState<EventStatus>('active')
  const [page, setPage] = useState(1)

  useEffect(() => {
    setPage(1)
  }, [search, cityId, status])

  const { data, isLoading, isError } = useAdminEvents({
    search: search || undefined,
    cityId,
    status,
    page,
  })

  return (
    <div className="p-6">
      <h1 className="mb-4 text-xl font-semibold">Eventos</h1>

      <EventsFilters
        onSearchChange={setSearch}
        cityId={cityId}
        onCityIdChange={setCityId}
        status={status}
        onStatusChange={setStatus}
      />

      {isLoading && <p>Carregando...</p>}
      {isError && <p>Não foi possível carregar os eventos.</p>}

      {data && (
        <>
          <EventsTable events={data.data} />
          <Pagination
            currentPage={data.meta.current_page}
            lastPage={data.meta.last_page}
            total={data.meta.total}
            onPageChange={setPage}
          />
        </>
      )}
    </div>
  )
}
