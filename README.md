# Palco

Plataforma cultural: agenda de eventos artísticos, rede de colaboração entre artistas e ferramenta de formação cultural.

Monorepo com 3 projetos:

| Pasta            | O que é                              | Stack                          |
|------------------|---------------------------------------|---------------------------------|
| `palco-backend`  | API                                    | Laravel (PHP), MySQL, Sanctum   |
| `palco-admin`    | Painel interno (cadastro, relatórios)  | React + TypeScript + Vite       |
| `palco-frontend` | Painel do usuário (PWA)                | React + TypeScript + Vite + PWA |

Ver [CLAUDE.md](./CLAUDE.md) para princípios de arquitetura e [ROADMAP.md](./ROADMAP.md) para as fases de desenvolvimento.

## Rodando localmente

```bash
# instala tudo (raiz + 3 sub-projetos)
npm install
composer install --working-dir=palco-backend

# sobe backend (:8000) + admin (:5173) + frontend (:5174) juntos
npm run dev
```

Cada sub-projeto também roda isolado — ver o `CLAUDE.md` de cada pasta.

## Testes

```bash
# backend
cd palco-backend && php artisan test

# E2E (admin + frontend, com os dev servers de pé)
npx playwright test
```