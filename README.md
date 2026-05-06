<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## Penggunaan CSS

# 🚀 Dashboard Admin UI - Poppins

Template dashboard admin responsive dengan desain modern, ringan, dan menggunakan font **Poppins**.

![Status](https://img.shields.io/badge/status-ready-success)
![Responsive](https://img.shields.io/badge/responsive-yes-blue)
![Font](https://img.shields.io/badge/font-Poppins-orange)

---

## ⚡ Quick Start (Langsung Pakai)

Ikuti langkah ini (±1 menit):

### 1. Buat file

```
index.html
style.css
script.js
```

---

### 2. Tambahkan font Poppins ke HTML

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
```

---

### 3. Hubungkan CSS

```html
<link rel="stylesheet" href="style.css">
```

---

### 4. Hubungkan JavaScript

```html
<script src="script.js"></script>
```

---

### 5. Tambahkan struktur dasar

```html
<button id="menuToggle" class="menu-toggle">☰</button>

<div id="sidebarOverlay" class="sidebar-overlay"></div>

<aside id="sidebar" class="sidebar">
  <nav class="menu">
    <a href="#">Dashboard</a>
    <a href="#">Data</a>
  </nav>
</aside>

<main class="content">
  <h1>Dashboard</h1>
</main>
```

---

### 6. Jalankan

Buka:

```
index.html
```

✅ Selesai — dashboard sudah jalan

---

## ✨ Fitur

* Sidebar responsive
* Toggle menu mobile
* Overlay saat sidebar aktif
* Close sidebar dengan ESC
* Auto close menu di mobile
* Card UI
* Button UI
* Table UI
* Badge status
* Form inline
* Full font **Poppins**

---

## 📁 Struktur File

```
project/
├── index.html
├── style.css
├── script.js
└── README.md
```

---

## 🧩 Cara Pakai Komponen

### Card

```html
<div class="card">
  <h3>Judul</h3>
  <p>Isi konten</p>
</div>
```

---

### Button

```html
<button class="btn-inline btn-inline-primary">Simpan</button>
<button class="btn-inline btn-inline-secondary">Batal</button>
```

---

### Form

```html
<form class="form-inline">
  <input type="text" placeholder="Cari...">
  <button class="btn-inline btn-inline-primary">Cari</button>
</form>
```

---

### Table

```html
<div class="table-wrapper">
  <table>
    <thead>
      <tr>
        <th>Nama</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Andi</td>
      </tr>
    </tbody>
  </table>
</div>
```

---

### Badge

```html
<span class="badge-inline badge-inline-success">Aktif</span>
<span class="badge-inline badge-inline-warning">Pending</span>
<span class="badge-inline badge-inline-danger">Nonaktif</span>
```

---

## ⚙️ Cara Kerja JavaScript

Script akan otomatis:

* membuka sidebar saat tombol diklik
* menutup saat klik overlay
* menutup saat tekan `ESC`
* auto close menu di mobile
* reset sidebar saat resize ke desktop

---

## 📱 Responsive

| Device  | Behavior                |
| ------- | ----------------------- |
| Desktop | Sidebar tetap           |
| Mobile  | Sidebar hidden + toggle |

---

## 🛠️ Troubleshooting

### Sidebar tidak muncul

Pastikan ini ada:

* `id="menuToggle"`
* `id="sidebar"`
* `id="sidebarOverlay"`

---

### CSS tidak terbaca

```html
<link rel="stylesheet" href="style.css">
```

---

### JS tidak jalan

```html
<script src="script.js"></script>
```

---

### Font tidak berubah

Pastikan Google Fonts sudah dipasang.

---

## 🚀 Pengembangan Lanjutan

Template ini bisa dikembangkan menjadi:

* Dark mode
* Modal interaktif
* Dropdown menu
* Tabs
* Toast notification
* Pagination
* Chart dashboard

---

## 📄 Lisensi

Bebas digunakan untuk project pribadi, internal, atau pengembangan sendiri.

---

## 💡 Notes

Template ini dibuat untuk cepat dipakai tanpa framework.
Cukup copy, paste, dan langsung bisa dipakai 🚀

