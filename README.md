# Visual Word Media (VWM)

A faith-based media ministry platform (operating since 2000) with a two-part architecture: a **Vue 3 SPA frontend** and a **PHP/MySQL REST API + admin panel backend**, deployed to shared hosting (Hostinger).

---

## Repository Layout

```
vwm/
├── visual_word_media/      ← Vue 3 + Vite SPA frontend
├── vwm_backend/            ← PHP backend (REST API + session-based admin panel)
├── .github/workflows/      ← CI/CD: GitHub Pages (frontend) + SFTP (backend)
├── deploy.ps1              ← Manual PowerShell FTP deploy fallback for backend
├── CLAUDE.md               ← Claude Code guidance
└── .github/copilot-instructions.md  ← Detailed file-by-file map & DB schema
```

The two halves communicate **only over HTTP** — there is no shared build. The frontend has no knowledge of PHP; the backend has no knowledge of Vue.

---

## Frontend — `visual_word_media/`

**Stack:** Vue 3 (Composition API, `<script setup>`) · Vite 5 · Vue Router 4

### Commands

```bash
cd visual_word_media
npm install
npm run dev       # Vite dev server → http://localhost:5173
npm run build     # production build → dist/
npm run preview   # serve the production build locally
```

> No test suite, linter, or formatter is configured.

### Key Architecture Decisions

| Decision | Why |
|----------|-----|
| **Hash routing** (`createWebHashHistory`) | Avoids server-side 404s on shared hosting — no rewrite rules needed |
| **`base: './'`** in `vite.config.js` | Relative asset paths so the SPA works at domain root or any subpath |
| **`VITE_API_BASE`** env var | Points frontend at the Hostinger `/api` path without code changes |
| **`useBaseUrl` composable** (`img()`) | Prepends `BASE_URL` to image paths for correct resolution on GitHub Pages subpaths |

### Frontend Structure

```
visual_word_media/src/
├── main.js                  ← App entry; mounts Vue + router
├── App.vue                  ← Root: NavBar + RouterView + FooterBar
├── style.css                ← Global styles & CSS custom properties (--gold, --navy, etc.)
├── router/index.js          ← All routes; guards for requiresAuth + guestOnly
├── composables/
│   ├── useAuth.js           ← Shared auth state (module-level refs, shared across components)
│   └── useBaseUrl.js        ← img() helper for BASE_URL-aware image paths
├── services/
│   └── api.js               ← Generic fetch wrapper; auto-injects Bearer token
├── components/
│   ├── NavBar.vue           ← Sticky header with mobile hamburger + auth buttons
│   └── FooterBar.vue        ← Site footer
└── views/                   ← One .vue file per route
    ├── HomeView.vue         ← Landing page (public)
    ├── AboutView.vue        ← Story, vision, mission (public)
    ├── MinistriesView.vue   ← 6 ministry wings (public)
    ├── PrayerView.vue       ← Prayer requests (public)
    ├── PartnersView.vue     ← Partnership info (public)
    ├── ContactView.vue      ← Contact form (public)
    ├── TestimoniesView.vue  ← Public testimonies (public)
    ├── VolunteerView.vue    ← Volunteer registration form (public)
    ├── VerifyEmailView.vue  ← Email verification (public)
    ├── LoginView.vue        ← Login (guestOnly)
    ├── RegisterView.vue     ← Registration (guestOnly)
    ├── ForgotPasswordView.vue  ← Reset request (guestOnly)
    ├── ResetPasswordView.vue   ← Reset via token (guestOnly)
    ├── SetPasswordView.vue     ← Forced password set (requiresAuth)
    ├── EventsView.vue       ← Events list (requiresAuth)
    ├── TeachingsView.vue    ← Audio teachings (requiresAuth)
    └── ResourcesView.vue    ← Biblical/media resources (requiresAuth)
```

### Frontend Conventions

- **All HTTP goes through `src/services/api.js`** — never call `fetch` directly in views/composables. The wrapper auto-injects the `Authorization: Bearer <token>` header and unwraps the standard response shape `{ success, message, data }`.
- **Shared auth state** lives in `useAuth.js` using module-level refs — all components importing `useAuth()` share the same reactive `user` and `token`.
- **Protected routes** use `meta: { requiresAuth: true }`; guest-only routes (login, register) use `meta: { guestOnly: true }`. Router guards in `src/router/index.js` read the token from `localStorage` to enforce these.
- **Auth storage:** `vwm_token` and `vwm_user` in `localStorage`.
- **Static images** live under `public/images/` organized by section (`ministries/`, `events/`, `contact/`, `gemini/`, etc.).

### Auth Flow (Frontend)

```
useAuth.login(email, password)
  → api.post('/auth/login.php', { email, password })
  → stores vwm_token + vwm_user in localStorage
  → router guard checks token for protected routes
  → token sent as Bearer header on every subsequent API call (via api.js)
```

---

## Backend — `vwm_backend/`

**Stack:** PHP (no framework) · MySQL (PDO) · Hostinger shared hosting

### Backend Structure

