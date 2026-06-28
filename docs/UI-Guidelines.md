# Sehati — UI Guidelines

## Design Philosophy

Professional, trustworthy, calm, medical, government-ready, accessible.

---

## Color Palette

### Primary — Medical Blue

Used for: buttons, links, active states, brand elements.

| Token           | CSS Variable            | Usage                            |
| --------------- | ----------------------- | -------------------------------- |
| `primary-50`  | `--color-primary-50`  | Backgrounds, hover states        |
| `primary-100` | `--color-primary-100` | Light backgrounds, badges        |
| `primary-200` | `--color-primary-200` | Focus rings, borders             |
| `primary-300` | `--color-primary-300` | Outline button borders           |
| `primary-500` | `--color-primary-500` | Focus borders                    |
| `primary-600` | `--color-primary-600` | **Primary buttons**, links |
| `primary-700` | `--color-primary-700` | Hover states, headings           |
| `primary-800` | `--color-primary-800` | Active/pressed states            |
| `primary-900` | `--color-primary-900` | Dark text on light bg            |

### Secondary — Emerald Green

Used for: health indicators, success states, secondary actions.

| Token                                 | Usage             |
| ------------------------------------- | ----------------- |
| `secondary-50` – `secondary-100` | Light backgrounds |
| `secondary-600`                     | Secondary buttons |
| `secondary-700`                     | Hover states      |

### Semantic Colors

- **Warning:** `amber-*` — caution, medium risk
- **Danger:** `red-*` — errors, high risk
- **Success:** `emerald-*` — success, low risk
- **Neutral:** `gray-*` — text, borders, backgrounds

---

## Typography

**Font Family:** Inter (Google Fonts)

| Class             | Size (mobile → desktop) | Weight | Usage                       |
| ----------------- | ------------------------ | ------ | --------------------------- |
| `.text-display` | 2.25rem → 3.75rem       | 800    | Hero headings               |
| `.text-h1`      | 1.875rem → 2.25rem      | 700    | Page titles                 |
| `.text-h2`      | 1.5rem → 1.875rem       | 700    | Section headings            |
| `.text-h3`      | 1.25rem                  | 600    | Card titles, subsections    |
| `.text-body`    | 1rem                     | 400    | Body text (gray-600)        |
| `.text-body-sm` | 0.875rem                 | 400    | Secondary text (gray-500)   |
| `.text-caption` | 0.75rem                  | 400    | Captions, timestamps        |
| `.text-label`   | 0.875rem                 | 500    | Form labels (gray-700)      |
| `.text-helper`  | 0.75rem                  | 400    | Input hints (gray-500)      |
| `.text-error`   | 0.75rem                  | 400    | Validation errors (red-600) |

---

## Spacing

| Usage           | Value                                      |
| --------------- | ------------------------------------------ |
| Section padding | `.section-spacing` — 3rem / 4rem / 5rem |
| Card padding    | `p-4` (sm), `p-6` (md), `p-8` (lg)   |
| Form field gap  | `mb-4`                                   |
| Button internal | `px-4 py-2.5` (md)                       |

---

## Container Widths

| Token                   | Width  | Usage               |
| ----------------------- | ------ | ------------------- |
| `--container-narrow`  | 640px  | Forms, consent page |
| `--container-default` | 768px  | Content pages       |
| `--container-wide`    | 1024px | Landing sections    |
| `--container-full`    | 1280px | Max layout width    |

---

## Components

### Button `<x-button>`

```blade
<x-button variant="primary" size="md">Label</x-button>
```

Variants: `primary`, `secondary`, `success`, `danger`, `outline`, `ghost`
Sizes: `sm`, `md`, `lg`

### Input `<x-input>`

```blade
<x-input name="email" label="Email" type="email" required />
```

Props: `name`, `label`, `type`, `placeholder`, `required`, `error`, `hint`, `value`

### Select `<x-select>`

```blade
<x-select name="gender" label="Jenis Kelamin" :options="['L'=>'Laki-laki','P'=>'Perempuan']" />
```

### Textarea `<x-textarea>`

```blade
<x-textarea name="notes" label="Catatan" rows="4" />
```

### Radio `<x-radio>`

```blade
<x-radio name="answer" value="1" label="Tidak Pernah" description="Penjelasan" />
```

### Checkbox `<x-checkbox>`

```blade
<x-checkbox name="agree" label="Saya setuju" required />
```

### Card `<x-card>`

```blade
<x-card title="Title" variant="elevated">Content</x-card>
```

Variants: `default`, `bordered`, `elevated`

### Alert `<x-alert>`

```blade
<x-alert type="success" title="Berhasil" dismissible>Pesan</x-alert>
```

Types: `info`, `success`, `warning`, `danger`

### Badge `<x-badge>`

```blade
<x-badge variant="success">Rendah</x-badge>
```

Variants: `primary`, `secondary`, `success`, `warning`, `danger`, `gray`

### Progress `<x-progress>`

```blade
<x-progress :percent="50" label="Step 3/6" color="primary" />
```

### Modal `<x-modal>`

```blade
<x-modal id="my-modal" title="Konfirmasi" size="md">Content</x-modal>
```

---

## Section Wrappers

| Partial                       | Usage                            |
| ----------------------------- | -------------------------------- |
| `partials.section-hero`     | Full-width hero with CTA         |
| `partials.section-content`  | Content with max-width container |
| `partials.section-cta`      | Call-to-action with primary bg   |
| `partials.section-features` | Grid layout for feature cards    |
| `partials.faq`              | Accordion FAQ                    |
| `partials.navbar`           | Sticky top navbar                |
| `partials.footer`           | Dark footer with links           |

---

## Accessibility

- All interactive elements have visible focus states (`.focus-ring`)
- Color contrast meets WCAG AA (4.5:1 for text)
- Form inputs have proper labels
- Required fields marked with red asterisk
- Alert components have `role="alert"`
- Progress bars have ARIA attributes
- Modal close buttons have `aria-label`
- Navbar mobile toggle has `aria-controls` and `aria-expanded`
