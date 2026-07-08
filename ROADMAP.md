# Roadmap — Palco

Fases baseadas nos módulos do PDF de produto. Regra: **um módulo por vez, ponta a ponta** (back → admin → frontend → testes) antes de abrir o próximo. Fase 1 detalhada; demais fases em bullets — ganham detalhe quando chegar a vez delas.

## Fase 0 — Setup (hoje)
- Monorepo, git, scaffold dos 3 projetos (Laravel + Sanctum, 2x React/TS/Vite), Playwright, CLAUDE.md de cada pasta.

## Fase 1 — Auth + Módulo Eventos (busca por localização é o core)
Este é o módulo central do app: listar eventos dos próximos dias e facilitar a compra/participação gratuita, **filtrando por cidade ou cidades escolhidas pelo usuário**. Localização não é um filtro a mais — é o eixo de navegação.

- **Auth**: registro/login (Sanctum, token), papéis básicos (`user`, `admin` — papéis mais ricos tipo artista/organização vêm na Fase 3/4).
- **Localização**: usuário define cidade(s) de interesse (ao menos 1, pode seguir mais de uma). Evento tem cidade obrigatória. Listagem principal sempre filtrada pela(s) cidade(s) do usuário por padrão.
- **Módulo Eventos** (campos-chave do PDF): título, sinopse, tipo, data/hora (com recorrência), local, cidade, valor de entrada (gratuito / faixa / pague quanto puder), cartaz, tags/categorias.
- **Ações do usuário**: favoritar, lembrar-me, ver detalhes, compartilhar. (Comprar ingresso pode ficar como link externo por enquanto — gateway de pagamento próprio é decisão de fase futura.)
- **Admin**: CRUD de eventos, lista de presença.
- **Testes**: Feature tests (PHPUnit) por endpoint; specs Playwright do fluxo "usuário busca evento na cidade X e favorita".

## Fase 2 — Mostras e Festivais
- Agrupam múltiplos eventos sob uma "temporada"/mostra, mesma lógica de localização.

## Fase 3 — Artistas
- Perfil de artista (bio, competências, portfólio, eventos que participou), vínculo com eventos da Fase 1.

## Fase 4 — Organizações / Cias / Escolas + Apoiadores
- Perfis de organização, vínculo com eventos e artistas. Apoiadores/patrocinadores como módulo simples de exibição.

## Fase 5 — Colaboração e Comunidade
- Radar de colaboração, currículo artístico, classificados criativos, indicações, chat/inbox (pt. 2, pode ficar pra depois do MVP).

## Fase 6 — Notificações Inteligentes
- Baseadas em favoritos, quem o usuário segue, e localização.

## Fase 7 — Cursos e Formação Artística
- Módulo similar ao de Eventos (cadastro, busca por categoria/localização/valor/data), avaliação pós-curso.

## Fase 8 — Recursos adicionais e monetização
- Filtros avançados, mapa interativo, modo offline (PWA), reputação/avaliação, perfis verificados, painel admin web completo, patrocínios, taxa sobre ingressos, doações.
