# CLAUDE.md — Start Here

This is the base file for the **hexnity-wp-child** theme — a child
theme of the parent "Hexnity WP AI Theme" (folder
`hex-wp-theme-template`, sibling directory to this one). **Always read
this file first**, at the start of every session working in this
directory, before touching anything else.

## What to do after reading this file

1. **Read `GUIDELINES.md` in this same directory next, always.** It's
   the compatibility contract with the parent theme — the design-token
   system (`theme-options.css`), required file headers, the updater
   isolation rule, and more. Every change in this child theme must
   respect it. This file (`claude.md`) governs how you work here in
   general; `GUIDELINES.md` governs what would break parent
   compatibility specifically. Read both, every session.
1a. **If the task involves converting an HTML/CSS concept file into a
   WordPress page template (or touches an existing converted
   template's tokens/classes), also read
   `TEMPLATE-CONVERSION-GUIDE.md` next, before starting.** It's the
   step-by-step correct process for that specific workflow — how
   values get mapped onto `theme-options.css`, why font-sizes must
   always use one of the 11 existing `.hex-*` fluid classes and never
   a hardcoded value or a hand-written `clamp()`, and how new shared
   classes get authored in `site-theme.css`. Not needed for unrelated
   child-theme work (e.g. a pure `functions.php` tweak).
2. **Check whether `project.json` and `action-map.json` already exist
   in this directory.**
   - **If they don't exist yet**: create them now, before making any
     other change, following the exact same shape the parent theme
     uses for its own copies (`../hex-wp-theme-template/project.json`
     and `../hex-wp-theme-template/action-map.json` — read both as
     the reference pattern). Populate them from what is *actually* in
     this child theme's own directory at the time you create them —
     don't copy the parent's content or guess at structure this child
     theme doesn't have. A session that only has the parent theme's
     context (no access to this directory's real files) must not
     fabricate these — it should only ever leave *instructions* here
     (as this file does), never invented data.
     - `project.json`: an index of this child theme's own files and
       what each contains (functions, purpose) — mirroring the
       parent's `project.json` shape (`meta`/`docs`/`structure`/
       `files`/`testing` keys, or whatever subset is actually relevant
       to a theme this small; don't force sections that don't apply).
     - `action-map.json`: an append-only log, one entry per action
       taken against a user requirement — same `{date,
       user_requirement, action, files_touched}` shape the parent
       uses.
   - **If they already exist**: read them first, exactly as the
     parent theme's own convention requires — they're the up-to-date
     record of this child theme's own structure and history.
3. Do **not** read entire files by default once `project.json` exists
   — use it as an index to find the specific file/function you need,
   same discipline as the parent theme.

## Update rules (do these every time you make a change)

- **`action-map.json`**: append an entry for every action taken, tied
  to the user requirement that triggered it — never overwrite or
  delete prior entries.
- **`project.json`**: update whenever files/functions are added,
  removed, or change purpose.
- **`GUIDELINES.md`**: update it if you discover a *new*
  parent-compatibility constraint (the parent added a reserved key,
  renamed an enqueue handle, changed the schema in a way that affects
  this child theme, etc.) — keep it current, the same spirit as the
  parent's own "Boundaries & rules" section in its `claude.md`.
- **Versioning**: bump `style.css`'s `Version:` header AND
  `functions.php`'s `HEXNITY_WP_CHILD_VERSION` constant together on
  any real change (see `GUIDELINES.md` §6).
- **`TEMPLATE-CONVERSION-GUIDE.md`**: update it after finishing any
  task in the HTML→template workflow (a new template conversion, a
  token-mapping fix, a new shared class) — see that file's own
  "Keeping this guide current" section for exactly what to update and
  what NOT to put there (it stays purely prescriptive — the current
  correct process and the current template inventory only; narrative
  of what went wrong along the way belongs in `action-map.json`, not
  this file).
- **Page Content / Site Content JSON backfill — always automatic,
  never wait to be asked** (user instruction, 2026-09-01: *"add rule
  to generate json always, because I dont need to tell u this
  again"*). `TEMPLATE-CONVERSION-GUIDE.md` step 9 and `GUIDELINES.md`
  §9's backfill invariant are standing rules, not one-off requests —
  check them, unprompted, as a normal part of finishing any task that
  touches a template or the header/footer:
  - **Page Content backfill is now code-automated, not a manual
    per-session step** (added 2026-09-01, per explicit user report
    that assigning a template to a Page generated no JSON at all):
    `functions.php`'s `hexnity_wp_child_maybe_backfill_page_content()`,
    hooked to `save_post_page`, calls `hex_save_page_content()` itself
    the moment ANY Page is saved with a registered template and no
    content yet — see `TEMPLATE-CONVERSION-GUIDE.md` rule 9. When you
    create/convert a template, your job is to add its default payload
    to `hexnity_wp_child_get_template_defaults()`'s registry (this
    *is* the "build the default JSON payload" step now) — you no
    longer need to know or check whether a Page is already assigned;
    the hook handles that whenever a Page (existing or future) picks
    up that template.
  - **Site Content backfill (header/footer) is now code-automated too**
    (added 2026-09-01, per explicit user question about how a live-site
    theme update via the GitHub updater could ever create Site Content
    JSON automatically, since a code update alone never touches a
    site's database): `functions.php`'s
    `hexnity_wp_child_maybe_backfill_site_content()`, hooked to
    `admin_init`, calls `hex_save_site_content()` itself for `header`
    and/or `footer` the moment either section is genuinely empty on
    whatever site this code is running on — a fresh install, or a site
    whose "Header & Footer" admin page has never been saved. Idempotent
    and safe on every admin request: never overwrites content that's
    already there. Deliberately excludes `logo_url`/`logo_alt` (left
    unset so `hexnity_wp_child_get_site_logo()`'s Customizer-logo
    fallback applies on every site automatically) and the footer's
    `columns`/`bottom_links` (already covered by `site-footer.php`'s
    own PHP-level fallback array). See `GUIDELINES.md` §9.
  - If a template genuinely has no registry entry yet (an oversight,
    or a template that predates this mechanism), note that clearly as
    an open item so it isn't forgotten — but don't block the rest of
    the task on it.
  - Any time you're already touching a template's content or the
    header/footer for another reason, re-check
    `hex_get_page_content()` / `hex_get_site_content()` actually come
    back non-empty before considering the task done — backfill again
    immediately if a row has gone missing (DB reset, migration),
    without being asked.
  - **How to actually reach this site's database (discovered
    2026-09-01, template-broadband.php's backfill):** this is a Local
    (by Flywheel) site. The system `php` on `PATH` cannot reach its
    MySQL — Local runs a per-site MySQL instance on its own socket, and
    only Local's own bundled PHP build is configured to find it. Use:
    ```
    "/Users/nishanshashintha/Library/Application Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php" \
      -c "/Users/nishanshashintha/Library/Application Support/Local/run/IEpT99PEB/conf/php/php.ini" \
      /path/to/a/temp-script-in-this-theme-dir.php
    ```
    (that `php.ini`'s `mysqli.default_socket` points at
    `run/IEpT99PEB/mysql/mysqld.sock` — the run-ID `IEpT99PEB` is
    specific to this Local site and was confirmed live by bootstrapping
    `wp-load.php` and checking `get_bloginfo('url')` returned
    `http://mindlabz-new.local`; if Local ever reassigns the run ID,
    re-find it the same way — search
    `~/Library/Application Support/Local/run/*/mysql/mysqld.sock` for
    the one that's actually running, or match against
    `~/Library/Application Support/Local/run/*/conf/mysql/my.cnf`).
    Do **not** hit the live site over HTTP for verification — this site
    has site-wide password protection active, which will just redirect
    every request; instead bootstrap `wp-load.php`, use
    `query_posts()`/`the_post()` to set up the loop for a specific page
    ID, then `include` the template file directly and inspect the
    captured output.

## Boundaries & rules

- **STRICT folder boundary (user-mandated, 2026-08-29): work only
  inside this child theme's own directory
  (`wp-content/themes/hexnity-wp-child/`). No exceptions.** Do not
  read, write, edit, delete, or even open-to-verify any file outside
  this directory — that includes the parent theme
  (`../hex-wp-theme-template/`) and anything else in
  `wp-content/`. This applies even for things that feel read-only or
  low-risk: checking how the parent defines a class, confirming an
  enqueue handle, "just looking" at a parent file to debug something
  in this theme. Don't. If a task cannot be completed without a
  parent-side (or otherwise outside-this-directory) change — a new
  reserved key, a renamed enqueue handle, a schema change, a
  design-system class edit, anything under
  `hex-wp-theme-template/` — **stop and tell the user explicitly what
  needs to change and where**, rather than making the change yourself
  or guessing at the parent's current state from memory. See
  `GUIDELINES.md` §7 for the underlying reasoning (this is the mirror
  image of the rule the parent theme's own `claude.md` states for
  itself).
- **Parent compatibility is authoritative in `GUIDELINES.md`**, not
  here — this file is about *process* (how to work in this
  directory); `GUIDELINES.md` is about *compatibility* (what would
  break the parent relationship). Don't duplicate its content here;
  keep the two files' concerns separate so neither drifts out of sync
  with the other.
