# Image Map

Map of every static image/video asset in the repo and where it's used. Updated 2026-07-11 (real photography + video hero integrated from temp_imgs; orphaned files removed).

All frontend images live under `visual_word_media/public/images/` and videos under `visual_word_media/public/videos/`. Assets are served from the site root (e.g. `public/images/foo.jpg` → `/images/foo.jpg`) per Vite's static-asset convention. `dist/` is the build output — an exact mirror — and is omitted below.

## Logo

| File | Used in |
|---|---|
| `images/logo.png` | [NavBar.vue](visual_word_media/src/components/NavBar.vue:5), [FooterBar.vue](visual_word_media/src/components/FooterBar.vue:7), [LoginView.vue](visual_word_media/src/views/LoginView.vue:5), [RegisterView.vue](visual_word_media/src/views/RegisterView.vue:5), [ForgotPasswordView.vue](visual_word_media/src/views/ForgotPasswordView.vue:5), [ResetPasswordView.vue](visual_word_media/src/views/ResetPasswordView.vue:5), [VerifyEmailView.vue](visual_word_media/src/views/VerifyEmailView.vue:5) |

## `videos/` — hero video banner (3 files, all used)

Cycled automatically every 8 s via `goToSlide()` in [HomeView.vue](visual_word_media/src/views/HomeView.vue). Each has a corresponding poster image in `images/homepage/`.

| File | Poster | Used in |
|---|---|---|
| `videos/hero-1.mp4` | `images/homepage/slide-1.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — hero video slot 1 |
| `videos/hero-2.mp4` | `images/homepage/slide-2.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — hero video slot 2 |
| `videos/hero-3.mp4` | `images/homepage/slide-3.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — hero video slot 3 |

## `images/homepage/` — homepage photos (10 files, all used)

| File | Used in |
|---|---|
| `slide-1.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — hero video poster 1 |
| `slide-2.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — hero video poster 2 |
| `slide-3.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — hero video poster 3 |
| `our-vision.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue:60), [AboutView.vue](visual_word_media/src/views/AboutView.vue:22) — Vision card |
| `our-mission.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue:65), [AboutView.vue](visual_word_media/src/views/AboutView.vue:28) — Mission card |
| `ministry-impact.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — IMPACT ministry wing card |
| `ministry-60x360.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — 60x360 ministry wing card |
| `ministry-oculus.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — OCULUS ministry wing card |
| `ministry-production.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — PRODUCTION ministry wing card |
| `ministry-new-life.jpg` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — NEW LIFE ministry wing card |

## `images/events/` — event type photos (5 files, all used)

Bound via `:src="et.image"` in [EventsView.vue:83](visual_word_media/src/views/EventsView.vue:83).

| File | Used in |
|---|---|
| `youth-camps.jpg` | [EventsView.vue](visual_word_media/src/views/EventsView.vue) — Youth Camps event type |
| `creative-gatherings.jpg` | [EventsView.vue](visual_word_media/src/views/EventsView.vue) — Creative Arts Gatherings event type |
| `bible-studies.jpg` | [EventsView.vue](visual_word_media/src/views/EventsView.vue) — Bible Studies event type |
| `training-workshops.jpg` | [EventsView.vue](visual_word_media/src/views/EventsView.vue) — Training Workshops event type |
| `prayer-leadership.jpg` | [EventsView.vue](visual_word_media/src/views/EventsView.vue) — Prayer & Leadership Meetings event type |

## `images/about/` — about page impact area photos (6 files, all used)

Bound via `:src="area.image"` in [AboutView.vue:94](visual_word_media/src/views/AboutView.vue:94).

