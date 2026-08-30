# HTML → WordPress Page Template Conversion Guide

This file documents the **correct process** for converting a static
HTML/CSS design concept (dropped into this theme's root directory)
into a WordPress
page template in this child theme, using this theme's own design-token
system. It is a **living reference** — read it before starting a new
conversion, and **update it after finishing any task that touches this
workflow** (a new template, a token-mapping change, a new shared
class). Keep the "How To" section below purely prescriptive — the
correct way to do it, nothing else. Do not narrate what went wrong on
the way to getting it right; that belongs in `action-map.json`, not
here.

Read this together with `claude.md` (process) and `GUIDELINES.md`
(parent-compatibility contract) — this file is the third leg,
specific to the HTML→template workflow.

## The ten governing rules

1. **Only two CSS files exist in this workflow: `assets/css/src/site-theme.css`
   and `theme-options.css`. Never create a third.** No mirror file, no
   per-template stylesheet, no inline `<style>` block.
2. **Every color, font, size, radius, and spacing value must resolve
   to a real Theme Options field (`--hex-*` in `theme-options.css`) —
   never a hardcoded literal, and never a bespoke token invented just
   for one template.**
3. **Every font-size must come from one of this theme's 11 existing
   fluid text classes — never a hardcoded `font-size`, never a
   hand-written `clamp()`.**
4. **Every font-family must come from the theme's 4 real font fields
   (`--hex-font-heading`/`-body`/`-accent`/`-mono`) via a class — never
   a one-off `font-family` declaration in a component rule.**
5. **Every component (button, card, badge, form field, nav link, icon)
   must use the parent theme's real existing class — never a
   recreated lookalike.** Only author a new class when nothing existing
   matches, and even then, follow the exact same authoring pattern.
6. **Zero inline `style=""` attributes, ever.** Every value gets a
   named class.
7. **Stay inside this child theme's own directory.** If a step
   requires editing the parent theme (`../hex-wp-theme-template/`) —
   e.g. making a new class truly live sitewide — stop and tell the
   user; do not attempt it from here.
8. **Every piece of editor-facing text must come from
   `hex_get_page_content()`** (`GUIDELINES.md` §8) — never hardcoded
   directly in the template — with a `??` fallback equal to the
   original design's exact copy.
9. **A template is not finished when the code is wired to the JSON
   framework — it is only finished once that page's default JSON has
   actually been saved to the database.** Wiring the template to
   `hex_get_page_content()`/`??` fallbacks makes the page *capable* of
   reading saved content; it does not itself put anything in the
   `hex_page_content` table. Every time a template is created or
   converted, immediately build the full default JSON payload (the
   exact same values as the template's own fallbacks) and call
   `hex_save_page_content( $page_id, $payload )` for the live Page(s)
   already assigned that template, before considering the task done.
   Do this again any time you discover the table is empty for a page
   that should have content (e.g. the DB was reset) — it is not a
   one-time step tied to the original conversion, it is a standing
   invariant to check and restore whenever it's found missing.
10. **Every template calls `get_header()`/`get_footer()` — never renders
    its own inline header/footer markup.** As of 2026-08-30 this child
    theme has its own branded global header/footer (`header.php`/
    `footer.php` + `template-parts/site-header.php`/`site-footer.php`,
    `GUIDELINES.md` §9), so a new template must not duplicate that
    chrome inline (no `<!DOCTYPE>`/`<head>`/`<body>` boilerplate, no
    inline `<header>`/`<footer>` block) — it just calls `get_header()`
    near the top and `get_footer()` at the end, same as any ordinary
    WordPress template. `template-home.php` originally rendered its own
    standalone header/footer (a deliberate landing-page choice at the
    time); that was reversed the same day the global chrome was added,
    specifically because it duplicated what the new files already
    render. Header/footer text is a **separate** JSON store from rule
    8/9's Page Content framework — see step 10 below.

## Step-by-step: how to convert a concept file

### 1. Read the entire source file first

Read the whole HTML/CSS concept file before writing anything. Extract
every distinct value used: every color, every font-family, every
font-size, every line-height/letter-spacing/font-weight, every
border-radius, every spacing/padding/gap value. You need the full
picture before you can decide what maps where.

### 2. Map every value onto real Theme Options fields

Open `theme-options.css`. For each value from the concept:

- **If an existing `--hex-*` field represents the same concept**
  (e.g. `--hex-color-primary` for the design's main brand color,
  `--hex-h1-size-desktop` for its H1 size), **overwrite that field's
  value** with the concept's exact number. This is a real, sitewide
  change — every page that reads that token changes, not just the new
  template. That is intentional: Theme Options is global by design.
- **If no field exists for a concept the design needs** (e.g. a
  monospace font stack, a second/stronger border shade, a lighter
  accent tint for dark surfaces), **add a new field**, following the
  existing naming convention exactly (`--hex-{concept}` or
  `--hex-{component}-{property}`, matching the pattern of the ~187
  fields already there). Never prefix new fields with a template name
  (no `--hex-meridian-*`, no `--hex-child-*`) — they are real, global
  schema additions, auto-detected by the Theme Options admin page as
  editable "Custom Tokens" per its own documented mechanism.
- **Keep the derived flat mirror keys in sync.** For every heading
  family (`h1`–`h6`, `body`, `lead`, `large`, `small`, `meta`), the
  flat key `--hex-{key}-size` (no `-mobile`/`-desktop` suffix) must
  always equal `--hex-{key}-size-desktop`. Verify this after every
  edit (a one-line script comparing the two values per family is
  enough).
- **Every length must be `px`.** No `rem`/`em`/`%` — this project's
  entire schema is `px`, per `GUIDELINES.md` §3.
- **When one design value must map onto one of several existing
  fields with overlapping meaning** (e.g. the concept has a single
  brand hue but the schema separates "primary" from "accent"), pick
  the field that matches the *load-bearing* usage (the one driving the
  most prominent UI, typically buttons/CTAs) and document the choice —
  don't leave it arbitrary.
- **When several distinct concept values must consolidate onto one
  shared field** (the schema has fewer size tiers than the concept has
  distinct sizes — this is normal and expected), pick the value used
  by the *majority* of instances, or the most visually load-bearing
  one, and accept the minor size drift on the others. This is a
  deliberate, correct trade-off — not a mistake to avoid.

### 3. Font sizes: use the 11 existing fluid classes — nothing else

This theme has exactly 11 classes that provide fluid, responsive font
sizing, all driven by **one shared `clamp()` rule** already defined
once in `site-theme.css`:

```
.hex-h1, .hex-h2, .hex-h3, .hex-h4, .hex-h5, .hex-h6,
.hex-body, .hex-lead, .hex-large, .hex-small, .hex-meta, .prose {
	font-size: clamp(
		var(--mobile-font-size),
		calc(var(--mobile-font-size) + (var(--desktop-font-size) - var(--mobile-font-size))
			* ((100vw - var(--hex-static-breakpoint-s)) / (var(--hex-static-breakpoint-xl) - var(--hex-static-breakpoint-s)))),
		var(--desktop-font-size)
	) !important;
}
```

Each class only sets its own `--mobile-font-size`/`--desktop-font-size`
custom properties (fed by the matching `--hex-{key}-size-mobile/-desktop`
Theme Options fields) — the clamp formula itself is shared and must
never be duplicated per class or per template.

**Rule: to size ANY piece of text — a real heading tag or not — apply
the closest-fitting one of these 11 classes directly to the HTML
element.** Never write `font-size` in your own CSS class. Never write
a new `clamp()` expression, however small the range. These classes are
documented (in `site-theme.css`'s own comments) to work on *any*
element, not just real `<h1>`–`<h6>` tags — use a heading class on a
big display number, a stat figure, a pull-quote mark, a logo wordmark,
whatever fits the size best.

Practical mapping method:
1. List the concept's actual desktop font-size for the element.
2. Find the closest of the 11 tiers by desktop value (after step 2's
   token mapping has set/confirmed all 11 tiers' values).
3. Apply that class directly in the HTML (`class="existing-layout-class hex-h5"`,
   etc.) alongside whatever layout/color class the element already has.
4. When a class needs to size a whole cluster of descendants that
   don't have their own more specific size class (e.g. all footer
   links inside `.footer-grid`, or the plain body copy inside a card
   whose heading/tag already carry their own explicit class), apply
   the tier class to the **container** — the fluid rule's `!important`
   on any more-specific child class still wins, so this is safe and
   avoids tagging dozens of individual leaf elements. **Exception:**
   native form controls (`input`/`select`/`textarea`) do not reliably
   inherit `font-size` from an ancestor in all browsers — tag each one
   directly instead of relying on container cascade.

**Font-family works the same way, but with only 4 fields and 2
utility classes** (`--hex-font-heading`/`-body`/`-accent`/`-mono` in
`theme-options.css`; `.font-accent`/`.font-mono` in `site-theme.css`):
- Real `<h1>`–`<h6>` tags and `<body>` get their font for free from
  the `@layer base` rules already in `site-theme.css` — no class
  needed.
- Any other element that should use the same decorative/heading font
  (a big display number, a logo wordmark, a pull-quote mark, a stat
  figure) gets `class="font-accent"` added directly.
- Any element that should use the monospace font (labels, tags,
  eyebrows, badges, captions styled as mono in the concept) gets
  `class="font-mono"` added directly.
- Never write `font-family: var(--hex-font-*, ...)` inline in a
  component's own rule — always the class, on the element, in the
  HTML. If the concept needs a font role these 4 fields don't cover,
  that's a real product decision (a 5th font field) — flag it rather
  than repurposing an existing field's slot incorrectly.

### 4. Spacing, radius, and one-off layout numbers

- Reuse `var(--hex-spacing-xs/-sm/-md/-lg/-xl/-2xl)` **only** where the
  concept's value exactly equals that scale's current stored value
  (8/16/24/32/48/64px, unless intentionally changed in step 2).
- **Critical gotcha:** `var(--hex-spacing-md, 26px)` does **not** fall
  back to `26px` when the concept wants 26 but the token is
  actually 24 — the fallback only applies when the property is
  *undefined*. A defined token's real stored value always wins,
  silently, over any mismatched fallback you write. If a property's
  desired value doesn't exactly match an existing scale step, **write
  it as a plain literal px value**, not a `var()` call with a
  mismatched fallback.
- The same logic applies to `--hex-radius-sm/-md/-lg` and any other
  scale token: only use `var()` when the token's real value already
  equals what you need (adjust the token in step 2 if it should).
- Purely structural layout numbers with no Theme Options concept at
  all (grid-template-columns ratios, a page container max-width,
  transition durations) stay as plain literals — not everything needs
  to be a token, matching how the parent's own `.accordion`/`.tabs`/
  `.badge` classes already mix tokens with literals for exactly this
  reason.

### 5. Components: reuse real classes, don't recreate them

Before writing any new CSS for a button, card, badge, form field, nav
link, or icon, check whether the parent's real component classes
already cover it — apply them directly in the HTML:

| Component | Real classes |
|---|---|
| Buttons | `.btn` + `.btn-primary` / `.btn-secondary` / `.btn-danger` / `.btn-text` / `.btn-outline` + size modifier `.btn-xs/-sm/-lg/-xl` |
| Cards | `.card` |
| Badges/pills | `.badge` + `.badge-default` / `.badge-primary` / `.badge-success` / `.badge-warning` / `.badge-danger` |
| Form fields | `.form-control` (input/select/textarea), `.form-label` (label) |
| Nav | `.nav-menu` (container), `.nav-link` (each link) |
| Icons | `.icon` + size modifier `.icon-sm` / `.icon-lg`; put `stroke="currentColor"` on the SVG's paths so it actually picks up the class's color |
| Headings/body text sizing | the 11 fluid classes from step 3 |
| Font-family | `.font-accent` (heading/decorative font), `.font-mono` (monospace) — real h1-h6/body get theirs free from `@layer base` |

**Only when the concept's visual treatment genuinely doesn't match any
existing variant** (checked against every variant, not just the
default), author a new one — following the exact same pattern as the
existing variants (`var(--hex-{key}, {fallback})`, same property
shape). Two real examples already added this way: `.btn-ghost` (a
neutral-bordered button variant `.btn-outline` doesn't cover, since
that one is hardcoded to the primary brand color) and `.badge-outline`
/ `.badge-mono` (two tag/pill treatments the default filled badge
variants don't match). New variants go in `assets/css/src/site-theme.css`,
in the same `@layer components` block, never as a one-off rule buried
in a template-specific stylesheet.

### 6. New layout patterns with no existing class — author them in `site-theme.css`, the only CSS file

For structural patterns the design system has no class for at all — a
hero split, a numbered row list, a stats grid, a dark contrast band, a
two-column path split, a form split, a footer grid — author new
classes directly in `assets/css/src/site-theme.css`. There is no
second file to maintain and nothing to keep in sync — this is the only
stylesheet, and it is directly enqueued (see step 7).

- **Give them clean, generic names**, not page/template-prefixed ones
  (`.hero-card`, `.row-item`, `.stats-grid` — not `.meridian-hero-card`).
  They are real, live, sitewide-usable classes the moment you save
  this file — name them as if another future template will reuse them,
  because it can.
- **Add them in a clearly commented `@layer components` block**, in
  the exact same `var(--hex-{key}, {fallback})` authoring style as
  everything else in the file (the original parent-inherited `.btn`/
  `.card`/etc. block above it).
- **Every value that has a real Theme Options concept must use its
  token** (colors, radius, the button/form/card/section fields, the 11
  fluid text classes for sizing, `.font-accent`/`.font-mono` for
  family). Structural-only numbers (grid ratios, one-off gaps that
  don't match the spacing scale — see step 4) stay literal.
- **Responsive breakpoints are layout-only.** A `@media` block may
  change `grid-template-columns`, `gap`, `padding`, or `display`
  (show/hide) — it must never redeclare a `font-size` or `font-family`,
  since every text element already carries a fluid `.hex-*` class and
  a font-family class that already scale/apply correctly on their own.

### 7. Why this file can be directly enqueued, and how

`site-theme.css` used to be Tailwind-v4 source (`@theme { ... }` at
the top) — a build artifact meant only as a reference to eventually
port into the parent theme, because a browser can't process `@theme`
unbuilt. As of 2026-08-29 it was rewritten to plain, browser-native
CSS: the `@theme { ... }` at-rule became a plain `:root { ... }` block
(Tailwind compiles `@theme` to exactly that in its own output, so
nothing else in the file needed to change). That is the **only**
change needed to make the whole file — the original parent-inherited
content and every template-specific addition alike — work correctly
with zero build step.

Enqueue it directly from `functions.php`, depending on `hex-tailwind`
so the runtime `--hex-*` tokens it reads are already registered.
Originally conditional on `is_page_template( 'the-file.php' )` so it
only loaded on the page(s) that needed it; broadened to **unconditional**
2026-08-30 once `header.php`/`footer.php` made this file's classes
(`.site-nav`/`.footer-grid`/`.font-mono`/etc.) global site chrome
rather than something only one or two templates used — a new template
never needs to add itself to an enqueue condition for this file, it
already loads everywhere. This duplicates (harmlessly — identical
computed values, same runtime tokens) whatever shared classes the
parent's own real Tailwind build already provides sitewide; it's only
actually *needed* for the child-theme-specific classes the parent
doesn't have. This is still not a Tailwind build pipeline in the child
theme — no npm, no package.json, no compile step, just an ordinary
hand-written CSS file, the same kind of thing `style.css` already is.

### 8. Text content: read it from the Page Content JSON framework, never hardcode it

Per `GUIDELINES.md` §8, the parent theme ships a framework
(`inc/page-content.php`) for keeping a page's text out of the template
file and in one editable JSON object per page instead. **This is
required for every template in this workflow, converted or new.**

- Call `hex_get_page_content()` once near the top of the template
  (guard with `function_exists()` — a template file can in principle
  be parsed before the parent's `inc/` is loaded):
  ```php
  $content = function_exists( 'hex_get_page_content' ) ? hex_get_page_content() : array();
  ```
- Design a nested JSON key structure that mirrors the page's own
  sections (`hero`, `services`, `platform`, `results`, `brands`,
  `quote`, `compliance`, `paths`, `contact`, `footer`, ...) — group
  repeated items (service rows, feature cards, stats, footer columns)
  as arrays of objects rather than flat numbered keys.
- For every text node, read it with a `??` fallback equal to the
  original design's exact copy — a brand-new page has no saved JSON
  yet (`hex_get_page_content()` returns `array()`), so the template
  must still render identically to the source design with nothing
  configured. For repeated items, the fallback is a whole default
  array; `foreach` over `$content['section']['items'] ?? [ ...defaults... ]`.
- **Escape on output, every time, regardless of what the framework
  already sanitized on save**: `esc_html()` for plain text,
  `esc_url()` for links, `wp_kses_post()` only for the few fields that
  intentionally carry safe inline HTML (an accent `<span>`, a bold
  lead-in word inside an eyebrow) — never `esc_html()` on those, or
  the tags render literally as text.
- Decorative/structural markup that isn't really editor-facing content
  (SVG icons, wrapper `<div>`s, CSS classes, the grid/layout structure
  itself) stays hardcoded in the template — the JSON framework is for
  copy an editor would reasonably want to change, not for markup.
- Verify the conversion by actually running the template with an empty
  `$content = array()` (mock the handful of WP functions it calls —
  `esc_html`/`esc_url`/`wp_kses_post`/`home_url`/`language_attributes`/
  `bloginfo`/`wp_head`/`wp_footer`/`body_class`/`wp_body_open` — and
  `include` the file inside `ob_start()`/`ob_get_clean()`) — zero PHP
  warnings/notices and the rendered output matching the original
  design confirms every fallback path actually works, not just the
  happy path with JSON already saved.

### 9. Backfill the page's JSON into the database — mandatory, every time

Step 8 makes the template *able* to read saved content; it does not
put any content in the database. A template is not done until this
step has also run.

- Build one JSON payload per live Page already assigned the template,
  matching the exact nested key structure step 8 designed, using the
  exact same values as the template's own `??` fallbacks (the original
  design's copy) — so the saved row and the fallback render identically
  at first.
- Save it with `hex_save_page_content( $page_id, $payload )`. Find the
  right page ID(s) with `get_posts()` filtered on
  `_wp_page_template` = the template's filename (or check the Page
  editor directly) — do not guess an ID.
- Verify the round-trip: re-fetch with `hex_get_page_content( $page_id )`
  and confirm it comes back non-empty, then load the live page and
  confirm nothing double-encodes (an `&`-containing field or a
  `wp_kses_post()` field rendering as literal escaped text means an
  escaping bug, not a save bug).
- Do this via a small temporary script that bootstraps WordPress
  through `wp-load.php` (the same pattern as checking/backfilling the
  Home page's content) — place it in this theme's own directory, run
  it once, then delete it. It is never a permanent theme file and is
  never committed.
- If a page is created for the template *after* this step (e.g. the
  template exists but no Page uses it yet), this step is deferred
  until a Page is actually assigned it — but treat "a Page now uses
  this template and its JSON row is still empty" as an open item to
  close out, not a background detail to skip.
- **Check this invariant any time you're touching a template's content**,
  not only right after creating it — a table that goes empty later
  (a DB reset, a migration) is the same "not done" state and gets
  fixed the same way.

### 10. Header/footer text: the Site Content JSON framework — a separate store from step 8

The header and footer are **global chrome**, not this template's own
content — per rule 10, a template never renders them inline, it just
calls `get_header()`/`get_footer()`. Their text (brand name, phone, nav
CTA, footer columns/links, etc.) therefore does **not** live in this
page's `hex_get_page_content()` JSON (step 8) — it lives in a sibling
per-site framework, `hex_get_site_content( 'header' | 'footer' )`,
edited on its own "Header & Footer" admin page rather than a per-page
meta box. Full schema, current field list, and the "How to edit"
instructions live in `GUIDELINES.md` §9 — read that section rather
than duplicating it here. What matters for this workflow:

- **Never add header/footer fields to a template's own Page Content
  JSON schema** (step 8) — if a new template needs a header/footer
  field that doesn't exist yet, that's a change to
  `template-parts/site-header.php`/`site-footer.php` and the Site
  Content schema, not to the page's own JSON.
- **The primary nav is a real WP nav menu** (`theme_location =>
  'primary'`), edited at Appearance → Menus — not JSON, not a
  hardcoded array in the template.
- **The same "wired but not saved" invariant from rule 9 applies here
  too**, just against `hex_save_site_content()` and the "primary" nav
  menu instead of `hex_save_page_content()` and a page ID — check
  `hex_get_site_content( 'header' )`/`( 'footer' )` come back non-empty
  and the "primary" menu location actually has items, any time this
  chrome is touched, and backfill with `GUIDELINES.md` §9's documented
  defaults if either is found empty.

### 11. The PHP template file itself

- `Template Name:` header comment so it's selectable in the Page
  editor's Template dropdown.
- Per rule 10: call `get_header()` near the top and `get_footer()` at
  the end — never render an inline `<header>`/`<footer>` or the
  surrounding `<!DOCTYPE>`/`<head>`/`<body>` boilerplate; `header.php`
  already opens the document and `footer.php` already closes it
  (including `wp_head()`/`wp_footer()`).
- Wrap the template's own content in `<main id="primary">…</main>`
  (outside `.page-hero`, which is title-band chrome, not main content).
  `template-parts/site-header.php`'s skip-link targets `#primary` —
  every new template should give it something real to jump to. (Known
  gap, not yet fixed as of 2026-08-30: `template-home.php` and
  `template-ai-technology.php` predate this and don't have the wrapper
  yet — a real accessibility gap on those two, not something to copy
  into new templates.)
- Every element uses a class from steps 3–6 — zero inline `style=""`,
  zero literal colors/fonts/sizes typed directly into the markup.
- WordPress conventions: `esc_url( home_url( '/' ) )` for internal
  links, `esc_html()` around dynamic PHP output — don't hand-roll what
  core already provides.

### 12. Bookkeeping — every single time

- Bump `style.css`'s `Version:` header **and**
  `functions.php`'s `HEXNITY_WP_CHILD_VERSION` constant together
  (`GUIDELINES.md` §6).
- Append an entry to `action-map.json` (what changed, why, files
  touched) — never edit past entries.
- Update `project.json`'s `structure` section for any new/changed file.
- **Update this file** — see "Keeping this guide current" below.
- Verify before calling anything finished: `php -l` on every changed
  PHP file, brace-count balance on every changed CSS file
  (`grep -o "{" file | wc -l` vs `grep -o "}" file | wc -l`), zero
  remaining `style="` in the template, and every class used in the
  HTML actually resolves to something (a quick script cross-checking
  classes-used-in-HTML against classes-defined-in-CSS-plus-known-real-parent-classes
  catches typos and orphaned classes cheaply).

## Do NOT

- Do not create a third CSS file for any reason — only `site-theme.css`
  and `theme-options.css` exist in this workflow. No per-template
  stylesheet, no mirror file, no inline `<style>` block.
- Do not hardcode a `font-size` anywhere, for any reason.
- Do not write a `clamp()` formula yourself — the one shared rule in
  step 3 is the only one that should ever exist.
- Do not write a one-off `font-family` declaration in a component
  rule — always `.font-accent` / `.font-mono` (or the free `@layer
  base` h1-h6/body treatment), applied as a class in the HTML.
- Do not invent a scoped/prefixed custom token set for one template
  (no `--hex-child-*`, no `--hex-meridian-*`) — map to the real global
  schema, extending it with cleanly-named new fields when needed.
- Do not write `var(--hex-spacing-x, value)` where `value` differs
  from that token's actual current stored value — it will silently
  render the token's real value, not your fallback. Use a literal.
- Do not recreate a parent component class under a different name —
  `.btn`, `.card`, `.badge`, `.form-control`, `.nav-link`, `.icon` and
  their variants already exist; use them.
- Do not redeclare `font-size`/`font-family` inside a `@media` block —
  breakpoints are layout-only; the fluid classes already handle
  responsive sizing on their own.
- Do not hardcode editor-facing text directly in the template — read
  it from `hex_get_page_content()` with a fallback, per step 8.
- Do not skip output escaping on a `hex_get_page_content()` value just
  because the framework already sanitized it on save — always
  `esc_html()`/`esc_url()`/`wp_kses_post()` on output too.
- Do not touch anything outside this child theme's own directory. If
  a step needs a parent-theme change, stop and tell the user.
- Do not consider a template "done" once it's wired to
  `hex_get_page_content()` — that only makes the page *capable* of
  showing saved content. Always also save the default JSON to the
  database for the page(s) using it (step 9) before finishing.
- Do not assume a previously-backfilled page's JSON row still exists —
  check it every time you touch that template's content; the table
  can go empty independently of the template code (DB reset,
  migration) and that is the same "not done" state as never having
  backfilled it.
- Do not render a template's own inline `<header>`/`<footer>` markup
  or `<!DOCTYPE>`/`<head>`/`<body>` boilerplate — always
  `get_header()`/`get_footer()` (rule 10). This theme's own branded
  chrome already renders that; a template that duplicates it produces
  two headers/footers or drifts out of sync with the real one.
- Do not put header/footer text in a page's own `hex_get_page_content()`
  JSON — that content belongs in the Site Content framework
  (`hex_get_site_content()`, step 10 / `GUIDELINES.md` §9), which is
  site-wide, not per-page.

## Current template inventory

| Template file | Template Name | Source concept | Wired to JSON framework | DB row backfilled (step 9) | Notes |
|---|---|---|---|---|---|
| `template-home.php` | Meridian Home | `concept-b-meridian_1.html` (theme root) | Yes | **Yes (re-backfilled 2026-08-30, after the table was found empty despite the original 2026-08-29 save)** | No longer standalone as of 2026-08-30 — now calls `get_header()`/`get_footer()` like every other template, delegating to `header.php`/`footer.php` + `template-parts/site-header.php`/`site-footer.php` (GUIDELINES.md §9) instead of its own inline `<header>`/`<footer>` markup. The `nav`/`footer` keys this page's own JSON used to carry are gone (that content now lives in the site-wide Site Content JSON, `hex_get_site_content()` — see §9's own backfill status); this page's `hex_get_page_content()` JSON keeps `site`/`hero`/`logo_strip`/`services`/`platform`/`results`/`brands`/`quote`/`compliance`/`paths`/`contact` (note: `site.phone`/`site.email` remain here too, read independently by the Contact section). Assigned to Page ID 8 ("Home"), the static front page. |
| `template-ai-technology.php` | AI & Technology | `template.html` (theme root — a pre-drafted template using generic/mockup classes, mapped onto this theme's real system rather than treated as final markup) | Yes | **Yes (backfilled 2026-08-30)** | Not standalone — uses the parent's `get_header()`/`get_footer()`. Reuses `template-home.php`'s `.dark-band`/`.feature-list`/`.compliance-box`/`.badge-mono` patterns for its two body sections; introduced two new shared classes, `.page-hero` and `.breadcrumb`, for its title band. Text reads from `hex_get_page_content()` — `hero` (`breadcrumb_label`/`heading`/`lede`/`cta_label`/`cta_url`), `features` (array of `index`/`title`/`desc`), `compliance` (`heading`/`text`/`badges`) top-level keys. Assigned to Page ID 29 ("AI & Technology"), published. |
| `template-services.php` | Services | Extracted from `template-home.php`'s own "Services" section (`#services`), not a concept file | Yes | **Yes (backfilled 2026-08-30)** | Not standalone — `get_header()`/`get_footer()`. `.page-hero`/`.breadcrumb` title band, then `template-home.php`'s exact `.rows`/`.row-item` markup/copy (no new CSS). `hero` (`breadcrumb_label`/`heading`/`lede`/`cta_label`/`cta_url`, `cta_url` falls back to `home_url('/contact/')` — a real page, not a `#contact` anchor, since this is a standalone page not Home), `rows` (array of `num`/`title`/`desc`/`tags`/`url` — the first row's `url` fallback is `home_url('/ai-technology/')`, not the `#platform` anchor `template-home.php`'s own copy of this row uses, for the same reason). Assigned to Page ID 37 ("Services"), published. |
| `template-our-brands.php` | Our Brands | Extracted from `template-home.php`'s "Brands" section (`#brands`) + its quote block, not a concept file | Yes | **Yes (backfilled 2026-08-30)** | Not standalone — `get_header()`/`get_footer()`. `.page-hero`/`.breadcrumb`, then `.brand-grid`/`.brand-card` (exact `template-home.php` markup/copy) plus `.quote-block` (the Compare Your Bills customer testimonial — carried along as brand evidence). `hero` (same 5 keys, `cta_url` falls back to `home_url('/contact/')`), `cards` (array of `tag`/`title`/`desc`/`link_label`/`url`/`domain`/`rating`), `quote` (`text`/`attribution`). Assigned to Page ID 38 ("Our Brands"), published. |
| `template-results.php` | Results | Extracted from `template-home.php`'s "Results" section (`#results`), not a concept file | Yes | **Yes (backfilled 2026-08-30)** | Not standalone — `get_header()`/`get_footer()`. `.page-hero`/`.breadcrumb`, then the exact `.stats-grid` markup/copy. `hero` (same 5 keys, `cta_url` falls back to `home_url('/contact/')`; the lede is new copy — the original section had no lede of its own, only an eyebrow/heading), `stats` (array of `figure`/`label`). Assigned to Page ID 39 ("Results"), published. |
| `template-contact.php` | Contact | Extracted from `template-home.php`'s "Contact" section (`#contact`), not a concept file | Yes | **Yes (backfilled 2026-08-30)** | Not standalone — `get_header()`/`get_footer()`. `.page-hero`/`.breadcrumb` (no CTA button in the hero — the form is right below it), then the exact `.form-split` form markup/copy. `hero` (`breadcrumb_label`/`heading`/`lede` only, no `cta_label`/`cta_url`), `site` (`phone`/`email`/`address`, read independently the same way `template-home.php`'s own Contact section does — not from the Site Content header/footer framework), `contact` (`eyebrow`/`heading`/`text`/`form_options`/`submit_label`/`fine_print`). Assigned to Page ID 40 ("Contact"), published. |

## Keeping this guide current

After finishing any task in this workflow (a new template conversion,
a token-mapping correction, a new shared class), do two things here:

1. If the *process itself* changed (a new rule learned, a new correct
   pattern established) — update the relevant "How To" section above
   so it stays the single, current, correct instruction. Don't leave
   two conflicting versions of a rule in this file.
2. Add or update the template's row in "Current template inventory"
   above.

Keep this file itself free of narrative — "what went wrong last time"
belongs in `action-map.json`'s dated entries, not here. This file only
ever states the current, correct way to do the work.
