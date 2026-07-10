import { Link, useNavigate, useParams } from 'react-router-dom'
import { useCreateEvent, useEvent, useUpdateEvent } from '../hooks'
import type { EventFormValues } from '../types'
import { EventForm } from './EventForm'

export function EventFormPage() {
  const { id } = useParams()
  const navigate = useNavigate()
  const eventId = id ? Number(id) : undefined
  const isEditing = eventId !== undefined

  const { data: event, isLoading } = useEvent(eventId)
  const createEvent = useCreateEvent()
  const updateEvent = useUpdateEvent(eventId ?? 0)

  const mutation = isEditing ? updateEvent : createEvent

  function handleSubmit(values: EventFormValues) {
    mutation.mutate(values, {
      onSuccess: () => navigate('/eventos'),
    })
  }

  if (isEditing && isLoading) {
    return (
      <div className="p-6">
        <p>Carregando...</p>
      </div>
    )
  }

  const initialValues: EventFormValues | undefined = event
    ? {
        title: event.title,
        synopsis: event.synopsis ?? '',
        type: event.type as EventFormValues['type'],
        venueName: event.venueName,
        cityId: event.city.id,
        ticketUrl: event.ticketUrl ?? '',
      }
    : undefined

  return (
    <div className="p-6">
      <Link to="/eventos" className="mb-4 inline-block text-sm text-blue-600 underline">
        &larr; Voltar
      </Link>
      <h1 className="mb-4 text-xl font-semibold">{isEditing ? 'Editar evento' : 'Novo evento'}</h1>
      <EventForm
        initialValues={initialValues}
        onSubmit={handleSubmit}
        isSubmitting={mutation.isPending}
        errorMessage={mutation.error?.response?.data.message}
        fieldErrors={mutation.error?.response?.data.errors}
      />
    </div>
  )
}