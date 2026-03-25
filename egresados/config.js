tailwind.config = {
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "primary": "#0d631b",
        "on-primary": "#ffffff",
        "background": "#f9f9f9",
        "surface": "#f9f9f9",
        "outline-variant": "#bfcaba",
        "primary-fixed-dim": "#88d982",
        "on-surface": "#1a1c1c",
        "on-surface-variant": "#40493d",
        "surface-container-lowest": "#ffffff",
        "primary-container": "#2e7d32",
        "on-primary-fixed-variant": "#005312"
      },
      fontFamily: {
        "headline": ["Public Sans"],
        "body": ["Public Sans"],
        "label": ["Public Sans"]
      },
      borderRadius: {
        "DEFAULT": "0.125rem",
        "lg": "0.25rem",
        "xl": "0.5rem",
        "full": "0.75rem"
      },
    },
  },
}