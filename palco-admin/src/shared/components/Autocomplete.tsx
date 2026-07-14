import { useEffect, useState } from 'react'
import { useDebouncedValue } from '../hooks/useDebouncedValue'

const SEARCH_DEBOUNCE_MS = 400

type AutocompleteProps<T> = {
  items: T[]
  getLabel: (item: T) => string
  getValue: (item: T) => string | number
  value: T | undefined
  onChange: (item: T | undefined) => void
  onSearchChange: (search: string) => void
  isLoading?: boolean
  placeholder?: string
}

export function Autocomplete<T>({
  items,
  getLabel,
  getValue,
  value,
  onChange,
  onSearchChange,
  isLoading,
  placeholder,
}: AutocompleteProps<T>) {
  const [inputText, setInputText] = useState(value ? getLabel(value) : '')
  const [isOpen, setIsOpen] = useState(false)
  const debouncedInputText = useDebouncedValue(inputText, SEARCH_DEBOUNCE_MS)

  useEffect(() => {
    setInputText(value ? getLabel(value) : '')
    // getLabel de propósito fora do array: é recriada a cada render do
    // wrapper (ex. CityAutocomplete), e essa sincronização só deve rodar
    // quando o item selecionado muda de verdade — senão apaga o texto
    // digitado a cada tecla, antes do usuário conseguir selecionar.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [value])

  useEffect(() => {
    onSearchChange(debouncedInputText)
  }, [debouncedInputText, onSearchChange])

  function handleSelect(item: T) {
    onChange(item)
    setInputText(getLabel(item))
    setIsOpen(false)
  }

  function handleClear() {
    onChange(undefined)
    setInputText('')
    setIsOpen(false)
  }

  return (
    <div className="relative">
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
        className="w-full rounded border border-gray-300 px-3 py-2 pr-8"
      />
      {value && (
        <button
          type="button"
          onMouseDown={handleClear}
          aria-label="Limpar seleção"
          className="absolute inset-y-0 right-2 text-gray-400 hover:text-gray-600"
        >
          &times;
        </button>
      )}
      {isOpen && (
        <ul className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded border border-gray-300 bg-white shadow">
          {isLoading && <li className="px-3 py-2 text-gray-500">Carregando...</li>}
          {!isLoading && items.length === 0 && <li className="px-3 py-2 text-gray-500">Nenhum resultado.</li>}
          {!isLoading &&
            items.map((item) => (
              <li key={getValue(item)}>
                <button
                  type="button"
                  onMouseDown={() => handleSelect(item)}
                  className="block w-full px-3 py-2 text-left hover:bg-gray-100"
                >
                  {getLabel(item)}
                </button>
              </li>
            ))}
        </ul>
      )}
    </div>
  )
}
