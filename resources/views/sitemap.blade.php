<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
@php
$date = gmdate('Y-m-d\TH:i:s\Z');
@endphp
<urlset
   xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 
                        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

   <url>
      <loc>{{ route('home') }}</loc>
      <lastmod>{{ $date }}</lastmod>
      <changefreq>daily</changefreq>
      <priority>1.0</priority>
   </url>

   

   <url>
      <loc>{{ route('aboutUs') }}</loc>
      <lastmod>{{ $date }}</lastmod>
      <changefreq>monthly</changefreq>
      <priority>0.7</priority>
   </url>

   <url>
      <loc>{{ route('ContactUs') }}</loc>
      <lastmod>{{ $date }}</lastmod>
      <changefreq>monthly</changefreq>
      <priority>0.6</priority>
   </url>

   <url>
        <loc>{{ route('PrivacyPolicy') }}</loc>
        <lastmod>{{ $date }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>
    
    <url>
        <loc>{{ route('TermsConditions') }}</loc>
        <lastmod>{{ $date }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

    <url>
        <loc>{{ route('CancellationReturnsPolicy') }}</loc>
        <lastmod>{{ $date }}</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.5</priority>
    </url>

   

</urlset>