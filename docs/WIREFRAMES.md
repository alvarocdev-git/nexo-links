# Nexo Links — Wireframes

Low-fidelity wireframes for the three core screens. Mobile-first; desktop keeps the
same single-column layout centered with a max width.

## 1. Public profile page — `/{username}`

```
┌──────────────────────────────┐
│                              │
│           ( avatar )         │
│           @username          │
│      Short bio goes here     │
│                              │
│  ┌────────────────────────┐  │
│  │ ★ LIVE NOW — Stream    │  │   ← highlighted link (accent color, subtle pulse)
│  └────────────────────────┘  │
│  ┌────────────────────────┐  │
│  │ My portfolio           │  │
│  └────────────────────────┘  │
│  ┌────────────────────────┐  │
│  │ Latest blog post       │  │
│  └────────────────────────┘  │
│  ┌────────────────────────┐  │
│  │ Event — starts in 02:14│  │   ← countdown link
│  └────────────────────────┘  │
│                              │
│      ◉  ◉  ◉  ◉  ◉          │   ← social icons footer (IG, X, GitHub,
│                              │     WhatsApp, email… owner picks which)
│        made with Nexo Links      │   ← footer, links to the repo
└──────────────────────────────┘
```

## 2. Dashboard — `/dashboard`

```
┌──────────────────────────────────────────────┐
│ NexoLinks mark  | Analytics | Design | Settings │  ← top nav
├──────────────────────────────────────────────┤
│  [ + Add link ]           View my page ↗     │
│                                              │
│  ⠿ ┌────────────────────────────────────┐    │
│    │ My portfolio                       │    │
│    │ https://alvarocdev.com             │    │
│    │ [visible ●] [edit] [delete]  123 ⟶ │    │  ← click count
│    └────────────────────────────────────┘    │
│  ⠿ ┌────────────────────────────────────┐    │
│    │ Latest blog post                   │    │
│    │ https://blog...     ⏰ scheduled    │    │
│    │ [hidden ○] [edit] [delete]    8 ⟶  │    │
│    └────────────────────────────────────┘    │
│                                              │
│  (⠿ = drag handle for reordering)            │
└──────────────────────────────────────────────┘
```

## 3. Auth — `/register`

```
┌──────────────────────────────┐
│         Nexo Links           │
│     Create your page         │
│                              │
│  Username                    │
│  ┌────────────────────────┐  │
│  │ link.alvarocdev.com/ □ │  │  ← live availability check
│  └────────────────────────┘  │
│  Email                       │
│  ┌────────────────────────┐  │
│  Password                    │
│  ┌────────────────────────┐  │
│                              │
│  [      Create account    ]  │
│                              │
│  Already have one? Log in    │
└──────────────────────────────┘
```

## Analytics view (dashboard tab)

```
┌──────────────────────────────────────────────┐
│  Clicks — last 30 days          [7d|30d|90d] │
│  ▂▃▅▂▇▆▃▂▁▃▅▆▇▅▃▂ (bar chart per day)        │
│                                              │
│  Per link              Clicks    Top referrer│
│  My portfolio             123    google.com  │
│  Latest blog post           8    direct      │
└──────────────────────────────────────────────┘
```