| File | Used in |
|---|---|
| `youth-empowerment.jpg` | [AboutView.vue](visual_word_media/src/views/AboutView.vue) — Youth Empowerment Programs |
| `media-ministry.jpg` | [AboutView.vue](visual_word_media/src/views/AboutView.vue) — Media and Creative Ministry |
| `awareness-programs.jpg` | [AboutView.vue](visual_word_media/src/views/AboutView.vue) — Awareness & Educational Programs |
| `leadership-camps.jpg` | [AboutView.vue](visual_word_media/src/views/AboutView.vue) — Discipleship & Leadership Camps |
| `family-counseling.jpg` | [AboutView.vue](visual_word_media/src/views/AboutView.vue) — Family & Counseling Initiatives |
| `digital-outreach.jpg` | [AboutView.vue](visual_word_media/src/views/AboutView.vue) — Content Production & Digital Outreach |

## `images/ministries/` — ministry wing photos (9 files, all used)

Bound via `:src="camp.image"` (60x360 section) and `:src="a.image"` (OCULUS section) in [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue).

| File | Used in |
|---|---|
| `evangelism-camps.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Evangelism Camps (60x360) |
| `youth-discipleship-camps.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Youth Discipleship Camps (60x360) |
| `leadership-development.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Leadership Development Camps (60x360) |
| `village-ministry.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Village-Focused Ministry (60x360) |
| `artist-fellowships.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Artist Fellowships (OCULUS) |
| `creative-workshops.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Creative Workshops (OCULUS) |
| `art-exhibitions.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Art Exhibitions (OCULUS) |
| `creative-mentorship.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Creative Mentorship (OCULUS) |
| `culture-faith.jpg` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Culture & Faith (OCULUS) |

## `images/gemini/` — AI-generated illustrations (13 files, all used)

Remaining gemini images not yet replaced with real photography.

| File | Used in |
|---|---|
| `network105.png` | [HomeView.vue](visual_word_media/src/views/HomeView.vue) — NETWORK105 ministry wing card |
| `media-seminars.png` | [EventsView.vue](visual_word_media/src/views/EventsView.vue) — Media Awareness Seminars event type |
| `Competitions.png` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Competitions (OCULUS) |
| `Theology_Art.png` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Theology & Art (OCULUS) |
| `Bible_Studies_Artists.png` | [MinistriesView.vue](visual_word_media/src/views/MinistriesView.vue) — Bible Studies for Artists (OCULUS) |
| `articles.png` | [ResourcesView.vue:80](visual_word_media/src/views/ResourcesView.vue:80) |
| `bible-studies_2.png` | [ResourcesView.vue:81](visual_word_media/src/views/ResourcesView.vue:81) |
| `media-awareness.png` | [ResourcesView.vue:82](visual_word_media/src/views/ResourcesView.vue:82) |
| `family-guidance.png` | [ResourcesView.vue:83](visual_word_media/src/views/ResourcesView.vue:83) |
| `youth-discipleship.png` | [ResourcesView.vue:84](visual_word_media/src/views/ResourcesView.vue:84) |
| `creative-arts.png` | [ResourcesView.vue:85](visual_word_media/src/views/ResourcesView.vue:85) |
| `video-teachings.png` | [ResourcesView.vue:86](visual_word_media/src/views/ResourcesView.vue:86) |
| `audio-messages.png` | [ResourcesView.vue:87](visual_word_media/src/views/ResourcesView.vue:87) |

## `images/realistic/resources/` — resource card photos (6 files, all used)

Bound via `:src="r.image"` in [ResourcesView.vue:17](visual_word_media/src/views/ResourcesView.vue:17).

| File | Used in (source lines) |
|---|---|
| `media-awareness.jpg` | [ResourcesView.vue:64,71](visual_word_media/src/views/ResourcesView.vue:64) |
| `bible-studies.jpg` | [ResourcesView.vue:65](visual_word_media/src/views/ResourcesView.vue:65) |
| `video-teachings.jpg` | [ResourcesView.vue:66](visual_word_media/src/views/ResourcesView.vue:66) |
| `family-guidance.jpg` | [ResourcesView.vue:67,70](visual_word_media/src/views/ResourcesView.vue:67) |
| `audio-messages.jpg` | [ResourcesView.vue:68](visual_word_media/src/views/ResourcesView.vue:68) |
| `youth-discipleship.jpg` | [ResourcesView.vue:69,72](visual_word_media/src/views/ResourcesView.vue:69) |

