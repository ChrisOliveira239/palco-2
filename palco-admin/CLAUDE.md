# palco-admin — CLAUDE.md

Painel interno (gestão): cadastro de eventos, artistas, organizações, relatórios. React + TypeScript + Vite. Público-alvo: quem administra o Palco, não o usuário final.

Ler também [CLAUDE.md raiz](../CLAUDE.md) — inclui a regra de ritmo: **cada tela de CRUD/relatório sai em pedaços** (lista → parar; formulário → parar; ligação com a API/relatório → parar), nunca tudo de uma vez.

## TypeScript para quem vem de JS

Christian conhece bem JS/React, mas TS ainda é ponto fraco — sempre que um conceito novo aparecer no código (generics, `interface` vs `type`, union/discriminated union, utility types como `Pick`/`Omit`), **parar e explicar com exemplo antes de seguir**, não assumir conhecimento prévio. Regra combinada com ele: quando disser `/explique`, dar explicação básica e exemplificada do que acabou de ser feito.

Ideia geral (nível 0, só pra fixar o vocabulário):
- `interface Event { title: string }` — descreve o "formato" de um objeto. Se uma função recebe algo desse formato, o TS avisa em tempo de build se faltar campo ou o tipo estiver errado (isso substitui boa parte do PropTypes que existia em JS).
- Props de componente também são tipadas: `function EventCard({ title }: { title: string })`.
- Isso vai aparecer bastante ao tipar as respostas da API (Fase 1: `Event`, `PaginatedResponse<Event>`, etc.) — cada um desses é uma aula rápida quando chegar a vez.

## Estrutura de pastas — por feature, não por tipo técnico

```
src/
├── features/
│   └── eventos/
│       ├── api.ts            # chamadas HTTP desse recurso
│       ├── hooks.ts           # hooks de TanStack Query (useEvents, useEvent, useCreateEvent...)
│       ├── types.ts            # tipos TS do domínio Eventos
│       └── components/         # componentes só usados aqui (EventForm, EventTable...)
├── shared/
│   ├── api/client.ts            # instância axios (baseURL, interceptor de auth token)
│   └── components/              # componentes realmente genéricos (Button, Modal...)
└── app/
    ├── router.tsx                # react-router
    └── AuthContext.tsx            # Context API — só estado de sessão/UI
```

Evita `src/components`, `src/services`, `src/hooks` genéricos crescendo sem fim — quem mexe no módulo Eventos mexe só em `features/eventos`.

## Componentes — reusar antes de criar

Antes de implementar uma tela nova (lista, formulário, relatório):

1. **Procurar primeiro** se já existe componente pronto em `shared/components/` (ou em outra feature, se fizer sentido reusar) — não escrever de novo o que já existe.
2. **Se não existir**, avaliar se o pedaço em questão é candidato a componente reusável (paginação, tabela genérica, input de filtro, modal, etc.) *antes* de implementar a tela — nascer já em `shared/components/` se a resposta for sim, em vez de escrever solto na feature e extrair depois.
3. **Criar componente local livremente** (dentro de `features/<recurso>/components/`) só quando for algo específico/isolado daquela tela — não força abstração em `shared/` pra algo que só uma tela usa.

Toda tela de lista/CRUD nasce dividida por responsabilidade — nunca um arquivo monolítico com estado+fetch+filtros+tabela juntos: um componente "Page" (dono do estado e dos hooks de fetch) compõe `Filters` + `Table`/`List` + `Pagination` (esta última reusada de `shared/components/`, não recriada por tela).

## Dados de servidor: TanStack Query. Estado de UI: Context

- **TanStack Query** cuida de toda chamada que busca/muta dado do backend (lista de eventos, criar evento, etc.) — ele já resolve cache, loading, erro, refetch. Não escrever `useEffect` + `fetch` manual pra isso.
- **Context API** só pra estado que não vem do servidor: usuário autenticado, tema, sidebar aberta/fechada. Nunca guardar lista de eventos num Context — isso é trabalho do Query.

## Testes

E2E (Playwright) mora em `../e2e/tests/admin/`, não dentro desta pasta — ver [CLAUDE.md raiz](../CLAUDE.md). Specs cobrem fluxo completo (login admin → CRUD de evento), não teste unitário de componente isolado por enquanto.

## Comandos

```bash
npm run dev      # :5173
npm run build
npm run lint
```