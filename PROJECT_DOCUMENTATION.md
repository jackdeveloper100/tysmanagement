# TYS Time & Project Management – Project Documentation

> **Update:** the code was upgraded for PHP 8 after this document was written – see [UPGRADE_PHP8.md](UPGRADE_PHP8.md). The versions, PHPExcel/Swiftmailer/Sentry/Dotenv details and the PHP-version notes below describe the *old* PHP 7 setup; a `composer.json` now exists.

> Generated from the code in this repository only. Anything not found in the code is marked **"Not found in codebase"**. No secret values are reproduced here; the `.env` file and the files under `*/runtime/logs/` do contain real secrets (see [Security notes](#16-security-notes--known-issues)).

---

## 1. Project overview and features

A **Yii2 "advanced"-style application** (separate `backend`, `frontend`, `common`, `console`) used by a creative agency (labels in the UI/code reference "TYS", "SCE", "Slack", "Jira") to:

- Track **projects ("jobs")** per **client**, each with a manager, status, budget hours, links, Slack/Docs/Jira references and accounting notes.
- Track **sub-jobs** per project, optionally linked to an external SCE job number.
- Let employees log **time entries ("tasks")** against a job/sub-job, by day or for a whole week.
- Run **accounting reports** and export them to Excel (`.xls`).
- Manage **artwork "comps"** (design comps): upload files, keep revision history per item, create named **versions** (snapshots) of a comp, generate previews/thumbnails (images and PDFs).
- Manage **clients**, client **logins** (client portal users), client **domains** and **hosting** records (expiration/auto-renew tracking).
- A **client portal** (`frontend`) where client users see only their client's projects and artwork versions.
- A small **JSON/XML API** (token auth) for hours logging and project lookups.
- Console tools to create users and import legacy data from CSV URLs.

| App | Entry | Audience | Identity class |
|---|---|---|---|
| `backend` (app id `mcborax`) | `backend/web/index.php` | Internal staff | `common\models\User` |
| `frontend` (app id `app-frontend`) | `frontend/web/index.php` | Client portal users | `common\models\ClientLogin` |
| `console` (app id `app-console`) | `./yii` | Developers/ops | – |

---

## 2. Tech stack and versions

**No `composer.json`/`composer.lock` exists in the repo** – only the installed `vendor/` directory. Versions below come from `vendor/composer/installed.json`.

**Language / runtime**
- PHP version is **not declared anywhere**. The code uses `yii\base\Object` (`console/components/MigrationProxy.php`), which only works on **PHP ≤ 7.1** (this name is reserved from PHP 7.2). Treat PHP 5.6–7.1 as the realistic target (inference, not stated in the code).
- PHP extension **Imagick** is required (`common/config/bootstrap.php` configures Intervention Image with driver `imagick`; `ArtworkItemState` uses `\Imagick` for PDF previews).
- MySQL/MariaDB (SQL such as `ON UPDATE CURRENT_TIMESTAMP`, `NOW()`, `DATE()`, `CONCAT()`).
- Apache with `mod_rewrite` (`backend/web/.htaccess`; no `.htaccess` in `frontend/web`).

**PHP packages (selected)**

| Package | Version |
|---|---|
| yiisoft/yii2 | 2.0.12 |
| yiisoft/yii2-bootstrap / -jui / -swiftmailer / -composer | 2.0.6 / 2.0.6 / 2.0.7 / 2.0.5 |
| kartik-v/yii2-grid, yii2-krajee-base | 3.0.8, 1.8.8 |
| swiftmailer/swiftmailer | 5.4.8 |
| vlucas/phpdotenv | 2.2.1 |
| mito/yii2-sentry, sentry/sentry | 1.0.2, 1.6.2 |
| guzzlehttp/guzzle | 6.3.0 |
| intervention/image | 2.4.1 |
| alex-bond/yii2-thumbler | 1.1.1 |
| alex-bond/yii2-elfinder, -redactor, -select2-widget | dev-master, dev-master, 1.0.0 |
| phpoffice/phpexcel | 1.8.1 |
| league/csv | 8.2.2 |
| tightenco/collect (`collect()` helper) | v5.2.43 |
| skinka/type-enum | 1.0.1 |
| 2amigos/yii2-date-picker-widget, nex/yii2-chosen | 1.0.6, dev-master |

**Front-end assets (bower-asset)**: jQuery 2.2.4, jQuery UI 1.11.4, Bootstrap 3.3.7, Font Awesome 4.7.0, Select2 4.0.4, Chosen, DataTables 1.10.12, Dropzone 4.3.0, Fancybox 2.1.7, moment 2.14.1, x-editable 1.5.1, raven-js 3.12.2, etc.

> Note: `backend/config/main.php` references `mihaildev\elfinder\Controller`, but the installed package is `alex-bond/yii2-elfinder`. Whether it provides that class was not verified – check if `/elfinder` fails.

---

## 3. Project / folder structure

```
tymanagement/
├── .env                  # environment config (contains secrets – do not commit)
├── .version              # "2.0.0"
├── yii                   # console entry point
├── assets/               # uploaded files & thumbnail cache (see §9)
│   ├── artworkItems/  artworkItemsPreviews/  cache/
│   ├── clients/ (logos)  compPoster/  defaults/
├── backend/              # internal staff app
│   ├── components/       # Controller (auth by default), Form base, ModelEventCatcherComponent
│   ├── config/           # main.php, db.php, params.php, bootstrap.php
│   ├── modules/
│   │   ├── core/         # login, logout, password reset, dashboard, profile, error
│   │   ├── tracker/      # jobs, sub-jobs, tasks (hours), artwork, description templates
│   │   ├── tys/          # clients, client logins, domains, hostings, users
│   │   ├── accounting/   # reports + Excel export
│   │   └── api/          # token-authenticated JSON/XML API
│   ├── views/layouts, views/partial (_sidebar.php)
│   ├── web/              # document root (index.php, .htaccess, static/)
│   └── runtime/          # logs, cache (writable)
├── frontend/             # client portal
│   ├── components/       # Controller, Form
│   ├── modules/core, modules/tracker
│   ├── config/  views/  web/  runtime/
├── common/               # shared code
│   ├── config/           # main.php (db, cache, mailer, thumbler, sentry), params.php, bootstrap.php
│   ├── models/           # ActiveRecords, enums, SCEProjectTracker
│   ├── helpers/          # AlexBondHelper
│   ├── widgets/          # ActiveForm, GridView, Nav, etc. wrappers
│   └── mail/             # contact.php, reservations.php, layouts/html.php
├── console/
│   ├── controllers/      # UserController, ImportController
│   ├── components/       # MigrateController, MigrationProxy
│   ├── migrations/       # 24 migrations (m160423 … m171002)
│   └── config/
└── vendor/               # Composer dependencies (checked in / pre-installed)
```

Yii aliases (`common/config/bootstrap.php`): `@common`, `@frontend`, `@backend`, `@console`, `@assets` (= `<root>/assets`). Backend adds `@partial`; frontend adds `@partial` and `@layouts`.

---

## 4. Installation and setup

There is no `composer.json`, README or install script in the repo, so the steps below are derived from how the code boots.

1. **Web server**: point the vhost document root to `backend/web` (staff) and, separately, `frontend/web` (client portal). Enable `mod_rewrite` and `AllowOverride` for `backend/web/.htaccess`. `frontend/web` has no `.htaccess` – add an equivalent rewrite to `index.php` (the config uses pretty URLs with `showScriptName => false`).
2. **PHP**: install an interpreter compatible with Yii 2.0.12 / this code (see §2) and the extensions Yii needs plus **imagick**, **mbstring**, **pdo_mysql**, **fileinfo** (`mime_content_type`), **gd/imagick** for image work.
3. **Dependencies**: `vendor/` is already present. If you need to rebuild it you must recreate a `composer.json` from the package list in §2 (none is provided).
4. **Environment**: create `.env` in the project root (see §5).
5. **Database**: create an empty MySQL database, put its DSN/credentials into `.env`, then run migrations:
   ```bash
   php yii migrate
   ```
   (`console/migrations` is the default path; the `rbac_init` migration pulls in Yii's RBAC tables via `MigrationProxy`.)
6. **Writable directories**: `backend/runtime`, `frontend/runtime`, `common/runtime`, `console/runtime`, `backend/web/assets`, `frontend/web/assets`, and `assets/` (including `assets/cache`) must be writable by the web server user. elFinder also writes to `frontend/web/static/user_files`.
7. **First user**: `php yii user/create` (prompts for title, email, first/last name, Slack username, password). **Role assignment is not done by this command** – see §8.

---

## 5. `.env` configuration (values hidden)

Loaded with `Dotenv\Dotenv(<root>)` in `yii`, `backend/web/index.php` (and `frontend/web/index.php`). Keys present in the existing `.env`, with the meaning derived from code:

| Variable | Used for | Value |
|---|---|---|
| `YII_DEBUG` | Yii debug flag. If true (and not test) enables Gii and, for web, the debug module with `allowedIPs = ['*.*.*.*']` | `0` currently |
| `YII_ENV` | Yii environment; also passed to Sentry | `"prod"` currently |
| `DB_DSN`, `DB_USERNAME`, `DB_PASSWORD` | MySQL connection (`common/config/main.php`) | *hidden* |
| `DB_SCHEMA_CACHE`, `DB_SCHEMA_CACHE_DURATION` | Schema cache on/off and seconds | `1`, `3600` |
| `MEMCACHE_HOST`, `MEMCACHE_PORT` | Only used by the commented-out Memcache cache config | *hidden* / `11211` |
| `REDIS_HOST`, `REDIS_PORT`, `REDIS_DATABASE` | **Defined in `.env` but not referenced by any PHP file in the project** | *hidden* |
| `MAILER_FILE_TRANSFER` | `useFileTransport` – `1` writes mails to runtime instead of sending | `1` |
| `SMTP_HOST`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_PORT`, `SMTP_ENCRYPTION` | SwiftMailer SMTP transport | *hidden* (port/encryption currently empty) |
| `COOKIE_VALIDATION_KEY` | Request cookie validation key (backend + frontend) | *hidden* |
| `SENTRY_DSN` | If non-empty, registers Sentry component + log target (errors & warnings, 404s excluded) | *hidden* |
| `ASSETS_URL` | Public base URL where `/assets` is served (thumbnails `…/cache/…`, default images, client logos) | *hidden* (a URL) |
| `SCE_PROJECT_TRACKER_URL`, `SCE_PROJECT_TRACKER_TOKEN` | External SCE project-tracker API | *hidden* |
| `SCE_PROJECT_TRACKER_LOOKKUP_ENABLED` | Enables SCE lookups (spelling as in code) | `0` currently |

Variables **read by code but missing from the current `.env`**: `URL_FRONTEND`, `DOMAIN_FRONTEND`, `DOMAIN_BACKEND` (`common/config/params.php`), `USER_FILES_URL` (elFinder base URL + params), `UPLOADS_URL`, `UPLOADS_CACHE_URL`. They evaluate to `false` when unset.

Hard-coded config worth knowing (`common/config/main.php`): language `en`, **timezone `America/Los_Angeles`**, currency code `RUR`, charset `utf8`.

---

## 6. Database, migrations and seeders

Run with `php yii migrate` (custom `console\components\MigrateController` exists to proxy framework migrations).

### Tables (from migrations)

| Table | Key columns / notes |
|---|---|
| `user` | `email` (unique), `password` (hash), `password_reset_token`, `auth_key`, `access_token` (API), `first_name`, `last_name`, `title`, `slack_username`, `phone`, `cell`, `hourly_rate`, `admin` (bool), `status` (1 active / 2 disabled), timestamps |
| `client` | `name` (unique), `is_default`, `soft_deleted` |
| `client_login` | `client_id` → client (CASCADE), `email` (unique), `password`, `auth_key`, `all_project_access` |
| `client_login_project` | PK (`client_login_id`, `project_id`) → `client_login`, `job` (CASCADE) |
| `job` | `internal_id` (unique string, "TYS ID"), `name`, `manager_id` → user, `client_id` → client, `sce_job_number`, `accounting_notes`, `budget_hours`, `status` (default 1), `soft_deleted`, `creator_id`, `slack_channel`, `docs_link`, `jira_link`, `internal` (bool) |
| `sub_job` | `job_id` → job, `job_number` (the name), `sce_job_number`, `status` (default 0), `soft_deleted`, `creator_id`. (`job_description` column dropped in `m161101`.) |
| `task` | `user_id`, `job_id`, `sub_job_id` (nullable), `task_item_id`, `description`, `date`, `hours` |
| `job_link` | `job_id` (CASCADE), `name`, `url` |
| `favorites` | PK (`user_id`, `job_id`) |
| `domains` | `client_id` (CASCADE), `domain`, `registrar`, `auto_renew`, `expiration_date` |
| `hosting` | `client_id` (CASCADE), `provider`, `auto_renew`, `expiration_date` |
| `artwork_comp` | `project_id` → job, `name`, `description`, `status` |
| `artwork_item` | `comp_id` → artwork_comp, `name`, `file_name` |
| `artwork_item_state` | `item_id` → artwork_item, `file_name` (unique, random), `original_file_name` |
| `artcomp_version` | `artcomp_id` (CASCADE), `name` |
| `artcomp_version_state` | `artcomp_version_id`, `artwork_item_id` (CASCADE), `artwork_item_state_id` (snapshot of which revision was current) |
| `user_description_template` | `user_id` (CASCADE), `name`, `content` |
| `auth_*` | Yii RBAC tables (`auth_rule`, `auth_item`, `auth_item_child`, `auth_assignment`) from `m140506_102106_rbac_init` |

Migration order: users → clients → jobs → sub_jobs → tasks → client_logins → user/job extra info → artwork comp/item/state → job_links → RBAC init → RBAC roles → user token → favorites → domains → hosting → new sub-job system → artcomp versions → client_login_projects → user description templates → internal projects → sub-job status.

**Gaps found**
- `common\models\ArtworkWireframe` maps to table `artwork_wireframe`, but **no migration creates it** and nothing else references the model.
- Foreign key from `artcomp_version_state.artwork_item_state_id` is not declared (only the other two are).

### Seeders
There are **no seeders/fixtures**. The only seeded data is the **RBAC roles** created by migration `m160912_170404_rbac_roles` (it calls `removeAll()` first, so re-running wipes RBAC data). See §8.

### Console data tools
- `php yii user/create` – interactive user creation.
- `php yii import/users | clients | jobs | times | reconnect-sub-jobs` – import legacy data from CSV **URLs** you type in at prompts (`import/jobs` contains hard-coded project-manager name → user mapping and expects those users to exist; `reconnect-sub-jobs` links `sub_job.sce_job_number` using the SCE API data).

---

## 7. Routes and APIs

Both web apps use pretty URLs and Yii's default pattern `/<module>/<controller>/<action>` (module controllers: `module/controller/action`; action IDs are kebab-case, e.g. `actionAddWeekly` → `/tracker/tasks/add-weekly`).

### Backend custom URL rules (`backend/config/main.php`)
| Pattern | Route |
|---|---|
| `/` | `tracker/tasks/index` |
| `/tracker/artwork/preview/<id>.<ext>` | `/tracker/artwork/preview` |
| `<module>` | `<module>/default/index` (no `DefaultController` exists in any module – such URLs 404) |

Also: `/elfinder` (controllerMap, requires login; roots at `@frontend/web/static/user_files`; `netmount` disabled). Gii is enabled when `YII_DEBUG` is true.

### Backend web routes and access

Default (`backend\components\Controller`): any logged-in user (`@`). Roles listed are the `AccessControl` rules in each controller.

| Module / controller | Actions | Required role |
|---|---|---|
| `core/auth` | `login`, `request-password-reset`, `reset-password` (guests); `logout` (`@`) | – |
| `core/out-auth` | `site-login` (CSRF off) | guests |
| `core/dashboard` | `index` | logged in |
| `core/profile` | `edit` (own slack username, phones, new password ≥5 chars) | logged in |
| `core/error` | `error` | – |
| `tracker/jobs` | `index`, `view`, `create`, `update`, `delete` (soft), `toggle-favorite`, `create-link`, `delete-link` | `employee` |
| `tracker/sub-jobs` | `index`, `create`, `update`, `delete`, `export` | `manager` |
| `tracker/tasks` | `index`, `add`, `add-weekly`, `update`, `delete`, `get-sub-jobs` (JSON) | `employee` (edit/delete of others' entries needs `manager`) |
| `tracker/artwork` | `comp`, `create-comp`, `update-comp`, `delete-comp`, `delete-item`, `delete-state`, `update-name`, `upload`, `upload-revision`, `history`, `preview`, `download`, `create-version` | `employee` |
| `tracker/description-templates` | `index`, `create`, `update`, `delete` | logged in (no role rule; **note: not scoped to owner**) |
| `tys/clients` | `index`, `create`, `update`, `delete` (soft), `set-default` | `manager` |
| `tys/client-logins` | `index`, `create`, `update`, `delete` (per `client`) | `manager` |
| `tys/domains` | `all`, `index`, `create`, `update`, `delete` | `manager` |
| `tys/hostings` | `index`, `create`, `update`, `delete` | `manager` |
| `tys/users` | `index`, `create`, `update` | `superuser` |
| `accounting/reports` | `index`, `projects`, `generate-excel`, `generate-excel-projects` | `accountant` |
| `accounting/user-reports` | `view` (any user's report) | `accountant` |
| | `my` | `employee` |

### Frontend (client portal) routes
| URL | Route | Notes |
|---|---|---|
| `/` | `tracker/jobs/index` | list of the client's projects |
| `/core/auth/login`, `/core/auth/logout` | login/logout | uses `ClientLogin` |
| `/core/dashboard/index` | dashboard | |
| `/tracker/jobs/view?id=` | project view | 404 if job not in client's `client_id` |
| `/tracker/artwork/comp?id=[&version=]` | comp view; with no `version` redirects to the latest version, or shows a "choose version" page | |
| `/tracker/artwork/history?id=&version=` | item history in a version | |
| `/tracker/artwork/preview/<id>.<ext>`, `/tracker/artwork/download?id=` | file streaming | |

All frontend controllers extend `frontend\components\Controller` (logged-in required).

### REST-like API (`backend/modules/api`, base path `/api/...`)
- CORS `*`, CSRF disabled, responses via `yii\rest\Serializer`; content negotiation `application/json` (default) or `application/xml`.
- **Auth**: `QueryParamAuth` → pass the user's `access_token` as the **`access-token` query parameter** (Yii default). Tokens are generated at login. `LoginController` itself is unauthenticated.

| Method & path | Description |
|---|---|
| `POST /api/login/login` | Body JSON/form: `login` (email), `password`. On success regenerates and returns the user record (including its `access_token`). |
| `GET /api/hours/get-today-hours` | Current user's tasks for today (joined with job). |
| `POST /api/hours/save-hours` | Creates a task for the current user via `TaskForm`; fields: `job_id`, `sub_job_id`, `task_item_id`, `description`, `date`, `hours`. 201 on success, 400 + errors otherwise. |
| `GET /api/jobs/get-projects` | `{id: "<internal_id> <name>"}` for all jobs. |
| `GET /api/jobs/get-current-projects` | Same, only status `Current`. |
| `GET /api/jobs/get-sub-projects?id=<jobId>` | `{id: job_number}` for a job's sub-jobs. |

---

## 8. Authentication and permissions

**Staff (backend)**
- Login form: email + password (`core/auth/login`), "remember me" = 30 days (`enableAutoLogin`). Password hashing: `Yii::$app->security->generatePasswordHash`.
- `User::beforeValidate()` **regenerates `auth_key` on every validation/save**, which invalidates existing "remember me" cookies of that user whenever the record is saved.
- API auth: random `access_token` stored on `user`; `findIdentityByAccessToken` just looks it up (no expiry).
- Password-reset: `AuthController` imports `PasswordResetRequestForm` and `ResetPasswordForm`, but **these classes are not present in `backend/modules/core/models`** (only `LoginForm` and `ProfileForm`), so those two actions will error. Flash messages for them are in Russian.

**RBAC** (`yii\rbac\DbManager`, roles created by migration, hierarchy `superuser ⊃ accountant ⊃ manager ⊃ employee`):

| Role | Description (from migration) | Notable access |
|---|---|---|
| `employee` | General employee | projects, own hours, artwork, own report |
| `manager` | Manager (inherits employee) | + sub-jobs, clients, client logins, domains, hosting; edit/delete anyone's hours; sees all hours |
| `accountant` | Accountant (inherits manager) | + accounting reports/exports, any user's report |
| `superuser` | Super user (inherits all) | + user management |

- Roles are assigned in the UI by a superuser (`tys/users`, field `role`): `UserForm::save()` revokes all and assigns the chosen role.
- `console/config/main.php` sets `defaultRoles => ['employee']` for the **console** app only; the backend config has no default roles.
- **Bootstrapping gap**: `php yii user/create` does not assign a role, and no code assigns the first `superuser`. You will need to insert an `auth_assignment` row manually for the first admin (suggestion; not automated in code). The `user.admin` boolean column is saved by forms but not used in any access rule I found.
- Sidebar items are shown by `checkAccess`: Projects & Manage Hours (employee), Reports (accountant), TYS Users (superuser), Clients & Domains (manager).

**Clients (frontend)**
- Separate identity `ClientLogin` (own table, own login form). Project visibility: `client_login.client_id` must match the job's client, and unless `all_project_access` is set, the job must be in `client_login_project`. (The list is filtered this way; `jobs/view` only checks the client match.)
- `ClientLogin::findIdentityByAccessToken` throws `NotSupportedException`.
- Frontend artwork `preview`/`download` take only an item-state `id` and **do not check client ownership**.

---

## 9. Important controllers, models, services and components

**Components / base classes**
- `backend\components\Controller` – default `@` access; `backend\components\Form` – abstract form-model pattern (`setModel`, `getIsNewRecord`); most CRUD uses a `*Form` model that copies validated attributes onto an ActiveRecord and calls `save()`.
- `backend\components\ModelEventCatcherComponent` – logs validation errors for every model; **defined but not registered** in any config `bootstrap`.
- `common\models\SCEProjectTracker` – the only "service" class (see §10).

**Key models (`common/models`)**
- `Job`, `SubJob`, `Task`, `Client`, `User`, `ClientLogin`, `JobLink`, `UserDescriptionTemplate`, `Domains`, `Hosting`.
- Artwork: `ArtworkComp` → `ArtworkItem` → `ArtworkItemState`; `ArtcompVersion` + `ArtcompVersionState`.
- Enums (`skinka/type-enum`): `JobStatus` (Estimate 1, Current 2, On hold 3, Completed 4, Canceled 5, Archived 6, Hosting 7; the dropdown list omits Completed), `SubJobStatus` (Current 0, Completed 1, Canceled 2), `TaskItem` (11 work types, Administrative … Business Development), `UserStatus` (Active 1, Disabled 2), `HoursEnum` (0:30 … 12:00 in 30-min steps).
- Helpers/widgets: `AlexBondHelper` (date/time formatting, shortcode helper), `common\widgets\*` wrappers around Bootstrap/Kartik widgets.

**Notable controllers**
- `tracker/TasksController` – hours list/CRUD, weekly entry, AJAX sub-job lookup (can filter out completed sub-jobs with `only_active`).
- `tracker/ArtworkController` – comp pages, Dropzone-style uploads (`upload`, `upload-revision`), raw file streaming, version creation.
- `accounting/ReportsController` – filtered grouped time report and two Excel exports.
- `tys/UsersController` – user CRUD + role assignment (superuser only).

---

## 10. Third-party integrations

| Integration | Where | Notes |
|---|---|---|
| **SCE project tracker API** | `common/models/SCEProjectTracker.php` | `GET {SCE_PROJECT_TRACKER_URL}/api/jobs` and `/api/jobs/{id}/cost-centers` with `access-token` query param; results cached 60 s in the default cache; returns `[]` if `SCE_PROJECT_TRACKER_LOOKKUP_ENABLED` is falsy. Used in reports, sub-job export and `import/reconnect-sub-jobs`. |
| **Sentry** | `common/config/main.php` | via `mito/yii2-sentry` when `SENTRY_DSN` set; `raven-js` is also installed. |
| **SMTP (SwiftMailer)** | `common/config/main.php` | mail templates `common/mail/*` (`contact.php`, `reservations.php` look like leftovers from another project; the password-reset mail path is not implemented). |
| **Gravatar** | `User::getAvatar()`, `ClientLogin::getAvatar()` | URLs built from MD5 of email. |
| **elFinder / Redactor** | `backend/config/main.php`, job/profile views | file manager for `frontend/web/static/user_files`. |
| **Imagick / Intervention Image / Thumbler** | `ArtworkItemState`, `ArtworkComp` | PDF first-page → JPEG, image previews, resized cache in `assets/cache`. |
| **PHPExcel** | accounting + sub-jobs export | writes `.xls` (Excel5). |
| **Slack / Jira / Docs** | `job.slack_channel`, `jira_link`, `docs_link`, `user.slack_username` | stored as data/links only; **no Slack or Jira API calls were found**. |

---

## 11. Cron jobs and queues

**Not found in codebase.** There are no scheduled console commands, queue workers, or queue config. Redis/Memcache variables exist in `.env` but nothing uses them (cache is `FileCache`; Memcache config is commented out). The only time-based behaviour is the 60-second cache of SCE API responses.

---

## 12. Development and production commands

```bash
# Console help
php yii help

# Database
php yii migrate                 # apply all pending migrations
php yii migrate/down 1          # revert last
php yii migrate/create name     # new migration

# Users / data import
php yii user/create
php yii import/users            # and clients | jobs | times | reconnect-sub-jobs

# Local serve (document root = backend/web or frontend/web)
php -S localhost:8080 -t backend/web
php -S localhost:8081 -t frontend/web
```

Environment switches: `YII_DEBUG=1` / `YII_ENV=dev` in `.env` enables Gii (and the debug module on web with `allowedIPs = ['*.*.*.*']` – open to everyone, **never enable in production**). Current `.env` is `YII_DEBUG=0`, `YII_ENV="prod"`. No tests or test config were found in the repository.

---

## 13. Deployment instructions

No deployment scripts, CI config, Dockerfile or docs exist. A deployment consistent with the code:

1. Upload the project (including `vendor/`) to the server.
2. Create production `.env` (`YII_DEBUG=0`, `YII_ENV=prod`, real DB, SMTP with `MAILER_FILE_TRANSFER=0`, unique `COOKIE_VALIDATION_KEY`, `SENTRY_DSN`, `ASSETS_URL`, `SCE_*`, and the missing `URL_FRONTEND`, `DOMAIN_*`, `USER_FILES_URL`, `UPLOADS_*` as required).
3. Vhosts: `backend/web` and `frontend/web` as separate document roots; HTTPS recommended.
4. Make `runtime/`, `web/assets/` and `/assets` writable; serve `/assets` publicly at `ASSETS_URL` (the app emits URLs of the form `ASSETS_URL/cache/<thumb>`, `ASSETS_URL/defaults/*.png`, `ASSETS_URL/clients/logos/<id>.png`).
5. `php yii migrate`.
6. Clear caches after schema changes: `backend/runtime/cache` etc. (schema cache is on for 3600 s via `DB_SCHEMA_CACHE=1`).
7. Create the first user and manually give it the `superuser` role (see §8).

---

## 14. Common troubleshooting

| Symptom | Likely cause (from code) |
|---|---|
| New column/table "not found" after migration | Schema cache (`DB_SCHEMA_CACHE=1`, 3600 s). Clear the `runtime/cache` directories or set `DB_SCHEMA_CACHE=0`. |
| User can log in but sees 403/redirect on most pages | No RBAC role assigned (see §8). |
| `Class 'mihaildev\elfinder\Controller' not found` on `/elfinder` | Installed package is `alex-bond/yii2-elfinder`; verify namespace. |
| Password-reset pages error | Missing `PasswordResetRequestForm`/`ResetPasswordForm` classes. |
| Thumbnails/PDF previews broken or default images shown | Imagick missing, `assets/` not writable, or `ASSETS_URL` not pointing at the web-served `/assets`. |
| Emails never arrive | `MAILER_FILE_TRANSFER=1` writes mail files instead of sending; set to `0` and configure SMTP. |
| SCE columns empty in exports | `SCE_PROJECT_TRACKER_LOOKKUP_ENABLED` is `0`, or URL/token wrong. Note `sub_job` export indexes the SCE job list directly and may error if `sce_job_number` isn't in the returned list. |
| "Remember me" suddenly logs a user out | `auth_key` is regenerated on every save of that `user`/`client_login`. |
| Fatal errors on PHP ≥ 7.2 | `yii\base\Object` usage / old Yii 2.0.12 – run on the PHP version the project was built for. |
| Pretty URLs 404 on the portal | `frontend/web` has no `.htaccess`. |
| Everything works but nothing is logged to Sentry | `SENTRY_DSN` empty. Local logs go to `<app>/runtime/logs/app.log` (errors/warnings). |

---

## 15. Important business logic

- **Job numbering**: if `internal_id` ("TYS ID") is empty on a new job it becomes `max(internal_id) + 1`. The column is a string, so `MAX` is lexicographic in MySQL – IDs of differing lengths can yield wrong next values.
- **Soft deletes**: `Job::delete()` and `Client::delete()` only set `soft_deleted = true`. Job search defaults to non-deleted. (`SubJob` has a `soft_deleted` flag but the controller calls the normal `delete()`, which hard-deletes.)
- **Default client**: `ClientsController::setDefault` clears `is_default` on all clients and sets one; new jobs pre-select it.
- **Time entries**: `hours` is a float (options 0:30 – 12:00 in 30-min steps in the UI); `task_item_id` must be one of the 11 `TaskItem` values; new-entry form pre-selects the user's last used work type. `add-weekly` creates one `task` per day with hours > 0. Non-managers only see and edit their own entries (others return 404); managers see all.
- **Favorites**: users can star jobs; the job dropdown puts favorites first, prefixed with `* `.
- **Accounting report**: groups task rows by `user, job+sub_job, date, description` summing hours; fee = `user.hourly_rate × summed hours`; export columns include TYS job/sub-job, SCE job/sub-job, cost centers, requestor/approver (from SCE) and accounting notes. Filters: job, sub-job, work date range, entry date range, include/exclude clients.
- **Artwork versioning**: a comp has items; each item has revisions (`artwork_item_state`, files stored under `assets/artworkItems/<item_id>/<random>.<ext>`). Uploading a revision adds a state; the **latest state** (by `updated_at`) is the current one. Creating a **version** stores, per item, a pointer to the then-latest state – the client portal shows comps by version (latest version by default). Deleting the last state of an item deletes the item; deleting a comp cascades through items/states/files and the comp poster image. Saving an item/state touches the parent `updated_at`.
- **Previews**: PDF → first page JPEG via Imagick; images via Intervention; cropped-center thumbnails cached through Thumbler.
- **Client logos**: PNG only (min height 30), stored as `assets/clients/logos/<client_id>.png`.
- **Client portal visibility**: see §8.
- **Sub-job status**: Current / Completed / Canceled; the AJAX sub-job lookup can hide Completed ones.

---

## 16. Security notes / known issues

These were observed while reading the code; address before relying on the system:

1. **Secrets in the repo/logs**: `.env` holds live-looking credentials, and `backend/runtime/logs/app.log*` contains a full environment dump including the cookie validation key, Sentry DSN and the SCE token. **Rotate those secrets**, delete the logs and ensure `.env`/`runtime` are never served or committed.
2. **Debug mode** (`YII_DEBUG=1`) opens Gii and the debug panel to all IPs.
3. API: wildcard CORS, CSRF disabled, long-lived tokens in the query string; `RateLimiter` is configured but `User` doesn't implement `RateLimitInterface`, so no limiting happens.
4. Frontend artwork `preview`/`download` and `description-templates` update/delete lack ownership checks (any logged-in user with a valid ID).
5. `User::findByUsername` and `ClientLogin::findByUsername` query a non-existent `username` column.
6. `ModelEventCatcherComponent` and `ArtworkWireframe` appear to be unused.
7. `frontend/components/Controller.php` imports `common\models\Settings` and `Mobile_Detect`, neither of which exists in the repo (harmless unless used).
8. `SubJob` has a `soft_deleted` flag, but nothing overrides `delete()` for it, so deleting a sub-job is a hard delete (and fails if tasks reference it, due to the foreign key).
