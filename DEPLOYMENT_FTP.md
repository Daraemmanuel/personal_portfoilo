# FTP Deployment Guide (no SSH)

This guide is for **FTP-only** deployment. You do **not** need SSH or terminal access on the server.

---

## Why you see 404s

| Error | Cause |
|-------|--------|
| `build/assets/app-xxx.js` / `app-xxx.css` not found | The **build** folder on the server is out of date or incomplete. Laravel reads `public/build/manifest.json` and loads the hashed filenames from it. If the manifest references files that don’t exist (e.g. from an old build), you get 404. |
| `uploads/projects/xxx.png` not found | Uploaded images are stored under **`public/uploads/`**. If that folder (and its contents) is not uploaded or is in the wrong place, image URLs return 404. |

---

## 1. Fix JS/CSS 404s (build assets)

Your app expects the **Vite build** to be in the **web root** as `build/` (i.e. `public/build/` in your repo).

### Step 1: Build locally

On your machine, in the project root:

```bash
npm ci
npm run build
```

### Step 2: Upload the whole build folder

- **Local folder:** `public/build/`
- **On server:** upload everything inside `public/build/` so that the **document root** of your site contains a `build/` folder.

So on the server you must have:

- `build/manifest.json`
- `build/js/` (all `.js` files)
- `build/assets/` (all `.css` and any other assets)

**Important:**

- Upload **all** files from `public/build/` (manifest + `js/` + `assets/`). Do not upload only one of them.
- **Replace** the existing `build/` on the server with this new build (same hashes as in the manifest).
- Your document root must be the folder that contains `index.php` and this `build/` folder (usually Laravel’s `public/`). If your document root is the project root instead of `public/`, then `build/` must be at `public/build/` and your server must serve from `public/` (see “Check document root” below).

After this, the URLs Laravel generates (e.g. `/build/js/app-xxxxx.js`) will match the files on the server and the JS/CSS 404s should stop.

---

## 2. Fix image 404s (uploads)

Image URLs look like: `https://daraemmanuel.name.ng/uploads/projects/filename.png`.

So the server must serve files from **`uploads/`** inside the **same document root** that has `build/` and `index.php`.

### Step 1: Where uploads live locally

- Uploaded files are stored under: **`public/uploads/`**
- Project images: `public/uploads/projects/`
- Article images: `public/uploads/articles/`
- **Images inside descriptions** (rich text) are also stored in `public/uploads/projects/`. Uploading the full `uploads/` folder fixes both featured images and description images.

### Step 2: Upload the uploads folder

- **Local folder:** `public/uploads/` (entire folder and all subfolders)
- **On server:** upload so that the document root has an `uploads/` folder, e.g.:

  - `uploads/`
  - `uploads/projects/`
  - `uploads/projects/SWlJjDSJknpPOaowwfi9…oNUWD7labfUK2.png`
  - `uploads/projects/kFLyUivPR5HdPVgIu4r5…ND4UE2w2YRcS8.jpg`
  - etc.

So:

1. Copy the **entire** `public/uploads/` directory from your computer (including all project and article images).
2. Upload it via FTP to the **same level** as `build/` and `index.php` (i.e. inside the document root).

After this, `https://daraemmanuel.name.ng/uploads/projects/...` will point to real files and image 404s should stop.

---

## 3. Check document root

Laravel’s **public** folder must be the **document root** (web root) of the site.

- Correct: document root = `public/` → URLs like `/build/...`, `/uploads/...` work.
- Wrong: document root = project root → Laravel may still work via `public/index.php`, but `/build/` and `/uploads/` might not be under the root, so you get 404 for assets and images.

On the server you should have something like:

```
document_root/
  index.php
  build/
    manifest.json
    js/
    assets/
  uploads/
    projects/
    articles/
```

If your host uses a folder like `public_html` or `htdocs`, then **upload the contents of `public/`** (including `index.php`, `build/`, `uploads/`, etc.) into that folder, so that `build/` and `uploads/` are inside the document root.

---

## 3b. Document root is `public_html`, app is in a subfolder (e.g. `personal_portfolio`)

Some hosts use **`public_html`** as the document root, while your Laravel project lives in a subfolder (e.g. `personal_portfolio`). In that case:

- The site is served from **`public_html`** → the browser requests `/uploads/...` from `public_html/uploads/`.
- Laravel’s `public_path('uploads')` points to **`personal_portfolio/public/uploads`** → new uploads are saved there.
- So images are **saved** in `personal_portfolio/public/uploads` but **requested** from `public_html/uploads` → 404.

**Fix:** Tell Laravel to save uploads into the document root’s `uploads` folder.

1. In your server **`.env`**, set the full path to the uploads folder inside the document root:
   ```env
   UPLOADS_PATH=/home/your_cpanel_user/public_html/uploads
   ```
   Replace `your_cpanel_user` (and path) with the real path to `public_html` on your host. You can find it in your hosting panel or by putting a small PHP file in `public_html` that does `echo __DIR__;` and visiting it.

2. Create the folder on the server if it doesn’t exist: via FTP, create `uploads` (and inside it `projects/`, `articles/`) inside **`public_html`**.

3. Optionally copy existing images from `personal_portfolio/public/uploads/` into `public_html/uploads/` so old links work.

After this, new uploads will be stored in `public_html/uploads/`, so `/uploads/...` will resolve correctly. No code changes are needed besides this `.env` value.

---

## 4. Environment

In production `.env`:

- `APP_URL=https://daraemmanuel.name.ng` (no trailing slash)
- `APP_ENV=production`
- `APP_DEBUG=false`

**No SSH?** You can't run `php artisan config:cache`. That's OK — Laravel works without it. Set the values above in `.env` and upload the file via FTP. The app reads `.env` on each request.

---

## 5. Quick checklist

- [ ] Run `npm run build` locally.
- [ ] Upload **entire** `public/build/` (manifest + `js/` + `assets/`) to server document root as `build/`.
- [ ] Upload **entire** `public/uploads/` (including `projects/`, `articles/`, etc.) to server document root as `uploads/`.
- [ ] Document root = folder that contains `index.php`, `build/`, and `uploads/`.
- [ ] If document root is `public_html` and the app is in a subfolder: set `UPLOADS_PATH` in `.env` to the full path to `public_html/uploads` (see section 3b).
- [ ] `APP_URL` and production `.env` set correctly.

After this, both the “build/assets/app-xxx” and “uploads/projects/xxx” 404s should be resolved. No SSH or artisan commands are needed. If you still get 404s for build or images, see **FTP_TROUBLESHOOTING.md**.
