import { useCurrentUser, useLogout } from '../hooks'

export function HomePage() {
  const { data: user, isLoading, isError } = useCurrentUser()
  const { mutate: logout, isPending } = useLogout()

  if (isLoading) {
    return <p>Carregando...</p>
  }

  if (isError) {
    return <p>Não foi possível confirmar sua sessão.</p>
  }

  return (
    <div className="flex min-h-screen flex-col items-center justify-center gap-4">
      <p>Olá, {user?.name}</p>
      <button
        type="button"
        onClick={() => logout()}
        disabled={isPending}
        className="rounded bg-black px-4 py-2 text-white disabled:opacity-50"
      >
        {isPending ? 'Saindo...' : 'Sair'}
      </button>
    </div>
  )
}