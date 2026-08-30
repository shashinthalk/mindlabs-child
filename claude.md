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
