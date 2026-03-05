-- Database Schema for CNN Clone
-- Database: rsk0_03

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`slug`, `name`) VALUES
('world', 'World'),
('politics', 'Politics'),
('business', 'Business'),
('tech', 'Tech'),
('health', 'Health'),
('entertainment', 'Entertainment');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
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

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `category`, `image`, `summary`, `content`, `date`, `author`) VALUES
(1, 'Global Summit Reaches Historic Agreement on Climate Action', 'world', 'https://images.unsplash.com/photo-1623177667290-c585e371f86d?w=800&q=80', 'Leaders from 190 nations have signed a pact to reduce carbon emissions by 40% within the next decade.', 'In a landmark decision, world leaders gathered in Geneva today to sign the \'Future Earth Pact\'. This agreement mandates a strict reduction in industrial carbon output. \'It is a good day for the planet,\' said the UN Secretary-General. Critics, however, argue that the timeline is too aggressive for developing nations. The summit continues tomorrow with discussions on ocean conservation.', '2025-10-24 14:30:00', 'Sarah Jenkins'),
(2, 'Tech Giant Unveils Revolutionary Quantum Chip', 'tech', 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=800&q=80', 'The new \'Q-Core\' processor promises to solve complex problems in seconds that would take supercomputers years.', 'Silicon Valley was abuzz today as the Q-Core chip was revealed. Promising speeds 1000x faster than current generation CPUs, this quantum processor effectively brings quantum computing to the enterprise level. Cybersecurity experts are warning that current encryption methods may soon be obsolete.', '2025-10-24 11:15:00', 'David Chen'),
(3, 'Market Rally Continues as Inflation Numbers Drop', 'business', 'https://images.unsplash.com/photo-1611974765215-fadbf4c37b97?w=800&q=80', 'Wall Street sees another green day as the federal reserve signals a pause in rate hikes.', 'The S&P 500 hit a new yearly high this morning. Consumer spending remains robust despite fears of a recession. Analysts predict a strong holiday season for retail. \'The soft landing is looking more and more likely,\' stated chief economist Maria Rodriguez.', '2025-10-23 09:45:00', 'Amanda Lee'),
(4, 'New Mars Rover Sends Back Stunning High-Res Panoramas', 'tech', 'https://images.unsplash.com/photo-1540573133985-00b69f7e7678?w=800&q=80', 'The \'Explorer VII\' has successfully touched down and is transmitting 8K images of the Red Planet\'s surface.', 'NASA\'s latest mission has struck gold—or rather, red dust. The images returned effectively map out the northern hemisphere of Mars in unprecedented detail. Scientists are particularly interested in a rock formation that suggests ancient water flow.', '2025-10-23 16:20:00', 'Dr. Alan Grant'),
(5, 'Senate Passes Controversial Infrastructure Bill', 'politics', 'https://images.unsplash.com/photo-1520690214124-2405c5217036?w=800&q=80', 'After a 24-hour marathon session, the bill passed 52-48. It includes major funding for high-speed rail.', 'The vote was split largely along party lines. The opposition argued the spending was reckless, while proponents called it a necessary investment in the nation\'s crumbling bridges and roads. The President is expected to sign it into law on Friday.', '2025-10-22 21:00:00', 'James Wilson'),
(6, 'Summer Blockbuster Breaks Opening Weekend Records', 'entertainment', 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&q=80', '\'Galaxy Guardians 4\' raked in $250 million domestically, smashing previous records.', 'Movie theaters are back in full swing. The latest superhero installment proves that audiences still crave the big screen experience. Critics praised the visual effects but found the plot somewhat derivative.', '2025-10-21 10:30:00', 'Lisa Kudrow'),
(7, 'Breakthrough Study Links Coffee to Longer Life', 'health', 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&q=80', 'Researchers found that drinking 2-3 cups of coffee daily could lower the risk of heart disease.', 'The study followed 100,000 participants over 20 years. However, doctors warn against loading up on sugar and cream. \'Black coffee is the way to go,\' says Dr. Smith. The study was published in the American Journal of Medicine.', '2025-10-20 08:00:00', 'Dr. Oz');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
