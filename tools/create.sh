cd /home/iwork/tools
dbname=$1
if [ ! $dbname ]; then
    echo "error: need dbname."
    exit
fi

mkdir -p /home/images/$dbname
chown www:www /home/images/$dbname

mysql -e "CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci"
#mysql $dbname < table.sql
#mysql $dbname < data.sql 