```
vwm_backend/
├── config/
│   ├── db.php               ← PDO singleton getDB() — connects to MySQL on 127.0.0.1:3306
│   └── mail.php             ← Mail configuration
├── includes/
│   ├── cors.php             ← CORS headers + JSON error handler (allowed origins list)
│   ├── auth.php             ← Token auth: getAuthUser(), requireAuth(), requireAdmin()
│   └── mailer.php           ← Email sending helper
├── init_db.php              ← One-time DB setup: creates tables, seeds data (excluded from deploys)
├── api/                     ← REST API endpoints (Bearer token auth)
│   ├── auth/
│   │   ├── login.php            ← POST: email+password → token (24h expiry)
│   │   └── register.php         ← POST: name+email+password → new user
│   ├── events/index.php        ← GET (auth): active events; POST/PUT (admin): manage
│   ├── testimonials/index.php  ← GET (public): active testimonials; POST/PUT (admin): manage
│   ├── audio/index.php          ← GET (auth): audio teachings; POST (admin): upload
│   ├── videos/index.php         ← GET (auth): YouTube videos with 1-hour DB cache
│   ├── volunteers/
│   │   ├── index.php           ← POST (public): volunteer registration
│   │   └── verify.php          ← Volunteer verification
│   ├── books/
│   │   ├── index.php           ← Books listing
│   │   └── enquiry.php         ← Book enquiry submission
│   ├── contact/index.php       ← Contact form submission
│   ├── prayer/index.php        ← Prayer request submission
│   ├── home/index.php          ← Home page content
│   ├── resources/index.php     ← Resources listing
│   └── mail/send.php           ← Mail sending endpoint
└── admin/                    ← Session-based HTML admin panel (separate from API)
    ├── _auth.php               ← Session auth check (separate from API token auth)
    ├── _header.php / _footer.php  ← Shared HTML templates
    ├── index.php               ← Redirects → dashboard.php
    ├── dashboard.php           ← Admin home: role summary + stats
    ├── login.php / logout.php  ← Admin session login/logout
    ├── users.php               ← User management (CRUD)
    ├── members.php             ← Members management
    ├── events.php              ← Event management
    ├── testimonials.php        ← Testimony moderation
    ├── audio.php               ← Audio upload & management
    ├── books.php               ← Books management
    ├── resources.php           ← Resources management
    ├── volunteers.php          ← Volunteer application review
    ├── registrants.php         ← Registrant review
    ├── home-content.php        ← Home page content editor
    └── sql.php                 ← SQL utility
```

### Database Tables

`users`, `password_resets`, `events`, `testimonials`, `volunteer_registrations`, `audio_teachings`, `settings`, `youtube_cache`

### Backend Conventions

- **CORS-first:** Every API endpoint includes `includes/cors.php` **before any output**. Allowed origins: `localhost:5173`, `visualword.in`, `www.visualword.in`, `eloieloie.github.io`, and the Hostinger domain.
- **Standard response shape:** All API responses return `{ "success": bool, "message": string, "data": any }`.
- **PDO prepared statements** for all DB queries — no raw string interpolation.
- **Soft deletes:** Content tables (`events`, `testimonials`, `audio`) use `is_active` — never hard-delete.
- **YouTube cache:** `youtube_cache` table with 1-hour TTL to avoid API rate limits.
- **JSON columns** for multi-value fields (e.g. volunteer `ministry_areas`, `service_type`, `availability`).

---

## Two Independent Auth Systems

The platform has **two separate auth systems that do not share state**:

| | REST API | Admin Panel |
|---|---|---|
| **Used by** | Vue SPA frontend | Admin staff (server-rendered HTML) |
| **Mechanism** | Bearer token (`users.auth_token`, 24h expiry) | PHP sessions |
| **Storage** | `localStorage` (`vwm_token`, `vwm_user`) | Server-side session |
| **Guard** | `includes/auth.php` → `requireAuth()` / `requireAdmin()` | `admin/_auth.php` |
| **Login endpoint** | `api/auth/login.php` | `admin/login.php` |

---

## Deployment

### Frontend → GitHub Pages

- **Auto-deploys** on push to `master` or `dev` via `.github/workflows/deploy-pages.yml`
- Custom domain: **https://visualword.in**
- Build output: `dist/`

### Backend → Hostinger (SFTP)

- **Auto-deploys** on push to `master` when files under `vwm_backend/**` change via `.github/workflows/deploy-backend.yml`
- `init_db.php` is **always excluded** from deploys (contains one-time setup)
- Manual fallback: `./deploy.ps1` (PowerShell FTP), or `./deploy.ps1 -IncludeInitDb` for first-time DB setup

### Production URLs

| Service | URL |
|---------|-----|
| Frontend | https://visualword.in |
| API base | https://lightsalmon-porpoise-885538.hostingersite.com/api |
| Admin panel | https://lightsalmon-porpoise-885538.hostingersite.com/admin/ |

---

## Environment Variables

### Frontend (`visual_word_media/.env`)

```
VITE_API_BASE=https://lightsalmon-porpoise-885538.hostingersite.com/api
```

### Backend (`vwm_backend/config/db.php`)

Database credentials are hardcoded in `config/db.php` (Hostinger MySQL on `127.0.0.1:3306`).

---

## Further Reading

- **[CLAUDE.md](CLAUDE.md)** — Architecture summary for Claude Code
- **[.github/copilot-instructions.md](.github/copilot-instructions.md)** — Full file-by-file map of both apps, DB schema, and detailed conventions
