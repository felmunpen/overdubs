# Overdubs 🎵

Overdubs is a Laravel-based web application that lets music lovers rate albums, write reviews, create playlists, and discover new music — a musical spin on the Letterboxd experience.

## 📋 Features

- Rate and review albums
- Follow other users and read their reviews
- Build and share playlists
- Explore new and trending music
- User profiles and activity feeds

## 🚀 Getting Started

These instructions will help you get Overdubs running on your local machine.

### ✅ Requirements

- PHP ≥ 8.1
- Composer
- Laravel ≥ 10
- MySQL or PostgreSQL
- Node.js + npm (for frontend assets)

### 🔧 Installation

1. **Clone the repository:**

```bash
git clone https://github.com/felmunpen/overdubs.git
cd overdubs
```

2. **Install PHP dependencies:**

```bash
composer install
```

3. **Copy and configure your environment file:**

```bash
cp .env.example .env
```

Edit `.env` and set up your database, mail, etc.

4. **Generate application key:**

```bash
php artisan key:generate
```

5. **Run migrations:**

```bash
php artisan migrate
```

6. **(Optional) Seed database:**

```bash
php artisan db:seed
```

7. **Install frontend dependencies and compile assets:**

```bash
npm install
npm run dev
```

8. **Start the local development server:**

```bash
php artisan serve
```

> ⚠️ **Note:** The local IP address and port may vary depending on your system configuration. Check the terminal output for the correct URL.

---

## 🧪 Testing

```bash
php artisan test
```

---

## 🤝 Contributing

Pull requests are welcome! To contribute:

1. Fork the repository
2. Create your branch (`git checkout -b feature/awesome-feature`)
3. Commit your changes (`git commit -m 'Add awesome feature'`)
4. Push to the branch (`git push origin feature/awesome-feature`)
5. Open a Pull Request

---

## 👤 Author

- [@felmunpen](https://github.com/felmunpen)

---

## 🛠️ Built With

- [Laravel](https://laravel.com/)
- [Blade](https://laravel.com/docs/blade)
- [MySQL](https://www.mysql.com/)

