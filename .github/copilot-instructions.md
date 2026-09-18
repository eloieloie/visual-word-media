# Visual Word Media — Project Guidelines

## Project Overview

Faith-based media ministry platform (Visual Word Media, operating since 2000).  
Two-part architecture: a Vue 3 SPA frontend and a PHP/MySQL backend API, deployed to shared hosting.

---

## Folder Structure & Relationships

```
vwm/
├── visual_word_media/   ← Vue 3 frontend (Vite)
├── vwm_backend/         ← PHP backend (REST API + Admin panel)
└── deploy.ps1           ← PowerShell FTP deployment script for the backend
```

### `visual_word_media/` — Frontend SPA

Built with **Vue 3 + Vite**, using hash-based routing (`/#/path`) and a relative `base` path for flexible deployment.

```
visual_word_media/
├── src/
│   ├── main.js                  ← App entry point; mounts Vue + router
│   ├── App.vue                  ← Root component (NavBar + RouterView + FooterBar)
│   ├── style.css                ← Global styles
│   ├── router/index.js          ← All routes; guards for requiresAuth + guestOnly meta
│   ├── composables/useAuth.js   ← Shared auth state (login, logout, register, token)
│   ├── services/api.js          ← Generic fetch wrapper; injects Bearer token automatically
│   ├── components/
│   │   ├── NavBar.vue           ← Sticky header with mobile hamburger + auth buttons
│   │   └── FooterBar.vue        ← Site footer
│   └── views/
│       ├── HomeView.vue         ← Landing page; ministry overview + 6 ministry wings
│       ├── AboutView.vue        ← Story, vision, mission, impact stats
│       ├── MinistriesView.vue   ← Details on 6 ministry wings
│       ├── PrayerView.vue       ← Prayer requests / intercession info
│       ├── PartnersView.vue     ← Partnership opportunities
│       ├── ContactView.vue      ← Contact form + info
│       ├── TestimoniesView.vue  ← Public testimonies filtered by category
│       ├── VolunteerView.vue    ← Public volunteer registration form
│       ├── LoginView.vue        ← Auth login form (guestOnly route)
│       ├── RegisterView.vue     ← User registration (guestOnly route)
│       ├── ForgotPasswordView.vue  ← Password reset request (guestOnly route)
│       ├── ResetPasswordView.vue   ← Reset via token (guestOnly route)
│       ├── EventsView.vue       ← Filterable events list (requiresAuth)
│       ├── TeachingsView.vue    ← Audio teachings (requiresAuth)
│       └── ResourcesView.vue   ← Biblical/media resources (requiresAuth)
└── public/
    ├── admin/index.html         ← Redirect shim to backend admin panel
    └── images/                  ← Static image assets organised by section
```

**Auth flow (frontend):**  
`useAuth` composable → `api.post('/auth/login.php')` → stores `vwm_token` + `vwm_user` in `localStorage` → router guard checks token for protected routes.

**API base URL** is set via `VITE_API_BASE` environment variable (see `visual_word_media/.env`).  
Production value: `https://lightsalmon-porpoise-885538.hostingersite.com/api`

---

### `vwm_backend/` — PHP Backend

Runs on shared hosting (Hostinger). Serves both a REST API and an HTML admin panel.

```
vwm_backend/
├── config/db.php          ← PDO singleton `getDB()` — connects to MySQL on 127.0.0.1:3306
├── includes/
│   ├── cors.php           ← CORS headers (allowed origins: localhost:5173, visualword.in, hostingersite.com)
│   └── auth.php           ← Token auth helpers: getAuthUser(), requireAuth(), requireAdmin()
├── init_db.php            ← One-time DB setup: creates users + events tables, seeds events
├── api/
│   ├── auth/
│   │   ├── login.php          ← POST: email+password → token (24h expiry)
│   │   ├── register.php       ← POST: name+email+password → new user
│   │   ├── forgot-password.php← POST: email → password reset token via email
│   │   └── reset-password.php ← POST: token+new_password → updates password
│   ├── events/index.php       ← GET (auth): active events; POST/PUT (admin): create/edit
│   ├── testimonials/index.php ← GET (public): active testimonials; POST/PUT (admin): manage
│   ├── audio/index.php        ← GET (auth): audio teachings + file URLs; POST (admin): upload
│   ├── videos/index.php       ← GET (auth): YouTube videos with 1-hour DB cache
│   └── volunteers/index.php   ← POST (public): volunteer registration form submission
└── admin/
    ├── _auth.php          ← Session-based auth check (separate from API token auth)
    ├── _header.php        ← Shared HTML header template
    ├── _footer.php        ← Shared HTML footer template
    ├── index.php          ← Redirects → dashboard.php
    ├── dashboard.php      ← Admin home: role summary + stats
    ├── login.php          ← Admin login form (session-based)
    ← logout.php           ← Clears session
    ├── users.php          ← User management (CRUD)
    ├── events.php         ← Event management
    ├── testimonials.php   ← Testimony moderation
    ├── audio.php          ← Audio upload & management
    ├── volunteers.php     ← Volunteer application review
    └── migrate.php        ← Creates additional tables + seeds sample data (requires admin login)
```

**Database tables:** `users`, `password_resets`, `events`, `testimonials`, `volunteer_registrations`, `audio_teachings`, `settings`, `youtube_cache`

**Auth model:**
- REST API uses **Bearer token** (stored in `users.auth_token`, expires 24h)
- Admin panel uses **PHP sessions** (managed by `admin/_auth.php`)
- Two separate auth systems that do not share state

---

## Architecture Decisions

- **Hash routing** (`createWebHashHistory`) on the frontend avoids server-side 404s on shared hosting
- **`is_active` soft deletes** on content tables (events, testimonials, audio) — never hard-delete records
- **JSON columns** (`ministry_areas`, `service_type`, `availability` on volunteers) for flexible multi-select data
- **YouTube cache** in DB (`youtube_cache` table) — 1-hour TTL to avoid API rate limits
- **CORS-first** backend — every API file includes `cors.php` before any output
- Admin panel is entirely session-based (not SPA) and lives at `/admin/` on the backend host

---

## Conventions

- All API responses use the shape: `{ "success": bool, "message": string, "data": any }`
- All DB queries use **PDO prepared statements** (no raw interpolation)
- Frontend API calls go through `src/services/api.js` — never use `fetch` directly in views/composables
- Protected frontend routes use `meta: { requiresAuth: true }` in the router
- Guest-only routes (login, register) use `meta: { guestOnly: true }` in the router
- Static images live under `public/images/` organised by section (`contact/`, `meaningful/events/`, etc.)

---

## Build & Deploy

```bash
# Frontend — build
cd visual_word_media
npm install
npm run build       # outputs to dist/

# Backend — deploy via FTP
./deploy.ps1                        # uploads vwm_backend/ (excludes init_db.php)
./deploy.ps1 -IncludeInitDb         # also uploads init_db.php for first-time setup
```

Production URLs:
- **Frontend:** https://visualword.in
- **API base:** https://lightsalmon-porpoise-885538.hostingersite.com/api
- **Admin panel:** https://lightsalmon-porpoise-885538.hostingersite.com/admin/
