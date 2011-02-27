#/bin/sh
OUT=htdocs/___sitemaps

rm -f $OUT/sitemap_*
echo '<?xml version="1.0" encoding="UTF-8"?>'                              > $OUT/sitemap_index.xml
echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' >> $OUT/sitemap_index.xml

for X in `ls html`
do
    ./sitemap.sh html/$X |gzip -9 > $OUT/sitemap_$X.xml.gz

    now=`date -u --iso-8601=seconds |sed -e "s/0000/00:00/"`
    echo '  <sitemap>'                                     >> $OUT/sitemap_index.xml
    echo "    <loc>http://man.cx/sitemap_$X.xml.gz</loc>"  >> $OUT/sitemap_index.xml
    echo "    <lastmod>$now</lastmod>"                     >> $OUT/sitemap_index.xml
    echo '  </sitemap>'                                    >> $OUT/sitemap_index.xml
done
echo '</sitemapindex>'                                 >> $OUT/sitemap_index.xml
