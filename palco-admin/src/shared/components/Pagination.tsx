type PaginationProps = {
  currentPage: number
  lastPage: number
  total: number
  onPageChange: (page: number) => void
}

export function Pagination({ currentPage, lastPage, total, onPageChange }: PaginationProps) {
  return (
    <div className="mt-4 flex items-center gap-3 text-sm text-gray-500">
      <button
        type="button"
        onClick={() => onPageChange(currentPage - 1)}
        disabled={currentPage <= 1}
        className="rounded border border-gray-300 px-2 py-1 disabled:opacity-50"
      >
        Anterior
      </button>
      <span>
        Página {currentPage} de {lastPage} — {total} registro(s)
      </span>
      <button
        type="button"
        onClick={() => onPageChange(currentPage + 1)}
        disabled={currentPage >= lastPage}
        className="rounded border border-gray-300 px-2 py-1 disabled:opacity-50"
      >
        Próxima
      </button>
    </div>
  )
}