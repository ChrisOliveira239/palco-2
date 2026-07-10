import { useEffect, useState } from 'react'
import { Link } from 'react-router-dom'
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
      <div className="mb-4 flex items-center justify-between">
        <h1 className="text-xl font-semibold">Eventos</h1>
        <Link to="/eventos/novo" className="rounded bg-black px-4 py-2 text-white">
          Novo evento
        </Link>
      </div>

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
