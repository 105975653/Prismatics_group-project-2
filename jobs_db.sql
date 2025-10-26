-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 04:01 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_members`
--

CREATE TABLE `about_members` (
  `id` int(11) NOT NULL,
  `display_order` tinyint(4) DEFAULT 0,
  `full_name` varchar(100) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `assignment1_contrib` varchar(255) DEFAULT '',
  `assignment2_contrib` varchar(255) DEFAULT '',
  `quote_native` text NOT NULL,
  `quote_translation` text NOT NULL,
  `favorite_languages` varchar(255) DEFAULT '',
  `hidden_note` text DEFAULT '',
  `note_color` varchar(7) DEFAULT '#333333',
  `photo` varchar(255) DEFAULT '',
  `dream_job` varchar(100) DEFAULT '',
  `coding_snack` varchar(100) DEFAULT '',
  `hometown` varchar(100) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about_members`
--

INSERT INTO `about_members` (`id`, `display_order`, `full_name`, `student_id`, `assignment1_contrib`, `assignment2_contrib`, `quote_native`, `quote_translation`, `favorite_languages`, `hidden_note`, `note_color`, `photo`, `dream_job`, `coding_snack`, `hometown`) VALUES
(1, 1, 'Farhan Monirul Islam', '105975653', 'Apply page HTML, Full CSS ', 'Task numbers 1,3,4,5,6,7', '“কোড হলো হাস্যরসের মতো। যখন তোমাকে এটা ব্যাখ্যা করতে হয়, তখন সেটা খারাপ।”', 'Translation: Code is like humor. When you have to explain it, it’s bad.', 'Python, Ruby and HTML', 'Hidden note from Farhan: I loved working with the team — it was a chance to grow, solve challenges, and bring our ideas to life.', '#ff0000', 'farhan.jpg', 'Computer Scientist', 'Coffee', 'Dhaka'),
(2, 2, 'Thisum Rajaratne', '105736999', 'Home page html', 'Task 2', '“හොඳ නිර්මාණයක් හොඳ කේතයක් තරම්ම වැදගත්.”', 'Translation: \"Good design is just as important as good code\".', 'Ruby', 'Hidden note from Thisum: Being part of this project was exciting and taught me new skills while creating something meaningful together.', '#0400ff', 'thisum.png', 'Farmer far from city', 'Chocolate', 'Kandy'),
(3, 3, 'Chamathka Sandali Amarasekara', '105704983', 'Job Description page', 'NIL', '“තාක්ෂණය  මිනිසුන්ගේ ජීවිත වෙනස් කළ හැකියැයි මම විශ්වාස කරනවා, සහ මම එම වෙනසකට සම්බන්ධවීමට කැමතියි.”', 'Translation: \"I believe that technology can change people\'s lives, and I want to be involved in that change.\"', 'Python', 'Hidden note from Chamathka: I really enjoyed contributing and collaborating. Every step showed me the value of teamwork and creativity.', '#ee00ff', 'chamathka.png', 'Software Developer', 'Oreo', 'Colombo'),
(4, 4, 'Mahabub Hasan Rahat', '105952504', 'About page', 'NIL', '“কোডিং হলো যুক্তির মাধ্যমে প্রকাশিত সৃজনশীলতা।”', 'Translation: \"Coding is creativity expressed in logic.\"', 'Python', 'Hidden note from Rahat: This project was a rewarding journey where I learned, shared ideas, and grew alongside a great team.', '#333333', 'rahat.jpg', 'AI Engineer', 'Pizza', 'Dhaka');

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `eoi_id` int(11) NOT NULL,
  `job_ref` varchar(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `dob` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `street` varchar(40) NOT NULL,
  `suburb` varchar(40) NOT NULL,
  `state` varchar(3) NOT NULL,
  `postcode` char(4) NOT NULL,
  `country` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(12) NOT NULL,
  `workstyle` varchar(10) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `htmllevel` tinyint(4) DEFAULT NULL COMMENT 'used tinyint instead of just int for saving storage and faster reload',
  `csslevel` tinyint(4) DEFAULT NULL,
  `jslevel` tinyint(4) DEFAULT NULL,
  `designlevel` tinyint(4) DEFAULT NULL,
  `otherskills` text DEFAULT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `references` text DEFAULT NULL,
  `newsletter` varchar(100) DEFAULT NULL,
  `status` enum('New','Current','Final') DEFAULT 'New' COMMENT 'ENUM is a data type that allows only one value from a predefined list.',
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`eoi_id`, `job_ref`, `first_name`, `last_name`, `dob`, `gender`, `street`, `suburb`, `state`, `postcode`, `country`, `email`, `phone`, `workstyle`, `startdate`, `skills`, `htmllevel`, `csslevel`, `jslevel`, `designlevel`, `otherskills`, `resume`, `references`, `newsletter`, `status`, `submission_time`) VALUES
(1, 'PM32X', 'Farhan', 'Monirul Islam', '2006-11-15', 'male', '353 burwood', 'hawthorn', 'VIC', '3122', 'Kuwait', 'farhanswinburne@gmail.com', '66706591', 'remote', '2025-10-27', 'html, css, other', 5, 7, 10, 1, 'Python, Ruby and etc', 'uploads/Farhan_Monirul Islam_Resume_1761447407.pdf', 'Nick - Tutor of COS10026 at Swinburne University A', 'No', 'New', '2025-10-26 02:56:47'),
(2, 'DS14R', 'Thisum', 'Rajarnathe', '2005-12-11', 'male', '353 burwood', 'hawthorn', 'NSW', '3122', 'Kuwait', 'thisumrocks@gmail.com', '667065911', 'remote', '2025-10-30', 'html, css', 1, 1, 1, 1, '', '', '', 'No', 'New', '2025-10-26 02:58:20'),
(3, 'DA81E', 'Rahat', 'Mahabub', '2006-12-12', 'male', '353 burwood', 'hawthorn', 'QLD', '3122', 'Kuwait', 'rahat@gmail.com', '6670659113', 'remote', NULL, 'html, css, design', 1, 1, 1, 3, '', '', '', 'No', 'New', '2025-10-26 03:00:56');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_ref` varchar(5) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `team` varchar(80) NOT NULL,
  `location` varchar(60) NOT NULL,
  `salary` varchar(60) NOT NULL,
  `reports_to` varchar(120) NOT NULL,
  `short_description` text NOT NULL,
  `responsibilities` text NOT NULL,
  `requirements_essential` text NOT NULL,
  `requirements_preferable` text NOT NULL,
  `employment_type` varchar(30) DEFAULT 'Full-time',
  `closing_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_ref`, `job_title`, `team`, `location`, `salary`, `reports_to`, `short_description`, `responsibilities`, `requirements_essential`, `requirements_preferable`, `employment_type`, `closing_date`) VALUES
(1, 'PM32X', 'Product Manager', 'Product Team Position', 'Melbourne', '$90,000 – $110,000 p.a.', 'Head of Product', 'As a Product Manager at Prismatics, you’ll lead cross-functional teams to define, build, and launch new features that delight users and drive business growth. You’ll work closely with engineering, design, and marketing teams to turn ideas into impactful digital products.', 'Define and prioritize the product roadmap.\nCollaborate with designers and developers to deliver features on schedule.\nGather and analyze customer feedback to inform product decisions.\nMonitor performance metrics and drive continuous improvement.', 'Experience managing software products or digital platforms.\nExcellent communication and leadership skills.\nStrong understanding of Agile or Scrum methodologies.', 'Background in UX design or technical development.\nExperience with data analytics tools (Google Analytics, Mixpanel, etc.).', 'Full-time', '2025-12-31'),
(2, 'DS14R', 'UI/UX Designer', 'Design Team Position', 'Sydney', '$80,000 – $95,000 p.a.', 'Design Lead', 'As a UI/UX Designer at Prismatics, you’ll craft user-centered designs for web and mobile applications. You’ll collaborate with developers and product managers to turn creative concepts into intuitive, visually stunning interfaces that enhance user experience.', 'Create wireframes, prototypes, and visual mockups.\nConduct user research and usability testing.\nCollaborate with product and development teams to refine user flows.\nEnsure brand consistency across digital platforms.', 'Proficiency in Figma or Adobe XD.\nStrong portfolio demonstrating user-focused design.\nUnderstanding of responsive and accessible design principles.', 'Experience working in Agile teams.\nKnowledge of front-end development (HTML, CSS, JavaScript).', 'Full-time', '2025-12-31'),
(3, 'DA81E', 'Data Analyst', 'Insights & Analytics Team Position', 'Melbourne', '$85,000 – $100,000 p.a.', 'Head of Insights', 'The Insights Team transforms data into decisions. As a Data Analyst, you’ll work with large datasets to uncover trends, measure success, and inform strategic planning. You’ll collaborate with multiple teams to visualize data and communicate actionable insights that guide company growth.', 'Collect, clean, and analyze data from multiple sources.\nDevelop dashboards and visualizations.\nPresent insights to stakeholders.\nSupport data-driven decision making.', 'Proficiency in SQL, Excel, and visualization tools like Power BI or Tableau.', 'Experience with Python or R. \n understanding of statistical modeling.', 'Full-time', '2025-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`) VALUES
(1, 'Admin', 'c1c224b03cd9bc7b6a86d77f5dace40191766c485cd55dc48caf9ac873335d6f');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_members`
--
ALTER TABLE `about_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`eoi_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_members`
--
ALTER TABLE `about_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `eoi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
