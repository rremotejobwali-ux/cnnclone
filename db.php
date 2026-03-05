<?php
// Database configuration
$host = 'localhost';
$db   = 'rsk0_03';
$user = 'rsk0_03';
$pass = '123456';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Function to auto-create tables if they don't exist
function initDatabase($pdo) {
    // Create Tables
    $sql = "
    CREATE TABLE IF NOT EXISTS `categories` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `slug` varchar(50) NOT NULL,
      `name` varchar(100) NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `slug` (`slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `articles` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `category` varchar(50) NOT NULL,
      `image` varchar(255) NOT NULL,
      `summary` text NOT NULL,
      `content` text NOT NULL,
      `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `author` varchar(100) NOT NULL,
      PRIMARY KEY (`id`),
      KEY `category` (`category`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    CREATE TABLE IF NOT EXISTS `users` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `username` varchar(50) NOT NULL,
      `password` varchar(255) NOT NULL,
      `email` varchar(100) NOT NULL,
      `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `username` (`username`),
      UNIQUE KEY `email` (`email`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($sql);

    // Seed Categories
    $stmt = $pdo->query("SELECT COUNT(*) FROM categories");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO `categories` (`slug`, `name`) VALUES
            ('world', 'World'),
            ('politics', 'Politics'),
            ('business', 'Business'),
            ('tech', 'Tech'),
            ('health', 'Health'),
            ('entertainment', 'Entertainment'),
            ('science', 'Science'),
            ('travel', 'Travel'),
            ('style', 'Style')");
    }

    // Ensure Default User Exists and Password is Correct
    $targetEmail = 'rafia@gmail.com';
    $targetUsername = 'Rafia';
    $chkUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $chkUser->execute([$targetEmail]);
    $existingUser = $chkUser->fetch();
    
    $uPass = password_hash('09876', PASSWORD_DEFAULT);
    
    if (!$existingUser) {
        $uStmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $uStmt->execute([$targetUsername, $targetEmail, $uPass]);
    } else {
        // Force update password and username
        $uStmt = $pdo->prepare("UPDATE users SET password = ?, username = ? WHERE id = ?");
        $uStmt->execute([$uPass, $targetUsername, $existingUser['id']]);
    }


    // Seed Articles - EXTENSIVE DATA
    $stmt = $pdo->query("SELECT COUNT(*) FROM articles");
    if ($stmt->fetchColumn() < 10) { // If fewer than 10 articles, re-seed/add more
        $articles = [
            // WORLD
            ["Global Summit Reaches Historic Agreement", "world", "https://images.unsplash.com/photo-1623177667290-c585e371f86d?w=1200&q=80", "Leaders from 190 nations have signed a pact to reduce carbon emissions.", "Comprehensive details on the new climate accord."],
            ["Tensions Rise in Eastern Europe", "world", "https://images.unsplash.com/photo-1543336307-e8357ad64988?w=1200&q=80", "Diplomatic talks stall as border exercises continue.", "Analysis of the geopolitical situation."],
            ["Historic Peace Treaty Signed", "world", "https://images.unsplash.com/photo-1533090161767-e6ffed986c88?w=1200&q=80", "Former rivals shake hands in a moment watched by millions.", "A look back at the conflict and the path to peace."],
            ["Major Earthquake Strikes Pacific", "world", "https://images.unsplash.com/photo-1593642532744-d377ab507dc8?w=1200&q=80", "7.2 magnitude quake prompts tsunami warnings.", "Emergency response teams are on high alert."],
            
            // POLITICS
            ["Senate Passes Infrastructure Bill", "politics", "https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=1200&q=80", "The bill includes major funding for high-speed rail.", "Details on the vote count and opposition arguments."],
            ["Election Special: Swing States", "politics", "https://images.unsplash.com/photo-1541872703-74c59636a226?w=1200&q=80", "Polling shows a tight race in key battlegrounds.", "Expert analysis on voter demographics."],
            ["Local Mayor Scandal Deepens", "politics", "https://images.unsplash.com/photo-1555848962-6e79363ec58f?w=1200&q=80", "Leaked tapes reveal alleged corruption.", "City council calls for an immediate investigation."],
            ["New Tax Plan Proposed", "politics", "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=1200&q=80", "Government aims to close loopholes for corporations.", "Economists weigh in on the potential impact."],

            // BUSINESS
            ["Market Rally Continues", "business", "https://images.unsplash.com/photo-1611974765215-fadbf4c37b97?w=1200&q=80", "Inflation numbers drop, boosting investor confidence.", "S&P 500 hits a new yearly high."],
            ["Crypto Regulations Incoming", "business", "https://images.unsplash.com/photo-1518546305927-5a555bb7020d?w=1200&q=80", "New bill aims to bring transparency to digital assets.", "Market reaction has been mixed."],
            ["Tech Stocks Take a Hit", "business", "https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=1200&q=80", "Earnings reports miss expectations for major giants.", "Nasdaq down 3% in pre-market trading."],
            ["Startup Unicorns IPO Watch", "business", "https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=1200&q=80", "Wall Street prepares for a wave of new listings.", "Investors are eyeing the fintech sector."],
            ["The 4-Day Work Week Trial", "business", "https://images.unsplash.com/photo-1521737604893-d14cc237f11d?w=1200&q=80", "Results show increased productivity and happiness.", "Major companies consider permanent adoption."],

            // TECH
            ["Revolutionary Quantum Chip Unveiled", "tech", "https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200&q=80", "Solves problems in seconds that take years for supercomputers.", "Implications for encryption and research."],
            ["Mars Rover Sends 8K Images", "tech", "https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?w=1200&q=80", "Stunning panoramas of the Red Planet.", "Scientists analyze rock formations for signs of water."],
            ["AI Singularity Debate", "tech", "https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1200&q=80", "Experts discuss the future of human-level AI.", "Ethical concerns remain a top priority."],
            ["Electric Jets Certified", "tech", "https://images.unsplash.com/photo-1559087316-6b2633ccfd92?w=1200&q=80", "Zero-emission short-haul flights are now a reality.", "Aviation industry hails a green future."],
            ["New VR Headset Review", "tech", "https://images.unsplash.com/photo-1622979135228-5b1ed317b9bd?w=1200&q=80", "Is the metaverse finally ready for prime time?", "Hands-on with the latest hardware."],

            // HEALTH
            ["Coffee Linked to Longer Life", "health", "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1200&q=80", "Study shows 2-3 cups daily reduces heart risk.", "Doctors confirm benefits of antioxidants."],
            ["Personalized Nutrition Trends", "health", "https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=1200&q=80", "DNA testing for diet plans is on the rise.", "Is it science or snake oil?"],
            ["Yoga vs Pilates", "health", "https://images.unsplash.com/photo-1518611012118-696072aa579a?w=1200&q=80", "Which is better for core strength?", "Experts break down the benefits."],
            ["New Flu Variant Warning", "health", "https://images.unsplash.com/photo-1584036561566-b93a901668d7?w=1200&q=80", "WHO urges caution as cases spike in Asia.", "Vaccine efficacy being tested."],

            // ENTERTAINMENT
            ["Summer Blockbuster Records", "entertainment", "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1200&q=80", "Galaxy Guardians 4 smashes expectations.", "Fans queue for hours for opening night."],
            ["Fashion Week Sustainability", "entertainment", "https://images.unsplash.com/photo-1537832816519-689ad163238b?w=1200&q=80", "Recycled materials take the runway.", "Top designers commit to green practices."],
            ["Game of Thrones Prequel", "entertainment", "https://images.unsplash.com/photo-1515634928627-2a4e0dae3ddf?w=1200&q=80", "HBO confirms production has begun.", "Leaked set photos excite fans."],
            ["Award Season Predictions", "entertainment", "https://images.unsplash.com/photo-1478720568477-152d9b164e63?w=1200&q=80", "Who will take home the gold this year?", "Critics praise indie darlings."],
             // MORE FILLERS
            ["The Art of Minimalist Living", "style", "https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?w=1200&q=80", "How decluttering your home can declutter your mind.", "Interviews with top interior designers."],
            ["Top 10 Hidden Travel Gems", "travel", "https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=1200&q=80", "Destinations you need to visit before they get crowded.", "From Albania to Uzbekistan."],
        ];

        $insert = $pdo->prepare("INSERT INTO articles (title, category, image, summary, content, author) VALUES (?, ?, ?, ?, ?, ?)");
        
        foreach ($articles as $a) {
            // Check if title exists to avoid duplicates
            $chk = $pdo->prepare("SELECT id FROM articles WHERE title = ?");
            $chk->execute([$a[0]]);
            if ($chk->rowCount() == 0) {
                $insert->execute([
                    $a[0], // Title
                    $a[1], // Category
                    $a[2], // Image
                    $a[3], // Summary
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. " . $a[4], // Content
                    'CNN Staff' // Author
                ]);
            }
        }
    }
}

// Initialize tables
initDatabase($pdo);
?>
