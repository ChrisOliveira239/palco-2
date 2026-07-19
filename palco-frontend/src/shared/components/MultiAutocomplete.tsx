import { useEffect, useState } from 'react'
import { useDebouncedValue } from '../hooks/useDebouncedValue'

const SEARCH_DEBOUNCE_MS = 400

type MultiAutocompleteProps<T> = {
  items: T[]
  getLabel: (item: T) => string
  getValue: (item: T) => string | number
  selected: T[]
  onChange: (items: T[]) => void
  onSearchChange: (search: string) => void
  isLoading?: boolean
  placeholder?: string
}

export function MultiAutocomplete<T>({
  items,
  getLabel,
  getValue,
  selected,
  onChange,
  onSearchChange,
  isLoading,
  placeholder,
}: MultiAutocompleteProps<T>) {
  const [inputText, setInputText] = useState('')
  const [isOpen, setIsOpen] = useState(false)
  const debouncedInputText = useDebouncedValue(inputText, SEARCH_DEBOUNCE_MS)

  useEffect(() => {
    onSearchChange(debouncedInputText)
  }, [debouncedInputText, onSearchChange])

  function isSelected(item: T) {
    return selected.some((selectedItem) => getValue(selectedItem) === getValue(item))
  }

  function handleToggle(item: T) {
    if (isSelected(item)) {
      onChange(selected.filter((selectedItem) => getValue(selectedItem) !== getValue(item)))
    } else {
      onChange([...selected, item])
    }
    setInputText('')
  }

  function handleRemove(item: T) {
    onChange(selected.filter((selectedItem) => getValue(selectedItem) !== getValue(item)))
  }

  return (
    <div className="relative">
      {selected.length > 0 && (
        <div className="mb-2 flex flex-wrap gap-2">
          {selected.map((item) => (
            <span
              key={getValue(item)}
              className="flex items-center gap-1 rounded-full bg-brand-surface px-3 py-1 text-sm text-brand-text"
            >
              {getLabel(item)}
              <button
                type="button"
                onMouseDown={() => handleRemove(item)}
                aria-label={`Remover ${getLabel(item)}`}
                className="text-brand-text-muted hover:text-brand-text"
              >
                &times;
              </button>
            </span>
          ))}
        </div>
      )}
      <input
        type="text"
        value={inputText}
        placeholder={placeholder}
        onChange={(event) => {
          setInputText(event.target.value)
          setIsOpen(true)
        }}
        onFocus={() => setIsOpen(true)}
        onBlur={() => setIsOpen(false)}
        onKeyDown={(event) => {
          if (event.key === 'Escape') {
            setIsOpen(false)
          }
        }}
        className="w-full rounded border border-brand-text-muted/30 bg-brand-surface px-3 py-2 text-brand-text"
      />
      {isOpen && (
        <ul className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded border border-brand-text-muted/30 bg-brand-surface shadow">
          {isLoading && <li className="px-3 py-2 text-brand-text-muted">Carregando...</li>}
          {!isLoading && items.length === 0 && (
            <li className="px-3 py-2 text-brand-text-muted">Nenhum resultado.</li>
          )}
          {!isLoading &&
            items.map((item) => (
              <li key={getValue(item)}>
                <button
                  type="button"
                  onMouseDown={() => handleToggle(item)}
                  className="flex w-full items-center justify-between px-3 py-2 text-left text-brand-text hover:bg-brand-dark"
                >
                  {getLabel(item)}
                  {isSelected(item) && <span className="text-brand-accent">✓</span>}
                </button>
              </li>
            ))}
        </ul>
      )}
    </div>
  )
}