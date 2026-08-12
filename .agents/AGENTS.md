# CBC-BMapDB Design Guidelines

When creating or modifying Vue components for the CBC-BMapDB workspace, you MUST strictly adhere to the following design aesthetics:

## 1. Color Palette & Backgrounds
- **Primary Accents**: Use `text-pin-lime`, `bg-pin-green`, `hover:bg-pin-green-dark`, `hover:bg-pin-green-light`, and `bg-pin-gray`.
- **Gradients**: Use `bg-gradient-to-br from-pin-green to-cbc-dark-green` for vibrant, modern card backgrounds.
- **Glassmorphism**: Use `bg-white/10 backdrop-blur-sm text-white hover:bg-white/20` for floating buttons and overlay cards.

## 2. Typography
- **Headings & Highlights**: ALWAYS use `font-display` (Montserrat) for headings (`h1`, `h2`, `h3`) and prominent statistics. Use `text-gray-900` on light backgrounds and `text-white` on dark backgrounds.
- **Body**: Use default `font-sans` (Roboto) with `text-gray-600` on light backgrounds and `text-white/80` on dark backgrounds.

## 3. Shapes & Shadows
- **Corners**: Favor large rounded corners: `rounded-xl`, `rounded-2xl`, and `rounded-full` for badges/buttons.
- **Shadows**: Use custom shadows `shadow-card`, `shadow-glass`, and elevate on hover with `hover:shadow-xl` or `hover:shadow-2xl`.

## 4. Micro-animations & Hover States
- **Transitions**: Apply `transition-all duration-700` or `duration-500` for smooth state changes.
- **Hover Lift**: Add `hover:-translate-y-1` or `hover:-translate-y-2` to buttons and cards.
- **Click Squash**: Add `active:scale-95 duration-200` to clickable elements to provide tactile feedback.
- **Group Hovers**: Use the `group` class on parent elements and `group-hover:scale-110`, `group-hover:translate-x-1`, `group-hover:text-pin-lime` on children for complex interactive effects.

## 5. Layout & Spacing
- Use `<div class="section-padding"><div class="container-custom">` as the standard wrapper for content sections.
- Use generous vertical spacing (e.g., `py-20 lg:py-32`) for major page sections.
