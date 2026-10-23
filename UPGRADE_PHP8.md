# PHP 7.0 → PHP 8.x upgrade notes

The code was upgraded locally and tested on **PHP 8.4.6 and PHP 8.5.12 (XAMPP, Windows) with MariaDB**. `composer.json` allows `>=8.2` (`config.platform` pinned to 8.2, so `composer.lock` works on any PHP from 8.2 to 8.5). Run the checks at the bottom on the server after deploying.

Backups made before any change:
- `C:\xampp\htdocs_backup\tymanagement_php7_backup\` – full copy of the project (original `vendor/` and `.env` included, `runtime/` excluded)
- `C:\xampp\htdocs\tymanagement_vendor_php7_old_2605\` – the original PHP 7 `vendor/` folder

## What changed

| Area | Before | After |
|---|---|---|
| Dependency management | no `composer.json` (only a committed `vendor/`) | new `composer.json` + `composer.lock` |
| Yii | 2.0.12 | 2.0.55 |
| `.env` loader | `new Dotenv($path)` (phpdotenv 2) | `Dotenv::createUnsafeImmutable($path)` (phpdotenv 5.7) in `yii`, `backend/web/index.php`, `frontend/web/index.php`; `getenv()` keeps working |
| Mailer | `yii2-swiftmailer` + Swift_SmtpTransport | `yii2-symfonymailer` 4; DSN built from the same `SMTP_*` vars in `common/config/main.php` (`SMTP_ENCRYPTION=ssl` → `smtps`, otherwise `smtp`; empty `SMTP_HOST` → null transport) |
| Sentry | `mito/yii2-sentry` + sentry 1.6 | `sentry/sentry` 4 with new `common/components/SentryTarget.php` (same `SENTRY_DSN`, errors+warnings, 404s excluded) |
| Excel | PHPExcel 1.8 | PhpSpreadsheet (`ReportsController`, `SubJobsController`). Exports now return via `Response::sendContentAsFile()` instead of echoing to `php://output`, because the old way throws `HeadersAlreadySentException` under current Yii |
| CSV import | league/csv 8 | league/csv 9 (`ImportController::csvRows()`) |
| HTTP | Guzzle 6 | Guzzle 7 |
| Collections | tightenco/collect 5.2 | `illuminate/collections` 10 (`collect()` helper) |
| elFinder | `alex-bond/yii2-elfinder` (bundled elFinder had PHP 8 syntax errors) | `mihaildev/yii2-elfinder` 1.4 + `studio-42/elfinder` (same namespace, config unchanged) |
| Thumbnails | `alex-bond/yii2-thumbler` + Zebra_Image 2.2.4 | local copy in `lib/yii2-thumbler/` (installed via a composer `path` repository) using Zebra_Image 3 (namespaced class) |
| Kartik grid | 3.0.8 | 3.5.3 |
| Intervention Image | 2.4 (static `ImageManagerStatic`; deprecation errors on 8.4/8.5) | 3.11 (`ImageManager` + Gd/Imagick driver; `common/models/ArtworkItemState.php`, `common/config/bootstrap.php`) |
| nex/yii2-chosen | dev-master from GitHub (`[null => '']` array key fails on PHP 8.5) | local patched copy `lib/yii2-chosen/` |
| Zebra_Image | 2.2.4 | local copy `lib/zebra-image/` (3.0.0, `imagedestroy()` removed - deprecated in 8.5) |
| `MigrationProxy` | `yii\base\Object` | `yii\base\BaseObject` |
| Image driver | always `imagick` | `imagick` if the extension is loaded, otherwise `gd` (`common/config/bootstrap.php`) |
| Removed (unused) | `alex-bond/yii2-redactor`, `alex-bond/yii2-select2-widget`, `raven-js`, `yii2-pjax` standalone etc. | – |

