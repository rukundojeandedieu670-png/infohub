# News Page - Layout & Positioning Guide

## 📐 Visual Architecture

```
┌─────────────────────────────────────────────────────────┐
│                   TOPBAR (Red Gradient)                 │
│  🔴 BREAKING: Latest news headline | 📘 𝕏 💼          │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                   STICKY NAVBAR (White)                 │
│ [Logo] [Search Bar] [News* Jobs Business] [Login]       │
│ *Active with green underline                            │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│          STICKY CATEGORY TABS (White w/ Border)         │
│ [All News*] [Tech] [Business] [Jobs] [Education] [*]    │
│ *Current category highlighted in green                  │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│                    HERO SECTION                         │
│  ┌─────────────────────────┐  ┌──────────────────┐    │
│  │   Featured Article      │  │  🔥 Trending #1  │    │
│  │  ┌─────────────────┐    │  │  🔥 Trending #2  │    │
│  │  │   Image (300px) │    │  │  🔥 Trending #3  │    │
│  │  │ ★ FEATURED      │    │  │  🔥 Trending #4  │    │
│  │  └─────────────────┘    │  │  🔥 Trending #5  │    │
│  │  Category • Date        │  └──────────────────┘    │
│  │  Title (Responsive)     │                          │
│  │  Excerpt (160 chars)    │                          │
│  │  [Read Full Story →]    │                          │
│  └─────────────────────────┘                          │
└─────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────┐
│               MAIN CONTENT AREA                          │
│                                                          │
│  ┌──────────────────────────────────┐  ┌────────────┐  │
│  │        ARTICLE GRID (1fr)        │  │  SIDEBAR   │  │
│  │                                  │  │  (340px)   │  │
│  │  ┌─ Article Card #1 ─┐          │  │            │  │
│  │  │ [Img] Title       │          │  │ 📧 NewsLet│  │
│  │  │       Excerpt    │          │  │ 💼 Hot Job│  │
│  │  │ [Read More →]    │          │  │ 🎓 Scholar│  │
│  │  │ 👁️ Views 💬 Cmt │          │  │ 📅 Events │  │
│  │  └──────────────────┘          │  │ 🏢 Business│  │
│  │                                  │  │ 🌐 Follow │  │
│  │  ┌─ Article Card #2 ─┐          │  │            │  │
│  │  │ (Same layout)    │          │  │            │  │
│  │  └──────────────────┘          │  │            │  │
│  │                                  │  │            │  │
│  │  ... More Articles (12 total)   │  │            │  │
│  │                                  │  │            │  │
│  └──────────────────────────────────┘  └────────────┘  │
│                                                          │
└──────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────┐
│              PAGINATION                                 │
│  « First ‹ Previous [1] [2] [3] [4] Next › Last »      │
│                      ↑ Current page (green)             │
└──────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────┐
│                    FOOTER (Dark)                        │
│  ┌─────────┐ ┌──────────┐ ┌────────┐ ┌──────────┐    │
│  │ About   │ │ Quick    │ │Support │ │ Legal    │    │
│  │ InfoHub │ │ Links    │ │        │ │ Privacy  │    │
│  └─────────┘ └──────────┘ └────────┘ └──────────┘    │
│  © 2024 InfoHub Rwanda. Building Rwanda's digital... 🇷🇼 │
└──────────────────────────────────────────────────────────┘
```

---

## 🎨 Color Scheme & Typography

### **Primary Colors**
| Color | Hex | Usage |
|-------|-----|-------|
| Green (Primary) | #16a34a | Buttons, active states, links |
| Blue (Accent) | #2563eb | Headers, widget backgrounds |
| Gray Light | #f8fafc | Page background, subtle backgrounds |
| Gray Dark | #0f172a | Main text, dark sections |
| Text Secondary | #64748b | Secondary text, metadata |
| Border | #e2e8f0 | Borders, dividers |

### **Widget Gradients**
| Widget | Gradient | Icon |
|--------|----------|------|
| Newsletter | Blue → Lighter Blue | 📧 |
| Hot Jobs | Pink → Coral | 💼 |
| Scholarships | Orange → Light Orange | 🎓 |
| Events | Purple → Light Purple | 📅 |
| Businesses | Green → Light Green | 🏢 |
| Follow Us | Cyan → Light Cyan | 🌐 |

