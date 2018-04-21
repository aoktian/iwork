iwork
=
一款轻量级研发项目管理工具，主要针对中小互联网敏捷开发团队，页面清爽简单，功能精简好用。<br>
基于linux+nignx+php7+mysql开发


基本配置在 ./setting 目录下，app.php.example 和 database.php.example 是样例，需要修改文件名
-

mysql 里面要先创建一个数据库
-
```Java
CREATE DATABASE IF NOT EXISTS iwork DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;<br>
```

初始化数据表
-
```Java
php artisan "ctl=migrate"
php artisan "ctl=migrate&act=seeder"
```

nginx root目录配置到 public
-

改 nginx 的 location 配置
-
```Java
location / {
    try_files $uri $uri/ /index.php?$query_string;
    index  index.php;
}
```

我的服务器安装脚本，仅供参考，具体环境根据自己的需求配置
-
```Java
yum -y install wget net-tools
yum -y install gcc gcc-c++ autoconf automake libtool make cmake

#【安装nginx】
yum -y install zlib zlib-devel openssl openssl-devel pcre-devel

#确保pcre被安装
cd /usr/local/src
wget ftp://ftp.csx.cam.ac.uk/pub/software/programming/pcre/pcre-8.39.tar.gz
tar -zxf pcre-8.39.tar.gz
cd pcre-8.39
./configure
make
make install

cd /usr/local/src
groupadd www
useradd -g www www
wget http://nginx.org/download/nginx-1.10.3.tar.gz
tar zxf nginx-1.10.3.tar.gz
cd nginx-1.10.3
./configure --prefix=/usr/local/services/nginx --user=www --group=www --with-http_stub_status_module --with-pcre --without-mail_pop3_module --without-mail_imap_module --without-mail_smtp_module
make && make install

#配置自行修改

#【安装php】
cd /usr/local/src
yum install -y libxml2 libxml2-devel curl curl-devel
wget http://cn.php.net/get/php-7.1.3.tar.gz/from/this/mirror
mv mirror php-7.1.3.tar.gz
tar zxf php-7.1.3.tar.gz
cd php-7.1.3
./configure --prefix=/usr/local/services/php --with-config-file-path=/usr/local/services/php/etc --with-curl --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-zlib --with-openssl --enable-fpm --enable-opcache --enable-xml --enable-mbregex --enable-mbstring --enable-zip --enable-inline-optimization
make && make install
cp php.ini-production /usr/local/services/php/etc/php.ini
cp /usr/local/services/php/etc/php-fpm.conf.default /usr/local/services/php/etc/php-fpm.conf
cp /usr/local/services/php/etc/php-fpm.d/www.conf.default /usr/local/services/php/etc/php-fpm.d/www.conf
rm -rf /usr/bin/php
ln -s /usr/local/services/php/bin/php /usr/bin/php

rm -rf /etc/init.d/php-fpm
cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm
chmod +x /etc/init.d/php-fpm
chkconfig --add php-fpm
chkconfig php-fpm on
service php-fpm start
```

我的邮箱
-
email: aoktian@foxmail.com
