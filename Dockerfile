FROM php:8.2-apache

WORKDIR /var/www/html

# Install dependencies
RUN docker-php-ext-install mysqli pdo_mysql

# Enable Apache modules
RUN a2enmod rewrite

# Copy application files
COPY ./src/ /var/www/html/

# Set permissions
RUN chown -R www-Data:www-Data /var/www/html

# Expose port 9000 to the outside world
EXPOSE 9000

# Start Apache when the container launches
CMD ["apache2-foreground"]
