# Palco — CLAUDE.md (raiz)

Agenda cultural: eventos, artistas, organizações, cursos e colaboração. Ver PDF original do produto para o escopo completo de módulos (não versionado aqui — pedir ao Christian se precisar consultar de novo).

**Core do produto**: facilitar a busca de eventos e artistas **por localização** — cidade do usuário ou cidades selecionadas por ele. Filtro de cidade/região não é um filtro avançado a mais, é o eixo principal de navegação do app (listagem de eventos, busca de artistas, tudo passa por "nessa cidade / nessas cidades"). Ter isso em mente ao desenhar schema, endpoints e telas — localização é cidadã de primeira classe, não um campo opcional.

## Quem sou eu neste projeto

Christian é fullstack sênior (4+ anos JS/PHP/Laravel/React em produção). Neste projeto ele quer **duas coisas ao mesmo tempo**: entregar o produto E treinar deliberadamente SOLID / Clean Code / MVC — então as decisões de arquitetura devem vir com o **porquê**, não só o "o quê". Quando a alternativa mais simples e a "mais correta" divergirem, mostrar as duas e explicar o trade-off em vez de escolher calado.

**TypeScript é ponto fraco dele.** Ele já mexeu com JS/React por anos mas tem pouca bagagem em TS. Sempre que um conceito de TS novo aparecer (generics, utility types, discriminated unions, etc.), parar e explicar antes de seguir — não assumir que ele já sabe só porque é sênior em JS.

**Comando `/explique`**: sempre que Christian disser `/explique`, dar uma explicação básica e exemplificada do que acabou de ser feito (o código anterior, a decisão tomada, etc.) — assumir que ele quer entender do zero, com exemplo prático, não só a definição formal do conceito.

**Ritmo de cada CRUD/relatório: com calma, nunca "de uma vez".** Cada CRUD e cada relatório (de qualquer módulo — Eventos, Artistas, Cursos, etc.) deve ser planejado e construído em etapas pequenas e discutidas, não gerado inteiro numa tacada só. Na prática: antes de sair escrevendo migration+model+controller+tela de um recurso inteiro, parar em cada camada (ex: migration → parar; Action de criação → parar; endpoint → parar; tela → parar), explicar a decisão daquele pedaço, e só avançar pro próximo pedaço quando ele confirmar. Isso vale tanto pra `palco-backend` (CRUDs, Actions, relatórios via query/Resource) quanto pra `palco-admin`/`palco-frontend` (telas de CRUD, tabelas de relatório). Não assumir "modo rápido" mesmo que o pedido pareça simples — perguntar o nível de detalhe se não estiver claro.

## Stack

| Camada    | Tech |
|-----------|------|
| Backend   | PHP 8.2 + Laravel, MySQL, Sanctum (auth por token) |
| Admin     | React + TypeScript + Vite |
| Frontend  | React + TypeScript + Vite (PWA) — plano futuro: React Native |
| Testes    | PHPUnit (backend), Playwright (E2E cross-app) |

## Estrutura do monorepo

```
palco-2/
├── palco-backend/    # API Laravel — ver palco-backend/CLAUDE.md
├── palco-admin/      # painel interno React — ver palco-admin/CLAUDE.md
├── palco-frontend/   # painel do usuário (PWA) React — ver palco-frontend/CLAUDE.md
├── e2e/              # Playwright, testa admin + frontend juntos
├── ROADMAP.md        # fases de desenvolvimento (baseado no PDF do produto)
└── package.json      # scripts raiz (dev concorrente, playwright)
```

Cada sub-pasta tem seu próprio `CLAUDE.md` com convenções específicas da stack — leia o daquela pasta antes de mexer nela. Este arquivo raiz é só o que é comum aos três.

## Princípios (valem nos 3 projetos)

- **MVC**: separar dado (Model), regra de exibição (View/componente) e regra de negócio (Controller/Action) — regra de negócio nunca mora dentro de Controller nem de componente React.
- **SOLID**: aplicado de forma pragmática, não cerimonial. Cada classe/módulo com uma razão de mudar (SRP); abrir extensão por composição/interface em vez de editar o que já funciona (OCP); interfaces só onde existir mais de uma implementação real, não "por via das dúvidas" (evitar abstração especulativa, que é o oposto de YAGNI).
- **Clean Code**: nomes que dizem o que a coisa é, funções pequenas e com um nível de abstração, comentário só quando o código não consegue explicar o "porquê" sozinho.
- Não adicionar tratamento de erro, fallback, ou abstração para casos que não existem ainda. Três linhas parecidas > abstração prematura.

## Convenção de commits

Conventional Commits (`feat:`, `fix:`, `refactor:`, `test:`, `docs:`, `chore:`) — mensagem curta no assunto, corpo só quando o "porquê" não for óbvio pelo diff.

## Fases de desenvolvimento

Ver [ROADMAP.md](./ROADMAP.md). Regra geral: **um módulo do produto por vez, ponta a ponta** (migration → Action/Controller → tela admin → tela usuário → teste) antes de abrir o próximo módulo. Evita ter 8 módulos "meio prontos" ao mesmo tempo. Dentro de cada módulo, vale a regra de ritmo acima: CRUD e relatório construídos camada por camada, com calma — nunca tudo de uma vez.
