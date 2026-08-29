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

## Boundaries & rules

- **Folder boundary**: confine all work (reads, writes, edits,
  deletes) to this child theme's own directory
  (`wp-content/themes/hexnity-wp-child/`) — do not reach into the
  parent theme's directory to fix or verify something from here. If a
  genuinely parent-side change is needed, that belongs to a session
  explicitly scoped to the parent theme instead. See `GUIDELINES.md`
  §7 for the full reasoning (this is the mirror image of the rule the
  parent theme's own `claude.md` states for itself).
- **Parent compatibility is authoritative in `GUIDELINES.md`**, not
  here — this file is about *process* (how to work in this
  directory); `GUIDELINES.md` is about *compatibility* (what would
  break the parent relationship). Don't duplicate its content here;
  keep the two files' concerns separate so neither drifts out of sync
  with the other.