## `images/meaningful/volunteer/` — volunteer role icons (8 files, all used)

Bound via `:src="role.image"` in [VolunteerView.vue:33](visual_word_media/src/views/VolunteerView.vue:33).

| File | Used in |
|---|---|
| `registration-success.svg` | [VolunteerView.vue:75](visual_word_media/src/views/VolunteerView.vue:75) |
| `prayer-partner.svg` | [VolunteerView.vue:278](visual_word_media/src/views/VolunteerView.vue:278) |
| `evangelism-outreach.svg` | [VolunteerView.vue:279](visual_word_media/src/views/VolunteerView.vue:279) |
| `youth-mentor.svg` | [VolunteerView.vue:280](visual_word_media/src/views/VolunteerView.vue:280) |
| `media-creative.svg` | [VolunteerView.vue:281](visual_word_media/src/views/VolunteerView.vue:281) |
| `teaching-discipleship.svg` | [VolunteerView.vue:282](visual_word_media/src/views/VolunteerView.vue:282) |
| `mission-partner.svg` | [VolunteerView.vue:283](visual_word_media/src/views/VolunteerView.vue:283) |
| `church-partnership.svg` | [VolunteerView.vue:284](visual_word_media/src/views/VolunteerView.vue:284) |

## `images/contact/` — contact info icons (4 files, all used)

| File | Used in |
|---|---|
| `office.svg` | [ContactView.vue:20](visual_word_media/src/views/ContactView.vue:20) |
| `phone.svg` | [ContactView.vue:27](visual_word_media/src/views/ContactView.vue:27) |
| `email.svg` | [ContactView.vue:34](visual_word_media/src/views/ContactView.vue:34) |
| `hours.svg` | [ContactView.vue:41](visual_word_media/src/views/ContactView.vue:41) |

## `images/stock/` — stock photography (2 files, all used)

| File | Used in |
|---|---|
| `photo-1469571486292-b53601020cb6.jpg` | [RegisterView.vue:66](visual_word_media/src/views/RegisterView.vue:66) |
| `photo-1590602847861-f357a9332bbc.jpg` | [TeachingsView.vue:73](visual_word_media/src/views/TeachingsView.vue:73) |

## Dynamic / runtime images (not static assets)

| Source | Used in | Notes |
|---|---|---|
| YouTube thumbnail URL | [vwm_backend/api/videos/index.php:108-119](vwm_backend/api/videos/index.php:108) → `:src="v.thumbnail"` in [TeachingsView.vue:44](visual_word_media/src/views/TeachingsView.vue:44) | Cached server-side in `youtube_cache` table (1h TTL) |

## Known issue

[index.html:5](visual_word_media/index.html:5) references `<link rel="icon" href="/favicon.svg">`, but no `favicon.svg` exists in `public/` — broken/missing asset.

## Summary

| Directory | Files | Used | Orphaned |
|---|---|---|---|
| `videos/` | 3 | 3 | 0 |
| `images/logo.png` | 1 | 1 | 0 |
| `images/homepage/` | 10 | 10 | 0 |
| `images/events/` | 5 | 5 | 0 |
| `images/about/` | 6 | 6 | 0 |
| `images/ministries/` | 9 | 9 | 0 |
| `images/gemini/` | 13 | 13 | 0 |
| `images/realistic/resources/` | 6 | 6 | 0 |
| `images/meaningful/volunteer/` | 8 | 8 | 0 |
| `images/contact/` | 4 | 4 | 0 |
| `images/stock/` | 2 | 2 | 0 |
| **Total** | **67** | **67** | **0** |
