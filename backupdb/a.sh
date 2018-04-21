CURDIR=$(cd $(dirname ${BASH_SOURCE[0]}); pwd )
cd $CURDIR

before=`date +%Y-%m-%d --date '7 days ago'`
rm -rf ${before}.tar.gz

now=`date +%Y-%m-%d`
mysqldump iwork > ${now}.sql
tar czf ${now}.tar.gz ${now}.sql
rm -rf ${now}.sql
