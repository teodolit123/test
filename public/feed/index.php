<?php
header("Content-Type: application/rss+xml; charset=UTF-8");

use App\Database;
use App\XmlGenerator;

Database::query("SELECT * FROM posts ORDER BY id DESC LIMIT :count");
Database::bind(':count', RSS_COUNTS);
$posts = Database::fetchAll();
foreach ($posts as $post) {
    $rssFeed .= '<item>';
    $rssFeed .= '<title>' . XmlGenerator::rss($post['title']) . '</title>';
    $rssFeed .= '<category>' . XmlGenerator::rss($post['category']) . '</category>';
    $rssFeed .= '<description>' . XmlGenerator::rss($post['subtitle']) . '</description>';
    $rssFeed .= '<link>' . URL_ROOT . '/blog/' . $post['slug'] . '</link>';
    $rssFeed .= '<pubDate>' . $post['updated_at'] . '</pubDate>';
    $rssFeed .= '<dc:creator>' . TITLE . '</dc:creator>';
    $rssFeed .= '</item>';
}

$rssFeed .= '</channel>';
$rssFeed .= '</rss>';

file_put_contents("feed/rss.xml", $rssFeed);
header("refresh:0;url=rss.xml");