### **Typography**
```css
Font Stack: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto

Sizes:
- Logo: 1.25rem (20px)
- H2 (Featured Title): 1.4rem (22px)
- H3 (Article Title): 1.05rem (17px)
- Body: 0.9rem (14px)
- Small: 0.8rem (13px)
- Tiny: 0.75rem (12px)

Weight:
- Regular: 400
- Medium: 500
- Semi-bold: 600
- Bold: 700
```

---

## 📏 Spacing & Layout

### **Section Widths**
```
Content width: max-width: 1200px
Padding (sides): 1.5rem on desktop
Sidebar width: 340px fixed
Article image width: 200px
Hero section gap: 2rem
Card padding: 1.5rem
```

### **Responsive Breakpoints**

```css
Desktop (1200px+):
├─ 2-column layout (1fr + 340px sidebar)
├─ All sections visible
└─ Full-size images

Tablet (768-1024px):
├─ 1-column grid (sidebar below)
├─ Article cards responsive
└─ Hero section stacks

Mobile (<768px):
├─ Full-width responsive
├─ Navbar components wrap
├─ Sidebar widgets stack
└─ Smaller fonts/padding

Small Mobile (<480px):
├─ Compact spacing
├─ Reduced padding
├─ Touch-friendly sizes
└─ Single column
```

---

## 🔢 Grid Dimensions

### **Hero Section (2fr 1fr)**
```
Desktop:  Featured (670px) | Trending (340px)
Tablet:   Featured (100%) stacked over Trending (100%)
Mobile:   Single column (100%)
```

### **Main Content (1fr 340px)**
```
Desktop:  Articles (860px) | Sidebar (340px)
Tablet:   Articles (100%) + Sidebar (100%) stacked
Mobile:   Articles (100%) + Sidebar (100%) stacked
```

### **Article Cards (200px 1fr)**
```
Image width: 200px
Content: Remaining width
Gap: 1.5rem

Responsive: 
- Tablet: Stack vertically
- Mobile: Image above content
```

### **Pagination Grid**
```
Flex wrap: Responsive button layout
Gap: 0.5rem
Justify: Center
```

### **Footer Grid**
```
4 columns: repeat(auto-fit, minmax(250px, 1fr))
Gap: 2rem
Mobile: Stacks to 1 column
```

---

## 🧮 Exact Measurements

### **Heights**
| Element | Height | Notes |
|---------|--------|-------|
| Topbar | auto (≈40px) | Padding 0.75rem |
| Navbar | auto (≈60px) | Padding 0.75rem |
| Hero image | 300px | Featured article |
| Article card image | 150px | Grid articles |
| Sidebar widget | auto (≈200px) | Varies by content |

### **Widths**
| Element | Width | Notes |
|---------|-------|-------|
| Container | 1200px | max-width |
| Sidebar | 340px | Fixed, 6 widgets |
| Article image | 200px | Grid layout |
| Hero featured | 2fr | 2-column layout |
| Hero trending | 1fr | 2-column layout |

### **Gaps & Padding**
| Element | Value | Use |
|---------|-------|-----|
| Section gap | 2rem | Between main sections |
| Grid gap (main) | 2rem | Between articles and sidebar |
| Article gap | 1.5rem | Between image and content |
| Widget gap | 1.5rem | Between sidebar widgets |
| Card padding | 1.5rem | Article card content |
| Section padding | 1.5rem | Horizontal edges |

---

## 🎯 Content Positioning

### **Topbar Content**
```
Left: 🔴 BREAKING: [News headline]
Right: [Social icons]
Alignment: space-between
Flex wrap: wrap
```

### **Navbar Content**
```
Left: [Logo]
Center: [Search form] (flex: 1)
Right: [Nav links] [Auth buttons]
Wrap: Below 768px
```

