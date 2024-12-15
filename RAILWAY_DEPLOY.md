# Deploy to Railway

This guide will help you deploy the Pharmacy Management System to Railway.

## Prerequisites

1. A [Railway](https://railway.app) account
2. Git installed on your machine
3. Your project pushed to a Git repository (GitHub, GitLab, or Bitbucket)

## Deployment Steps

### 1. Push Your Code to Git

Make sure all the deployment files are committed:

```bash
git add .
git commit -m "Add Railway deployment configuration"
git push origin main
```

### 2. Create a New Project on Railway

1. Go to [Railway Dashboard](https://railway.app/dashboard)
2. Click **"New Project"**
3. Select **"Deploy from GitHub Repo"**
4. Choose your repository

### 3. Add MySQL Database

1. In your Railway project, click **"+ New"**
2. Select **"Database"** → **"MySQL"**
3. Railway will automatically create a MySQL instance

### 4. Configure Environment Variables

Go to your service's **Variables** tab and add the following:

| Variable    | Value                                                          |
| ----------- | -------------------------------------------------------------- |
| `APP_KEY`   | Generate with `php artisan key:generate` and add `base64:...`  |
| `APP_ENV`   | `production`                                                   |
| `APP_DEBUG` | `false`                                                        |
| `APP_URL`   | Your Railway app URL (e.g., `https://your-app.up.railway.app`) |

#### Link MySQL Variables

Railway auto-generates MySQL connection variables. You need to reference them:

| Variable      | Railway Reference   |
| ------------- | ------------------- |
| `DB_HOST`     | `${MYSQL_HOST}`     |
| `DB_PORT`     | `${MYSQL_PORT}`     |
| `DB_DATABASE` | `${MYSQL_DATABASE}` |
| `DB_USERNAME` | `${MYSQL_USER}`     |
| `DB_PASSWORD` | `${MYSQL_PASSWORD}` |

> **Tip:** In Railway, you can reference variables from other services using `${{MYSQL.MYSQL_HOST}}` syntax in the Variables panel.

### 5. Deploy

Railway will automatically deploy your application. The build process will:

1. Build a Docker container with PHP 8.2 and Node.js 18
2. Install Composer dependencies
3. Install NPM dependencies and build frontend assets
4. Run database migrations
5. Cache configuration, routes, and views

### 6. Access Your Application

Once deployed, Railway will provide you with a public URL like:

```
https://your-app-name.up.railway.app
```

## Default Login

After deployment, you can log in with the seeded admin credentials (if seeded).

## Troubleshooting

### Build Fails

- Check the **Build Logs** in Railway dashboard
- Ensure all dependencies are in `composer.json` and `package.json`

### Database Connection Issues

- Verify MySQL service is running in Railway
- Check that database environment variables are correctly linked

### 500 Error

- Check **Deploy Logs** for PHP errors
- Ensure `APP_KEY` is set
- Verify `storage` and `bootstrap/cache` directories are writable

### Assets Not Loading

- Ensure `APP_URL` matches your Railway domain
- Run `php artisan storage:link` if using storage links

## Custom Domain

1. Go to your service **Settings**
2. Under **Networking**, click **"Generate Domain"** for a public URL
3. Or click **"Custom Domain"** to add your own domain
4. Update your DNS records as instructed

## Environment Variables Reference

```env
APP_NAME="Pharmacy Management System"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${MYSQL_HOST}
DB_PORT=${MYSQL_PORT}
DB_DATABASE=${MYSQL_DATABASE}
DB_USERNAME=${MYSQL_USER}
DB_PASSWORD=${MYSQL_PASSWORD}

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```
