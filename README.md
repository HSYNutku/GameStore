# 🎮 GameVault

GameVault, kullanıcıların oyun ekleyebildiği, yorum yapabildiği ve topluluk etkileşimi sağlayabildiği **Steam benzeri bir web uygulamasıdır**.
Proje PHP ve MySQL kullanılarak geliştirilmiştir.

---

## 🚀 Özellikler

### 👤 Kullanıcı Sistemi

* Kayıt olma ve giriş yapma
* Profil sayfası
* Kullanıcı bilgileri görüntüleme

### 🎮 Oyun Sistemi

* Kullanıcılar oyun ekleyebilir
* Admin onay sistemi (moderasyon)
* Oyun detay sayfası
* Oyun arama ve filtreleme

### 🛠️ Admin Paneli

* Onay bekleyen oyunları görüntüleme
* Oyun onaylama / reddetme
* Yönetim yetkileri

### 💬 Yorum Sistemi

* Oyunlara yorum yapma
* 1-5 arası puanlama
* Kullanıcı bazlı yorumlar

### 🌐 Topluluk (Forum)

* Forum gönderileri oluşturma
* Yorum yapma
* Beğeni sistemi

---

## 🧱 Teknolojiler

* **Backend:** PHP (PDO)
* **Veritabanı:** MySQL
* **Frontend:** HTML, CSS, Bootstrap 5
* **Diğer:** Font Awesome

---

## ⚙️ Kurulum

### 1. XAMPP Kur

* Apache ve MySQL’i başlat

### 2. Projeyi Yerleştir

```bash
C:\xampp\htdocs\gamestore
```

### 3. Veritabanı Oluştur

phpMyAdmin → yeni veritabanı:

```sql
gamestore
```

### 4. Tabloları Kur

SQL sekmesinde gerekli tabloları çalıştır

---

### 5. config.php Ayarla

```php
$this->db = new PDO("mysql:host=localhost;dbname=gamestore;charset=utf8", "root", "");
```

---

### 6. Siteyi Çalıştır

```bash
http://localhost/gamestore
```

---

## 🔐 Admin Girişi

Varsayılan admin:

* Kullanıcı adı: **admin**
* Şifre: **123456**

---

## 🔄 Sistem Nasıl Çalışır?

1. Kullanıcı oyun ekler
2. Oyun → **pending** durumuna düşer
3. Admin panelde görünür
4. Admin onaylarsa → **approved** olur
5. Sadece onaylı oyunlar ana sayfada görünür

---

## 📂 Proje Yapısı

```
gamestore/
│
├── includes/
│   ├── header.php
│   ├── footer.php
│
├── config.php
├── index.php
├── add_game.php
├── game_details.php
├── admin_games.php
├── login.php
├── register.php
├── profile.php
```

---

## 🔒 Güvenlik

* PDO Prepared Statements kullanıldı
* SQL Injection önlemleri alındı
* Şifreler hashlenerek saklanır

---

## 🚀 Geliştirilebilir Özellikler

* 🛒 Sepet & satın alma sistemi
* ❤️ Favorilere ekleme
* 🔔 Bildirim sistemi
* 🎮 Oyun kategorileri
* 🧠 Yapay zeka öneri sistemi

---

## 👨‍💻 Geliştirici

Bu proje eğitim amaçlı geliştirilmiştir.

---

## ⭐ Lisans

Bu proje açık kaynaklıdır ve eğitim amaçlı kullanılabilir.
