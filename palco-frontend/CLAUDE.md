# palco-frontend — CLAUDE.md

Painel do usuário final: buscar eventos/artistas/cursos por cidade, favoritar, se inscrever. React + TypeScript + Vite, empacotado como **PWA** (instalável, com service worker via `vite-plugin-pwa`, configurado em `vite.config.ts`).

Ler também [CLAUDE.md raiz](../CLAUDE.md) e [palco-admin/CLAUDE.md](../palco-admin/CLAUDE.md) — as convenções de estrutura por feature, TanStack Query + Context, e a seção "TypeScript para quem vem de JS" valem igual aqui. Este arquivo só cobre o que é específico do frontend.

## PWA — o que já está configurado

- `vite-plugin-pwa` gera o service worker e o manifest automaticamente no build (`npm run build`), sem precisar escrever nada na mão.
- `registerType: 'autoUpdate'`: versão nova do site substitui a antiga sozinha na próxima visita.
- Ícones em `public/pwa-192x192.png` e `public/pwa-512x512.png` são **placeholders** (cor sólida) — trocar por arte de verdade quando tiver identidade visual definida.
- Testar "instalável" de verdade só funciona em build de produção (`npm run build && npm run preview`), não no `npm run dev`.

## Caminho futuro: React Native

Quando o app nativo entrar em pauta, o que muda:
- Navegação: `react-router-dom` → `react-navigation` (conceito de rota é parecido, API é diferente).
- Nada de manifest PWA / service worker — isso é conceito só de web.
- `localStorage`/`Context` de auth → `AsyncStorage` ou `SecureStore` pra guardar o token.
- A camada `features/*/api.ts` e `hooks.ts` (TanStack Query) tende a ser reaproveitável quase sem mudança — é por isso que vale manter a lógica de dados separada de componente visual desde já.

## Core: busca por localização

Tela principal = lista de eventos filtrada pela(s) cidade(s) que o usuário escolheu (ver [CLAUDE.md raiz](../CLAUDE.md)). Isso é o que aparece primeiro ao abrir o app — não é uma aba de filtro secundária.

## Testes

E2E (Playwright) mora em `../e2e/tests/frontend/`. Specs cobrem o fluxo do usuário final (buscar por cidade → ver evento → favoritar), não teste unitário de componente isolado por enquanto.

## Comandos

```bash
npm run dev       # :5174
npm run build     # gera dist/ com service worker + manifest
npm run preview   # testa o build de produção (PWA só funciona aqui)
```