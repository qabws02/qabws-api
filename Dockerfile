FROM php:8.2-apache

# تثبيت إضافات MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# نسخ الملفات للسيرفر
COPY . /var/www/html/

# تفعيل Apache على المنفذ الافتراضي
EXPOSE 80

# تشغيل Apache
CMD ["apache2-foreground"]
