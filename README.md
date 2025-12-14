# Project Setup & Git Workflow Guide

This README provides the essential steps for setting up the Laravel project locally, along with simple GitHub workflow instructions for collaboration.

---

## 🚀 Project Installation

Clone the repository:

```bash
git clone https://github.com/Huma-volve/round8-cure-php-team-three.git
```

Install dependencies:

```bash
composer install
```

Copy environment file:

```bash
cp .env.example .env
```

Generate Laravel app key:

```bash
php artisan key:generate
```

Run migrations:

```bash
php artisan migrate
```

Start the server:

```bash
php artisan serve
```

---

## 🌿 GitHub Workflow Instructions

### 🔹 1. Create a New Branch (Run once per feature)

```bash
git checkout -b branch-name
```

This command creates a new branch **and switches to it immediately**.  
Use it **only once** when creating a new branch.

---

### 🔹 2. Save & Push Your Work

```bash
git add .
git commit -m "your message"
git push origin branch-name
```

- `git add .` → Stage all changes  
- `git commit` → Save changes locally  
- `git push` → Upload branch to GitHub  

---

### 🔹 3. Pull Latest Updates From Main

Use this to stay updated with the latest code:

```bash
git pull origin main
```

Run this frequently to avoid merge conflicts.

---

### Pulling Updates Without Losing Local Changes

If you have local changes that you don’t want to push yet, you can temporarily store them, pull the latest updates, and then re-apply your changes using:

```bash
git stash            # Temporarily saves your local changes
git pull origin main # Pulls the latest changes from the main branch
git stash pop        # Restores your previously stashed changes
```

## ✅ Notes

- Always make pull request whene you push.  
- Never push directly to the **main** branch.  
- Always pull before starting new work.  

