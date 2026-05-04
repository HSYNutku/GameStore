# 🎮 GameStor – Oyun Platformu (Bitirme Projesi)

GameStor, kullanıcıların oyun ekleyebildiği, arkadaş ekleyip sohbet edebildiği ve oyun satın alıp kütüphanesinde saklayabildiği **Steam benzeri sosyal bir oyun platformudur**.

---

## 🚀 Özellikler

### 🎮 Oyun Sistemi

* Kullanıcılar oyun ekleyebilir
* Admin onay sistemi (onaysız oyunlar görünmez)
* Oyun detay sayfası
* Tür, fiyat ve arama filtreleme

---

### 👤 Kullanıcı Sistemi

* Kayıt / Giriş sistemi
* Profil sayfası
* Profil resmi ve kullanıcı bilgileri

---

### 👥 Arkadaş Sistemi

* Kullanıcı arama
* Arkadaş isteği gönderme
* Kabul / reddetme
* Arkadaş listesi görüntüleme

---

### 💬 Chat Sistemi

* Kullanıcılar arasında mesajlaşma
* AJAX ile otomatik mesaj yenileme
* Okunmamış mesaj sistemi

---

### 🔔 Bildirim Sistemi

* Navbar’da:

  * 🔔 Arkadaş istekleri
  * 💬 Okunmamış mesaj sayısı
* Bildirim dropdown sistemi

---

### 🟢 Online / Offline Sistemi

* Kullanıcı aktifliği takip edilir (`last_active`)
* Online / Offline durum gösterimi

---

### 🛒 Satın Alma Sistemi

* Oyun satın alma
* Kullanıcıya özel kütüphane
* Satın alınan oyunların listelenmesi

---

### 🎮 Kütüphane Sistemi

* Kullanıcının satın aldığı oyunları görüntüleme

---

### 🔍 Filtreleme & Arama

* Oyun adına göre arama
* Tür filtresi
* Fiyat aralığı filtresi

---

## 🛠️ Kullanılan Teknolojiler

* **Backend:** PHP (PDO)
* **Veritabanı:** MySQL
* **Frontend:** HTML, CSS, Bootstrap 5
* **JavaScript:** AJAX (fetch API)
* **Server:** XAMPP

---

## 🧱 Veritabanı Yapısı

### Ana Tablolar:

* `users` → kullanıcılar
* `games` → oyunlar
* `friends` → arkadaş ilişkileri
* `messages` → mesajlar
* `library` → satın alınan oyunlar

---

## ⚙️ Kurulum

1. XAMPP başlat (Apache + MySQL)
2. `htdocs` içine projeyi koy:

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

## 🔑 Admin Girişi

Varsayılan admin:

* Kullanıcı adı: `admin`
* Şifre: `123456`

---

## 📌 Proje Amacı

Bu proje, modern web uygulamalarında:

* Veritabanı yönetimi
* Kullanıcı etkileşimi
* Gerçek zamanlıya yakın sistemler
* Sosyal özellikler

gibi konuları uygulamalı olarak öğrenmek amacıyla geliştirilmiştir.

---

## 🚀 Gelecek Geliştirmeler

* 🕒 Son görülme (last seen)
* ✔️ Mesaj görüldü sistemi
* 💬 Yazıyor (typing) özelliği
* ⭐ Oyun puanlama sistemi
* 🟢 Gerçek zamanlı (WebSocket) chat

---

## 👨‍💻 Geliştirici

**Hüseyin Utku Kocahüyük**

---

## 📄 Lisans

Bu proje eğitim amaçlı geliştirilmiştir.
