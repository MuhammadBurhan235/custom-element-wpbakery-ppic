# Custom Element WPBakery – PPIC (ppicurug.ac.id)

**A modular WordPress/WPBakery element library built for Politeknik Penerbangan Indonesia Curug (PPIC), giving non-technical staff a drag-and-drop way to manage complex academic directories, catalogs, and institutional pages — without ever touching PHP.**

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=flat&logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-Plugin-21759B?style=flat&logo=wordpress&logoColor=white)
![WPBakery](https://img.shields.io/badge/WPBakery-vc__map-orange?style=flat)
![CSS3](https://img.shields.io/badge/CSS3-Flexbox%2FGrid-1572B6?style=flat&logo=css3&logoColor=white)
![Vanilla JS](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=flat&logo=javascript&logoColor=black)
![Version](https://img.shields.io/badge/Version-2.3.17-blue?style=flat)
![License](https://img.shields.io/badge/License-GPLv2-green?style=flat)
![Built with AI](https://img.shields.io/badge/Built%20with-GitHub%20Copilot%20%7C%20Gemini-8A2BE2?style=flat&logo=githubcopilot&logoColor=white)

---

## 1. Project Overview

This repository is a **WordPress mu/plugin-style extension for WPBakery Page Builder** that ships **100+ custom shortcode elements** tailored to the PPIC institutional website ([ppicurug.ac.id](https://ppicurug.ac.id)) — everything from hero sliders and stats counters to a fully filterable lecturer directory, an accreditation grid, a training catalog, and PPID (public information) compliance pages.

Rather than hardcoding institutional content into page templates, every element is registered through the WPBakery `vc_map` API as a **self-contained, drag-and-drop building block**. Editors compose entire pages — Home, Dosen (Lecturers), Klinik (Clinic), Prodi (Study Programs), PPID, Pelatihan (Training), and more — visually in the WordPress backend, while the plugin handles all rendering, data parsing, filtering, and responsive behavior behind the scenes.

Each element lives in its own file under [`elements/`](elements/), is registered independently in [`custom-element-wpbakery-ppic.php`](custom-element-wpbakery-ppic.php), and ships with scoped CSS/JS so that adding or removing a block never risks breaking another part of the site.

### 📸 Visual Impact: Before & After

📁 **[View the full Before/After screenshot set on Google Drive](https://drive.google.com/drive/folders/1Ii3paiCxLpaD8X9lguBuQZn8lMMjaUwd)**

The screenshot set above documents the tangible, user-facing difference these WPBakery elements made to [ppicurug.ac.id](https://ppicurug.ac.id) once they went live. Side-by-side comparisons show the site moving from a handful of static pages to a modern, information-dense institutional portal:

- **New navigation & information architecture** — the header grew from a bare menu into a structured mega menu (`Beranda`, `Tentang PPIC`, `Akademik`, `Sipencatar`, `Pelatihan`, `Penelitian & Publikasi`, `Berita`, `Layanan`, `PPID`), with a dedicated utility bar (`PPID`, `Sipencatar`, `FAQ`, `Berita`, `Kalender`, `Kontak`) surfacing previously buried information above the fold.
- **New, previously non-existent pages** — entire sections such as `Profil Akademisi` (the CSV-driven, filterable lecturer directory), `Katalog Pelatihan` (training catalog with category filtering), `Sipencatar PPI Curug` (admission info hub), and the full `PPID` transparency portal (public information requests, service reports, complaint channels) did not exist before this plugin — they are now first-class, editor-managed pages.
- **Increased institutional transparency** — the PPID module alone exposes a public information request flow, service reports, and appeal/dispute channels that fulfill Indonesia's public-information-disclosure obligations, turning a compliance requirement into an accessible, well-designed self-service page.
- **Richer, more discoverable content** — the footer expanded into four organized columns (`PPI Curug` contact block, `Quick Links`, `Layanan`, `Ikuti Kami` social links) and the homepage now surfaces a live Instagram feed grid, giving visitors far more entry points into the institution's content than the previous static layout.
- **Consistent, on-brand visual language** — every new page inherits the same navy/gold PPIC design system (mega menu, cards, badges, accordions) instead of looking like a bolted-on addition, because each element was built to match the approved mockups pixel-for-pixel (see [Section 4](#4-ai-empowered-development-workflow)).

In short: the before/after evidence shows this isn't just a code refactor — it directly expanded what information the public can find and how easily PPIC's own staff can keep that information up to date.

---

## 2. The "Why": Universal & Technical Objectives

### 🎯 Universal Goal

Empowering **non-technical content managers** at PPIC to build and manage complex, interactive directory layouts via an intuitive **drag-and-drop WPBakery interface** — eliminating the need for hardcoded content updates every time a lecturer, program, or announcement changes.

### ⚙️ Technical Goal

Encapsulating complex backend logic — **CSV parsing, dynamic array filtering, native PHP loops** — and dynamic UI states into **modular, reusable WordPress shortcodes** mapped via the `vc_map` API, for strict **maintainability and codebase isolation** across a large, multi-page institutional site.

---

## 3. Features & Architecture

### 🧩 Modular Shortcode Architecture

- **100+ independent elements** (`ppic-*.php`), each registering exactly one shortcode + one `vc_map` definition, keeping the WPBakery "Add Element" panel organized by page section (Home, Dosen, Klinik, PPID, Prodi, Sipencatar, Pelatihan, Penelitian, Survey, Rental, Sisfo, etc.).
- A single bootstrap file ([`custom-element-wpbakery-ppic.php`](custom-element-wpbakery-ppic.php)) wires everything together with `require_once`, so every element is discoverable, isolated, and independently disable-able without cascading failures.
- Shared styling lives in [`assets/style.css`](assets/style.css), while page-specific concerns (mega menu, active/hover nav states, prodi navigation, PPID accordions) are split into their own dedicated stylesheets (`megamenu.css`, `activehovermegaada.css`, `prodinav.css`, `daftarinfo.css`) to avoid style leakage.

### 📊 Spreadsheet/CSV-Driven Content (No Hardcoding)

- Elements like the **Dosen Directory**, **Gallery**, **Leadership**, and **Training Catalog** support a `data_source: spreadsheet` mode: editors upload a CSV/Google-Sheets export straight from the WordPress Media Library and the plugin renders it automatically.
- A **self-healing CSV parser** (`ppic_dosen_directory_parse_csv_line`) auto-detects the delimiter (comma, semicolon, or tab) by sampling column counts, then recursively re-splits nested/mis-delimited lines — built to survive messy real-world exports from Google Sheets.
- **Flexible header aliasing** maps dozens of possible column-name variants (`nama`, `full_name`, `nama_lengkap` → all map to `name`) so staff don't need to follow a rigid spreadsheet schema.
- Google Drive share links are automatically converted into direct-viewable image URLs (`ppic_dosen_directory_normalize_photo_url`), so photo columns can just be "share link" pastes.

### 🔍 Custom Search & Filter Engine

- The Dosen Directory precomputes a per-card `search_blob` (name + bio + expertise + education, lowercased and concatenated) so **client-side search** stays instant with zero AJAX round-trips.
- Multi-facet filtering (Jenis Tenaga / Prodi / Jabatan) is driven by `data-*` attributes on each card and combined in vanilla JS with `Array.filter`-style boolean matching — fully reactive to search input, checkbox filters, and sort order simultaneously.
- A "Show all / Show less" pattern progressively discloses long filter option lists, keeping the sidebar usable even with 30+ possible values.

### 📱 Mobile-First Responsive UI

- Filter sidebars collapse into an accordion-style mobile toggle (`filter-mobile-toggle`) below desktop breakpoints, addressing WPBakery's notoriously tricky mobile Flexbox/Grid inheritance behavior.
- Grid-based layouts (stats, courses, accreditation, gallery) use CSS `grid-template-columns` with breakpoint-specific column counts (`repeat(5,...)` → `repeat(3,...)` → `repeat(2,...)` → `1fr`) tuned across `991px`, `768px`, and `575px` breakpoints.
- Expandable "Mata Kuliah" (courses taught) and PPID FAQ sections use lightweight vanilla-JS accordions instead of a JS framework, keeping the frontend dependency-free.

### 🛠️ Developer Tooling Built Into the Repo

- [`preview-bootstrap.php`](preview-bootstrap.php) + [`preview-all-elements.php`](preview-all-elements.php): a **WordPress-free local preview harness** that stubs out `add_shortcode`, `wp_enqueue_*`, and attachment functions so every element can be visually tested via `php -S` — no WordPress/WPBakery install required during development.
- [`start-preview.cmd`](start-preview.cmd): one-command local server launcher for that harness.
- [`download-plugin.php`](download-plugin.php): packages the working directory into an installable, versioned plugin `.zip` (auto-reads the `Version:` header) while excluding dev-only preview tooling from the distributable build.
- [`preview-data/`](preview-data/): sample CSVs (lecturers, gallery, leadership, training catalog) used to exercise the spreadsheet-parsing code paths without touching production data.

---

## 4. AI-Empowered Development Workflow

This project was built the way I intend to keep working as a professional developer: **with AI as an active collaborator, not a black box.** I paired with **GitHub Copilot Pro (Claude Sonnet 4.5)** and **Google Gemini Pro** throughout, using each tool where it was strongest, while treating every suggestion as a draft to be verified rather than a final answer.

### My Standard Operating Procedure: From Static Mockup to Live Element

Since the frontend UI/UX for PPIC is delivered by a separate design team as static `.html` mockup files, every new element follows the same disciplined, repeatable cycle before a single line of PHP is written:

1. **Extract** — Inspect the target component in the static mockup, pull the exact HTML structure (markup, class names, and nesting) straight from DevTools, and capture a screenshot of the rendered UI for visual reference.
2. **Constrain** — Feed the AI both artifacts — the raw HTML and the screenshot — as the source of truth, explicitly instructing it to wrap that _exact_ DOM structure and CSS class hierarchy rather than inventing its own markup.
3. **Generate** — The AI produces the `shortcode_atts()`, `vc_map()` parameter definitions, and PHP render loop needed to dynamically reproduce that markup from editable WPBakery fields.
4. **Verify** — I diff the AI's rendered output against the original mockup pixel-by-pixel, confirming zero deviation in class names, structure, or spacing before the element is wired into the plugin.

This turns "design mockup → WPBakery element" into a structured, low-ambiguity pipeline instead of an open-ended interpretation exercise — the AI's job is faithful translation, not creative reinterpretation.

### How AI was used

- **Boilerplate generation** — scaffolding the repetitive `shortcode_atts()` → render → `vc_map()` pattern across 100+ elements, so I could focus my own attention on the logic unique to each block instead of retyping structure.
- **Mockup-constrained markup generation** — given the extracted HTML and screenshot from the SOP above, generating PHP render loops and `vc_map` parameters that reproduce the designer's exact DOM structure and CSS classes, rather than approximating them from a text description.
- **Regex & parsing logic** — co-drafting the CSV delimiter-detection and header-aliasing regex (comma/semicolon/tab sniffing, nested-line re-splitting) that powers the spreadsheet-driven elements.
- **UI constraint scoping** — translating design mockups into concrete WPBakery `vc_map` param definitions (dropdowns, param groups, image pickers) and CSS layout rules.
- **Refactoring exploration** — asking AI to propose alternative structures (e.g., splitting shared CSV helpers vs. duplicating per element) so I could weigh trade-offs before committing to an approach.

### Where I stayed firmly in control

- **Precise, context-rich prompting** — I fed the assistant real file contents, existing naming conventions, and exact WPBakery API constraints instead of vague requests, which is the difference between usable output and generic scaffolding.
- **Mockup fidelity enforcement** — supplying the AI with the inspected HTML and a screenshot as hard constraints, then manually verifying the generated markup matched the approved design 1:1 before accepting it, rather than letting the AI "reinterpret" the layout.
- **Logic verification** — every CSV parser and filter function was traced by hand against messy real spreadsheet exports (mixed delimiters, blank rows, HTML-tainted cells) before being trusted in production.
- **Scope isolation discipline** — enforcing the one-file-per-element convention myself so AI-generated code for one block could never silently leak into or break another.
- **Debugging Flexbox/Grid on mobile** — the accordion/sidebar collapse behavior on small breakpoints required manual DevTools inspection and iteration; AI proposals were a starting point, not the fix.
- **Accept / reject / refine judgement** — a meaningful share of AI-suggested code was rewritten or discarded outright when it didn't match the project's conventions, security posture (`esc_html`, `esc_attr`, `esc_url` sanitization throughout), or performance goals.

> In short: AI accelerated the _first draft_, but every line that shipped was read, understood, and — where necessary — corrected by me. That review discipline is the workflow I want to bring to Moodle's Platform Solutions team.

---

## 5. Contribution Ranking

[![Contributors](https://img.shields.io/github/contributors/MuhammadBurhan235/custom-element-wpbakery-ppic?style=flat)](https://github.com/MuhammadBurhan235/custom-element-wpbakery-ppic/graphs/contributors)
[![Commit Activity](https://img.shields.io/github/commit-activity/t/MuhammadBurhan235/custom-element-wpbakery-ppic?style=flat)](https://github.com/MuhammadBurhan235/custom-element-wpbakery-ppic/graphs/commit-activity)

[![Contributors Graph](https://contrib.rocks/image?repo=MuhammadBurhan235/custom-element-wpbakery-ppic)](https://github.com/MuhammadBurhan235/custom-element-wpbakery-ppic/graphs/contributors)

| Rank  | Contributor                                               | Commits | Lines Added | Lines Removed |
| :---: | :-------------------------------------------------------- | :-----: | :---------: | :-----------: |
| 🥇 #1 | [MuhammadBurhan235](https://github.com/MuhammadBurhan235) |   34    |   +49,339   |    −6,070     |
| 🥈 #2 | [AndikaDzaki](https://github.com/AndikaDzaki)             |   12    |   +1,532    |      −62      |
| 🥉 #3 | [nayla123-rgb](https://github.com/nayla123-rgb)           |    1    |    +295     |       0       |

This table is generated straight from git history via `git shortlog -sn --all` (commits) and `git log --author="<name>" --numstat` (lines added/removed), so it stays accurate as the project grows — re-run those commands anytime to refresh the numbers. Live, always-current rankings are also available on the [GitHub Insights → Contributors](https://github.com/MuhammadBurhan235/custom-element-wpbakery-ppic/graphs/contributors) page.

---

## Repository Structure

```
custom-element-wpbakery-ppic/
├── custom-element-wpbakery-ppic.php   # Plugin bootstrap – registers all elements
├── assets/style.css                   # Shared/global element styles
├── elements/                          # 100+ self-contained WPBakery shortcode elements
│   ├── ppic-hero.php
│   ├── ppic-dosen-directory.php       # CSV-driven, filterable lecturer directory
│   ├── ppic-gallery-main.php
│   ├── ppic-training-catalog.php
│   └── ...
├── preview-data/                      # Sample CSVs for local testing
├── preview-bootstrap.php              # WordPress-free stub environment
├── preview-all-elements.php           # Renders every element for visual QA
├── start-preview.cmd                  # One-command local preview server
├── download-plugin.php                # Builds a distributable plugin .zip
└── *.css                              # Page-specific styling (nav, mega menu, PPID, etc.)
```

## Running the Local Preview

No WordPress install required:

```bash
php -S 127.0.0.1:8080 -t .
```

or on Windows, simply run:

```bash
start-preview.cmd
```

Then open `http://127.0.0.1:8080/preview-all-elements.php` to visually inspect every element rendered with sample data.
