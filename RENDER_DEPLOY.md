# Deploy to Render (Free)

## Steps

1. Go to [render.com](https://render.com) and sign up with GitHub
2. Click **"New +"** → **"Blueprint"**
3. Select your GitHub repo — Render will auto-detect `render.yaml`
4. Click **"Apply"** to deploy

Render will automatically:
- Create a MySQL database
- Install PHP + Node dependencies
- Build frontend assets
- Run migrations

Your app will be live at: `https://pharmacy-management-system.onrender.com`

## Manual Setup (if Blueprint doesn't work)

1. Click **"New +"** → **"Web Service"**
2. Connect GitHub repo
3. Settings:
   - **Runtime:** PHP
   - **Build Command:**
     ```
     composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan migrate --force
     ```
   - **Start Command:**
     ```
     vendor/bin/heroku-php-apache2 public/
     ```
4. Add **"New +"** → **"MySQL"** database
5. Copy the **Internal Database URL** and set as `DATABASE_URL` env var
6. Add env vars: `APP_KEY`, `APP_ENV=production`, `APP_DEBUG=false`
7. Deploy!

## Get APP_KEY

Run locally:
```bash
php artisan key:generate --show
```
Copy the `base64:...` value into Render's `APP_KEY` env var.