Front-end bower assets (jQuery 2.2.4, Bootstrap 3.3.7, Metronic plugins…) were kept at the **same versions** (pulled via asset-packagist) so the UI behaves as before.

## What was tested locally (PHP 8.4 and 8.5.12)

- All 24 migrations on an empty database (database dropped afterwards).
- Backend: login, then ~40 pages/actions for every module (jobs, sub-jobs, tasks incl. weekly, clients, client logins, domains, hostings, users, reports, profile, templates, artwork), creating a job and a task, uploading an artwork file, thumbnail generation, all three Excel exports (valid `.xls` produced).
- Frontend (client portal): login, project list/view, artwork page.
- Console: `php yii help`, `user/create`, migrations. Mailer and Sentry target instantiate and send.
- `php -l` on all 249 project PHP files under 8.5 (0 errors).

The same full run was repeated on PHP 8.5.12 after fixing the 8.5-only deprecations (Yii turns deprecations into exceptions, which had produced HTTP 500 on the job list, hours forms, job create/update and the reports page). Not tested: PDF previews (need the Imagick extension, not installed in either local PHP); password-reset pages (they were already broken – missing form classes); `import/*` console commands (need live CSV URLs); SCE API calls (disabled by `SCE_PROJECT_TRACKER_LOOKKUP_ENABLED=0`); sending real e-mail.

## Deploying to the DigitalOcean server

1. **Check the OS first.** CentOS 7 is end-of-life and I could not verify that any repository still ships PHP 8.5 (or even 8.3+) for it. If `yum` cannot give you PHP ≥ 8.2 with the extensions below, plan to move the app to a supported OS (e.g. AlmaLinux/Rocky 9 or Ubuntu 24.04) rather than forcing it.
2. **PHP-FPM extensions needed:** `pdo_mysql`, `mbstring`, `gd`, `fileinfo`, `xml`/`dom`/`simplexml`/`xmlwriter`, `zip` (PhpSpreadsheet), `curl`, `openssl`, `intl` (recommended), and `imagick` (PDF previews). The local C:/xampp/php85 ships `zip` and `intl` but has them disabled in its php.ini. In production prefer `error_reporting = E_ALL & ~E_DEPRECATED`, because Yii turns every PHP deprecation into an exception.
3. **Back up** the server code, `.env`, `/assets` and the database before touching anything.
4. **Upload the code**: everything except `vendor/` (or including it), `runtime/` contents, and **do not overwrite the server's `.env`**. Include `composer.json`, `composer.lock`, `lib/`.
   - Preferred: on the server run `composer install --no-dev --optimize-autoloader` (needs Composer 2 and outbound access to packagist.org and asset-packagist.org).
   - Alternative: upload the locally built `vendor/` (built on PHP 8.4; `config.platform` is pinned to 8.2).
5. Point php-fpm in the Nginx `fastcgi_pass` to the new PHP socket; keep the existing server blocks/basic-auth.
6. Clear caches: delete `backend/runtime/cache/*` and `frontend/runtime/cache/*` (Yii schema cache is on for 3600 s).
7. **No database migration is needed** – the schema did not change. Do not run `yii migrate` (the RBAC role migration calls `removeAll()`).
8. Make sure `.env` has `YII_DEBUG=0`, `YII_ENV="prod"` and keep `display_errors=Off` in `php.ini`.

## Server smoke checks after switching PHP

```bash
php -v && php -m | grep -Ei "pdo_mysql|mbstring|gd|imagick|zip|xml"
php yii help                      # console boots
curl -I https://ms.tyscreative.com/          # login redirect (behind basic auth if enabled)
tail -n 50 backend/runtime/logs/app.log      # no new errors
```
Then log in and open: Projects, Manage Hours, Reports (and download an export), an artwork comp with a PDF upload (checks Imagick), the client portal.

## Rollback

Switch php-fpm back to the old PHP 7.0 pool and restore the old `vendor/`, `composer`-less code and `.env` from the backups above. The database is untouched.
