## Projeyi Çalıştırma Talimatları

Bu proje, PHP8.3 ile geliştirilmiştir. Projeyi çalıştırmak için aşağıdaki adımları takip edebilirsiniz:

### Gerekli Ortam

- PHP 8.3 veya daha yeni bir sürüm
- XAMPP veya benzeri bir web sunucusu (Apache, Nginx vb.)
- MySQL veritabanı

### Projeyi Çalıştırma

1. **PHP Sürümünü Kontrol Edin**: Terminal veya komut istemcisinde `php -v` komutunu kullanarak PHP sürümünüzü kontrol edin. 8.3 veya daha yeni bir sürüm olduğundan emin olun.
2. **Web Sunucusunu Başlatın**: XAMPP veya benzeri bir araç kullanıyorsanız, Apache ve MySQL servislerini başlatın.
3. **Proje Dosyalarını Kopyalayın**: Proje dosyalarını XAMPP'in `htdocs` dizinine veya web sunucunuzun kök dizinine kopyalayın.
4. **Veritabanını Oluşturun**: `migrations` klasöründeki SQL dosyalarını kullanarak veritabanınızı oluşturun. `database.sql` dosyası veritabanı yapısını içermektedir. MySQL Workbench veya phpMyAdmin gibi araçları kullanarak bu dosyayı içe aktarabilirsiniz.
5. **Konfigürasyon Dosyasını Düzenleyin**: Proje kök dizinindeki `/config/db.php` dosyasını açın ve veritabanı bağlantı bilgilerinizi güncelleyin.
6. **Proje URL'sini Ayarlayın**: Web tarayıcınızda `http://localhost/proje_adi` adresine giderek projeyi açın. Eğer farklı bir dizine kopyaladıysanız, URL'yi buna göre güncelleyin.
