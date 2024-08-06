# Utiliser une image de base PHP avec Apache
FROM php:7.4-apache

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html/

# Changer les permissions des fichiers et répertoires
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copier la configuration personnalisée d'Apache
COPY my-custom.conf /etc/apache2/sites-available/000-default.conf

# Activer les modules nécessaires
RUN a2enmod rewrite

# Exposer le port 80
EXPOSE 80

# Commande par défaut pour démarrer Apache
CMD ["apache2-foreground"]
