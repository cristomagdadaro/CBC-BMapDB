# PIN System — UI/UX Design Guide for AI Agents

> **Purpose:** This document is a comprehensive reference for AI agents tasked with implementing, redesigning, or creating new UI/UX components in the PIN System (Plant Breeders and Innovators Network). Follow every section precisely — it defines the color system, typography, spacing, component patterns, animation system, layout rules, interaction patterns, and accessibility requirements that make up the PIN design language.

---

## Table of Contents

1. [Tech Stack & Architecture](#1-tech-stack--architecture)
2. [Design Tokens — Colors](#2-design-tokens--colors)
3. [Design Tokens — Typography](#3-design-tokens--typography)
4. [Design Tokens — Spacing & Layout](#4-design-tokens--spacing--layout)
5. [Design Tokens — Borders, Radii & Shadows](#5-design-tokens--borders-radii--shadows)
6. [CSS Architecture](#6-css-architecture)
7. [Component Patterns](#7-component-patterns)
   - [Buttons](#71-buttons)
   - [Cards](#72-cards)
   - [Badges](#73-badges)
   - [Form Inputs](#74-form-inputs)
   - [Navigation / Header](#75-navigation--header)
   - [Dropdowns / Menus](#76-dropdowns--menus)
   - [Accordions / FAQ](#77-accordions--faq)
   - [Modals / Dialogs](#78-modals--dialogs)
   - [Tooltips](#79-tooltips)
   - [Tables](#710-tables)
   - [Icon Containers](#711-icon-containers)
   - [Contact Cards](#712-contact-cards)
   - [CTA (Callout) Cards](#713-cta-callout-cards)
   - [Quick Links Lists](#714-quick-links-lists)
8. [Layout Patterns](#8-layout-patterns)
   - [Page Sections](#81-page-sections)
   - [Grid Systems](#82-grid-systems)
   - [Hero Sections](#83-hero-sections)
   - [Footer](#84-footer)
9. [Animation System](#9-animation-system)
   - [Keyframe Animations](#91-keyframe-animations)
   - [Transition Patterns](#92-transition-patterns)
   - [Scroll-triggered Reveals](#93-scroll-triggered-reveals)
   - [Staggered Animations](#94-staggered-animations)
   - [Marquee / Infinite Scroll](#95-marquee--infinite-scroll)
   - [Hover Micro-interactions](#96-hover-micro-interactions)
10. [Interaction Patterns](#10-interaction-patterns)
11. [Accessibility Requirements](#11-accessibility-requirements)
12. [Vue.js / Inertia.js Implementation Notes](#12-vuejs--inertiajs-implementation-notes)
13. [File Structure & Naming Conventions](#13-file-structure--naming-conventions)
14. [Checklist for New Components](#14-checklist-for-new-components)

---

## 1. Tech Stack & Architecture

| Layer        | Technology                                                |
| ------------ | --------------------------------------------------------- |
| Backend      | Laravel 10+ (PHP)                                         |
| Frontend     | Vue 3 (Options API + `<script setup>` Composition API)    |
| Routing      | Inertia.js — use `<Link :href="route('name')">` for all internal navigation |
| Styling      | Tailwind CSS 3 with `@tailwindcss/forms` and `@tailwindcss/typography` plugins |
| Fonts        | Google Fonts — **Inter** (body) + **Montserrat** (display) |
| Icons        | Inline SVGs or icon components — follow Lucide icon style  |
| Build        | Vite                                                       |

### Key Rules

- **Do NOT create new files** unless absolutely necessary. Update existing files first.
- If a new component must be created but the name already exists, suffix it with **V2** (e.g., `SubmitBtnV2.vue`).
- All internal links must use Inertia's `<Link>` component with `route()` helper, never plain `<a href>`.
- External links must use `<a href target="_blank" rel="noopener noreferrer">`.

---

## 2. Design Tokens — Colors

### Primary Palette (PIN Brand)

| Token              | Hex       | Usage                                        |
| ------------------ | --------- | -------------------------------------------- |
| `pin-green`        | `#036701` | Primary brand — buttons, links, accents       |
| `pin-green-dark`   | `#014001` | Hover state for primary buttons               |
| `pin-green-light`  | `#e6f2e6` | Subtle backgrounds, hover highlights, badges  |
| `pin-lime`         | `#65e701` | Accent highlights, gradients, stat numbers    |
| `pin-gray`         | `#f5f5f5` | Section backgrounds, alternating row tint     |

### Neutral Palette (Gray Scale from Tailwind)

| Token         | Usage                                  |
| ------------- | -------------------------------------- |
| `gray-900`    | Headings, primary text                 |
| `gray-700`    | Body text (secondary)                  |
| `gray-600`    | Descriptive text, supporting info      |
| `gray-500`    | Muted text, copyright                  |
| `gray-400`    | Placeholder text, disabled text        |
| `gray-200`    | Borders, dividers                      |
| `gray-100`    | Input backgrounds, scrollbar tracks    |
| `white`       | Card backgrounds, page background      |

### Legacy Colors (Preserved for Backward Compatibility)

```
cbc-yellow-green: #acc638    cbc-dark-green: #006837
cbc-olive-green: #acc638     cbc-light-green: #d3d75e
cbc-yellow: #F7C806          cbc-brown: #41291d
cbc-footer: #212120
```

> **Rule:** Always prefer `pin-*` colors for new components. Legacy `cbc-*` colors should only be used when maintaining existing internal-facing pages. Never mix `cbc-*` and `pin-*` in the same component.

### Color Usage Patterns

```html
<!-- Background alternation -->
<section class="bg-white">...</section>       <!-- Default -->
<section class="bg-pin-gray">...</section>    <!-- Alternating -->

<!-- Opacity variants -->
<div class="bg-pin-green/10">...</div>         <!-- 10% tint for badge backgrounds -->
<div class="bg-pin-green/20">...</div>         <!-- 20% tint for hover states -->
<div class="bg-white/80 backdrop-blur-xl">...  <!-- Glass morphism -->
<div class="bg-white/10">...</div>             <!-- White over dark backgrounds -->
<div class="bg-white/20">...</div>             <!-- Icon containers on brand bg -->

<!-- Selection color (already configured globally) -->
::selection { background-color: rgba(3, 103, 1, 0.2); }
```

---

## 3. Design Tokens — Typography

### Font Families

| Class          | Font        | Weight Range | Usage                                    |
| -------------- | ----------- | ------------ | ---------------------------------------- |
| `font-display` | Montserrat  | 300–900      | Headings (h1–h6), hero text, brand name  |
| `font-body`    | Inter       | 300–800      | Body text, paragraphs, UI labels         |
| `font-sans`    | Roboto      | (fallback)   | System fallback only                     |

### Heading Hierarchy

| Element | Classes                                                         |
| ------- | --------------------------------------------------------------- |
| h1      | `text-4xl sm:text-5xl lg:text-6xl font-bold font-display tracking-tight text-gray-900` |
| h2      | `text-3xl sm:text-4xl font-bold font-display text-gray-900`     |
| h3      | `font-semibold text-lg text-gray-900`                           |
| h4      | `font-semibold text-gray-900`                                   |
| p (body)| `text-gray-600 leading-relaxed` or `text-gray-700`             |
| p (hero)| `text-lg sm:text-xl text-gray-600 max-w-2xl`                   |
| small   | `text-sm text-gray-500` or `text-xs text-gray-400`             |

### Text Utilities

```html
<!-- Gradient text -->
<span class="text-gradient">Highlighted Text</span>
<!-- Applies: bg-gradient-to-r from-pin-green to-pin-lime bg-clip-text text-transparent -->

<!-- Line clamping -->
<p class="line-clamp-2">...</p>  <!-- 2-line truncation -->
<p class="line-clamp-3">...</p>  <!-- 3-line truncation -->

<!-- Responsive font sizing (already in base CSS) -->
<!-- Mobile (< 640px): base font-size 14px -->
<!-- Desktop (≥ 1536px): base font-size 18px -->
```

---

## 4. Design Tokens — Spacing & Layout

### Section Spacing

| Pattern           | Classes                              |
| ----------------- | ------------------------------------ |
| Section vertical  | `py-20 lg:py-32`                     |
| Section horizontal| `section-padding` (responsive px-4 → px-24) |
| Container         | `container-custom` (max-w-7xl mx-auto) |
| Card internal     | `p-5` or `p-6`                       |
| Stack spacing     | `space-y-3`, `space-y-4`, `space-y-6`|
| Grid gap          | `gap-4`, `gap-6`, `gap-8`, `gap-10`, `gap-12` |

### Standard Section Template

```html
<section class="py-20 lg:py-32 bg-white" aria-labelledby="section-heading">
  <div class="section-padding">
    <div class="container-custom">
      <!-- Section header -->
      <div class="text-center mb-12 lg:mb-16">
        <span class="badge-primary mb-4">
          <!-- Icon w-3 h-3 mr-1 --> Category
        </span>
        <h2 id="section-heading" class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
          Section Title
        </h2>
        <p class="text-gray-600 max-w-2xl mx-auto">
          Supporting description text.
        </p>
      </div>
      <!-- Section content -->
      ...
    </div>
  </div>
</section>
```

---

## 5. Design Tokens — Borders, Radii & Shadows

### Border Radius

| Class        | Value      | Usage                        |
| ------------ | ---------- | ---------------------------- |
| `rounded-lg` | `0.5rem`   | Buttons, inputs, small cards |
| `rounded-xl` | `0.875rem` | Cards, dropdowns, panels     |
| `rounded-2xl`| `1rem`     | Large cards, image containers|
| `rounded-full`| `50%`     | Badges, avatar circles, pills|

### Box Shadows

| Class          | Value                                  | Usage                     |
| -------------- | -------------------------------------- | ------------------------- |
| `shadow-xs`    | `0 1px 2px 0 rgb(0 0 0 / 0.05)`       | Subtle depth              |
| `shadow-sm`    | Tailwind default                       | Default card resting state|
| `shadow-lg`    | Tailwind default                       | Button hover, focused card|
| `shadow-card`  | `0 4px 24px 0 rgba(0, 0, 0, 0.08)`    | Elevated card hover       |
| `shadow-glass` | `0 8px 32px 0 rgba(0, 0, 0, 0.1)`     | Glass morphism panels     |
| `shadow-xl`    | Tailwind default                       | Dropdown menus            |
| `shadow-2xl`   | Tailwind default                       | Modals, tooltips          |

### Glass Morphism

```html
<!-- Light glass (over light backgrounds) -->
<div class="glass">...</div>
<!-- Applies: bg-white/80 backdrop-blur-xl border border-white/20 shadow-glass -->

<!-- Dark glass (over images/gradients) -->
<div class="glass-dark">...</div>
<!-- Applies: bg-black/40 backdrop-blur-xl border border-white/10 -->
```

---

## 6. CSS Architecture

The stylesheets follow a strict layered architecture:

### File: `resources/css/app.css`

```
1. Google Fonts import
2. @tailwind base / components / utilities
3. Legacy utility classes (text-dark-color, text-title, etc.) — preserved
4. @layer components { ... }    — reusable component classes
5. Custom CSS (::selection, .animated-underline, .img-zoom, scrollbar)
6. Animation keyframes & helpers
7. Media queries (reduced-motion, high-contrast, print)
```

### File: `tailwind.config.js`

```
1. fontFamily — display, body, sans
2. colors — pin-*, cbc-*, action colors
3. borderRadius — custom xl, 2xl
4. boxShadow — xs, glass, card
5. keyframes — 9 animations (wiggle, fade-up, fade-in, slide-in-right/left, scale-in, marquee, float, pulse-ring)
6. animation — timing functions, durations, fill modes
```

### Adding New Styles

| What                 | Where                         |
| -------------------- | ----------------------------- |
| New reusable class   | `@layer components { ... }`   |
| New color token      | `tailwind.config.js` → `colors` |
| New animation        | `tailwind.config.js` → `keyframes` + `animation` |
| One-off raw CSS      | Below `@layer` blocks in `app.css` |
| Utility helpers      | `@layer utilities { ... }` in `app.css` |

> **Rule:** Never use inline `@keyframes` in Vue `<style>` blocks. All animations must live in `tailwind.config.js` or `app.css` so they are globally available.

---

## 7. Component Patterns

### 7.1 Buttons

Three variants — always include `focus-ring` for keyboard accessibility.

```html
<!-- Primary (filled green) -->
<button class="btn-primary">Submit</button>
<!-- bg-pin-green text-white px-6 py-3 rounded-lg font-medium
     hover:bg-pin-green-dark hover:shadow-lg hover:-translate-y-0.5
     active:translate-y-0 transition-all duration-300 focus-ring -->

<!-- Secondary (outlined green) -->
<button class="btn-secondary">Cancel</button>
<!-- bg-white text-pin-green border-2 border-pin-green px-6 py-3 rounded-lg
     hover:bg-pin-green-light hover:shadow-lg hover:-translate-y-0.5 -->

<!-- Ghost (text only) -->
<button class="btn-ghost">Dismiss</button>
<!-- text-pin-green px-4 py-2 rounded-lg hover:bg-pin-green-light -->
```

#### Button Sizing

```html
<!-- Small -->
<button class="btn-primary text-xs py-2 px-4">Small</button>
<!-- Default is text-base py-3 px-6 -->
<!-- Large -->
<button class="btn-primary text-lg py-4 px-8">Large</button>
```

#### Button with Icon

```html
<button class="btn-primary inline-flex items-center gap-2">
  <DownloadIcon class="w-4 h-4" />
  Download Guide
</button>
```

#### Inverted Button (white on brand background)

```html
<button class="inline-flex items-center gap-2 px-4 py-2 bg-white text-pin-green rounded-lg font-medium hover:bg-white/90 transition-colors focus-ring">
  <DownloadIcon class="w-4 h-4" />
  Download
</button>
```

#### Group Hover Button Pattern (arrow slides on hover)

```html
<Link class="btn-primary group">
  Explore
  <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
</Link>
```

---

### 7.2 Cards

#### Basic Card

```html
<div class="bg-white rounded-xl shadow-sm p-6">
  <h3 class="font-semibold text-gray-900 mb-2">Title</h3>
  <p class="text-gray-600 text-sm">Description</p>
</div>
```

#### Hoverable Card

```html
<div class="bg-white rounded-xl shadow-sm p-6 card-hover">
  <!-- card-hover: transition-all duration-500 hover:shadow-card hover:-translate-y-1 -->
</div>
```

#### Liftable Card (interactive)

```html
<div class="bg-white rounded-xl shadow-sm p-6 card-lift">
  <!-- card-lift: hover:-translate-y-1 shadow-card -->
</div>
```

#### Image Overlay Card (Database Cards Pattern)

```html
<div class="group rounded-2xl overflow-hidden shadow-card bg-white card-hover cursor-pointer">
  <!-- Image with zoom -->
  <div class="relative h-48 overflow-hidden">
    <img src="..." class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
    <!-- Gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent" />
    <!-- Tags over image -->
    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
      <span class="px-2 py-1 bg-white/20 backdrop-blur-sm text-white text-xs rounded-full">Tag</span>
    </div>
    <!-- Title over image -->
    <div class="absolute bottom-4 left-4 right-4">
      <h3 class="text-white font-bold text-lg group-hover:text-pin-lime transition-colors">Title</h3>
    </div>
  </div>
  <!-- Card body -->
  <div class="p-5">
    <p class="text-gray-600 text-sm line-clamp-2 mb-4">Description</p>
    <div class="flex items-center justify-between">
      <div class="text-sm">
        <span class="text-pin-lime font-bold">1,234</span>
        <span class="text-gray-500 ml-1">Records</span>
      </div>
      <span class="text-pin-green group-hover:translate-x-2 transition-transform">→</span>
    </div>
  </div>
</div>
```

#### Feature Card (About Section Pattern)

```html
<div class="bg-pin-gray rounded-xl p-6 group hover:bg-pin-green-light transition-all duration-300">
  <div class="w-12 h-12 bg-pin-green-light rounded-xl flex items-center justify-center mb-4 group-hover:bg-pin-green transition-colors">
    <IconComponent class="w-6 h-6 text-pin-green group-hover:text-white transition-colors" />
  </div>
  <h3 class="font-semibold text-gray-900 mb-2">Feature Title</h3>
  <p class="text-gray-600 text-sm">Feature description.</p>
</div>
```

#### Brand Accent Card (CTA style — white text on green)

```html
<div class="bg-pin-green text-white rounded-xl p-6">
  <div class="flex items-start gap-4">
    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
      <BookIcon class="w-6 h-6" />
    </div>
    <div>
      <h3 class="font-semibold text-lg mb-2">Title</h3>
      <p class="text-white/80 text-sm mb-4">Description text.</p>
      <button class="inline-flex items-center gap-2 px-4 py-2 bg-white text-pin-green rounded-lg font-medium hover:bg-white/90 transition-colors focus-ring">
        Action
      </button>
    </div>
  </div>
</div>
```

---

### 7.3 Badges

```html
<!-- Generic badge -->
<span class="badge">Default</span>
<!-- inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium -->

<!-- Primary (green tint) -->
<span class="badge-primary">
  <IconComponent class="w-3 h-3 mr-1" />
  Category
</span>
<!-- bg-pin-green-light text-pin-green -->

<!-- Accent (lime tint) -->
<span class="badge-accent">New</span>
<!-- bg-pin-lime/20 text-pin-green-dark -->

<!-- Inline badge on images (backdrop blur) -->
<span class="px-2 py-1 bg-white/20 backdrop-blur-sm text-white text-xs rounded-full">
  Tag
</span>

<!-- Badge as section header label -->
<span class="badge-primary mb-4">
  <LeafIcon class="w-3 h-3 mr-1" />
  Section Label
</span>
```

---

### 7.4 Form Inputs

```html
<input type="text" class="input-custom" placeholder="Search..." />
<!-- w-full px-4 py-3 rounded-lg border border-gray-200
     focus:border-pin-green focus:ring-2 focus:ring-pin-green/20
     transition-all duration-300 outline-none placeholder:text-gray-400 -->
```

#### Search Input with Icon

```html
<div class="relative">
  <SearchIcon class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
  <input type="text" class="input-custom pl-10" placeholder="Search databases..." />
</div>
```

#### Select / Filter Controls

```html
<select class="input-custom text-sm py-2">
  <option>All Categories</option>
  <option>Crops</option>
</select>
```

---

### 7.5 Navigation / Header

The header uses a **scroll-aware** design pattern:

```
Top of page (transparent):
  - Background: transparent
  - Text: white (over hero image)
  - Logo: white background container
  - CTA button: bg-white text-pin-green
  - Padding: py-5

Scrolled (glass):
  - Background: bg-white/95 backdrop-blur-xl shadow-lg
  - Text: gray-700 / pin-green
  - CTA button: bg-pin-green text-white
  - Padding: py-3 (compressed)
```

#### Implementation Pattern (Vue)

```javascript
data() {
  return { isScrolled: false };
},
mounted() {
  window.addEventListener('scroll', this.handleScroll);
  this.handleScroll();
},
beforeUnmount() {
  window.removeEventListener('scroll', this.handleScroll);
},
methods: {
  handleScroll() {
    this.isScrolled = window.scrollY > 20;
  }
}
```

```html
<header :class="[
  'fixed top-0 w-full z-50 transition-all duration-500',
  isScrolled
    ? 'bg-white/95 backdrop-blur-xl shadow-lg py-3'
    : 'bg-transparent py-5'
]">
  ...
</header>
```

#### Nav Link Pattern

```html
<!-- Desktop: pill-style rounded link -->
<Link :class="[
  'px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300',
  isActive
    ? 'bg-pin-green-light text-pin-green'
    : isScrolled
      ? 'text-gray-700 hover:text-pin-green hover:bg-pin-green-light/50'
      : 'text-white/90 hover:text-white hover:bg-white/10'
]">
  Link Text
</Link>

<!-- Mobile: full-width block link -->
<Link class="block w-full px-4 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-pin-green hover:bg-pin-green-light transition-colors">
  Link Text
</Link>
```

#### Dropdown Pattern

```html
<!-- Desktop dropdown container -->
<div class="absolute top-full -left-4 w-72 bg-white rounded-xl shadow-xl border border-gray-100 py-2 mt-2">
  <Link v-for="child in item.children" :key="child.name"
    class="flex items-start gap-3 px-4 py-3 rounded-lg mx-2 hover:bg-pin-green-light transition-colors group">
    <div class="w-8 h-8 bg-pin-green-light rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-pin-green transition-colors">
      <DatabaseIcon class="w-4 h-4 text-pin-green group-hover:text-white transition-colors" />
    </div>
    <div>
      <span class="font-medium text-gray-900 text-sm">{{ child.name }}</span>
      <p class="text-xs text-gray-500 mt-0.5">{{ child.description }}</p>
    </div>
  </Link>
</div>
```

---

### 7.6 Dropdowns / Menus

General dropdown rules:
- Container: `bg-white rounded-xl shadow-xl border border-gray-100 py-2`
- Item: `px-4 py-3 rounded-lg mx-2 hover:bg-pin-green-light transition-colors`
- Grouped items use `space-y-1`
- Animate in with `animate-fade-up` or `animate-scale-in`

---

### 7.7 Accordions / FAQ

```html
<div class="space-y-3">
  <div v-for="(item, index) in faqItems" :key="index"
    class="bg-white rounded-xl shadow-sm px-6">
    <button @click="toggleItem(index)"
      class="w-full flex items-center justify-between text-left font-medium text-gray-900 hover:text-pin-green py-4 transition-colors">
      <span>{{ item.question }}</span>
      <ChevronDownIcon :class="[
        'w-4 h-4 transition-transform duration-300',
        openIndex === index ? 'rotate-180' : ''
      ]" />
    </button>
    <div v-show="openIndex === index" class="text-gray-600 pb-4">
      {{ item.answer }}
    </div>
  </div>
</div>
```

---

### 7.8 Modals / Dialogs

```html
<!-- Overlay -->
<div class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center">
  <!-- Panel -->
  <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6 animate-scale-in">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-900">Title</h3>
      <button class="btn-ghost p-2">
        <XIcon class="w-5 h-5" />
      </button>
    </div>
    <div class="text-gray-600">Content</div>
    <div class="flex justify-end gap-3 mt-6">
      <button class="btn-ghost">Cancel</button>
      <button class="btn-primary">Confirm</button>
    </div>
  </div>
</div>
```

---

### 7.9 Tooltips

```html
<div class="group relative">
  <button>Trigger</button>
  <div class="tooltip -top-10 left-1/2 -translate-x-1/2 whitespace-nowrap">
    Tooltip text
  </div>
</div>
<!-- tooltip class: absolute z-50 px-3 py-2 text-sm text-white bg-gray-900
     rounded-lg shadow-lg opacity-0 invisible
     group-hover:opacity-100 group-hover:visible transition-all duration-200 -->
```

---

### 7.10 Tables

```html
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full">
      <thead>
        <tr class="bg-pin-gray border-b border-gray-200">
          <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Column</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        <tr class="hover:bg-pin-green-light/30 transition-colors">
          <td class="px-6 py-4 text-sm text-gray-700">Value</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
```

---

### 7.11 Icon Containers

Standard icon container pattern used for feature cards, contact info, and list items:

```html
<!-- Default (light) — on white or pin-gray backgrounds -->
<div class="w-10 h-10 bg-pin-green-light rounded-lg flex items-center justify-center text-pin-green">
  <MailIcon class="w-5 h-5" />
</div>

<!-- With group-hover color inversion -->
<div class="w-12 h-12 bg-pin-green-light rounded-xl flex items-center justify-center group-hover:bg-pin-green transition-colors">
  <IconComponent class="w-6 h-6 text-pin-green group-hover:text-white transition-colors" />
</div>

<!-- On dark/brand backgrounds -->
<div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
  <BookIcon class="w-6 h-6 text-white" />
</div>

<!-- Circular (for onboarding/alerts) -->
<div class="w-10 h-10 bg-pin-green-light rounded-full flex items-center justify-center">
  <HelpIcon class="w-5 h-5 text-pin-green" />
</div>
```

Size guide: `w-8 h-8` (small), `w-10 h-10` (default), `w-12 h-12` (large), `w-16 h-16` (hero).

---

### 7.12 Contact Cards

```html
<div class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow p-5">
  <div class="w-10 h-10 bg-pin-green-light rounded-lg flex items-center justify-center text-pin-green mb-3">
    <MailIcon class="w-5 h-5" />
  </div>
  <h3 class="font-semibold text-gray-900 mb-1">Email Us</h3>
  <a href="mailto:email@example.com" class="text-sm text-pin-green hover:underline">
    email@example.com
  </a>
</div>
```

---

### 7.13 CTA (Callout) Cards

See [Brand Accent Card](#72-cards) — uses `bg-pin-green text-white` with white/20 icon container and inverted white button.

---

### 7.14 Quick Links Lists

```html
<div class="bg-white rounded-xl p-6 shadow-sm">
  <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
  <div class="space-y-2">
    <a v-for="link in links" :key="link.label" :href="link.href"
      class="flex items-center justify-between p-3 rounded-lg hover:bg-pin-green-light transition-colors group">
      <span class="text-gray-700 group-hover:text-pin-green transition-colors">
        {{ link.label }}
      </span>
      <ExternalLinkIcon class="w-4 h-4 text-gray-400 group-hover:text-pin-green transition-colors" />
    </a>
  </div>
</div>
```

---

## 8. Layout Patterns

### 8.1 Page Sections

Every public-facing section follows this structure:

```html
<section class="py-20 lg:py-32 {bg-white|bg-pin-gray}" aria-labelledby="{id}">
  <div class="section-padding">
    <div class="container-custom">
      ...
    </div>
  </div>
</section>
```

- Alternate `bg-white` and `bg-pin-gray` between consecutive sections.
- Always use `aria-labelledby` pointing to the section's `<h2>` id.

### 8.2 Grid Systems

| Layout         | Classes                                    |
| -------------- | ------------------------------------------ |
| 2-column card  | `grid sm:grid-cols-2 gap-4`                |
| 3-column card  | `grid sm:grid-cols-2 lg:grid-cols-3 gap-6` |
| 4-column card  | `grid sm:grid-cols-2 lg:grid-cols-4 gap-6` |
| 2-column split | `grid lg:grid-cols-2 gap-12 lg:gap-20`     |
| Stats row      | `grid grid-cols-2 sm:grid-cols-4 gap-4`    |
| Footer         | `grid sm:grid-cols-2 lg:grid-cols-4 gap-10`|

### 8.3 Hero Sections

```html
<section class="relative min-h-screen flex items-center overflow-hidden">
  <!-- Background image or gradient -->
  <div class="absolute inset-0">
    <img src="..." class="w-full h-full object-cover" alt="" />
    <div class="absolute inset-0 bg-gradient-to-br from-pin-green-dark/95 via-pin-green/90 to-pin-green/80" />
  </div>
  
  <!-- Floating decorative particles (optional) -->
  <div class="absolute inset-0 overflow-hidden" aria-hidden="true">
    <div class="absolute w-2 h-2 bg-pin-lime/30 rounded-full animate-float"
         style="top: 20%; left: 10%; animation-delay: 0s;" />
    <!-- More particles with varied positions and delays -->
  </div>
  
  <!-- Content -->
  <div class="relative z-10 section-padding w-full">
    <div class="container-custom">
      <div class="max-w-3xl">
        <!-- Staggered reveal -->
        <span class="badge bg-white/10 backdrop-blur-sm text-white border border-white/20 mb-6 animate-fade-up">
          <LeafIcon class="w-3 h-3 mr-1" /> Badge Text
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6 animate-fade-up stagger-1">
          Hero Heading with <span class="text-pin-lime">Accent</span>
        </h1>
        <p class="text-lg sm:text-xl text-white/80 mb-8 max-w-2xl animate-fade-up stagger-2">
          Subheading text.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 animate-fade-up stagger-3">
          <Link class="btn-primary bg-white text-pin-green hover:bg-gray-100 group">
            Primary CTA <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </Link>
          <Link class="btn-secondary border-white/30 text-white hover:bg-white/10">
            Secondary CTA
          </Link>
        </div>
      </div>
      
      <!-- Stats grid -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-16 animate-fade-up stagger-4">
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 text-center border border-white/10">
          <p class="text-2xl sm:text-3xl font-bold text-white">1,234</p>
          <p class="text-white/60 text-sm mt-1">Stat Label</p>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scroll indicator -->
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
    <ChevronDownIcon class="w-6 h-6 text-white/60" />
  </div>
</section>
```

### 8.4 Footer

```html
<footer class="bg-gray-900 text-white" role="contentinfo">
  <!-- Main footer -->
  <div class="section-padding py-16">
    <div class="container-custom">
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
        <!-- Brand column (wider) -->
        <div class="sm:col-span-2 lg:col-span-1">
          <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-pin-green rounded-lg flex items-center justify-center">
              <SproutIcon class="w-6 h-6 text-white" />
            </div>
            <div>
              <p class="text-xs text-gray-400">Organization</p>
              <p class="text-sm font-bold font-display">Brand Name</p>
            </div>
          </div>
          <p class="text-gray-400 text-sm mb-6">Tagline text.</p>
          <!-- Social icons -->
          <div class="flex items-center gap-3">
            <a href="..." class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-pin-green transition-colors focus-ring" aria-label="Facebook">
              <FacebookIcon class="w-5 h-5" />
            </a>
          </div>
        </div>
        
        <!-- Link columns -->
        <div>
          <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
          <ul class="space-y-3">
            <li><Link class="text-gray-400 hover:text-white transition-colors focus-ring rounded">Link</Link></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Government links bar -->
  <div class="border-t border-white/10">
    <div class="section-padding py-6">
      <div class="container-custom">
        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs text-gray-500">
          <span class="font-medium text-gray-400">Label:</span>
          <a href="..." class="hover:text-white transition-colors">Link</a>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Copyright -->
  <div class="border-t border-white/10">
    <div class="section-padding py-6">
      <div class="container-custom text-center text-sm text-gray-500">
        <p>© {{ year }} Organization. All rights reserved.</p>
      </div>
    </div>
  </div>
</footer>
```

---

## 9. Animation System

### 9.1 Keyframe Animations

All animations are defined in `tailwind.config.js` and are available as Tailwind utilities:

| Class                 | Duration | Easing                         | Effect                       |
| --------------------- | -------- | ------------------------------ | ---------------------------- |
| `animate-fade-up`     | 0.6s     | cubic-bezier(0.16, 1, 0.3, 1) | Fade in + slide up 20px      |
| `animate-fade-in`     | 0.4s     | ease-out                       | Simple opacity fade          |
| `animate-slide-in-right` | 0.8s  | cubic-bezier(0.16, 1, 0.3, 1) | Slide from right             |
| `animate-slide-in-left`  | 0.8s  | cubic-bezier(0.16, 1, 0.3, 1) | Slide from left              |
| `animate-scale-in`    | 0.6s     | cubic-bezier(0.16, 1, 0.3, 1) | Scale from 0.9 + fade        |
| `animate-marquee`     | 30s      | linear, infinite               | Horizontal scroll -50%       |
| `animate-float`       | 6s       | ease-in-out, infinite          | Vertical bob ±10px           |
| `animate-pulse-ring`  | 1.5s     | cubic-bezier, infinite         | Expanding ring effect        |
| `animate-bounce`      | (Tailwind default)              | Bounce vertically             |

> **Signature easing:** `cubic-bezier(0.16, 1, 0.3, 1)` — Use this for all entrance animations. It provides a swift start with a gentle settle.

### 9.2 Transition Patterns

Standard transition classes used throughout:

```html
<!-- Color/background transitions -->
class="transition-colors duration-300"

<!-- All properties (buttons, cards) -->
class="transition-all duration-300"

<!-- Longer transitions (card hover) -->
class="transition-all duration-500"

<!-- Transform only -->
class="transition-transform duration-300"

<!-- Image zoom -->
class="transition-transform duration-700"

<!-- Shadow transition -->
class="transition-shadow"
```

### 9.3 Scroll-triggered Reveals

Use `IntersectionObserver` in Vue to trigger `animate-fade-up` when elements enter viewport:

```javascript
// In a Vue component or directive
mounted() {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-up');
          observer.unobserve(entry.target); // One-way reveal — no undo
        }
      });
    },
    { threshold: 0.1 }
  );

  this.$el.querySelectorAll('.reveal-on-scroll').forEach(el => {
    el.style.opacity = '0'; // Hidden initially
    observer.observe(el);
  });
}
```

```html
<div class="reveal-on-scroll">Content fades up when scrolled into view</div>
```

> **Rule:** Always use one-way reveals. Once an element animates in, it stays visible. No exit animations on scroll-out.

### 9.4 Staggered Animations

Use stagger classes to create cascading entrance effects:

```html
<div class="animate-fade-up">First item</div>
<div class="animate-fade-up stagger-1">Second (0.1s delay)</div>
<div class="animate-fade-up stagger-2">Third (0.2s delay)</div>
<div class="animate-fade-up stagger-3">Fourth (0.3s delay)</div>
<div class="animate-fade-up stagger-4">Fifth (0.4s delay)</div>
<div class="animate-fade-up stagger-5">Sixth (0.5s delay)</div>
```

For finer control, use `animation-delay-*` utilities:

```html
<div class="animate-float animation-delay-100">Particle 1</div>
<div class="animate-float animation-delay-300">Particle 2</div>
<div class="animate-float animation-delay-500">Particle 3</div>
```

### 9.5 Marquee / Infinite Scroll

```html
<div class="relative overflow-hidden">
  <!-- Gradient fades on edges -->
  <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10" aria-hidden="true" />
  <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10" aria-hidden="true" />
  
  <!-- Duplicate items for seamless loop -->
  <div class="flex animate-marquee hover:[animation-play-state:paused]">
    <div v-for="item in [...items, ...items]" :key="item.id"
      class="flex-shrink-0 mx-8 px-8 py-4 bg-pin-gray rounded-xl hover:bg-pin-green-light transition-colors cursor-pointer group">
      <span class="font-semibold text-gray-700 group-hover:text-pin-green whitespace-nowrap">
        {{ item.name }}
      </span>
    </div>
  </div>
</div>
```

> **Key:** Duplicate the array (`[...items, ...items]`) and animate to `translateX(-50%)` for seamless infinite scroll.

### 9.6 Hover Micro-interactions

| Effect               | Classes                                                            |
| -------------------- | ------------------------------------------------------------------ |
| Button lift          | `hover:-translate-y-0.5 active:translate-y-0`                     |
| Card lift            | `hover:-translate-y-1 hover:shadow-card`                          |
| Image zoom           | `hover:scale-110 transition-transform duration-700`                |
| Arrow slide          | `group-hover:translate-x-1` or `group-hover:translate-x-2`        |
| Color inversion      | `group-hover:bg-pin-green group-hover:text-white`                  |
| Underline grow       | `animated-underline` (CSS class)                                   |
| Link color shift     | `hover:text-pin-green transition-colors`                           |
| Background highlight | `hover:bg-pin-green-light transition-colors`                       |
| Scale on hover       | `hover:scale-105` or `hover:scale-110`                             |
| Logo pulse           | `hover:scale-110 transition-transform`                             |
| Shadow increase      | `hover:shadow-lg transition-shadow`                                |
| Chevron rotate       | `transition-transform duration-300` + `:class="open ? 'rotate-180' : ''"` |

---

## 10. Interaction Patterns

### Scroll-Aware UI

- **Header:** Transparent at top → glass morphism when scrolled (see [7.5](#75-navigation--header))
- **Scroll indicator:** `animate-bounce` at hero bottom, disappears on scroll
- **Parallax hints:** Background images with `object-cover` that shift subtly

### Click/Toggle States

```html
<!-- Tab / pill selector -->
<div class="flex bg-pin-gray rounded-lg p-1">
  <button :class="[
    'px-4 py-2 rounded-md text-sm font-medium transition-all',
    activeTab === 'map'
      ? 'bg-white text-pin-green shadow-sm'
      : 'text-gray-600 hover:text-gray-900'
  ]" @click="activeTab = 'map'">
    Map View
  </button>
  <button :class="[
    'px-4 py-2 rounded-md text-sm font-medium transition-all',
    activeTab === 'list'
      ? 'bg-white text-pin-green shadow-sm'
      : 'text-gray-600 hover:text-gray-900'
  ]" @click="activeTab = 'list'">
    List View
  </button>
</div>
```

### Selected / Active States

```html
<!-- List item with selected state -->
<div :class="[
  'p-4 rounded-xl cursor-pointer transition-all',
  isSelected
    ? 'bg-pin-green-light border-2 border-pin-green'
    : 'bg-white border border-gray-200 hover:border-pin-green/30'
]">
  Content
</div>

<!-- Sidebar active link -->
<Link :class="[
  'block px-4 py-2.5 rounded-lg transition-colors relative',
  isActive
    ? 'bg-pin-green-light text-pin-green font-medium'
    : 'text-gray-600 hover:bg-pin-green-light/50 hover:text-pin-green'
]">
  <!-- Active indicator bar -->
  <span v-if="isActive" class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-pin-green rounded-r-full" />
  Link Text
</Link>
```

### Mobile Menu

```html
<!-- Hamburger toggle -->
<button @click="isMenuOpen = !isMenuOpen" class="lg:hidden p-2 rounded-lg hover:bg-pin-green-light transition-colors">
  <MenuIcon v-if="!isMenuOpen" class="w-6 h-6" />
  <XIcon v-else class="w-6 h-6" />
</button>

<!-- Mobile overlay menu -->
<transition
  enter-active-class="transition-all duration-300 ease-out"
  enter-from-class="opacity-0 translate-y-[-10px]"
  enter-to-class="opacity-100 translate-y-0"
  leave-active-class="transition-all duration-200 ease-in"
  leave-from-class="opacity-100 translate-y-0"
  leave-to-class="opacity-0 translate-y-[-10px]"
>
  <div v-if="isMenuOpen" class="lg:hidden absolute top-full left-0 right-0 bg-white shadow-xl border-t border-gray-100 p-4">
    <div class="space-y-1">
      <!-- Mobile nav links -->
    </div>
    <div class="mt-4 pt-4 border-t border-gray-100">
      <Link class="btn-primary w-full text-center">Login / Register</Link>
    </div>
  </div>
</transition>
```

### Onboarding Tooltip

```html
<div class="fixed bottom-4 right-4 z-50 max-w-sm animate-fade-up">
  <div class="bg-white rounded-xl shadow-2xl p-5 border border-gray-100">
    <div class="flex items-start gap-3">
      <div class="w-10 h-10 bg-pin-green-light rounded-full flex items-center justify-center flex-shrink-0">
        <HelpIcon class="w-5 h-5 text-pin-green" />
      </div>
      <div>
        <h4 class="font-semibold text-gray-900 mb-1">Title</h4>
        <p class="text-sm text-gray-600 mb-3">Message</p>
        <div class="flex items-center gap-2">
          <button class="btn-primary text-xs py-2 px-4">Action</button>
          <button class="btn-ghost text-xs py-2 px-4">Dismiss</button>
        </div>
      </div>
    </div>
  </div>
</div>
```

---

## 11. Accessibility Requirements

### ARIA

- Every `<section>` must have `aria-labelledby` pointing to its heading `id`.
- Decorative elements must include `aria-hidden="true"`.
- Interactive icons must have `aria-label`.
- Footer must have `role="contentinfo"`.
- Main content must be wrapped in `<main id="main-content">`.

### Skip Navigation

```html
<a href="#main-content"
   class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-pin-green focus:text-white focus:px-4 focus:py-2 focus:rounded-lg">
  Skip to main content
</a>
```

### Focus Styles

All interactive elements must include `.focus-ring`:

```css
.focus-ring {
  @apply focus:outline-none focus-visible:ring-2 focus-visible:ring-pin-green focus-visible:ring-offset-2;
}
```

### Reduced Motion

Already configured globally — all animations and transitions collapse to near-zero duration:

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

### High Contrast

```css
@media (prefers-contrast: high) {
  .glass, .glass-dark {
    background: white !important;
    border: 2px solid black !important;
  }
}
```

### Print

```css
@media print {
  .no-print { display: none !important; }
  * { background: white !important; color: black !important; }
}
```

Add `class="no-print"` to navigation, floating elements, and decorative items.

### Color Contrast

- Text on white: use `gray-900` (headings), `gray-700`/`gray-600` (body) — all pass WCAG AA.
- Text on `pin-green` (#036701): use `white` — passes AAA.
- Text on `pin-gray` (#f5f5f5): use `gray-900`/`gray-700` — passes AA.
- Never use `pin-lime` (#65e701) as text on white — fails contrast. Use only for accents/decorative.

---

## 12. Vue.js / Inertia.js Implementation Notes

### Component Structure

```vue
<script>
import { Link } from '@inertiajs/vue3';

export default {
  components: { Link },
  data() {
    return {
      // reactive state
    };
  },
  mounted() {
    // IntersectionObserver, scroll listeners
  },
  beforeUnmount() {
    // Cleanup listeners
  },
  methods: {
    // Event handlers
  }
};
</script>

<template>
  <!-- Template HTML -->
</template>

<style scoped>
/* Component-specific styles only — prefer Tailwind classes */
</style>
```

### Internal Navigation

```html
<!-- ALWAYS use Inertia Link for internal routes -->
<Link :href="route('projects.twg.index')" class="btn-primary">
  Go to TWG Database
</Link>

<!-- NEVER use plain <a> for internal routes -->
<!-- ❌ <a href="/projects/twg">TWG</a> -->
```

### External Links

```html
<a href="https://external.example.com"
   target="_blank"
   rel="noopener noreferrer"
   class="text-pin-green hover:underline">
  External Link
</a>
```

### Conditional Classes (Vue)

```html
<!-- Use array syntax for complex conditional classes -->
<div :class="[
  'base-classes',
  condition ? 'active-classes' : 'inactive-classes'
]">

<!-- Use object syntax for toggling individual classes -->
<div :class="{
  'bg-pin-green-light': isActive,
  'border-pin-green': isSelected,
  'opacity-50 pointer-events-none': isDisabled
}">
```

### Reactive Scroll Listeners

```javascript
data() {
  return { isScrolled: false };
},
mounted() {
  this._onScroll = () => { this.isScrolled = window.scrollY > 20; };
  window.addEventListener('scroll', this._onScroll, { passive: true });
  this._onScroll(); // Initialize state
},
beforeUnmount() {
  window.removeEventListener('scroll', this._onScroll);
}
```

### IntersectionObserver for Animations

```javascript
mounted() {
  this._observer = new IntersectionObserver(
    (entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-fade-up');
          this._observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.1 }
  );
  this.$el.querySelectorAll('[data-reveal]').forEach(el => {
    el.style.opacity = '0';
    this._observer.observe(el);
  });
},
beforeUnmount() {
  this._observer?.disconnect();
}
```

### Scoped Slots (Header Pattern)

Pass scroll state from parent layout to child content:

```html
<!-- Layout component -->
<header>
  <slot name="links" :isScrolled="isScrolled" />
</header>

<!-- Page component -->
<template #links="{ isScrolled }">
  <NavLink :is-scrolled="isScrolled" label="Home" />
</template>
```

---

## 13. File Structure & Naming Conventions

### Key Directories

```
resources/
  css/
    app.css                 ← Global styles, Tailwind directives
  js/
    Components/             ← Reusable UI components
      Header/               ← Header-specific components
    Layouts/                ← Layout wrappers (BodyLayout, HeaderLayout)
      components/           ← Layout sub-components (PublicPageSection)
    Pages/                  ← Route-specific pages
      Projects.vue          ← Main homepage
      Header.vue            ← Navigation content
      Projects/             ← Sub-project pages
        BreedersMap/
        TWG/
      Support/              ← Info/help pages
        components/         ← Shared support page components
tailwind.config.js          ← Design tokens, animations
```

### Naming Conventions

| Item               | Convention                  | Example                    |
| ------------------ | --------------------------- | -------------------------- |
| Vue component file | PascalCase                  | `PublicPageSection.vue`    |
| CSS class          | kebab-case                  | `btn-primary`, `card-hover`|
| Tailwind color     | kebab-case with prefix      | `pin-green-light`          |
| Animation          | kebab-case                  | `animate-fade-up`          |
| Route name         | dot-notation                | `projects.twg.index`       |
| ARIA id            | kebab-case with context     | `help-heading`             |

---

## 14. Checklist for New Components

When implementing or redesigning any component, verify every item:

### Design

- [ ] Uses `pin-*` color palette (never raw hex values for brand colors).
- [ ] Headings use `font-display` (Montserrat), body uses `font-body` (Inter).
- [ ] Card backgrounds are `white` with `rounded-xl shadow-sm`.
- [ ] Section backgrounds alternate `bg-white` / `bg-pin-gray`.
- [ ] Icons are inside proper icon containers (see [7.11](#711-icon-containers)).
- [ ] Badges use `.badge-primary` or `.badge-accent` pattern.

### Interaction

- [ ] Buttons have hover lift (`hover:-translate-y-0.5`) and active press (`active:translate-y-0`).
- [ ] Cards have `card-hover` or `card-lift` class.
- [ ] Links have `hover:text-pin-green transition-colors`.
- [ ] Group hover effects use `group` on parent, `group-hover:*` on children.
- [ ] All interactive elements include `focus-ring` class.

### Animation

- [ ] Entrance animations use `animate-fade-up` (not custom keyframes).
- [ ] Staggered content uses `stagger-1` through `stagger-5` classes.
- [ ] Scroll-triggered reveals use `IntersectionObserver` with one-way behavior.
- [ ] All animations use the signature easing: `cubic-bezier(0.16, 1, 0.3, 1)`.

### Layout

- [ ] Wrapped in `section-padding` → `container-custom`.
- [ ] Uses standard grid patterns (2/3/4 columns, responsive).
- [ ] Proper vertical spacing: `py-20 lg:py-32` for sections.
- [ ] Mobile-responsive (test at 320px, 640px, 768px, 1024px, 1280px).

### Accessibility

- [ ] `<section>` has `aria-labelledby` pointing to heading id.
- [ ] Decorative elements have `aria-hidden="true"`.
- [ ] Social/icon links have `aria-label`.
- [ ] Color contrast meets WCAG AA (4.5:1 for text, 3:1 for large text).
- [ ] Focus states are visible (`.focus-ring`).
- [ ] Supports `prefers-reduced-motion` (handled globally).

### Code

- [ ] Internal links use `<Link :href="route('...')">` (Inertia).
- [ ] External links have `target="_blank" rel="noopener noreferrer"`.
- [ ] Scroll/resize listeners are cleaned up in `beforeUnmount()`.
- [ ] No inline `@keyframes` — use Tailwind animation utilities.
- [ ] Component-specific styles are `<style scoped>` only if absolutely necessary.

---

## Quick Reference — Common Class Combos

```
Section:       py-20 lg:py-32 bg-white / bg-pin-gray
Wrapper:       section-padding > container-custom
Card:          bg-white rounded-xl shadow-sm p-6 card-hover
Button:        btn-primary / btn-secondary / btn-ghost
Badge:         badge-primary / badge-accent
Input:         input-custom
Heading:       text-3xl sm:text-4xl font-bold text-gray-900
Body text:     text-gray-600 leading-relaxed
Icon box:      w-10 h-10 bg-pin-green-light rounded-lg flex items-center justify-center text-pin-green
Glass:         bg-white/80 backdrop-blur-xl border border-white/20 shadow-glass
Gradient text: text-gradient
Hover lift:    hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300
Group hover:   group → group-hover:text-pin-green group-hover:bg-pin-green
Stagger:       animate-fade-up stagger-{1-5}
Focus:         focus-ring
```

---

*Last updated: Auto-generated from `newUIResources/` analysis. Keep this file in sync when design tokens change.*
