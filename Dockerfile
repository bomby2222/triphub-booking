FROM php:8.2-apache

# ติดตั้ง System Dependencies และ PHP Extensions สำหรับจัดการไฟล์รูปภาพ
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# เปิดใช้งาน mod_rewrite ของ Apache
RUN a2enmod rewrite

# ดึง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ปรับขนาดลิมิตการอัปโหลดไฟล์ของ PHP เป็น 64MB
RUN echo "upload_max_filesize = 64M\npost_max_size = 64M\nmemory_limit = 256M" > /usr/local/etc/php/conf.d/uploads.ini

# คัดลอกโปรเจกต์
COPY . .

# ตั้งค่า Apache Document Root ให้ชี้เข้า public และเปิด AllowOverride All สำหรับ .htaccess
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -i '/<Directory ${APACHE_DOCUMENT_ROOT}>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# ติดตั้ง Composer Dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ตั้งค่าสิทธิ์การเข้าถึงไฟล์
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# สั่งเชื่อมโยง Storage Link, เคลียร์/สร้าง Cache และเปิดพอร์ตตามที่ Render กำหนด
CMD sed -i "s/80/${PORT:-80}/g" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && \
    php artisan storage:link || true && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    apache2-foreground