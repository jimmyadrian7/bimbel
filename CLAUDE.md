# CLAUDE.md

Context for Claude (or any AI assistant) working in this repository. Read this
before making changes — the codebase relies on a few strong, mostly-implicit
conventions, and following them is more important than writing "idiomatic"
Laravel/Angular code from scratch.

## What this is

**SOOF** — a management system for a *bimbel* (Indonesian tutoring/course
center), internally named `bimbel`. It manages students (siswa), teachers
(guru), courses/branches (kursus), billing & receipts (tagihan/kwitansi),
payroll, expenses, referrals, a public-facing website module, and WhatsApp
notifications.

- **Backend:** PHP 7.3+, Slim 4 framework, Illuminate Database (Eloquent)
  used standalone (not full Laravel), Twig for server-rendered PDF reports
  (via dompdf), PHP-DI for the container.
- **Frontend:** AngularJS 1.8 (legacy, `ui-router`, `angular.module` pattern —
  **not** Angular 2+/modern Angular), bundled with Webpack, Bootstrap 5.
- **DB:** MySQL, no formal migration framework — schema changes ship as
  idempotent "patch" endpoints (see below).

Run locally: `composer install && composer setup` (interactive `.env` setup,
see `scripts/setup.php`), `npm install && npm run build` (or `npm run watch`
for dev), `composer start` serves the PHP backend on `localhost:8080`.

## Backend architecture — read this before adding any model

This is the single most important thing to understand:

**Any `Model/<Name>.php` file placed under `module/<Module>/Model/`, extending
`Bimbel\Core\Model\BaseModel`, is automatically wired into a generic REST API.**

`dependencies/ModelList.php` scans every `module/*/Model/*.php` file at
runtime and registers `<ClassName>` → full namespace. `module/Master/_routes/`
then exposes generic routes that work for *any* registered model:

- `GET  /api/{models}` — list (plural: model name + literal `s`, e.g. `kursus`
  model → `/api/kursuss`, `agama` → `/api/agamas`, `program_belajar` →
  `/api/program_belajars`). **Pluralization is always just `+ "s"`, never
  linguistically correct Indonesian plurals.**
- `GET  /api/{model}/{id}` — detail (singular model name)
- `POST /api/{model}` — create
- `POST /api/update/{model}` — update
- `POST /api/delete/{model}` — delete
- `POST /api/{models}/custom` — list with filter/sort body

Multi-word model names (`ProgramBelajar`) are addressed in the URL as
lowercase snake_case (`program_belajar` / `program_belajars`), converted to
StudlyCase server-side (`Controller::getModel()`).

**Consequence:** you almost never need to write new routes for a new master
data / CRUD entity. Just:
1. Create `module/<SomeModule>/Model/<Name>.php` extending `BaseModel`.
2. Set `$table`, `$fillable`, optionally override `fetchDetail()` /
   `fetchAllData()` / `create()` / `update()` / `delete()`.
3. It's live at `/api/<name_snake_case>s`.

Look at `module/Master/Model/Agama.php` (simplest example) or
`module/Master/Model/ProgramBelajar.php` before writing a new one.

### BaseModel conventions (`core/default/Core/Model/BaseModel.php`)
- `$searchField` defaults to `"nama"` — used by the `?search=` query param.
- `getValue(&$attributes, $name)` — pulls a key out of the incoming payload
  and **unsets it**, so it's excluded from Eloquent mass-assignment. This is
  the standard way to handle nested/relational payload (e.g. `orang`,
  `iuran`, `jadwal`, `ref`) before calling `parent::create()/update()`.
- `fetchDetail($id, $obj)` sets `$data->editable` / `$data->deleteable` flags
  the frontend reads to enable/disable edit & delete buttons. Override this
  to add "can't delete if referenced elsewhere" guards (see `Agama`,
  `Kursus`, `ProgramBelajar` for the `COUNT(...) GROUP BY` pattern).

### Many-to-many relationships
Follow the existing patterns exactly rather than inventing new ones:
- **Simple toggle-style m2m** (checkbox list, e.g. Siswa↔Referal,
  Siswa↔ProgramBelajar): relation method + a separate "pilihan" accessor
  returning `{id: true, ...}` (see `Siswa::getRefAttribute()` /
  `getProgramBelajarPilihanAttribute()`), plus a `handleXxx($values)` method
  called from `create()`/`update()` that attaches/detaches based on that
  keyed object. Always `detach()` the pivot in `delete()` too.
- **Modal-picker-style m2m** (e.g. Guru↔Kursus): a custom Angular modal lets
  the user browse/add/remove full records; backend stores/attaches full
  `{id: ...}` objects (see `Guru::handleKursus()`).

### Schema changes: the "patch" system
There's no `php artisan migrate`. Schema changes are idempotent methods in
`module/FixData/Controller/Patch{N}Controller.php`, each method named
`patchN`, triggered manually by:

```
POST /api/patch/{version}/{subversion}
```

