# DT Ecommerce Child Theme

## Installation

1. Upload the `dt-theme-child/` folder to `/wp-content/themes/` on your server  
   (so the path becomes `/wp-content/themes/dt-theme-child/`)
2. In WordPress Admin → Appearance → Themes, activate **DT Ecommerce Theme Child**
3. The parent theme (`dt-theme-master`) must remain installed but does **not** need to be active

## What this child theme does

- Loads the parent stylesheet + all parent functionality automatically
- Removes the "Child Theme ❌" warning from WooCommerce → Status
- Provides a safe place for per-site CSS/PHP tweaks that survive parent updates

## Where to add customisations

| What | Where |
|---|---|
| CSS tweaks for this site | Bottom of `style.css` in this folder |
| PHP hooks / filters for this site | Bottom of `functions.php` in this folder |
| Admin panel settings | WP Admin → DT Theme Options |
| WooCommerce template overrides | Already handled by the parent theme |

## Important

Do **not** duplicate parent theme functions in `functions.php`.  
Use `add_filter` / `remove_filter` / `remove_action` to extend or override parent behaviour.
