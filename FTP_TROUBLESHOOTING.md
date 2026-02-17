# FTP deployment – still getting 404s?

Use this if you already uploaded `build` and still see errors like:

- `build/assets/app-B72AfDkS.js` or `app-NjQ0vPvE.css` 404  
- `build/js/Edit-DrMhuCgg.js` 404  
- `uploads/projects/xxx.png` 404  
- Images in project/article **descriptions** not showing  

---

## 1. Build 404s (JS/CSS and chunks like `Edit-xxx.js`)

**Cause:** The server’s `build` folder is incomplete or mixed (old + new). The `manifest.json` points to many chunk files (`app-xxx.js`, `Edit-xxx.js`, `Index-xxx.js`, etc.). If **any** of those files are missing, the browser gets 404.

**Fix:**

1. **On the server (FTP):** Delete the entire **`build`** folder (or rename it to `build_old`). Do not leave old files in place.
2. **On your PC:** In the project root run:
   ```bash
   npm run build
   ```
3. **Upload:** Upload the **entire** `public/build` folder to the server so you have:
   - `build/manifest.json`
   - `build/js/` with **every** `.js` file (70+ files)
   - `build/assets/` with **every** `.css` file  
   Use your FTP client’s “upload folder with subfolders” / “preserve structure” so **no file is skipped**.
4. **Browser:** Hard refresh (Ctrl+Shift+R or Cmd+Shift+R) or open the site in **incognito/private** mode. Old cached HTML can still request old script names; a fresh load uses the new manifest.

The filenames (e.g. `Edit-DrMhuCgg.js`) change every time you run `npm run build`. The manifest and **all** those files must come from the **same** build and **all** must be on the server.

---

## 2. Image 404s (featured images and images in descriptions)

**Cause:** The app serves all images from **`/uploads/`**. That includes:

- Project/article **featured** images  
- Images you add **inside** the description (rich text editor)  

Both are stored under **`public/uploads/`** (e.g. `public/uploads/projects/`). There is no separate folder for “description” images.

**Fix:**

1. On your PC, open the **`public/uploads`** folder and confirm you see:
   - `uploads/projects/` (with your project images and any images pasted in descriptions)
   - `uploads/articles/` (article images)
2. Via FTP, upload the **entire** `public/uploads` folder to the server so the **document root** (same place as `index.php` and `build/`) contains:
   - `uploads/`
   - `uploads/projects/` (and every file inside)
   - `uploads/articles/` (and every file inside)

After that, URLs like `https://daraemmanuel.name.ng/uploads/projects/xxx.png` will work for both the main image and images inside the description.

**If your document root is `public_html` but the app lives in a subfolder (e.g. `personal_portfolio`):** Laravel may be saving uploads to `personal_portfolio/public/uploads` while the site requests `/uploads/` from `public_html`. Set in your server `.env`:

```env
UPLOADS_PATH=/home/youruser/public_html/uploads
```

(Use the real path to `public_html` on your host.) Then create `public_html/uploads/` (and `projects/`, `articles/` inside it) and new uploads will go there. See **DEPLOYMENT_FTP.md** section 3b.

---

## 3. Quick check on the server

After uploading, the server document root should look like:

- `index.php`
- `build/manifest.json`
- `build/js/` (many .js files)
- `build/assets/` (e.g. app-xxx.css, Welcome-xxx.css, RichTextEditor-xxx.css)
- `uploads/projects/` (your image files)
- `uploads/articles/` (if you use article images)

If any of these are missing or empty, fix that and try again.
