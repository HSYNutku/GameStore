# 🎮 GameStore

GameStore, kullanıcıların oyun ekleyebildiği, satın alabildiği, arkadaş ekleyebildiği ve mesajlaşabildiği bir mini dijital oyun platformudur.
(Proje Steam benzeri bir sistem mantığında geliştirilmiştir.)

---

## 🚀 Özellikler

### 🎮 Oyun Sistemi

* Kullanıcılar oyun ekleyebilir
* Admin onay sistemi (pending / approved)
* Onaylanmamış oyunlar mağazada görünmez
* Oyun detay sayfası

---

### 🛒 Sepet & Satın Alma

* Sepete oyun ekleme
* Aynı oyunu sepete tekrar ekleme engeli
* Sipariş sistemi (`orders`, `order_items`)
* Satın alınan oyunlar otomatik olarak kütüphaneye eklenir
* Aynı oyun tekrar satın alınamaz

---

### 🎮 Kütüphane Sistemi

* Kullanıcının satın aldığı oyunlar listelenir
* Kullanıcıya özel içerik

---

### 👥 Arkadaş Sistemi

* Kullanıcı arama
* Arkadaş isteği gönderme
* Kabul / reddetme
* Arkadaş listesi

---

### 💬 Mesaj Sistemi

* Kullanıcılar arası mesajlaşma
* Okunmamış mesaj sayacı
* AJAX ile otomatik yenileme

---

### 🔔 Bildirim Sistemi

* Arkadaş istekleri
* Navbar üzerinde bildirim badge
* Dropdown bildirim paneli

---

### 🟢 Online / Offline Sistemi

* `last_active` ile kullanıcı takibi
* Online / offline durumu

---

### ⭐ Yorum & Puanlama

* Oyunlara yorum yapma
* 1–5 arası puanlama sistemi

---

### 🔍 Arama & Filtreleme

* Oyun adına göre arama
* Tür filtresi
* Fiyat aralığı filtresi

---

## 🛠️ Kullanılan Teknolojiler

* **Backend:** PHP (Core PHP)
* **Veritabanı:** MySQL / MariaDB
* **Database Access:** PDO (Prepared Statements)
* **Frontend:** HTML, CSS, Bootstrap 5
* **JavaScript:** AJAX (fetch API)
* **Server:** XAMPP

---

## 📂 Veritabanı Tabloları

* `users` → kullanıcılar
* `games` → oyunlar
* `cart` → sepet
* `orders` → siparişler
* `order_items` → sipariş detayları
* `library` → satın alınan oyunlar
* `friends` → arkadaş sistemi
* `messages` → mesajlar
* `comments` → yorumlar

---

## ⚙️ Kurulum

1. XAMPP başlat (Apache + MySQL)
2. Projeyi `htdocs` klasörüne at:

   ```
   C:\xampp\htdocs\gamestore
   ```
3. phpMyAdmin aç:

   ```
   http://localhost/phpmyadmin
   ```
4. Yeni veritabanı oluştur:

   ```
   gamestore
   ```
5. SQL tablolarını import et
6. Tarayıcıdan aç:

   ```
   http://localhost/gamestore
   ```

---

## 🔑 Demo Giriş

Admin hesabı:

* Kullanıcı adı: `admin`
* Şifre: `123456`

---

## 📌 Proje Amacı

Bu proje;

* Veritabanı yönetimi
* Kullanıcı etkileşimi
* Sosyal sistemler
* E-ticaret mantığı

gibi konuları uygulamalı olarak geliştirmek amacıyla yapılmıştır.

---

## 🚀 Gelecek Geliştirmeler

* 🕒 Son görülme (last seen)
* ✔️ Mesaj görüldü sistemi
* 💬 Yazıyor (typing)
* ⭐ Oyun puan ortalaması
* 🛒 Gelişmiş sepet sistemi
* 💳 Ödeme simülasyonu

---

## 👨‍💻 Geliştirici

**Hüseyin Utku Kocahüyük**

---

## 📄 Lisans

Bu proje eğitim amaçlı geliştirilmiştir.
