<?php
session_start();
require_once 'data.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$categoryId = isset($_GET['category']) ? $_GET['category'] : 'all';
$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Helper to truncate text
function truncate($text, $chars = 100) {
    if (strlen($text) <= $chars) return $text;
    return substr($text, 0, $chars) . "...";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CNN Clone - Breaking News, Latest News and Videos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="top-bar">
            <div class="left-nav">
                <div class="menu-toggle">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </div>
                <a href="?page=home" class="logo">CNN</a>
                <ul class="nav-links">
                    <li><a href="?page=home">US</a></li>
                    <li><a href="?page=category&category=world">World</a></li>
                    <li><a href="?page=category&category=politics">Politics</a></li>
                    <li><a href="?page=category&category=business">Business</a></li>
                    <li><a href="?page=category&category=health">Health</a></li>
                    <li><a href="?page=category&category=entertainment">Entertainment</a></li>
                    <li><a href="?page=category&category=tech">Tech</a></li>
                    <li><a href="#">Style</a></li>
                    <li><a href="#">Travel</a></li>
                    <li><a href="#">Sports</a></li>
                    <li><a href="#">More ▾</a></li>
                </ul>
            </div>
            <div class="right-nav">
                <div class="nav-icon dot">Watch</div>
                <div class="nav-icon">Listen</div>
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2" fill="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </div>
                <?php if (isset($_SESSION['username'])): ?>
                    <span style="font-size:13px; font-weight:bold; margin-right:10px;">Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php" class="btn-signin" style="background:black; color:white;">Sign Out</a>
                <?php else: ?>
                    <a href="login.php" class="btn-signin">Sign In</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <?php if ($page == 'home'): ?>
            <?php 
            $latest = getArticlesByCategory('all'); 
            // Organize data for the 3-column layout
            $mainStory = isset($latest[0]) ? $latest[0] : null;
            $middleStories = array_slice($latest, 1, 4); // 2 text only, 2 with thumbs
            $rightStories = array_slice($latest, 5, 5); // 5 small items
            ?>
            
            <?php if ($mainStory): ?>
            <div class="hero-grid">
                <!-- Column 1: Main Story (Left) -->
                <div class="main-story">
                    <a href="?page=article&id=<?= $mainStory['id'] ?>">
                        <img src="<?= $mainStory['image'] ?>" alt="<?= $mainStory['title'] ?>" onerror="this.onerror=null;this.src='https://placehold.co/800x450/000000/FFF?text=CNN+News'">
                        <h2><?= $mainStory['title'] ?></h2>
                        <div class="text-headline">
                            <p><?= truncate($mainStory['summary'], 150) ?></p>
                        </div>
                    </a>
                </div>

                <!-- Column 2: Editor's Picks (Middle) -->
                <div class="middle-column">
                    <?php if (isset($middleStories[0])): ?>
                        <div class="text-headline">
                            <h3><a href="?page=article&id=<?= $middleStories[0]['id'] ?>"><?= $middleStories[0]['title'] ?></a></h3>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($middleStories[1])): ?>
                        <div class="text-headline">
                            <h3><a href="?page=article&id=<?= $middleStories[1]['id'] ?>"><?= $middleStories[1]['title'] ?></a></h3>
                        </div>
                    <?php endif; ?>

                    <div class="thumbnail-row">
                         <?php if (isset($middleStories[2])): ?>
                            <div class="thumb-card">
                                <a href="?page=article&id=<?= $middleStories[2]['id'] ?>">
                                    <img src="<?= $middleStories[2]['image'] ?>" onerror="this.onerror=null;this.src='https://placehold.co/400x225/000000/FFF?text=News'">
                                    <h4><?= $middleStories[2]['title'] ?></h4>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($middleStories[3])): ?>
                            <div class="thumb-card">
                                <a href="?page=article&id=<?= $middleStories[3]['id'] ?>">
                                    <img src="<?= $middleStories[3]['image'] ?>" onerror="this.onerror=null;this.src='https://placehold.co/400x225/000000/FFF?text=News'">
                                    <h4><?= $middleStories[3]['title'] ?></h4>
                                </a>
                            </div>
                         <?php endif; ?>
                    </div>
                </div>

                <!-- Column 3: Sidebar List (Right) -->
                <div class="right-column">
                    <?php foreach ($rightStories as $item): ?>
                        <div class="sidebar-item">
                            <a href="?page=article&id=<?= $item['id'] ?>">
                                <img src="<?= $item['image'] ?>" onerror="this.onerror=null;this.src='https://placehold.co/200x150/000000/FFF?text=News'">
                            </a>
                             <h4><a href="?page=article&id=<?= $item['id'] ?>"><?= $item['title'] ?></a></h4>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Categorized Sections -->
            <?php 
            $displayCategories = ['world', 'politics', 'business', 'tech', 'health', 'entertainment'];
            foreach ($displayCategories as $catSlug):
                $catArticles = getArticlesByCategory($catSlug);
                if (empty($catArticles)) continue;
                $catName = isset($categories[$catSlug]) ? $categories[$catSlug] : ucfirst($catSlug);
                $catDisplay = array_slice($catArticles, 0, 4);
            ?>
            <div class="section-header">
                <h2><a href="?page=category&category=<?= $catSlug ?>" style="text-decoration:none; color:inherit;"><?= $catName ?></a></h2>
            </div>
            <div class="news-grid">
                <?php foreach ($catDisplay as $item): ?>
                    <div class="news-card">
                        <a href="?page=article&id=<?= $item['id'] ?>">
                            <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>" loading="lazy" onerror="this.src='https://via.placeholder.com/400x225?text=News'">
                            <h3><?= $item['title'] ?></h3>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endforeach; ?>

        <?php elseif ($page == 'category'): ?>
            <?php 
               $catArticles = getArticlesByCategory($categoryId);
               $catName = isset($categories[$categoryId]) ? $categories[$categoryId] : 'All News';
            ?>
            <div class="section-header">
                <h1><?= $catName ?></h1>
            </div>
            <div class="news-grid">
                <?php if (empty($catArticles)): ?>
                    <p>No news found in this category.</p>
                <?php else: ?>
                    <?php foreach ($catArticles as $item): ?>
                        <div class="news-card">
                            <a href="?page=article&id=<?= $item['id'] ?>">
                                <img src="<?= $item['image'] ?>" alt="<?= $item['title'] ?>">
                                <h3><?= $item['title'] ?></h3>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php elseif ($page == 'article'): ?>
            <?php 
                $article = getArticleById($articleId);
            ?>
            <?php if ($article): ?>
                <div class="article-layout">
                    <div class="article-content">
                        <h1><?= $article['title'] ?></h1>
                        <div class="article-meta">
                            By <strong><?= $article['author'] ?></strong> | Updated <?= $article['date'] ?>
                        </div>
                        <img src="<?= $article['image'] ?>" alt="<?= $article['title'] ?>" class="article-image">
                        <div class="article-body">
                            <p class="editor-note"><strong>(CNN) -</strong> <?= $article['content'] ?></p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                        </div>
                    </div>
                    <div class="sidebar">
                        <h3>More in <?= $categories[$article['category']] ?></h3>
                        <div class="right-column">
                            <?php 
                            $related = getArticlesByCategory($article['category']);
                            $count = 0;
                            foreach ($related as $item): 
                                if ($item['id'] == $article['id']) continue; // skip current
                                if ($count++ >= 5) break; 
                            ?>
                                <div class="sidebar-item">
                                    <a href="?page=article&id=<?= $item['id'] ?>">
                                        <img src="<?= $item['image'] ?>">
                                    </a>
                                     <h4><a href="?page=article&id=<?= $item['id'] ?>"><?= $item['title'] ?></a></h4>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <h1>Article Not Found</h1>
                <p>We couldn't find the article you were looking for.</p>
                <a href="?page=home">Return Home</a>
            <?php endif; ?>

        <?php else: ?>
            <h1>Page Not Found</h1>
        <?php endif; ?>
    </div>

<footer>
    <ul class="footer-links">
        <li><a href="#">Terms of Use</a></li>
        <li><a href="#">Privacy Policy</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact</a></li>
    </ul>
    <div class="copyright">© 2025 Cable News Network. A Warner Bros. Discovery Company. All Rights Reserved.</div>
</footer>

<script src="script.js"></script>
</body>
</html>
