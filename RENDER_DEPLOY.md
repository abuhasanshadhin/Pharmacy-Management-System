# Deploy to Render (Free)

## Steps

### Step 1 — Create Blueprint Project
1. Go to [render.com](https://render.com) and sign up with GitHub
2. Click **"New +"** → **"Blueprint"**
3. Select your GitHub repo — Render will auto-detect `render.yaml`
4. Set **Blueprint Name:** `pharmacy-management-system`
5. Click **"Deploy Blueprint"**

### Step 2 — Create MySQL Database (Manual)
1. In your project dashboard, click **"New +"** → **"MySQL"**
2. Set **Name:** `pharmacy-management-system-db`
3. Plan: **Free**
4. Click **"Create Database"**
5. Wait for it to provision

### Step 3 — Link Database to Web Service
1. Go to your **Web Service** → **"Environment"** tab
2. Click **"Add Environment Variable"**
3. Set **Key:** `DATABASE_URL`
4. Set **Value:** Click **"Insert Database Reference"** → Select your MySQL DB → Choose `DATABASE_URL`

### Step 4 — Add APP_KEY
Still in Environment tab, add:
- **Key:** `APP_KEY`
- **Value:** `base64:nrjgcgJOVV0Yp+p4gQkz+9/x2v7UgvMUeuMpCu0GoP0=`

### Step 5 — Deploy
Click **"Manual Deploy"** → **"Deploy latest commit"**

### Step 6 — Get Public URL
Go to **"Settings"** → **"Networking"** → **"Generate Domain"**

Your app will be live at: `https://pharmacy-management-system.onrender.com`

## Keep App Awake (Free Tier)
Render free tier sleeps after 15 min. Use [UptimeRobot](https://uptimerobot.com) to ping every 5 min.

## Get APP_KEY

Run locally:

```bash
php artisan key:generate --show
```

Copy the `base64:...` value into Render's `APP_KEY` env var.
