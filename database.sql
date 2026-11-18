-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `elite_ceylon_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `elite_ceylon_db`;

-- Create admins table to hold administrator credentials securely
CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `last_login` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create reviews table to hold user-submitted reviews
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `author_name` VARCHAR(100) NOT NULL,
    `author_info` VARCHAR(150) NOT NULL,
    `author_avatar` VARCHAR(255) NULL,
    `rating` INT NOT NULL,
    `review_text` TEXT NOT NULL,
    `tour_package` VARCHAR(100) NULL DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `approved` TINYINT DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create packages table to hold tour packages and day-by-day itineraries
CREATE TABLE IF NOT EXISTS `packages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `tag` VARCHAR(50) DEFAULT 'Best Seller',
    `duration` VARCHAR(50) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `min_persons` VARCHAR(50) DEFAULT 'Min 2 Persons',
    `image` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `full_itinerary` TEXT NULL,
    `inclusions` TEXT NULL,
    `featured` TINYINT(1) DEFAULT 1,
    `sort_order` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default reviews (pre-approved) with specified IDs for idempotency
INSERT INTO `reviews` (`id`, `author_name`, `author_info`, `author_avatar`, `rating`, `review_text`, `tour_package`, `created_at`, `approved`) VALUES
(1, 'Sarah Jenkins', 'Family Vacationer · UK', 'assets/images/sarah_jenkins.jpg', 5, 'Our family holiday with Elite Ceylon was absolutely flawless. The attention to detail, luxury stays, and the warmth of our guide made us feel completely safe and pampered. Sigiriya was breathtaking!', 'Sigiriya Fortress', '2026-07-12 14:15:00', 1),
(2, 'Elena Rostova', 'Ayurvedic Wellness · Russia', 'assets/images/ayurvedic.jpg', 5, 'The Ayurveda wellness retreat in Kandy was transforming. From authentic therapies to quiet yoga spots, Elite Ceylon curated a peaceful sanctuary. I will definitely return next year!', 'Custom Luxury Itinerary', '2026-06-28 09:30:00', 1),
(3, 'Sophia Laurent', 'Solo Traveler · France', 'assets/images/home%202.jpg', 5, 'As a solo female traveler, safety was my number one priority. Elite Ceylon went above and beyond to provide a highly professional, respectful, and safe guide who knew the island inside out.', NULL, '2026-05-15 16:12:00', 1),
(4, 'John Doe', 'Nature Photographer · US', 'assets/images/john_doe.jpg', 5, 'The Ella scenic train ride and the Yala wildlife safari exceeded all expectations. We saw three leopards! Thanks to Elite Ceylon Travels for coordinating such a majestic itinerary. Truly 5-star service!', 'Yala Wildlife Safari', '2026-08-04 11:05:00', 1),
(5, 'Kenji Tanaka', 'Landscape Explorer · Japan', 'assets/images/1.1.jpg', 4, 'Every stop was a photographer\'s dream. Sigiriya sunrise, stilt fishermen at Koggala, and Nine Arch Bridge. The guide knew the exact angles and timings for spectacular shots.', 'Ella Highlands', '2026-07-02 18:20:00', 1),
(6, 'Lucas Hoffmann', 'Historical Scholar · Germany', 'assets/images/home%203.jpg', 5, 'I am deeply impressed by their cultural excursions. Exploring the sacred ruins of Anuradhapura and Polonnaruwa with a knowledgeable historian guide was the highlight of my trip.', 'Kandy Sacred Heritage', '2026-04-10 10:45:00', 1),
(7, 'Michael Chen', 'Active Surf & Whales · Canada', 'assets/images/michael_chen.jpg', 5, 'I came for a surfing trip to Weligama and wellness in Kandy. The customized itinerary was a perfect balance of adventure and peaceful luxury. Highly recommend Elite Ceylon!', 'Weligama Surf Retreat', '2026-08-10 15:40:00', 1),
(8, 'David & Emma Miller', 'Honeymoon Couple · Australia', 'assets/images/gregory_lake.jpg', 5, 'We booked our honeymoon with Elite Ceylon. They welcomed us with orchids and champagne, private candlelit beach dinners, and beautiful ocean villas. The service was legendary!', 'Custom Luxury Itinerary', '2026-06-19 20:15:00', 1),
(9, 'Isabella Martinez', 'Luxury Retreat · Spain', 'assets/images/charuka-herath-KrpuG5qdmVA-unsplash.jpg', 5, 'Pasikuda Bay Sanctuary was heaven. Crystal-clear shallow waters, white sand, and 5-star spa service. Elite Ceylon arranged the transfers and local logistics with absolute precision.', 'Pasikuda Bay Sanctuary', '2026-03-05 14:30:00', 1)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`);

-- Seed default packages with full day-by-day itineraries
INSERT INTO `packages` (`id`, `title`, `tag`, `duration`, `price`, `min_persons`, `image`, `description`, `full_itinerary`, `inclusions`, `featured`, `sort_order`) VALUES
(1, '7-Day Classic Sri Lanka Highlights Tour', 'Best Seller', '7 Days / 6 Nights', 0.00, 'Min 2 Persons', 'assets/images/home 3.jpg', 'Embark on a captivating 7-day luxury Sri Lanka circuit covering ancient heritage, lush tea hills, wildlife safaris, and pristine southern beaches. Journey from the ancient rock fortress of Sigiriya to the sacred cultural hub of Kandy, take the world-famous highland train ride to Ella, experience a thrilling Yala leopard safari, and unwind on the golden shores of Mirissa before your departure.', 
'Day 01
Colombo Airport to Sigiriya
Arrive at Colombo Airport and transfer to Sigiriya. Check in to your hotel for a one-night stay. Depending on time, visit the Sigiriya Rock, Pidurangala Rock, or the Sigiriya view point to catch a stunning sunset.

Day 02
Sigiriya to Kandy
Travel from Sigiriya to Kandy. On the way, stop at the historical Dambulla Cave Temple. In Kandy, check in for a one-night stay. Visit the sacred Temple of the Tooth Relic, stroll through the lush Peradeniya Botanical Garden, and cap off the evening with a traditional Kandy cultural dance performance.

Day 03
Kandy to Nuwara Eliya
Travel from Kandy to the cool hills of Nuwara Eliya. Stay one night. Explore scenic tea factories and plantations, marvel at the Ramboda Waterfalls, stop at breathtaking valley viewpoints, and enjoy a peaceful stroll by Gregory Lake.

Day 04
Nuwara Eliya to Ella (By Scenic Train)
Board the most popular and scenic train ride from Nuwara Eliya to Ella. Stay one night in Ella. Explore the iconic Nine Arch Bridge, hike Little Adam\'s Peak, and visit the stunning Ravana Waterfalls and Upper Diyaluma Waterfalls.

Day 05
Ella to Yala
Travel from Ella down to Yala. Check in for a one-night stay. Embark on a thrilling half-day wildlife safari in Yala National Park, famous for having one of the highest leopard densities in the world.

Day 06
Yala to Mirissa
Travel from Yala to the coastal paradise of Mirissa for a one-night stay. Enjoy a wide range of activities: snorkeling with turtles, diving, surfing, whale watching, or visiting Coconut Tree Hill, Mirissa Beach, Weligama Beach, a local turtle farm, and the historic Galle Fort.

Day 07
Mirissa to Colombo Airport
After breakfast and beach activities, transfer from Mirissa back to Colombo International Airport for your departure flight.',
'Private Air-Conditioned Vehicle, English-Speaking Chauffeur/Guide, Luxury Boutique Hotel Accommodations, Daily Breakfast, Safari 4x4 Jeep Tour, All Fuel & Tolls', 1, 1),

(2, '12-Day Grand Sri Lanka Adventure', 'Premium Tour', '12 Days / 11 Nights', 0.00, 'Min 2 Persons', 'assets/images/sigiriya.jpg', 'An immersive 12-day luxury grand expedition traversing UNESCO World Heritage triangles, misty tea highlands, wild safari reserves, sun-drenched southern coastlines, and the vibrant capital of Colombo. Experience multi-night stays in Sigiriya, Ella, and Mirissa beach, enjoy the scenic highland train ride, track Yala leopards, explore historic Galle Fort, and discover Colombo\'s iconic landmarks.',
'Day 01 - 02
Colombo Airport to Sigiriya
Arrive and transfer to Sigiriya for a two-night stay. Experience the iconic Sigiriya Rock, Pidurangala Rock, or the Sigiriya view point. Discover the ancient ruined city of Polonnaruwa and go on an exciting elephant gathering safari in Minneriya National Park.

Day 03
Sigiriya to Kandy
Journey to Kandy, visiting the historical Dambulla Cave Temple on the way. Check in for one night. Visit the Temple of the Tooth Relic, walk through Peradeniya Botanical Garden, and enjoy a vibrant Kandy cultural dance show.

Day 04
Kandy to Nuwara Eliya
Travel to Nuwara Eliya and stay one night. Tour lush tea factories and plantations, view the scenic Ramboda Waterfalls, stop at breathtaking viewpoints, and unwind by Gregory Lake.

Day 05 - 06
Nuwara Eliya to Ella (By Train)
Take the legendary scenic train ride from Nuwara Eliya to Ella for a two-night stay. Explore Little Adam\'s Peak, the historic Nine Arch Bridge, Ravana Waterfalls, Upper Diyaluma Waterfalls, and local rock temples.

Day 07
Ella to Yala
Head down to Yala for a one-night stay. Experience a thrilling half-day afternoon safari in Yala National Park to spot leopards, elephants, bears, and exotic birds.

Day 08 - 10
Yala to Mirissa (Beach Stay)
Transfer to Mirissa for a relaxing three-night beach stay. Enjoy snorkeling with turtles, diving, surfing, whale watching, Coconut Tree Hill, Weligama Beach, a local turtle farm, and the historic Galle Dutch Fort.

Day 11
Mirissa to Colombo
Travel to Colombo for your final night. Enjoy a city tour visiting the famous Gangarama Temple, Lotus Tower, Port City, National Museum, historic Hindu temples, and the vibrant Pettah Market.

Day 12
Colombo to Airport
Transfer to Colombo International Airport for your departure flight home.',
'Dedicated Luxury Chauffeur & Private Transport, 4/5-Star Stays, Daily Gourmet Breakfast, 2 Safari Jeep Excursions, First-Class Train Tickets, Sightseeing Concierge', 1, 2),

(3, '5-Day Sri Lanka Highlights & Safari Tour', 'Short & Sweet', '5 Days / 4 Nights', 0.00, 'Min 2 Persons', 'assets/images/yala.jpg', 'The ultimate 5-day Sri Lankan getaway packed with iconic highlights. Experience the sacred hill capital of Kandy, travel into cool tea country in Nuwara Eliya, ride the scenic highland train to Ella\'s Nine Arch Bridge, witness wild elephants on an Udawalawe morning safari, and relax on the tropical beaches of Mirissa.',
'Day 01
Airport to Kandy
Arrive at the airport and transfer to Kandy for a one-night stay. Visit the sacred Temple of the Tooth Relic, stroll through the Peradeniya Botanical Garden, and witness a Kandy cultural dance performance.

Day 02
Kandy to Nuwara Eliya
Travel from Kandy to Nuwara Eliya. Stay one night. Explore a tea factory and lush tea plantations, view the Ramboda Waterfalls, visit scenic viewpoints, and relax near Gregory Lake.

Day 03
Nuwara Eliya to Ella (By Scenic Train)
Take the famous scenic train ride from Nuwara Eliya to Ella. Stay one night in Ella. Explore Little Adam\'s Peak, the Nine Arch Bridge, Ravana Waterfalls, Upper Diyaluma Waterfalls, and local rock temples.

Day 04
Ella to Mirissa (via Udawalawe Safari)
Travel from Ella to Mirissa for a one-night stay. Go on a morning safari at Udawalawe National Park to see wild elephants. In the afternoon, enjoy Mirissa Beach, snorkel with turtles, or enjoy surfing, diving, whale watching, and Coconut Tree Hill.

Day 05
Mirissa to Airport
Transfer from Mirissa to Colombo Airport for your departure flight.',
'Private AC Vehicle & Chauffeur, Handpicked Hotels, Daily Breakfast, Yala Wildlife Safari 4x4 Jeep, High Altitude Train Experience', 1, 3),

(4, '15-Day Ultimate Sri Lanka Explorer', 'Grand Tour', '15 Days / 14 Nights', 0.00, 'Min 2 Persons', 'assets/images/ella.jpg', 'The definitive 15-day island expedition covering Sri Lanka\'s hidden gems and famed destinations. From coastal Negombo and ancient Anuradhapura to a 3-night eastern beach sanctuary in Trincomalee with Pigeon Island snorkeling. Continue through Sigiriya fortress, Kandy\'s sacred heritage, misty tea hills, a 3-night Ella mountain retreat, Yala leopard safaris, and an immersive Colombo capital city finale.',
'Day 01
Colombo Airport to Negombo
Arrive at Colombo Airport and transfer to Negombo. Check in to your hotel for a relaxing one-night stay by the beach.

Day 02
Negombo to Anuradhapura
Travel to Anuradhapura. Spend the day exploring the highlights of this sacred ancient city and UNESCO World Heritage site. Stay one night.

Day 03 - 05
Anuradhapura to Trincomalee
Transfer to the beautiful coastal city of Trincomalee for a three-night stay. Enjoy snorkeling at Pigeon Island, diving, surfing, whale watching, relaxing on Nilaveli and Uppuveli beaches, and visiting Marble Beach, Trincomalee Dutch Fort, and the Koneswaram Hindu Temple.

Day 06 - 07
Trincomalee to Sigiriya
Journey from Trincomalee to Sigiriya for a two-night stay. Climb the iconic Sigiriya Rock, hike Pidurangala Rock, visit local viewpoints, explore the ancient ruins of Polonnaruwa, and go on a wildlife safari in Minneriya National Park.

Day 08
Sigiriya to Kandy
Travel to Kandy. On the way, visit the historic Dambulla Cave Temple. In Kandy, visit the sacred Temple of the Tooth Relic, stroll through the Peradeniya Botanical Garden, and attend a traditional Kandy cultural dance performance. Stay one night.

Day 09
Kandy to Nuwara Eliya
Travel up to Nuwara Eliya. Stay one night. Tour lush tea factories and plantations, see the Ramboda Waterfalls, visit viewpoints, and enjoy Gregory Lake.

Day 10 - 12
Nuwara Eliya to Ella (By Train)
Experience the world-famous scenic train ride to Ella. Stay three nights in Ella. Hike Little Adam\'s Peak, cross the Nine Arch Bridge, view Ravana Waterfalls and Upper Diyaluma Waterfalls, and visit local rock temples.

Day 13
Ella to Yala
Travel down to Yala. Check in for a one-night stay and enjoy a thrilling afternoon safari in Yala National Park.

Day 14
Yala to Colombo
Travel to Colombo for a one-night stay. Take a city tour covering Gangarama Temple, Lotus Tower, Port City, National Museum, Hindu temples, and Pettah Market.

Day 15
Colombo to Airport
Transfer from Colombo back to the airport for your departure flight.',
'Private Chauffeur-Driven Luxury Vehicle, Deluxe Accommodations, Daily Breakfast, Snorkeling Boat & Safari Jeeps, All Monument Passes, 24/7 Concierge', 1, 4),

(5, '10-Day Essential Sri Lanka Highlights', 'Best Value', '10 Days / 9 Nights', 0.00, 'Min 2 Persons', 'assets/images/home 2.jpg', 'A perfectly balanced 10-day luxury journey linking Sri Lanka\'s top UNESCO sites, central highlands, wildlife parks, and southern ocean resorts. Enjoy two-night stays in Sigiriya, Ella, and Mirissa, experience the scenic blue train, spot elephants and leopards in Minneriya and Yala, and explore historic Galle Dutch Fort.',
'Day 01 - 02
Colombo Airport to Sigiriya
Arrive and transfer to Sigiriya for a two-night stay. Climb the Sigiriya Rock Fortress, hike Pidurangala Rock, explore the ancient ruined city of Polonnaruwa, and enjoy a wildlife safari in Minneriya National Park.

Day 03
Sigiriya to Kandy
Travel to Kandy, stopping at Dambulla Cave Temple on the way. Check in for a one-night stay. Visit Kandy\'s Temple of the Tooth Relic, walk the Peradeniya Botanical Garden, and see a cultural dance show.

Day 04
Kandy to Nuwara Eliya
Journey to the cool climate of Nuwara Eliya. Stay one night. Tour lush tea factories and plantations, see Ramboda Waterfalls, enjoy views, and relax by Gregory Lake.

Day 05 - 06
Nuwara Eliya to Ella (By Train)
Board the scenic train ride to Ella. Stay two nights in Ella. Hike Little Adam\'s Peak, walk the Nine Arch Bridge, and view Ravana Waterfalls & Upper Diyaluma Waterfalls.

Day 07
Ella to Yala
Travel down to Yala. Check in for one night. Go on a thrilling afternoon wildlife safari in Yala National Park.

Day 08 - 09
Yala to Mirissa (Beach Stay)
Head to Mirissa for a two-night stay. Snorkel with turtles, go surfing or diving, enjoy whale watching, visit Coconut Tree Hill, Weligama Beach, a turtle farm, and the Galle Dutch Fort.

Day 10
Mirissa to Airport
Transfer from Mirissa to Colombo Airport for your departure flight.',
'Air-conditioned Private Transport, Handpicked 4-Star Hotels, Daily Breakfast, Guided Safari Excursion, All Highway Tolls & Airport Transfers', 1, 5)
ON DUPLICATE KEY UPDATE 
    `title` = VALUES(`title`),
    `tag` = VALUES(`tag`),
    `duration` = VALUES(`duration`),
    `price` = VALUES(`price`),
    `min_persons` = VALUES(`min_persons`),
    `image` = VALUES(`image`),
    `description` = VALUES(`description`),
    `full_itinerary` = VALUES(`full_itinerary`),
    `inclusions` = VALUES(`inclusions`),
    `featured` = VALUES(`featured`),
    `sort_order` = VALUES(`sort_order`);
