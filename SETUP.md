# hellotechnology — Perch Runway template setup

All templates are built from the three static designs in `/static`. This guide
explains how to turn them into a working site in the Perch admin.

## What was created

```
styles.css                                  ← copied from /static (site stylesheet)
assets/                                      ← put your images here (see below)
admin/templates/
  layouts/
    global.head.php                          ← fonts, stylesheet, icon library
    global.header.php                        ← logo + navigation (built from your pages)
    global.footer.php                        ← footer + Lucide icon initialiser
    cta.php                                   ← reusable closing call-to-action band
  navigation/
    nav-link.html                            ← nav item template
  pages/                                      ← MASTER PAGE TEMPLATES (pick when adding a page)
    home.php                                  ← home page          → home-blocks.html
    default.php                               ← standard page      → page-blocks.html
    service.php                               ← service landing    → service-blocks.html
    case-study.php                            ← case study         → case-study-blocks.html
    blog.php                                  ← blog archive
    blog-post.php                             ← single blog post
  content/                                     ← BLOCK SETS (one per page type — see lists below)
    home-blocks.html                          ← blocks for the home page
    page-blocks.html                          ← blocks for standard pages
    service-blocks.html                       ← blocks for service landing pages
    case-study-blocks.html                    ← blocks for case studies
    blog/
      intro.html                              ← editable heading/intro on the archive
  blog/                                        ← PERCH BLOG addon templates (styled to match)
    post.html                                 ← single post layout
    post_in_list.html                         ← post card + paging on the archive
    (+ the addon's other shipped templates: comment, category, author, meta_head…)
```

## 1. Add your images

The designs reference these — drop them into `/assets`:

- `logo-primary.svg` — header logo (dark)
- `logo-reversed.svg` — footer logo (light)
- `portrait.jpg` — optional; the Hero block also accepts an uploaded portrait

Everything else (work thumbnails, case-study/blog images) is uploaded through the
Perch admin when you fill in a block, and falls back to a styled placeholder if left empty.

## 2. Build pages from blocks

Each page type has its own focused set of blocks, so the admin only shows the
sections that make sense for that page.

1. In the admin go to **Pages → Add Page**.
2. Choose the master template for the page type:

   | Master template | Use for | Block set |
   |-----------------|---------|-----------|
   | **home** | The home page | `home-blocks.html` |
   | **default** | Standard / general pages | `page-blocks.html` |
   | **service** | Service landing pages (e.g. Web design) | `service-blocks.html` |
   | **case-study** | Case studies | `case-study-blocks.html` |

3. Open the page and use **Page content** to add, reorder and configure section blocks.

Most colours/icons are editable. Icons use **Lucide** names — browse them at
<https://lucide.dev/icons>. Several blocks (Section heading, Services, Features,
FAQ, Testimonials, Banner, Gallery, Rich text, CTA) appear in more than one set
so common sections are always to hand.

### Blocks per page type

**home** (`home-blocks.html`) — from `index.html`
Hero · Services (3 cards) · Selected work · About (split + stats) · AI feature
(dark split) · Steps (how it works) · Testimonials · Section heading · CTA

**default** (`page-blocks.html`) — general-purpose
Section heading · Rich text · Services (3 cards) · Features (small grid) ·
Testimonials · FAQ · Banner image · Image gallery · CTA

**service** (`service-blocks.html`) — from `web-design.html`
Service hero (browser mock) · Trust strip · Features (small grid) · Process
(numbered, dark) · Pricing tiers · FAQ · Section heading · CTA

**case-study** (`case-study-blocks.html`) — from `case-study.html`
Case-study hero · Banner image · Meta bar · Rich text · Pull quote · Results
metrics · Image gallery · More work (2-up) · CTA

## 3. Navigation

The header menu is generated from your top-level pages
(`perch_pages_navigation` → `nav-link.html`). Set each page's **Navigation text**
and order in the page options, and tick "Hide from navigation" for utility pages
(e.g. the single-post template page in step 4).

## 4. Blog (Perch Blog addon)

The blog uses the installed **Perch Blog** app. The two master templates call
`perch_blog_*` and the post markup lives in `admin/templates/blog/` (the addon's
own template folder), restyled to match the site.

1. **Archive page.** Pages → Add Page at URL `/blog`, master template **blog**.
   `perch_blog_custom()` lists posts (9 per page, newest first, paginated) using
   `blog/post_in_list.html`, above an editable intro region.
2. **Single-post page + routing.** Pages → Add Page, master template **blog-post**.
   - Give it a path such as `/blog/post`.
   - In the page's options add the URL rule **`blog/[slug:s]`**
     (Page options → URL). This routes `/blog/my-post-slug` to the template, which
     reads `perch_get('s')` and renders the post via `blog/post.html`.
   - Tick **Hide from navigation**.
   - Make sure the Blog's **post URL pattern** (Blog → Options) resolves to
     `/blog/{postSlug}` so the generated `postURL` matches this route. Adjust the
     route token if you prefer date-based URLs (e.g. `blog/[year:i]/[month:i]/[slug:s]`).
3. **Add posts.** Admin → **Blog → Add post**. Fields map straight onto the
   templates: Title, Date, Categories, Hero image (`image`), Excerpt/standfirst,
   and Post body (`postDescHTML`, Markdown). Social/SEO tags come from the addon's
   `meta_head.html` via `perch_blog_post_meta()`.

> The styled markup lives in `admin/templates/blog/post.html` and
> `post_in_list.html`. The original Perch field IDs are preserved, so editing in
> the admin works exactly as the addon expects — only the presentation changed.

## Notes

- The Lucide icon script and Google Fonts load from CDNs (as in the static files).
- `config.php` maps the `ht-runway` server name to `config.ht-runway.php`; the public
  document root is this folder and the Perch install lives in `/admin`.