### **Hero Section**
```
2-column grid:
- Left: Featured article card (2fr)
  └─ Image (300px height)
  └─ Badge (top-right, absolute)
  └─ Content (flex column)
  
- Right: Trending sidebar (1fr)
  └─ Title
  └─ 5 trending items (stacked)
```

### **Article Cards**
```
Grid: 200px image + 1fr content
- Image: 200px width, 150px height
- Category badge: top-right, absolute
- Metadata row: Author | Date
- Title: responsive font
- Excerpt: 120 chars max
- Footer: [Read More] [Stats]
```

### **Sidebar Widgets**
```
Each widget:
- Header: Colored gradient bar
- Content: Padding 1rem
- Width: 340px (minus padding = 310px effective)
- Height: Auto based on content
- Stack: 6 widgets vertically
```

### **Footer Columns**
```
4-column grid on desktop:
1. About InfoHub
2. Quick Links
3. Support (Email/Phone)
4. Legal (Privacy/Terms)

Mobile: Stacks to 1 column
```

---

## 📱 Responsive Transformations

### **1024px Breakpoint (Tablet)**
```css
/* Hero Section */
grid-template-columns: 1fr !important;

/* Main Content */
grid-template-columns: 1fr !important;
aside { grid-column: 1 / -1; }

/* Articles */
grid-template-columns: 1fr !important;
```

### **768px Breakpoint (Mobile)**
```css
/* Navbar */
flex-wrap: wrap;
gap: 0.5rem;

/* Search bar */
order: 3;
width: 100%;

/* Article cards */
padding: 1rem;
gap: 1rem;

/* Headings */
h2 { font-size: 1.2rem; }
h3 { font-size: 1rem; }
```

### **480px Breakpoint (Small Mobile)**
```css
/* Navbar */
font-size: 0.85rem;

/* Articles */
grid-template-columns: 1fr;
image { min-height: 120px; }

/* Spacing */
[padding] { padding: 0.5rem; }
[gap] { gap: 1rem; }
```

---

## ✅ Layout Checklist

Visual verification points:

- [ ] **Topbar**: 40px height, red gradient, breaks to 2 rows on mobile
- [ ] **Navbar**: 60px height, sticky, search expands to full width at 768px
- [ ] **Hero**: Featured article 300px tall, trending sidebar 340px wide
- [ ] **Category Tabs**: 50px height, sticky below navbar, horizontal scroll on mobile
- [ ] **Article Cards**: 200px image × 150px height, 1.5rem gap from content
- [ ] **Sidebar**: 340px width on desktop, full-width stacked on tablet/mobile
- [ ] **Pagination**: Centered, wrapped buttons, proper spacing
- [ ] **Footer**: 4 columns desktop, 1 column mobile, dark background fills screen width
- [ ] **Overall**: Max-width 1200px centered, 1.5rem side padding maintained
- [ ] **Responsive**: Smooth transition at all breakpoints, no content overflow

---

## 🎨 CSS Grid/Flexbox Reference

### **Main Content Grid**
```css
display: grid;
grid-template-columns: 1fr 340px;
gap: 2rem;
max-width: 1200px;
```

### **Article Cards Grid**
```css
display: grid;
grid-template-columns: 200px 1fr;
gap: 1.5rem;
```

### **Navbar Flex**
```css
display: flex;
justify-content: space-between;
align-items: center;
gap: 1.5rem;
flex-wrap: wrap;
```

### **Sidebar Flex**
```css
display: flex;
flex-direction: column;
gap: 1.5rem;
```

### **Footer Grid**
```css
display: grid;
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 2rem;
```

---

## 🎯 Final Notes

- **All measurements are responsive**: Use rem/em units, they scale with viewport
- **Media queries override**: Breakpoints at 1024px, 768px, 480px
- **Mobile-first philosophy**: Start with mobile layout, expand for larger screens
- **Test at actual breakpoints**: Use browser DevTools responsive mode
- **Touch-friendly**: Buttons/links minimum 44px height for mobile
- **Content readable**: Line length optimal, font sizes adjusted per screen

**The layout maintains professional spacing, proper hierarchy, and full responsiveness across all devices.**

---

Generated: 2024  
Status: ✅ Complete  
File: `/app/views/news/index.php`
