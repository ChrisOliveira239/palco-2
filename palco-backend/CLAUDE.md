# palco-backend — CLAUDE.md

API do Palco. Laravel + MySQL (`palco_2`) + Sanctum (auth por token, já instalado via `php artisan install:api`).

Ler também o [CLAUDE.md raiz](../CLAUDE.md) — princípios gerais (SOLID/MVC/Clean Code), o lembrete de que Christian está treinando arquitetura de propósito (explique o porquê das escolhas), e a regra de ritmo: **cada CRUD/relatório sai camada por camada** (migration → parar; Action → parar; endpoint → parar), nunca tudo de uma vez.

## Fluxo de uma request (o padrão a seguir)

```
Route → Controller (fino) → Form Request (validação) → Action (regra de negócio) → Model (Eloquent) → API Resource (formato de saída)
```

- **Controller**: só orquestra — recebe o Form Request já validado, chama a Action, devolve o Resource. Não tem `if` de regra de negócio.
- **Form Request** (`app/Http/Requests`): toda validação de entrada mora aqui, não no Controller.
- **Action** (`app/Actions/<Modulo>/VerboSubstantivo.php`, ex: `CreateEvent`, `ToggleEventFavorite`): 1 classe = 1 caso de uso = 1 método `handle()`. É aqui que SRP fica explícito — cada Action é testável isoladamente (Unit test) sem precisar subir HTTP.
- **Model** (`app/Models`): relacionamentos Eloquent e casts, sem lógica de negócio dentro do Model (nada de "fat model"). Scopes de query (`scopeInCity`, `scopeUpcoming`) podem morar no Model — são sobre *como buscar dado*, não regra de negócio.
- **API Resource** (`app/Http/Resources`): formato de saída da API, desacopla o shape JSON da estrutura da tabela.

### Por que não Repository Pattern
Eloquent **já é** a camada de abstração de persistência (Active Record). Empilhar um Repository genérico por cima (`EventRepositoryInterface` → `EloquentEventRepository`) não traz ganho real aqui — não vamos trocar de ORM, e widget de teste já dá pra mockar via `Model::factory()` ou banco de teste em memória. Isso seria abstração especulativa (viola YAGNI).

Onde interfaces **fazem sentido** de verdade: quando existe (ou vai existir em breve) mais de uma implementação real — ex. gateway de pagamento (Stripe vs Asaas, como no currículo do Christian), ou notificação (push vs email). Aí sim: `interface PaymentGateway` + implementações concretas, injetadas via container do Laravel (isso é DIP na prática, não só na teoria).

## Localização é campo de primeira classe

O core do produto é buscar eventos "na minha cidade / nas minhas cidades". Isso implica:
- `events.city_id` (ou `city` + `state`) é **obrigatório**, não opcional.
- Query principal de listagem sempre parte de um scope tipo `Event::inCities($cityIds)`, nunca um filtro que o front aplica depois de trazer tudo.
- Pensar em índice de banco em `city_id` desde a primeira migration do módulo Eventos — não é otimização prematura, é o caminho de acesso mais comum da aplicação.

## Testes

- **Feature tests** (`tests/Feature`): 1 arquivo por Controller/recurso, 1 teste por cenário de endpoint (Arrange/Act/Assert). Rodam contra SQLite em memória (`phpunit.xml` já configurado assim — rápido, isolado, não precisa do MySQL de dev).
- **Unit tests** (`tests/Unit`): Actions com lógica não-trivial, testadas sem HTTP.
- Rodar: `php artisan test` (ou `php artisan test --filter=NomeDoTeste`).

## Comandos úteis

```bash
php artisan make:model Event -mf          # model + migration + factory
php artisan make:request StoreEventRequest
php artisan make:resource EventResource
php artisan test
```