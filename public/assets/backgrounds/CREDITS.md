# Background assets

## hero-foil-dark.webp / hero-foil-dark.jpg

- Source: https://www.pexels.com/photo/foil-on-a-wall-in-the-dark-19889188/
- Photographer: Tim Mossholder
- License: Pexels License — free for commercial use, attribution not required.
  https://www.pexels.com/license/
- Local variants: 1920x1080 crop, WebP (~40 KB) and JPEG fallback (~93 KB).
- Used only on desktop (`min-width: 901px`) as the hero background, layered under ink gradients.

## page-grid-dark.webp / page-grid-dark.jpg

- Source: https://www.pexels.com/photo/dark-wavy-abstract-texture-with-blue-grid-lines-34851018/
- Photographer: Robert Grant
- License: Pexels License — free for commercial use, attribution not required.
  https://www.pexels.com/license/
- Local variants: 1440x600 crop, WebP (~63 KB) and JPEG fallback (~112 KB).
- Used only on desktop (`min-width: 901px`) for the Layanan and service-detail heroes.

## page-concrete.webp / page-concrete.jpg

- Source: https://www.pexels.com/photo/gray-concrete-wall-in-close-up-shot-12532681/
- Photographer: Tim Mossholder
- License: Pexels License — free for commercial use, attribution not required.
  https://www.pexels.com/license/
- Local variants: 1440x600 crop, WebP (~19 KB) and JPEG fallback (~51 KB).
- Used only on desktop (`min-width: 901px`) for the Portfolio hero.

## page-fabric-green.webp / page-fabric-green.jpg

- Source: https://www.pexels.com/photo/a-green-cloth-in-close-up-photography-7641223/
- Photographer: Eva Bronzini
- License: Pexels License — free for commercial use, attribution not required.
  https://www.pexels.com/license/
- Local variants: 1440x600 crop, WebP (~157 KB) and JPEG fallback (~198 KB).
- Used only on desktop (`min-width: 901px`) for the Tentang Kami hero.

## page-paper-black.webp / page-paper-black.jpg

- Source: https://www.pexels.com/photo/wrinkled-black-paper-8749388/
- Photographer: Tamanna Rumee
- License: Pexels License — free for commercial use, attribution not required.
  https://www.pexels.com/license/
- Local variants: 1440x600 crop, WebP (~85 KB) and JPEG fallback (~121 KB).
- Used only on desktop (`min-width: 901px`) for the Kontak hero.

All page backgrounds are desktop-only via `image-set()` inside `@media (min-width: 901px)`.
Mobile keeps the CSS-only ink gradient so no photo is downloaded on small screens.
