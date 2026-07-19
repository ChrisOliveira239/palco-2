import type { Event } from '../types'
import { EventCard } from './EventCard'

type EventsListProps = {
  events: Event[]
  hasSelectedCities: boolean
}

export function EventsList({ events, hasSelectedCities }: EventsListProps) {
  if (!hasSelectedCities) {
    return <p className="text-brand-text-muted">Selecione ao menos uma cidade pra ver os eventos.</p>
  }

  if (events.length === 0) {
    return <p className="text-brand-text-muted">Nenhum evento encontrado nessas cidades.</p>
  }

  return (
    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      {events.map((event) => (
        <EventCard key={event.id} event={event} />
      ))}
    </div>
  )
}