<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL ?>
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($urls as $url): ?>
        <url>
            <loc><?= $host . $url['url'] ?></loc>
            <changefreq><?= $url['change_freq'] ?></changefreq>
            <priority>0.5</priority>
        </url>
    <?php endforeach; ?>
</urlset>