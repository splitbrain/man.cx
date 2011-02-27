#!/bin/sh

echo '<?xml version="1.0" encoding="UTF-8"?>'
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'

find $1 -name '*.html' | \
perl -ne '
  $file = $_;
  chomp($file);
  $date = `date --iso-8601=seconds -u -r "$file"|sed -e "s/0000/00:00/"`;
  chomp($date);
  $file =~ s!html(/[^/]*)?/man(.?)/(.*)(\.html)$!$3($2)$1!;

  if($date){
    print "  <url>\n";
    print "    <loc>http://man.cx/$file</loc>\n";
    print "    <lastmod>$date</lastmod>\n";
    print "    <changefreq>monthly</changefreq>\n";
    print "  </url>\n";
  }
'

echo '</urlset>';
