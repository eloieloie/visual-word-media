# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> A detailed companion doc lives at [.github/copilot-instructions.md](.github/copilot-instructions.md) — it has the full file-by-file map of both apps and the DB schema. Read it when you need specifics beyond the architecture summary below.

## Repository layout

This is a two-part monorepo for **Visual Word Media**, a faith-based media ministry platform:

- [visual_word_media/](visual_word_media/) — Vue 3 + Vite SPA frontend
- [vwm_backend/](vwm_backend/) — PHP/MySQL REST API **and** a separate session-based HTML admin panel, on Hostinger shared hosting
- [deploy.ps1](deploy.ps1) — manual PowerShell FTP deploy for the backend

The two halves communicate only over HTTP — there is no shared build. The frontend has no knowledge of PHP; the backend has no knowledge of Vue.

## Commands

All frontend commands run from `visual_word_media/`:

```bash
cd visual_word_media
npm install
npm run dev       # Vite dev server on http://localhost:5173
npm run build     # outputs to dist/
npm run preview   # serve the production build locally
```

There is **no test suite, linter, or formatter** configured in either app — do not assume `npm test` / `npm run lint` exist.

The backend (PHP) has no build step. To run it locally you need a PHP + MySQL environment; in practice it is developed against the live Hostinger host.

## Deployment

- **Frontend** auto-deploys to GitHub Pages on push to `master` or `dev` ([.github/workflows/deploy-pages.yml](.github/workflows/deploy-pages.yml)). Custom domain: https://visualword.in
- **Backend** auto-deploys via SFTP on push to `master` when files under `vwm_backend/**` change ([.github/workflows/deploy-backend.yml](.github/workflows/deploy-backend.yml)). `init_db.php` is always excluded from deploys.
- `deploy.ps1` is the manual FTP fallback for the backend (`./deploy.ps1`, or `-IncludeInitDb` for first-time DB setup). Note it contains hardcoded credentials.

## Architecture essentials

**Two independent auth systems that do not share state:**
1. The **REST API** uses Bearer tokens (`users.auth_token`, 24h expiry). The Vue app stores the token + user in `localStorage` (`vwm_token`, `vwm_user`).
2. The **admin panel** (`vwm_backend/admin/`) uses PHP sessions, gated by `admin/_auth.php`. It is a server-rendered HTML app, not part of the SPA.

**Frontend conventions (enforce these):**
- All HTTP goes through [visual_word_media/src/services/api.js](visual_word_media/src/services/api.js) — never call `fetch` directly in views/composables. It auto-injects the Bearer token and unwraps the standard response shape.
- Shared auth state lives in [visual_word_media/src/composables/useAuth.js](visual_word_media/src/composables/useAuth.js) (module-level refs, shared across components).
- Routing is **hash-based** (`createWebHashHistory`) and `vite.config.js` uses `base: './'` — both deliberate, so the SPA works on shared hosting / subpaths without server rewrites. Don't switch to history mode.
- Protected routes use `meta: { requiresAuth: true }`; login/register use `meta: { guestOnly: true }`. Router guards in `src/router/index.js` read the token to enforce these.
- API base URL comes from `VITE_API_BASE` (in `visual_word_media/.env`), pointing at the Hostinger `/api` path.

**Backend conventions:**
- Every API endpoint includes `includes/cors.php` **before any output** — CORS-first.
- All API responses use the shape `{ "success": bool, "message": string, "data": any }`.
- All DB access uses PDO prepared statements via the `getDB()` singleton in `config/db.php`.
- Content tables (`events`, `testimonials`, `audio`) use `is_active` **soft deletes** — never hard-delete.
- YouTube data is cached in the `youtube_cache` table with a 1-hour TTL to avoid API rate limits.
- Some multi-value fields are stored as JSON columns (e.g. volunteer `ministry_areas`, `service_type`, `availability`).

## UI & Frontend Rules

- Use **UI/UX Pro Max** to search color palettes, generate `MASTER.md`, and verify WCAG accessibility contrast ratios.
- Apply **Anthropic Frontend Design** guidelines for typography jumps, hero layouts, and avoiding generic SaaS template patterns.

## UI Animation & Motion Rules

The frontend uses [motion-v](https://motion-vue.com/) (the Vue port of Motion, formerly Framer Motion) for animation — installed in `visual_word_media/package.json`.

- **Default animation engine:** Use `motion-v` (`<motion.div :animate="{ x: 100 }" />`) for interactive UI state transitions, modals, tooltips, and route changes — not hand-rolled CSS keyframes/transitions.
- **Hardware acceleration:** Animate only `opacity` and `transform`/`scale`/`translate`. Avoid animating `height`, `width`, or `margin` directly — use the `layout` prop for size/position changes instead.
- **Accessibility:** Respect reduced-motion preferences via `useReducedMotion()`.
- **Exit transitions:** Wrap conditionally rendered components in `<AnimatePresence>` so unmounts animate out instead of popping.

## graphify

This project has a knowledge graph at graphify-out/ with god nodes, community structure, and cross-file relationships.

Rules:
- For codebase questions, first run `graphify query "<question>"` when graphify-out/graph.json exists. Use `graphify path "<A>" "<B>"` for relationships and `graphify explain "<concept>"` for focused concepts. These return a scoped subgraph, usually much smaller than GRAPH_REPORT.md or raw grep output.
- If graphify-out/wiki/index.md exists, use it for broad navigation instead of raw source browsing.
- Read graphify-out/GRAPH_REPORT.md only for broad architecture review or when query/path/explain do not surface enough context.
- After modifying code, run `graphify update .` to keep the graph current (AST-only, no API cost).
