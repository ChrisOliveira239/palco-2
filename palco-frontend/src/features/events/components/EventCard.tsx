import type { Event } from '../types'

function formatNextSession(iso: string | null) {
  return iso ? new Date(iso).toLocaleString('pt-BR', { dateStyle: 'short', timeStyle: 'short' }) : null
}

type EventCardProps = {
  event: Event
}

export function EventCard({ event }: EventCardProps) {
  const nextSession = formatNextSession(event.nextSessionAt)

  return (
    <div className="flex flex-col gap-2 rounded bg-brand-surface p-4">
      <h3 className="text-lg font-semibold">{event.title}</h3>
      {event.synopsis && <p className="line-clamp-3 text-sm text-brand-text-muted">{event.synopsis}</p>}
      <p className="text-sm text-brand-text-muted">
        {event.city.name}/{event.city.state}
      </p>
      {nextSession && <p className="text-sm text-brand-text-muted">Próxima sessão: {nextSession}</p>}
      {event.ticketUrl && (
        <a
          href={event.ticketUrl}
          target="_blank"
          rel="noreferrer"
          className="mt-2 text-sm text-brand-accent hover:underline"
        >
          Ingressos
        </a>
      )}
    </div>
  )
}