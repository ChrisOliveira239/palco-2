import { useState } from 'react'
import { Modal } from '../../../shared/components/Modal'
import { formatBRL } from '../../../shared/utils/currency'
import { useCreateEventSession, useDeleteEventSession, useEventSessions, useUpdateEventSession } from '../hooks'
import type { EventSession, EventSessionFormValues } from '../types'
import { EventSessionForm } from './EventSessionForm'

const PRICING_LABELS: Record<EventSession['pricingType'], string> = {
  free: 'Gratuito',
  fixed: 'Pago',
}

function toDatetimeLocal(iso: string) {
  const date = new Date(iso)
  const pad = (n: number) => String(n).padStart(2, '0')
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`
}

function formatDisplay(iso: string | null) {
  return iso ? new Date(iso).toLocaleString('pt-BR') : '—'
}

function toFormValues(session: EventSession): EventSessionFormValues {
  return {
    startAt: toDatetimeLocal(session.startAt),
    endAt: session.endAt ? toDatetimeLocal(session.endAt) : '',
    pricingType: session.pricingType,
    price: session.price ?? '',
    capacity: session.capacity ? String(session.capacity) : '',
  }
}

type EventSessionsSectionProps = {
  eventId: number
}

export function EventSessionsSection({ eventId }: EventSessionsSectionProps) {
  const { data: sessions, isLoading } = useEventSessions(eventId)
  const [editingSession, setEditingSession] = useState<EventSession | null>(null)
  const [isFormOpen, setIsFormOpen] = useState(false)
  const [createFormKey, setCreateFormKey] = useState(0)

  const createSession = useCreateEventSession(eventId)
  const updateSession = useUpdateEventSession(eventId)
  const deleteSession = useDeleteEventSession(eventId)

  const mutation = editingSession ? updateSession : createSession
  const fieldErrors = mutation.error?.response?.data.errors
  const hasFieldErrors = fieldErrors !== undefined && Object.keys(fieldErrors).length > 0

  function handleSubmit(values: EventSessionFormValues) {
    if (editingSession) {
      updateSession.mutate(
        { id: editingSession.id, values },
        {
          onSuccess: () => {
            setEditingSession(null)
            setIsFormOpen(false)
          },
        },
      )
    } else {
      createSession.mutate(values, {
        onSuccess: () => {
          setCreateFormKey((key) => key + 1)
          setIsFormOpen(false)
        },
      })
    }
  }

  function handleRemoveClick(session: EventSession) {
    if (confirm(`Remover sessão de ${formatDisplay(session.startAt)}?`)) {
      deleteSession.mutate(session.id)
      if (editingSession?.id === session.id) {
        setEditingSession(null)
        setIsFormOpen(false)
      }
    }
  }

  function handleNewClick() {
    setEditingSession(null)
    setIsFormOpen(true)
  }

  function handleEditClick(session: EventSession) {
    setEditingSession(session)
    setIsFormOpen(true)
  }

  function handleCloseModal() {
    setIsFormOpen(false)
    setEditingSession(null)
  }

  return (
    <div className="mt-8 flex flex-col gap-4">
      <div className="flex items-center justify-between">
        <h2 className="text-lg font-semibold">Sessões</h2>
        <button type="button" onClick={handleNewClick} className="rounded bg-black px-4 py-2 text-sm text-white">
          Nova sessão
        </button>
      </div>

      {isLoading && <p>Carregando...</p>}

      {sessions && (
        <table className="w-full border-collapse text-left">
          <thead>
            <tr className="border-b">
              <th className="py-2">Início</th>
              <th className="py-2">Fim</th>
              <th className="py-2">Cobrança</th>
              <th className="py-2">Preço</th>
              <th className="py-2">Capacidade</th>
              <th className="py-2"></th>
              <th className="py-2"></th>
            </tr>
          </thead>
          <tbody>
            {sessions.map((session) => (
              <tr key={session.id} className="border-b">
                <td className="py-2">{formatDisplay(session.startAt)}</td>
                <td className="py-2">{formatDisplay(session.endAt)}</td>
                <td className="py-2">{PRICING_LABELS[session.pricingType]}</td>
                <td className="py-2">{session.pricingType === 'fixed' && session.price ? formatBRL(session.price) : '—'}</td>
                <td className="py-2">{session.capacity ?? '—'}</td>
                <td className="py-2">
                  <button onClick={() => handleEditClick(session)} className="text-blue-600 underline">
                    Editar
                  </button>
                </td>
                <td className="py-2">
                  <button onClick={() => handleRemoveClick(session)} className="text-red-600 underline">
                    Remover
                  </button>
                </td>
              </tr>
            ))}
            {sessions.length === 0 && (
              <tr>
                <td colSpan={7} className="py-4 text-center text-gray-500">
                  Nenhuma sessão cadastrada.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      )}

      {isFormOpen && (
        <Modal title={editingSession ? 'Editando sessão' : 'Nova sessão'} onClose={handleCloseModal}>
          <EventSessionForm
            key={editingSession?.id ?? `new-${createFormKey}`}
            initialValues={editingSession ? toFormValues(editingSession) : undefined}
            onSubmit={handleSubmit}
            onCancel={handleCloseModal}
            isSubmitting={mutation.isPending}
            errorMessage={hasFieldErrors ? undefined : mutation.error?.response?.data.message}
            fieldErrors={fieldErrors}
          />
        </Modal>
      )}
    </div>
  )
}
