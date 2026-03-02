# 🎮 Mini Game Store – Web Tabanlı Dijital Oyun Satış Sistemi
Bu proje, dijital oyun satış platformlarının (örnek: Steam benzeri sistemler) temel çalışma mantığını simüle eden web tabanlı bir uygulamadır. Kullanıcılar oyunları görüntüleyebilir, sanal bakiye ile satın alabilir ve kendi kütüphanelerinde saklayabilir.  Proje Amacı
Bu projenin amacı:
Web tabanlı e-ticaret sistemlerinin temel mantığını öğrenmek
Kullanıcı-ürün ilişkisini veritabanı üzerinden yönetmek
Rol bazlı yetkilendirme sistemi geliştirmek
PHP & MySQL ile CRUD işlemlerini uygulamak
Kullanılan Teknolojiler
HTML5 – Arayüz yapısı
CSS3 – Tasarım
Bootstrap – Responsive tasarım
PHP – Backend ve iş mantığı
MySQL – Veritabanı yönetimi
XAMPP – Local geliştirme ortamı
Kullanıcı Rolleri
Kullanıcı (Oyuncu)
Kayıt olma / giriş yapma
Oyunları listeleme
Oyun detaylarını görüntüleme
Sanal bakiye görüntüleme
Oyun satın alma
Kütüphanedeki oyunları görüntüleme
Admin
Oyun ekleme
Oyun silme
Oyun güncelleme
Kullanıcıları görüntüleme
Satış takibi
Veritabanı Yapısı
users
id
username
email
password
balance
role
games
id
title
description
price
image
library
id
user_id
game_id
purchase_date
Sistem Akışı
Kullanıcı sisteme giriş yapar.
Oyunları mağaza sayfasında görüntüler.
Satın almak istediği oyunu seçer.
Sistem:
Daha önce alınıp alınmadığını kontrol eder
Bakiye yeterli mi kontrol eder
İşlem başarılıysa:
Kullanıcının bakiyesi düşülür
Oyun kütüphaneye eklenir