e.g. `POST /api/patch/2/5` runs `Patch2Controller::patch5()`. Every patch
method **must** guard with `hasTable()` / `hasColumn()` checks so it's safe
to call more than once. Use `\Bimbel\FixData\Model\Utils::addMenuReport()` /
`updateAccessRight()` to register new sidebar menu items + role permissions
in the same patch (see any existing patch for the pattern). When you add a
new patch, note it in a comment/README so someone remembers to actually call
the endpoint after deploying — nothing calls it automatically.

### PDF reports (Twig + dompdf)
Reports live in `module/Report/View/*.twig`, generated by controllers in
`module/Report/Controller/` via `BaseReportController::toPdf()`. The
Kwitansi (receipt) is split into partials under
`module/Report/View/kwitansi/` (`header.twig`, `program.twig`, `terima.twig`,
`nominal.twig`, `rekening.twig`, `keterangan.twig`) — **never hardcode
business data (program names, payment types, etc.) directly in a twig file**;
pass it from the controller as a variable, ideally sourced from a
Konfigurasi-managed master-data model, the way `program_belajars` is now
passed into `program.twig` instead of 4 hardcoded checkboxes.

## Frontend architecture (AngularJS, legacy syntax)

Every feature module follows the same file layout under
`Frontend/module/<area>/<feature>/`:
```
<feature>.module.js      // angular.module('app.module.<area>.<feature>', [...])
<feature>.route.js       // ui-router states, resolves
<feature>.controller.js  // controllerAs: 'vm' pattern, $inject arrays
<feature>.js             // just imports the 3 files above (webpack entry)
html/table.html          // <app-table table="..." fields="vm.fields" .../>
html/form.html           // <app-form .../>
html/detail.html         // <app-detail .../>
html/modal/*.html        // any modals, $compile'd on demand in the controller
```
Parent module files (e.g. `Frontend/module/konfigurasi/konfigurasi.js` /
`.module.js`) aggregate sub-feature modules — **when adding a new
sub-feature, register it in both the parent `.module.js` (Angular module
deps array) and the parent `.js` (webpack import) or it silently won't
load.**

### Generic CRUD directives
`<app-table>`, `<app-form>`, `<app-detail>` (in `Frontend/utils/table/` and
`Frontend/utils/form/`) are generic components driven by a `fields` config
array (`{name, value, type, table, required, hidden, ...}`) and a `table`
attribute that's just the API model slug from above. Field `type` supports:
`number`, `date`, `password`, `month`, `time`, `selection`, `autocomplete`,
`file`, `textarea`, `boolean`, plain text (no type). **There is no built-in
multiselect/checkbox-list field type** — for m2m relationships, hand-roll a
checkbox list bound to `vm.data.<pilihan_key>[opt.value]` inside a custom tab
in `form.html`/`detail.html` (see Siswa's "Referensi" / "Program Belajar"
tabs), with the options list resolved via the route's `resolve` block calling
`req.get('<model>s')`.

### Sidebar navigation
Sub-navigation tabs (e.g. everything under "Konfigurasi") are **not**
hardcoded in Angular — `<app-subnavbar>` (`Frontend/utils/navbar/`) builds
them from `session.getGroupMenu(parentMenuKode)`, which reflects the `menu` +
`role_menu` DB tables. A new page only appears in the sidebar once its `menu`
row exists (via a patch calling `Utils::addMenuReport()`) **and** the state's
`nav` config value matches the menu's `kode`, and the `menu` field matches
the parent's kode. Get all three consistent or the link won't show/won't
highlight correctly.

## Naming/consistency conventions worth preserving
- Indonesian naming throughout (`siswa`=student, `guru`=teacher,
  `kursus`=course/branch, `tagihan`=invoice, `kwitansi`=receipt,
  `pembayaran`=payment, `pengeluaran`=expense, `konfigurasi`=settings,
  `nama`=name, `kode`=code). Keep new code/labels in Indonesian to match.
- Master-data entities (Agama, Kursus, ProgramBelajar, Referal, Menu, Role...)
  almost always have at least `kode` + `nama`, live under `module/Master/Model/`
  or their most relevant module, and get a Konfigurasi CRUD page.
- Model classes are singular (`Siswa`, `Kursus`, `ProgramBelajar`); DB tables
  are singular snake_case too (`siswa`, `kursus`, `program_belajar`) — pivot
  tables are `<a>_<b>` (`siswa_referal`, `guru_kursus`,
  `siswa_program_belajar`).

## Recent change: Program Belajar (see CHANGES.md)
A "Program Belajar" master-data entity was added end-to-end as a reference
example of all the conventions above: new model + Konfigurasi CRUD page, a
Siswa multi-select (checkbox pattern), and the Kwitansi receipt (PDF +
edit modal) now reads its program options from this table instead of a
hardcoded list. If asked to add another similar "master data + multi-select
+ used in a report" feature, mirror this implementation.
