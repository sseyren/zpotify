<p align="center"><img src="https://raw.githubusercontent.com/thesseyren/zpotify/master/resources/assets/images/zpotify.png" width="250px"></p>

## Ekran görüntüleri

<img src="https://raw.githubusercontent.com/thesseyren/zpotify/master/screenshots/screenshot1.png" width="400px">
<img src="https://raw.githubusercontent.com/thesseyren/zpotify/master/screenshots/screenshot2.png" width="400px">
<img src="https://raw.githubusercontent.com/thesseyren/zpotify/master/screenshots/screenshot3.png" width="400px">
<img src="https://raw.githubusercontent.com/thesseyren/zpotify/master/screenshots/screenshot4.png" width="400px">
<img src="https://raw.githubusercontent.com/thesseyren/zpotify/master/screenshots/screenshot5.png" width="400px">

## Kurulumu için gerekli adımlar

- `git clone https://github.com/thesseyren/zpotify.git`
- `composer install`
- `npm install`
- `cp .env.example .env` -> .env dosyasını düzenleyin.
- `cp config/jwt.example.php config/jwt.php` -> extra bir ayara gerek yok sadece kopya.
- `php artisan key:generate`
- `php artisan jwt:generate`
- `php artisan migrate:install`
- `php artisan migrate:refresh`
- `npm run prod`
- `php artisan serve` -> ile development sunucusunda deneyebilirsiniz.