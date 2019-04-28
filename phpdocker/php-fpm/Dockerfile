FROM phpdockerio/php73-fpm:latest
WORKDIR "/application"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php-memcached php7.3-mysql php7.3-bz2 php7.3-dba php7.3-enchant php7.3-gd php-gearman php7.3-gmp php-igbinary php-imagick php7.3-imap php7.3-interbase php7.3-intl php7.3-ldap php7.3-soap php-ssh2 php7.3-xmlrpc php7.3-xsl php-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*