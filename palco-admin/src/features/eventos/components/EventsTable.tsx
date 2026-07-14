import { Link } from 'react-router-dom'
import type { Event } from '../types'

type EventsTableProps = {
  events: Event[]
  onDeactivate: (id: number) => void
  onReactivate: (id: number) => void
}

export function EventsTable({ events, onDeactivate, onReactivate }: EventsTableProps) {
  function handleDeactivateClick(event: Event) {
    if (confirm(`Desativar o evento "${event.title}"?`)) {
      onDeactivate(event.id)
    }
  }

  function handleReactivateClick(event: Event) {
    if (confirm(`Reativar o evento "${event.title}"?`)) {
      onReactivate(event.id)
    }
  }

  return (
    <table className="w-full border-collapse text-left">
      <thead>
        <tr className="border-b">
          <th className="py-2">Título</th>
          <th className="py-2">Tipo</th>
          <th className="py-2">Cidade</th>
          <th className="py-2">Sessões</th>
          <th className="py-2">Status</th>
          <th className="py-2"></th>
          <th className="py-2"></th>
        </tr>
      </thead>
      <tbody>
        {events.map((event) => (
          <tr key={event.id} className="border-b">
            <td className="py-2">{event.title}</td>
            <td className="py-2">{event.type}</td>
            <td className="py-2">
              {event.city.name}/{event.city.state}
            </td>
            <td className="py-2">{event.sessionsCount ?? 0}</td>
            <td className="py-2">{event.active ? 'Ativo' : 'Inativo'}</td>
            <td className="py-2">
              <Link to={`/eventos/${event.id}/editar`} className="text-blue-600 underline">
                Editar
              </Link>
            </td>
            <td className="py-2">
              {event.active ? (
                <button onClick={() => handleDeactivateClick(event)} className="text-red-600 underline">
                  Desativar
                </button>
              ) : (
                <button onClick={() => handleReactivateClick(event)} className="text-green-600 underline">
                  Reativar
                </button>
              )}
            </td>
          </tr>
        ))}
        {events.length === 0 && (
          <tr>
            <td colSpan={7} className="py-4 text-center text-gray-500">
              Nenhum evento encontrado.
            </td>
          </tr>
        )}
      </tbody>
    </table>
  )
}