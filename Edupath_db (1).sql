-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 24, 2025 at 10:43 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Edupath_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accreditation`
--

CREATE TABLE `accreditation` (
  `id` int(11) NOT NULL,
  `uni_id` int(11) NOT NULL,
  `best_courses` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accreditation`
--

INSERT INTO `accreditation` (`id`, `uni_id`, `best_courses`) VALUES
(16, 41, '[\"Business\", \"Law\", \"Engineering\", \"Humanities\", \"Social Sciences\"]'),
(17, 49, '[\"Public Administration\", \"Law\", \"Information Technology\"]'),
(18, 43, '[\"Distance Learning\", \"Diverse Programmes\"]'),
(19, 44, '[\"Social Sciences\", \"Business\"]'),
(20, 45, '[\"Business\", \"Public Management\", \"Economics\", \"Law\"]'),
(21, 42, '[\"Agricultural Sciences\", \"Environmental Studies\"]'),
(22, 47, '[\"Medicine\", \"Health Sciences\", \"Allied Health\"]'),
(23, 46, '[\"Science\", \"Engineering\", \"Technology\"]'),
(24, 48, '[\"Architecture\", \"Urban Planning\", \"Surveying\"]'),
(25, 82, '[\"Agriculture\", \"Veterinary Sciences\"]'),
(26, 50, '[\"Engineering\", \"Technology\"]'),
(27, 51, '[\"Cooperative Business\", \"Entrepreneurship\"]'),
(28, 53, '[\"Medicine\", \"Health Sciences\"]'),
(29, 57, '[\"Theology\", \"Social Sciences\", \"Community Development\"]'),
(30, 55, '[\"Business\", \"Education\", \"Engineering\"]'),
(31, 56, '[\"Tourism\", \"Business\", \"Creative Arts\"]'),
(32, 60, '[\"Business\", \"Education\", \"Technology\"]'),
(33, 62, '[\"Business Administration\", \"Information Technology\", \"Applied Sciences\"]'),
(34, 63, '[\"Law\", \"Business\", \"Humanities\"]'),
(35, 66, '[\"Education\", \"Media\", \"Social Sciences\"]'),
(36, 61, '[\"Health Sciences\", \"Business\", \"Technology\"]'),
(37, 59, '[\"Medicine\", \"Nursing\", \"Health Sciences\"]'),
(38, 68, '[\"International Business\", \"Entrepreneurship\"]'),
(39, 76, '[\"Education\", \"Arts\", \"Science\"]'),
(40, 77, '[\"Teacher Education\", \"Social Sciences\"]'),
(41, 67, '[\"Business\", \"Law\", \"Technology\"]'),
(42, 83, '[\"Medicine\", \"Health Sciences\"]'),
(43, 69, '[\"Law\", \"Business\", \"Humanities\"]'),
(44, 87, '[\"Health Sciences\", \"Nursing\"]'),
(45, 84, '[\"Business\", \"Social Sciences\", \"Theology\"]'),
(46, 88, '[\"Business\", \"Tourism\", \"Social Sciences\"]'),
(47, 72, '[\"Business\", \"Law\", \"Information Technology\"]');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `university_name` varchar(255) DEFAULT NULL,
  `program_name` varchar(255) NOT NULL,
  `admission_requirements` text,
  `program_duration` int(11) DEFAULT NULL,
  `admission_capacity` int(11) DEFAULT NULL,
  `tuition_fees` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_pass` text NOT NULL,
  `cetificate` text NOT NULL,
  `specialSubject` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `university_name`, `program_name`, `admission_requirements`, `program_duration`, `admission_capacity`, `tuition_fees`, `created_at`, `total_pass`, `cetificate`, `specialSubject`) VALUES
(1, 'Abdulrahman Al-Sumait University (SUMAIT)', 'Technician Certificate in Counselling and\\nPsychology', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious subjects or National\\nVocational Award (NVA) Level III with At Least two Passes in Certificate\\nof Secondary Education Examination (CSEE).', 2, 100, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:52', '4', 'CSEE', '[]'),
(2, 'Abdulrahman Al-Sumait University (SUMAIT)', 'Diploma in Counselling and Psychology', 'Holders of: NTA level 4 in Counseling Psychology', 1, 100, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:52', '', '', ''),
(3, 'Abdulrahman Al-Sumait University (SUMAIT)', 'Ordinary Diploma in Arts with Education', 'Holders of Certificate in Arts with Education or Certificate of Teachers\\nEducation Grade III A', 2, 100, 'Local Fee: TSH. 750,000/= ,\\nForeigner Fee: USD 312/=', '2025-01-29 17:00:52', '', '', ''),
(4, 'Abdulrahman Al-Sumait University (SUMAIT)', 'Ordinary Diploma in Business Information\\nTechnology', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects including Basic\\nMathematics and English Language OR National Vocational Award\\n(NVA) Level III or Trade Test Grade I with a Certificate of Secondary\\nEducation Examination (CSEE)', 3, 100, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:52', '4', 'CSEE', '[Mathematics, English]'),
(5, 'Abdulrahman Al-Sumait University (SUMAIT)', 'Ordinary Diploma in Computing and Information', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in Non-religious subjects including passes\\nin Basic Mathematics and English Language.', 3, 200, 'Local Fee: TSH. 750,000/=  Foreigner Fee: USD 312/=', '2025-01-29 17:00:52', '4', 'CSEE', '[Mathematics,English]'),
(6, 'Abdulrahman Al-Sumait University (SUMAIT)', 'Ordinary Diploma in Office Administration', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects including English\\nLanguage.', 3, 100, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:52', '4', 'CSEE', '[English]'),
(7, 'Abdulrahman Al-Sumait University (SUMAIT)', 'Ordinary Diploma in Science with Education', 'holder of basic technician in education science', 2, 100, 'Local Fee: TSH. 1,100,000/= ,\\nForeigner Fee: USD 458/=', '2025-01-29 17:00:52', '', '', ''),
(8, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Accountancy', 'Holders of Basic Technician Certificate (NTA Level 4) in Accountancy,\\nFinance and Banking OR Advanced Certificate of Secondary Education\\nExamination (ACSEE) with at least one Principal pass and one\\nSubsidiary in Principal subjects.', 2, 200, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:52', '', '', ''),
(9, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Business Administration', 'Holders of Basic Technician Certificate (NTA Level 4) in Business\\nAdministration, Finance and Banking, Marketing OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at least\\none Principal pass and one Subsidiary in Principal subjects.', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:52', '', '', ''),
(10, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Community Development', 'Holders of Basic Technician Certificate (NTA Level 4 ) in Community\\nDevelopment, Social Work, Gender, Youth Work OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at least\\none Principal pass and one Subsidiary in Principal subjects.', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:52', '', '', ''),
(11, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Entrepreneurship Development', 'Holders of Basic Technician Certificate (NTA Level 4) in\\nEntrepreneurship Development OR Advanced Certificate of Secondary\\nEducation Examination (ACSEE) with at least one Principal pass and\\none Subsidiary in Principal subjects.', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:52', '', '', ''),
(12, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Human Resources Management', 'Holders of Basic Technician certificate (NTA Level 4) in Human\\nResource, Local Government Administration, Social work, Community\\nDevelopment, Business Management OR Advanced Certificate of\\nSecondary Education Examination (ACSEE) with at least one Principal\\npass and one Subsidiary in Principal subjects.', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:53', '', '', ''),
(13, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Information Communication\\nTechnology', 'Holders of Basic Technician Certificate (NTA Level 4) in Computing and\\nInformation Technology OR Advanced Certificate of Secondary\\nEducation (ACSEE) with at least one Principal pass and one Subsidiary\\nin Principal subjects', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:53', '', '', ''),
(14, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Law', 'Holders of advanced certificate of secondary', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:53', '1', 'ACSEE', '[]'),
(15, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Library Studies, Records Management\\nwith Ict', 'Holders of Basic Technician Certificate (NTA Level 4) in Records\\nManagement, Medical Records, Library and information studies OR\\nAdvanced Certificate of Secondary Education Examination (ACSEE)\\nwith at least one Principal pass and one Subsidiary in Principal\\nsubjects', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:53', '2', 'ACSEE', '[]'),
(16, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Marketing Management', 'Holders of Basic Technician Certificate (NTA Level 4) in Marketing,\\nPublic Relation, Business Administration, Accounting and Finance,\\nEconomics OR Advanced Certificate of Secondary Education\\nExamination (ACSEE) with at least one Principal pass and one\\nSubsidiary in Principal subjects.', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:53', '2', 'ACSEE', '[]'),
(17, 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Diploma in Procurement and Supply Chain\\nManagement', 'Holders of Basic Technician Certificate (NTA Level 4) in Procurement\\nand Supply, Logistics, Clearing and Forwarding, Purchasing and\\nInventory OR Advanced Certificate of Secondary Education\\nExamination (ACSEE) with at least one Principal pass and one\\nSubsidiary in Principal subjects.', 2, 200, 'Local Fee: TSH. 1,321,000/=', '2025-01-29 17:00:53', '2', 'ACSEE', '[]'),
(18, 'Catholic University of Health and Allied Sciences (CUHAS)', 'Diploma in Diagnostic Radiography', 'Holders of Certificate in Diagnostic Radiology with passes in science\\nsubjects including passes in Biology, Chemistry and Physics in\\nCertificate of Secondary Education Examination (CSEE)', 2, 120, 'Local Fee: TSH. 1,650,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Biology, Chemistry, Physics]'),
(19, 'Dar es Salaam University College of Education (DUCE)', 'Ordinary Diploma in Educational Laboratory\\nScience Technology', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in Non-religious subjects including two\\npasses in Physics, chemistry, biology, Geography and Mathematics', 3, 50, 'Local Fee: TSH. 1,000,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Physics, Chemistry, Biology, Geography, Mathematics]'),
(20, 'Hubert Kairuki Memorial University (HK)', 'Ordinary Diploma in Social Work', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects', 3, 100, 'Local Fee: TSH. 1,700,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(21, 'Jordan University College (JUCo)', 'Diploma in Law', 'Holders of Certificate in Law OR Holders of Advanced Certificate of\\nSecondary Education Examination (CSEE) with one Principal Pass and\\nOne Subsidiary in Principal subjects', 2, 80, 'Local Fee: TSH. 1,180,000/=', '2025-01-29 17:00:53', '1', 'ACSEE', '[]'),
(22, 'Jordan University College (JUCo)', 'Diploma in Psychology and Counseling', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects and Technician\\nCertificate in Psychology and Counselling OR Advanced Certificate of\\nSecondary Education Examination (ACSEE) with one Principal pass and\\none subsidiary in Principal subjects', 2, 80, 'Local Fee: TSH. 1,120,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(23, 'Jordan University College (JUCo)', 'Ordinary Diploma in Accountancy', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with a Certificate of Secondary Education\\nExamination(CSEE)', 3, 80, 'Local Fee: TSH. 1,180,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(24, 'Jordan University College (JUCo)', 'Ordinary Diploma in Business Administration', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with two passes in Certificate of Secondary\\nEducation Examination(CSEE)', 3, 80, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(25, 'Jordan University College (JUCo)', 'Ordinary Diploma in Business Administration and Tourism Management', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with a Certificate of Secondary Education\\nExamination(CSEE)', 3, 100, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(26, 'Jordan University College (JUCo)', 'Ordinary Diploma in Community Development', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with a Certificate of Secondary Education\\nExamination(CSEE)', 3, 80, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(27, 'Jordan University College (JUCo)', 'Ordinary Diploma in Computer Science', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA) III/Trade Test II with a Certificate of\\nSecondary Education Examination (CSEE).', 3, 80, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(28, 'Jordan University College (JUCo)', 'Ordinary Diploma in Education with Religious\\nStudies', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with a Certificate of Secondary Education\\nExamination (CSEE)', 3, 110, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(29, 'Jordan University College (JUCo)', 'Ordinary Diploma in Information and\\nCommunication Technology', 'Holder of Certificate of Secondary Education Examination (CSEE) with\\nat least four (4) passes in non religious subjects OR National Vocational Award (NVA) III/Trade Test II with a Certificate of\\nSecondary Education Examination (CSEE).', 3, 90, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(30, 'Jordan University College (JUCo)', 'Ordinary Diploma in Law', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with a Certificate of Secondary Education\\nExamination(CSEE)', 3, 80, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(31, 'Jordan University College (JUCo)', 'Ordinary Diploma in Library and Information\\nStudies', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with a Certificate of Secondary Education\\nExamination(CSEE)', 3, 80, 'Local Fee: TSH. 1,180,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(32, 'Jordan University College (JUCo)', 'Ordinary Diploma in Procurement and Supply\\nChain Management', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVAIII) with a Certificate of Secondary Education\\nExamination(CSEE)', 3, 80, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(33, 'Jordan University College (JUCo)', 'Ordinary Diploma in Psychology and Counselling', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects', 3, 80, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '', '', ''),
(34, 'Jordan University College (JUCo)', 'Ordinary Diploma in Records, Archives and\\nInformation Management', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA III) with a Certificate of Secondary Education\\nExamination (CSEE)', 3, 80, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '', '', ''),
(35, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Business Administration', 'Holders of Certificate in Business Administration, Accountancy,\\nEconomics, Banking and Finance, Marketing, Human Resource,\\nProcurement and Supplies, Law and Tax, Local Government\\nAdministration OR Advanced Certificate of Secondary Education\\nExamination (ACSEE) with at least one Principal pass and one\\nSubsidiary in Principal subjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '2', 'ACSEE', '[]'),
(36, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Computer Science', 'Holders of Certificate in Information Technology, Computer Science\\nInformation Technology, Business Information Technology OR Holder of\\nAdvanced Certificate of Secondary Education Examination (ACSEE)\\nwith one Principal pass and Subsidiary pass in principal subjects', 2, 800, 'Local Fee: TSH. 1,560,000/=', '2025-01-29 17:00:53', '', '', ''),
(37, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Guidance and Counseling', 'Holders of Certificate in Guidance and Counseling OR Holders of\\nAdvanced Certificate of Secondary Education Examination ( ACSEE) with at least one Principal Pass and one Subsidiary in Principal\\nsubjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '', '', ''),
(38, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Human Resource Management', 'Holders of Certificate in Business Administration, Accountancy, Human\\nResource, Supplies and Procurement OR Advanced Certificate of\\nSecondary Education Examination (ACSEE) with One Principal Pass and\\none Subsidiary in Principal subjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '', '', ''),
(39, 'Kampala International University in Tanzania (KIUT)', 'Diploma in in Conflict Resolution and Peace\\nStudies', 'Holders of Certificate in Conflict Resolution and Peace Studies,\\nCounselling Psychology OR Advanced Certificate of Secondary\\nEducation Examination (ACSEE) with one Principal Pass and One\\nSubsidiary in Principal Subjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '', '', ''),
(40, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Law', 'Holders of Certificate in Law, Criminal Investigation, police science,\\nCriminal Investigation, Police Science, OR Advanced Certificate of\\nSecondary Education Examination (ACSEE) with one Principal Pass and\\none Subsidiary in Principal subjects', 2, 800, 'Local Fee: TSH. 1,400,000/=', '2025-01-29 17:00:53', '', '', ''),
(41, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Marketing Management', 'Holders of Certificate in Business Administration, Accountancy, Human\\nResource Management, Marketing Management, Marketing & Public\\nRelation OR Advanced Certificate of Secondary Education Examination\\n(ACSEE) with at least one Principal Pass and one Subsidiary in Principal\\nsubjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '', '', ''),
(42, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Public Administration', 'Holders of Certificate in Public Administration, Local Government\\nAdministration, Business Administration, Human Resource\\nmanagement, Secretarial services, Community Development,\\nCommunity Health and Public Relation OR Advanced Certificate of\\nSecondary Education Examination (ACSEE) with at least one Principal\\nPass and one Subsidiary in Principal subjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '', '', ''),
(43, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Social Work', 'Holders of Certificate in Social Work, Community Health, theology,\\nCommunity Development, sociology, Social Protection OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at least\\none Principal Pass and one Subsidiary pass in Principal Subjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '', '', ''),
(44, 'Kampala International University in Tanzania (KIUT)', 'Diploma in Supplies and Procurement Management', 'Holders of Certificate in Procurement and Supply, Business\\nAdministration, Logistics, Perchance and Inventory/ clearing and\\nforwarding OR Advanced Certificate of Secondary Education Examination (ACSEE) with at least one Principal Pass and one\\nSubsidiary in Principal subjects', 2, 500, 'Local Fee: TSH. 1,160,000/=', '2025-01-29 17:00:53', '', '', ''),
(45, 'Kampala International University in Tanzania (KIUT)', 'Ordinary Diploma in Clinical Medicine', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith four (4) passes in non-religious subjects including Chemistry,\\nBiology and Physics/Engineering Sciences. A pass in Basic Mathematics\\nand English Language is an added advantage.', 3, 300, 'Local Fee: TSH. 1,900,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Chemistry, Biology, Physics]'),
(46, 'Kampala International University in Tanzania (KIUT)', 'Ordinary Diploma in Environmental Health\\nSciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nBiology Chemistry and Physics/Engineering Sciences.', 3, 100, 'Local Fee: TSH. 1,900,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Chemistry, Biology, Physics]'),
(47, 'Kampala International University in Tanzania (KIUT)', 'Ordinary Diploma in Medical Laboratory Sciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nChemistry, Biology, Physics/Engineering sciences/Basic Mathematics\\nand English Language.', 3, 200, 'Local Fee: TSH. 1,900,000/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[Chemistry, Biology, Physics, Mathematics, English]'),
(48, 'Kampala International University in Tanzania (KIUT)', 'Ordinary Diploma in Pharmaceutical Sciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nChemistry and Biology. A Pass in Basic Mathematics and English\\nLanguage Is An Added Advantage.', 3, 400, 'Local Fee: TSH. 1,900,000/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[Chemistry, Biology]'),
(49, 'Kilimanjaro Christian Medical University College (KCMUCo)', 'Diploma in Occupational Therapy', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non religious Subjects including C pass\\nin Biology and any two subjects in Chemistry, Mathematics,\\nGeography, Economics, Physics/Physical Sciences.', 3, 50, 'Local Fee: TSH. 2,380,000/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[Chemistry, Biology, Physics, Mathematics, Geography]'),
(50, 'Kilimanjaro Christian Medical University College (KCMUCo)', 'Ordinary Diploma in Medical Laboratory Sciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nChemistry, Biology, Physics/Engineering sciences/Basic Mathematics\\nand English Language.', 3, 100, 'Local Fee: TSH. 2,980,000/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[Chemistry, Biology, Physics, Mathematics, English]'),
(51, 'Marian University College (MARUCo)', 'Ordinary Diploma in Pharmaceutical Sciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nChemistry and Biology. A Pass in Basic Mathematics and English\\nLanguage Is An Added Advantage.', 3, 100, 'Local Fee: TSH. 2,250,400/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[Chemistry, Biology]'),
(52, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Architecture', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith Four (4) Passes in non-religious Subjects, three of which must be\\nMathematics, Physics, Chemistry/Geography', 3, 50, 'Local Fee: TSH. 900,000/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[Mathematics, Physics, Chemistry, Geography]'),
(53, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Bio Medical Equipment Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) Passes in non-religious Subjects including\\nPhysics,Chemistry and Basic Mathematics.', 3, 50, 'Local Fee: TSH. 1,000,000/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[Physics, Chemistry, Mathematics]'),
(54, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Business Administration', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects', 3, 50, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '4', 'CSEE ', '[]'),
(55, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Civil Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non-religious subjects including\\nPhysics, Chemistry and Basic Mathematics.', 3, 50, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Physics, Chemistry, Mathematics]'),
(56, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Computer Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non-religious subjects including\\nPhysics, Chemistry and Basic Mathematics subjects.', 3, 50, 'Local Fee: TSH. 1,000,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Physics, Chemistry, Mathematics]'),
(57, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Computer Science', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith Four (4) passes in non-religious subjects.', 3, 100, 'Local Fee: TSH. 1,500,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[]'),
(58, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Electric and Electronics Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non- religious subjects including a pass in Basic Mathematics and Physics/Engineering Sciences and Chemistry', 3, 100, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Mathematics, Physics, Chemistry]'),
(59, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Electrical Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non- religious subjects including a pass\\nin Basic Mathematics and Physics/Engineering Sciences', 3, 100, 'Local Fee: TSH. 1,400,000/=', '2025-01-29 17:00:53', '4', 'CSEE', '[Mathematics, Physics]'),
(60, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Electronics and Telecomunication\\nEngineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non-religious subjects including passes\\nin physics, Chemistry and Basic Mathematics.', 3, 100, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '', '', ''),
(61, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Food Science and Technology', 'Certificate of Secondary Education Examination (CSEE) with at least\\nfour passes in non-religious subjects three of which must be in\\nMathematics, Physics/Chemistry or Biology. OR Certificate of\\nSecondary Education Examination (CSEE) from Technical Secondary\\nSchool with a pass in at least four passes in non-religious subjects\\nthree of which must be in Mathematics, Engineering\\nScience/Chemistry and Biology and one of the following subjects:\\nArchitectural Drafting/Building, Construction, Electrical Engineering,\\nScience/Electrical Drafting or Workshop Technology/Mechanical\\nDrafting. OR Certificate of Secondary Education Examination (CSEE)\\nwith at least four passes in non-religious subjects two of which must\\nbe in the following subjects Mathematics, Physics, Chemistry or\\nBiology with Trade Test Grade 1 or National Vocational Award (NVA)\\nLevel III issued by VETA.', 3, 100, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '', '', ''),
(62, 'Mbeya University of Science and Technology (MUST)', 'Diploma in High Way Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) Passes in non-religious Subjects including\\nPhysics, Chemistry and Basic Mathematics.', 3, 100, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '', '', ''),
(63, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Information and Communication\\nTechnology', 'Holder of Certificate of Secondary Education with at least Four (4)\\npasses in non-religious subject including Basic Mathematics and\\nEnglish Language.', 3, 100, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '', '', ''),
(64, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Laboratory Sciences Technology', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non-religious subjects including\\nPhysics, Chemistry and Basic Mathematics.', 3, 100, 'Local Fee: TSH. 900,000/=', '2025-01-29 17:00:53', '', '', ''),
(65, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Mechanical Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non-religious subjects including passes\\nin physics, Chemistry and Basic Mathematics.', 3, 100, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '', '', ''),
(66, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Mechatronics Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non-religious subjects including\\nPhysics, Chemistry and Basic Mathematics.', 3, 100, 'Local Fee: TSH. 600,000/=', '2025-01-29 17:00:53', '', '', ''),
(67, 'Mbeya University of Science and Technology (MUST)', 'Diploma in Mining Engineering', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) passes in non-religious subjects including\\nPhysics, Chemistry and Basic Mathematics.', 3, 100, 'Local Fee: TSH. 1,400,000/=', '2025-01-29 17:00:53', '', '', ''),
(68, 'Moshi Cooperative University (MoCU)', 'Diploma in Business Information and\\nCommunication Technology', 'Holders of Certificate in Business and Information Technology OR\\nAdvanced Certificate of Secondary Education Examination (ACSEE)\\nwith at least one principal pass and one subsidiary in Principal\\nsubjects', 2, 150, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:53', '', '', ''),
(69, 'Moshi Cooperative University (MoCU)', 'Diploma in Co-operative Management and\\nAccounting', 'Holders of Certificate in Cooperative Management and Accounting OR\\nAdvanced Certificate of Secondary Education Examination (ACSEE)\\nwith at least one principal pass and one subsidiary in Principal\\nsubjects', 2, 300, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:53', '', '', ''),
(70, 'Moshi Cooperative University (MoCU)', 'Diploma in Enterprise Management', 'Holders of Certificate in Enterprise Management OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at least\\none principal pass and one subsidiary Principal subjects', 2, 250, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:53', '', '', ''),
(71, 'Moshi Cooperative University (MoCU)', 'Diploma in Human Resource Management', 'Holders of Certificate in Human Resource Management OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at least\\none principal pass and one subsidiary in Principal subjects', 2, 300, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:53', '', '', ''),
(72, 'Moshi Cooperative University (MoCU)', 'Diploma in Library and Archival Studies', 'Holders of Certificate in Library and Archival Studies OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at least\\none principal pass and one subsidiary Principal subjects', 2, 150, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:53', '', '', ''),
(73, 'Moshi Cooperative University (MoCU)', 'Diploma in Microfinance Management', 'Holders of Certificate in Accounting, Banking and Finance OR\\nAdvanced Certificate of Secondary Education Examination (ACSEE) with at least one principal pass and one subsidiary Principal subjects', 2, 250, 'Local Fee: TSH. 750,000/=', '2025-01-29 17:00:53', '', '', ''),
(74, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Diagnostic Radiography (ddr) - Evening', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects including English\\nand at least three (3) Credits in either Physics, Chemistry, Biology and\\nMathematics. Physics is a major subject.', 3, 35, 'Local Fee: TSH. 1,527,400/=', '2025-01-29 17:00:53', '', '', ''),
(75, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Diagnostic Radiography - Ddr - Regular', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects including English\\nand at least three (3) Credits one in Physics and two (2) others from\\neither Chemistry, Biology and Mathematics.', 3, 35, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(76, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Environmental Health Sciences -\\n(DEHS) (dar Es Salaam)', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) Passes in non-religious Subjects, including\\nEnglish and at least Three (3) Credits one in Mathematics and two\\nothers from Physics, Chemistry or Biology.', 3, 75, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(77, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Environmental Health Sciences (DEHS)\\n- Mpwapwa, Evening', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) Passes in non-religious Subjects, including\\nEnglish and at least three (3) Credits one in Mathematics and two\\nothers from Physics, Chemistry and Biology.', 3, 50, 'Local Fee: TSH. 1,527,400/=', '2025-01-29 17:00:53', '', '', ''),
(78, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Environmental Health Sciences (DEHS)\\n- Tanga - Evening', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) Passes in non-religious subjects, including\\nEnglish and at least three (3) Credits one in Mathematics and two\\nothers from Physics, Chemistry and Biology .', 3, 50, 'Local Fee: TSH. 1,527,400/=', '2025-01-29 17:00:53', '', '', ''),
(79, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Environmental Health Sciences (DEHS)\\n- Tanga - Regular', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) Passes in non-religious Subjects, including\\nEnglish and at least Three (3) Credits one in Mathematics and two\\nothers from Physics, Chemistry and Biology', 3, 50, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(80, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Environmental Health Sciences - DEHS\\n- (Dar es Salaam) - Evening', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) Passes in non-religious Subjects, including\\nEnglish and at least Three (3) Credits one in Mathematics and two\\nothers from Physics, Chemistry and Biology .', 3, 75, 'Local Fee: TSH. 1,527,400/=', '2025-01-29 17:00:53', '', '', ''),
(81, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Environmental Health Sciences - Dehs -\\nMpwapwa', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) Passes in non-religious Subjects, including\\nEnglish and at least Three (3) Credits one in Mathematics and two\\nothers from Physics, Chemistry and Biology.', 3, 50, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(82, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Medical Laboratory Sciences (DMLS)', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Five (5) Passes in non-religious Subjects including\\nMathematics and English and at least Three (3) Credits in Biology,\\nChemistry and Physics.', 3, 50, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(83, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Medical Laboratory Sciences (dmls) -\\nEvening', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Five (5) Passes in non-religious Subjects, including\\nMathematics and English and at least Three (3) Credits in Biology,\\nChemistry and Physics.', 3, 50, 'Local Fee: TSH. 1,527,400/=', '2025-01-29 17:00:53', '', '', ''),
(84, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Nursing (DN) - Evening', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Five (5) Passes in non-religious Subjects including\\nMathematics and English and at least Three (3) Credits in Biology,\\nChemistry and Physics,', 3, 50, 'Local Fee: TSH. 1,527,400/=', '2025-01-29 17:00:53', '', '', ''),
(85, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Nursing - DN - Regular', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Five (5) Passes in non-religious Subjects, including\\nPhysics, Mathematics and English and Credit passes in Biology and\\nChemistry.', 3, 50, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(86, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Orthopaedic Technology (DOT) - KCMC', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Four (4) Passes in non-religious Subjects with at least\\nThree (3) Credits from Physics, Chemistry, Biology or Mathematics.\\nCredit pass in Engineering subjects is also acceptable as the third\\ncredit pass.', 3, 15, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(87, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Pharmaceutical Sciences (DPS) -\\nEvening', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Fiver (5) Passes in non-religious subjects including Biology and Physics, and at least Three Credits in Mathematics,\\nChemistry and English.', 3, 35, 'Local Fee: TSH. 1,527,400/=', '2025-01-29 17:00:53', '', '', ''),
(88, 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Diploma in Pharmaceutical Sciences (DPS) -\\nRegular', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least Five (5) Passes in non-religious Subjects including Biology\\nand Physics, and at least Three (3) Credits in Mathematics, Chemistry\\nand English.', 3, 35, 'Local Fee: TSH. 777,400/=', '2025-01-29 17:00:53', '', '', ''),
(89, 'Muslim University of Morogoro (MUM)', 'Diploma in Islamic Banking and Finance', 'Holders of Certificate in Islamic Banking and Finance OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at least\\none Principal pass and one Subsidiary in Principal subjects', 2, 100, 'Local Fee: TSH. 1,000,000/= ,\\nForeigner Fee: USD 1,000/=', '2025-01-29 17:00:53', '2', 'ACSEE', '[]'),
(90, 'Muslim University of Morogoro (MUM)', 'Diploma in Law and Shariah', 'Holders of Certificate in Law and Shariah OR Advanced Certificate of\\nSecondary Education Examination (ACSEE) with at least one Principal\\npass and one Subsidiary in Principal subjects', 2, 100, 'Local Fee: TSH. 1,000,000/= ,\\nForeigner Fee: USD 1,000/=', '2025-01-29 17:00:53', '2', 'ACSEE', '[]'),
(91, 'Muslim University of Morogoro (MUM)', 'Diploma in Procurement and Logistics\\nManagement', 'Holders of Certificate in Procurement and Supply,Logistics,Purchasing\\nand Inventory OR Advanced Certificate of Secondary Education\\nExamination (ACSEE) with at least one Principal pass and one\\nSubsidiary in Principal subjects', 2, 100, 'Local Fee: TSH. 1,000,000/= ,\\nForeigner Fee: USD 1,000/=', '2025-01-29 17:00:53', '', '', ''),
(92, 'Muslim University of Morogoro (MUM)', 'Ordinary Diploma in Accountancy', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects including Basic\\nMathematics OR National Vocational Award (NVA) Level III with a\\nCertificate of Secondary Education Examination (CSEE)', 3, 150, 'Local Fee: TSH. 920,000/= ,\\nForeigner Fee: USD 920/=', '2025-01-29 17:00:53', '', '', ''),
(93, 'Muslim University of Morogoro (MUM)', 'Ordinary Diploma in Business Administration', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National Vocational Award (NVA)Level III with a Certificate of Secondary\\nEducation Examination (CSEE)', 3, 150, 'Local Fee: TSH. 920,000/= ,\\nForeigner Fee: USD 920/=', '2025-01-29 17:00:53', '', '', ''),
(94, 'Muslim University of Morogoro (MUM)', 'Ordinary Diploma in Journalism', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects OR National\\nVocational Award (NVA) Level III with a Certificate of Secondary\\nEducation Examination (CSEE)', 3, 100, 'Local Fee: TSH. 1,000,000/= ,\\nForeigner Fee: USD 1,000/=', '2025-01-29 17:00:53', '', '', ''),
(95, 'Muslim University of Morogoro (MUM)', 'Ordinary Diploma in Medical Laboratory Sciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nChemistry, Biology, Physics/Engineering sciences/Basic Mathematics\\nand English Language.', 3, 100, 'Local Fee: TSH. 2,200,000/= ,\\nForeigner Fee: USD 2,200/=', '2025-01-29 17:00:53', '', '', ''),
(96, 'Muslim University of Morogoro (MUM)', 'Ordinary Diploma in Science and Laboratory\\nTechnology', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith Four (4) passes in non-religious subjects including Two (2) Passes\\nin Biology, Chemistry, Physics/Engineering Sciences.', 3, 250, 'Local Fee: TSH. 1,200,000/= ,\\nForeigner Fee: USD 1,200/=', '2025-01-29 17:00:53', '', '', ''),
(97, 'Mwenge Catholic University (MWECAU)', 'Ordinary Diploma in Accountancy', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects including Basic\\nMathematics', 3, 100, 'Local Fee: TSH. 880,000/=', '2025-01-29 17:00:53', '', '', ''),
(98, 'Mwenge Catholic University (MWECAU)', 'Ordinary Diploma in Business Administration', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in Non-Religious Subjects OR National\\nVocational Award (NVA) III with a Certificate of Secondary Education\\nExamination (CSEE)', 3, 100, 'Local Fee: TSH. 880,000/=', '2025-01-29 17:00:53', '', '', ''),
(99, 'Mwenge Catholic University (MWECAU)', 'Ordinary Diploma in Computing and Information\\nTechnology', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious subjects including Basic\\nMathematics and English Language or National Vocational Award (NVA\\nLevel III) with a Certificate of Secondary Education Examination\\n(CSEE)', 3, 100, 'Local Fee: TSH. 880,000/=', '2025-01-29 17:00:53', '', '', ''),
(100, 'Mwenge Catholic University (MWECAU)', 'Ordinary Diploma in Law', 'Holders of certificate of secondary education examination (CSEE) with\\nat least four (4) passes in non- religious subjects including English\\nLanguage', 3, 100, 'Local Fee: TSH. 880,000/=', '2025-01-29 17:00:53', '', '', ''),
(101, 'Mwenge Catholic University (MWECAU)', 'Ordinary Diploma in Procurement and Supply', 'Holders of Certificate of Secondary Education Examination ( CSEE )\\nwith at least four (4) passes in non- religious subjects', 3, 80, 'Local Fee: TSH. 880,000/=', '2025-01-29 17:00:53', '', '', ''),
(102, 'Mwenge Catholic University (MWECAU)', 'Ordinary Diploma in Records, Archives and\\nInformation Management', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non religious subjects OR National\\nVocational Award (NVA) Level III with a Certificate of Secondary\\nEducation Examination (CSEE)', 3, 100, 'Local Fee: TSH. 880,000/=', '2025-01-29 17:00:53', '', '', ''),
(103, 'Mwenge Catholic University (MWECAU)', 'Ordinary Diploma in Social Work', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects', 3, 100, 'Local Fee: TSH. 880,000/=', '2025-01-29 17:00:53', '', '', ''),
(104, 'Mzumbe University (MU)', 'Diploma in Applied Statistics', 'Holders of Certificate in Applied Statistics OR Advanced Certificate of\\nSecondary Education with at least one Principal pass and one\\nSubsidiary in Principal subjects', 2, 75, 'Local Fee: TSH. 1,200,000/= ,\\nForeigner Fee: USD 2,500/=', '2025-01-29 17:00:53', '', '', ''),
(105, 'Mzumbe University (MU)', 'Diploma in Applied Statistics (das)', 'Holders of Certificate in Applied Statistics OR Advanced Certificate of\\nSecondary Education Examination (ACSEE) with One Principal Pass in\\nEither Chemistry, Biology or Advanced Mathematics and One\\nSubsidiary in any Subject', 2, 75, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '', '', ''),
(106, 'Mzumbe University (MU)', 'Diploma in Information Technology (dit)', 'Holders of Certificate in Information Technology, Computer\\nEngineering OR Advanced Certificate of Secondary Education\\nExamination (ACSEE) with One Principal Pass in either Physics,\\nChemistry, Biology or Advanced Mathematics and One Subsidiary', 2, 75, 'Local Fee: TSH. 1,200,000/=', '2025-01-29 17:00:53', '', '', ''),
(107, 'Open University of Tanzania (OUT)', 'Basic Technician Certificate in Entrepreneurship', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects', 1, 100, 'Local Fee: TSH. 850,000/=', '2025-01-29 17:00:53', '', '', ''),
(108, 'Open University of Tanzania (OUT)', 'Basic Technician Certificate in Hair and Beauty', 'Holders of certificate of Secondary Education Examination (CSEE) with\\nfour (4) passes in non-religious subjects', 1, 100, 'Local Fee: TSH. 850,000/=', '2025-01-29 17:00:53', '', '', ''),
(109, 'Open University of Tanzania (OUT)', 'Diploma in Common Wealth Youth Programme', 'Holders of Technician Certificate (NTA Level 5)in Youth work OR\\nAdvanced Certificate of Secondary Education Examination (ACSEE)\\nwith at least one Principal pass and Subsidiary in Principal subjects', 2, 300, 'Local Fee: TSH. 720,000/=', '2025-01-29 17:00:53', '', '', ''),
(110, 'Open University of Tanzania (OUT)', 'Diploma in Distance Education and Open Learning', 'Holders of Basic Technician Certificate (NTA Level 4) in Distance\\nEducation', 2, 300, 'Local Fee: TSH. 720,000/=', '2025-01-29 17:00:53', '', '', ''),
(111, 'Open University of Tanzania (OUT)', 'Diploma in Early Childhood Education (DECE)', 'Holder of certificate of secondary education examination (CSEE) with\\nFour passes or with two passes for those who have NVA level III\\nrecognized by VETA and Grade III A OR Basic technician certificate in\\nteaching with an average of B and above', 2, 10000, 'Local Fee: TSH. 720,000/=', '2025-01-29 17:00:53', '', '', ''),
(112, 'Open University of Tanzania (OUT)', 'Diploma in Library and Information Studies.', 'Holders of Technician Certificate (NTA level 5) in Library and\\nInformation studies', 2, 100, 'Local Fee: TSH. 500,000/=', '2025-01-29 17:00:53', '', '', ''),
(113, 'Open University of Tanzania (OUT)', 'Diploma in Poultry Production and Health (odpph)', 'Holders of Technician Certificate ( NTA Level 5 ) in Animal Production,\\nGeneral Agriculture With 2.0 GPA and above.', 2, 300, 'Local Fee: TSH. 1,215,000/=', '2025-01-29 17:00:53', '', '', ''),
(114, 'Open University of Tanzania (OUT)', 'Diploma in Primary Teacher Education (odpte)', 'Holder of Certificate of Secondary Education Examination (CSEE) with\\nfour Passes or with two Passes for Those Who Have Nva Level III\\nRecognized By Veta and Grade III A or Basic Technician Certificate in Teaching with An Average of B+ and above or Holder of Advinced\\nCertificate of Secondary Education with one Principal Pass and one\\nSubsidiary from Any two Subjects', 2, 10000, 'Local Fee: TSH. 720,000/=', '2025-01-29 17:00:53', '', '', ''),
(115, 'Open University of Tanzania (OUT)', 'Ordinary Diploma in Accountancy', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects', 3, 1000, 'Local Fee: TSH. 850,000/=', '2025-01-29 17:00:53', '', '', ''),
(116, 'Open University of Tanzania (OUT)', 'Ordinary Diploma in Business Administration', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects', 3, 1000, 'Local Fee: TSH. 850,000/=', '2025-01-29 17:00:53', '', '', ''),
(117, 'Open University of Tanzania (OUT)', 'Ordinary Diploma in Computing and Information\\nCommunication Technology', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith Four (4) passes in non-religious subjects.', 3, 180, 'Local Fee: TSH. 1,215,000/=', '2025-01-29 17:00:53', '', '', ''),
(118, 'Open University of Tanzania (OUT)', 'Ordinary Diploma in Procurement and Supply', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith at least four (4) passes in non-religious subjects', 3, 1000, 'Local Fee: TSH. 870,000/=', '2025-01-29 17:00:53', '', '', ''),
(119, 'Open University of Tanzania (OUT)', 'Ordinary Diploma in Social Work', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith four (4) passes in non religious subjects', 3, 100, 'Local Fee: TSH. 800,000/=', '2025-01-29 17:00:53', '', '', ''),
(120, 'Ruaha Catholic University (RUCU)', 'Diploma in Business Administration', 'Holders of Certificate in Business Administration OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with at Least\\none Principal pass and Subsidiary in Principal subjects', 2, 100, 'Local Fee: TSH. 1,215,000/=', '2025-01-29 17:00:53', '', '', ''),
(121, 'Ruaha Catholic University (RUCU)', 'Diploma in Computer Science', 'Holders of Certificate in Computer Science (CS), Information\\nTechnology (IT), Business Information Technology (BIT); OR Advanced\\nCertificate of Secondary Education Examination (ACSEE) with one (1)\\nprincipal pass and one (1) subsidiary pass.', 2, 70, 'Local Fee: TSH. 1,290,000/=', '2025-01-29 17:00:53', '', '', ''),
(122, 'Ruaha Catholic University (RUCU)', 'Diploma in Environmental Health Sciences', 'Holders of Technician Certificate (NTA Level 5) in Environmental\\nHealth Sciences', 2, 50, 'Local Fee: TSH. 1,400,000/=', '2025-01-29 17:00:53', '', '', ''),
(123, 'Ruaha Catholic University (RUCU)', 'Diploma in Human Resource Management', 'Holders of Holder of Certificate in Human Resource Management OR\\nAdvanced Certificate of Secondary Education Examination with one\\nPrincipal Pass and one Subsidiary in Principal subjects', 2, 100, 'Local Fee: TSH. 1,250,000/=', '2025-01-29 17:00:53', '', '', ''),
(124, 'Ruaha Catholic University (RUCU)', 'Diploma in Law', 'Holders of Certificate in Law OR Advanced Certificate of Secondary\\nEducation Examination with at least one Principal pass and one\\nSubsidiary in Principal subjects', 2, 100, 'Local Fee: TSH. 1,225,000/=', '2025-01-29 17:00:53', '', '', ''),
(125, 'Ruaha Catholic University (RUCU)', 'Diploma in Library and Information Studies', 'Holders of Certificate in Library and Information Studies OR Advanced\\nCertificate of Secondary Education Examination with at least one\\nPrincipal pass and one Subsidiary in Principal subjects', 2, 100, 'Local Fee: TSH. 1,215,000/=', '2025-01-29 17:00:53', '', '', ''),
(126, 'Ruaha Catholic University (RUCU)', 'Ordinary Diploma in Environmental Health\\nSciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nBiology Chemistry and Physics/Engineering Sciences.', 3, 300, 'Local Fee: TSH. 1,400,000/=', '2025-01-29 17:00:53', '', '', '');
INSERT INTO `courses` (`id`, `university_name`, `program_name`, `admission_requirements`, `program_duration`, `admission_capacity`, `tuition_fees`, `created_at`, `total_pass`, `cetificate`, `specialSubject`) VALUES
(127, 'Ruaha Catholic University (RUCU)', 'Ordinary Diploma in Medical Laboratory Sciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nChemistry, Biology, Physics/Engineering sciences/Basic Mathematics\\nand English Language.', 3, 300, 'Local Fee: TSH. 1,595,000/=', '2025-01-29 17:00:53', '', '', ''),
(128, 'Ruaha Catholic University (RUCU)', 'Ordinary Diploma in Pharmaceutical Sciences', 'Holders of Certificate of Secondary Education Examination (CSEE)\\nwith At Least four (4) Passes in non-religious Subjects including\\nChemistry and Biology. A Pass in Basic Mathematics and English\\nLanguage Is An Added Advantage.', 3, 400, 'Local Fee: TSH. 1,400,000/=', '2025-01-29 17:00:53', '', '', ''),
(129, 'Sokoine University of Agriculture (SUA)', 'Diploma in Crop Production and Management', 'Holders of Certificate in Crop production,Agriculture General,\\nCertificate in Agriculture and Livestock Production (CALP) OR\\nAdvanced Certificate of Secondary Education Examination (ACSEE)\\nPasses in Chemistry, Biology/zoology, Physics, Mathematics,\\nGeography or Science and Practice of Agriculture. The Candidate Must\\nPass Biology/zoology At Principal Level. Such Candidates Must also\\nPasses in English and Mathematics at Certificate of Secondary\\nEducation Examination (CSEE)', 2, 150, 'Local Fee: TSH. 1,130,000/=', '2025-01-29 17:00:53', '', '', ''),
(130, 'Sokoine University of Agriculture (SUA)', 'Diploma in Information and Library Sciences(dils)', 'Holders of Technical Certificate(NTA 5) in Librarianship, Records\\nManagement OR Advanced Certificate of Secondary Education with at\\nleast one Principal Pass in Mathematics, Physics, Biology, Chemistry,\\nScience and Practice of Agriculture, Geography, Economics,\\nCommerce, History, English, French and Kiswahili', 2, 150, 'Local Fee: TSH. 1,130,000/=', '2025-01-29 17:00:53', '', '', ''),
(131, 'Sokoine University of Agriculture (SUA)', 'Diploma in Information Technology', 'Holders of Certificate in Information Technology,Computer\\nEngineering OR Advanced Certificate of Secondary Education with at\\nleast one Principal Pass in Mathematics, Physics, Biology, Chemistry,\\nScience and Practice of Agriculture, Geography, Economics,\\nCommerce, History, English, French and Kiswahili.', 2, 150, 'Local Fee: TSH. 1,130,000/=', '2025-01-29 17:00:53', '', '', ''),
(132, 'Sokoine University of Agriculture (SUA)', 'Diploma in Laboratory Technology', 'Holders of Basic Certificate in Laboratory Technology with GPA 2.0\\nfrom recognized institution OR Advanced Certificate of Secondary\\nEducation with at least one Principal Pass in Mathematics, Physics,\\nBiology, Chemistry, Science and Practice of Agriculture, Geography,\\nEconomics, Commerce, History, English, French and Kiswahili.', 2, 150, 'Local Fee: TSH. 1,130,000/=', '2025-01-29 17:00:53', '', '', ''),
(133, 'Sokoine University of Agriculture (SUA)', 'Diploma in Records, Archives and Information\\nManagement (dram)', 'Holders of Technical Certificate (NTA level 5) in Records Management,\\nLibrarianship OR Advanced Certificate of Secondary Education with at\\nleast one Principal pass in Mathematics, Physics, Biology, Chemistry,\\nScience and Practice of Agriculture, Geography, Economics,\\nCommerce, History, English, French and Kiswahili', 2, 150, 'Local Fee: TSH. 1,130,000/=', '2025-01-29 17:00:53', '', '', ''),
(134, 'Sokoine University of Agriculture (SUA)', 'Diploma in Tropical Animal Health and Production', 'Holders of Certificate in Animal Health (AGRO VET), Certificate in\\nAnimal Health and Production (AHPC), Certificate in Agriculture and\\nLivestock Production (CALP) OR Advanced Certificate of Secondary\\nEducation Examination (ACSEE) Passes in Chemistry, Biology/zoology,\\nPhysics, Mathematics, Geography or Science and Practice of\\nAgriculture. The Candidate Must Pass Biology/zoology At Principal\\nLevel. Such Candidates Must also Passes in English and Mathematics at\\nCertificate of Secondary Education Examination (CSEE)', 2, 150, 'Local Fee: TSH. 1,130,000/=', '2025-01-29 17:00:53', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `search_history`
--

CREATE TABLE `search_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `min_price` int(11) DEFAULT NULL,
  `max_price` int(11) DEFAULT NULL,
  `search_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `search_history`
--

INSERT INTO `search_history` (`id`, `user_id`, `location`, `min_price`, `max_price`, `search_time`) VALUES
(1, 21, 'Dar es Salaam', 1000000, 3000000, '2025-03-11 09:55:52'),
(2, 21, 'Dar es Salaam', 1000000, 3000000, '2025-03-11 10:03:28');

-- --------------------------------------------------------

--
-- Table structure for table `security_questions`
--

CREATE TABLE `security_questions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `security_questions`
--

INSERT INTO `security_questions` (`id`, `user_id`, `question`, `answer_hash`) VALUES
(9, 20, 'Where did your study for your diploma?', '$2y$10$evLXJLMzC89siyhq5.hbDOWfEei279uR1yHQa.IkwtL68FjphZ.P2'),
(10, 21, 'Where did your study for your diploma?', '$2y$10$FhyFnyqB9CU9G7cTHg/nP.1sph4RAmXTZwTWeMGz4Oq7m.ZuHuoZ2');

-- --------------------------------------------------------

--
-- Table structure for table `student_results`
--

CREATE TABLE `student_results` (
  `id` int(11) NOT NULL,
  `examination_number` varchar(20) NOT NULL,
  `year_of_exam` int(11) NOT NULL,
  `exam_type` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `division` varchar(5) NOT NULL,
  `points` varchar(10) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_results`
--

INSERT INTO `student_results` (`id`, `examination_number`, `year_of_exam`, `exam_type`, `gender`, `division`, `points`, `user_id`) VALUES
(3, 'S0189/0002', 2020, 'CSEE', 'Male', 'I', '7', 20),
(4, 'S0189/0007', 2023, 'CSEE', 'Male', 'I', '11', 21);

-- --------------------------------------------------------

--
-- Table structure for table `student_subjects`
--

CREATE TABLE `student_subjects` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_name` varchar(50) NOT NULL,
  `score` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_subjects`
--

INSERT INTO `student_subjects` (`id`, `student_id`, `subject_name`, `score`) VALUES
(1, 3, 'CIV', 'B'),
(2, 3, 'HIST', 'A'),
(3, 3, 'GEO', 'A'),
(4, 3, 'KISW', 'A'),
(5, 3, 'ENGL', 'A'),
(6, 3, 'PHY', 'C'),
(7, 3, 'CHEM', 'A'),
(8, 3, 'BIO', 'A'),
(9, 3, 'COMP STUD', 'A'),
(10, 3, 'B/MATH', 'B'),
(11, 4, 'CIV', 'A'),
(12, 4, 'HIST', 'B'),
(13, 4, 'GEO', 'B'),
(14, 4, 'KISW', 'C'),
(15, 4, 'ENGL', 'B'),
(16, 4, 'PHY', 'C'),
(17, 4, 'CHEM', 'B'),
(18, 4, 'BIO', 'A'),
(19, 4, 'B/MATH', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
  `id` int(11) NOT NULL,
  `sn` varchar(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `head_office` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `sn`, `name`, `head_office`, `type`, `status`, `action`) VALUES
(41, '1', 'University of Dar es Salaam (UDSM)', 'Dar es Salaam', 'Public University', 'Accredited and Chartered', 'https://www.udsm.ac.tz/'),
(42, '2', 'Sokoine University of Agriculture (SUA)', 'Morogoro', 'Public University', 'Accredited and Chartered', 'https://www.sua.ac.tz/'),
(43, '3', 'Open University of Tanzania (OUT)', 'Dar es Salaam', 'Public University', 'Accredited and Chartered', 'https://www.out.ac.tz/'),
(44, '4', 'State University of Zanzibar (SUZA)', 'Zanzibar', 'Public University', 'Accredited', 'https://suza.ac.tz/'),
(45, '5', 'Mzumbe University (MU)', 'Morogoro', 'Public University', 'Accredited and Chartered', 'https://www.mzumbe.ac.tz/site/'),
(46, '6', 'Nelson Mandela African Institution of Science and Technology (NM-AIST)', 'Arusha', 'Public University', 'Accredited and Chartered', 'https://nm-aist.ac.tz/'),
(47, '7', 'Muhimbili University of Health and Allied Sciences (MUHAS)', 'Dar es Salaam', 'Public University', 'Accredited and Chartered', 'https://muhas.ac.tz/'),
(48, '8', 'Ardhi University (ARU)', 'Dar es Salaam', 'Public University', 'Accredited and Chartered', 'https://www.aru.ac.tz/'),
(49, '9', 'University of Dodoma (UDOM)', 'Dodoma', 'Public University', 'Accredited and Chartered', 'https://www.udom.ac.tz/'),
(50, '10', 'Mbeya University of Science and Technology (MUST)', 'Mbeya', 'Public University', 'Accredited and Chartered', 'https://www.must.ac.tz/'),
(51, '11', 'Moshi Cooperative University (MoCU)', 'Moshi', 'Public University', 'Accredited and Chartered', 'https://www.mocu.ac.tz/'),
(52, '12', 'Mwalimu Julius K. Nyerere University of Agriculture and Technology (MJNUAT)', 'Musoma', 'Public University', 'Provisional Licence', 'https://www.mjnuat.ac.tz/'),
(53, '13', 'Kairuki University (KU), formerly HKMU', 'Dar es Salaam', 'Private University', 'Accredited and Chartered', 'https://www.hkmu.ac.tz/'),
(54, '14', 'Abdulrahman Al-Sumait University (SUMAIT)', 'Zanzibar', 'Private University', 'Accredited', 'https://www.sumait.ac.tz/'),
(55, '15', 'St. Augustine University of Tanzania (SAUT)', 'Mwanza', 'Private University', 'Accredited and Chartered', 'https://www.saut.ac.tz/'),
(56, '16', 'Zanzibar University (ZU)', 'Zanzibar', 'Private University', 'Accredited and Chartered', 'https://www.zanvarsity.ac.tz/site/'),
(57, '17', 'Tumaini University Makumira (TUMA)', 'Arusha', 'Private University', 'Certificate of Full Registration and Chartered', 'https://makumira.ac.tz/'),
(58, '18', 'Aga Khan University (AKU)', 'Dar es Salaam', 'Private University', 'Accredited and Chartered', 'https://www.aku.edu/Pages/home.aspx'),
(59, '19', 'Catholic University of Health and Allied Sciences (CUHAS)', 'Mwanza', 'Private University', 'Accredited', 'https://www.bugando.ac.tz/'),
(60, '20', 'University of Arusha (UoA)', 'Arusha', 'Private University', 'Accredited and Chartered', 'https://osim.uoa.ac.tz/'),
(61, '21', 'St. Joseph University in Tanzania (SJUIT)', 'Dar es Salaam', 'Private University', 'Accredited', 'https://www.sjuit.ac.tz/'),
(62, '22', 'Teofilo Kisanji University (TEKU)', 'Mbeya', 'Private University', 'Accredited and Chartered', 'https://www.teku.ac.tz/'),
(63, '23', 'Muslim University of Morogoro (MUM)', 'Morogoro', 'Private University', 'Accredited and Chartered', 'https://www.mum.ac.tz/'),
(64, '24', 'Mwenge Catholic University (MWECAU)', 'Moshi', 'Private University', 'Accredited and Chartered', 'https://www.mwecau.ac.tz/'),
(65, '25', 'University of Iringa (UoI)', 'Iringa', 'Private University', 'Accredited', 'https://uoi.ac.tz/'),
(66, '26', 'St. John\'s University of Tanzania (SJUT)', 'Dodoma', 'Private University', 'Accredited and Chartered', 'https://www.sjut.ac.tz/'),
(67, '27', 'Kampala International University in Tanzania (KIUT)', 'Dar es Salaam', 'Private University', 'Accredited', 'https://kiut.ac.tz/'),
(68, '28', 'United African University of Tanzania (UAUT)', 'Dar es Salaam', 'Private University', 'Accredited', 'https://www.uaut.ac.tz/'),
(69, '29', 'Ruaha Catholic University (RUCU)', 'Iringa', 'Private University', 'Accredited', 'https://www.rucu.ac.tz/'),
(70, '30', 'Mwanza University (MzU)', 'Mwanza', 'Private University', 'Provisional Licence', 'https://mwanzauniversity.ac.tz/'),
(71, '31', 'Catholic University of Mbeya (CUoM), formerly CUCoM', 'Mbeya', 'Private University', 'Accredited', 'https://www.cucom.ac.tz/'),
(72, '32', 'Dar es Salaam Tumaini University (DarTU), formerly TUDARCo', 'Dar es Salaam', 'Private University', 'Accredited', 'https://tudarco.ac.tz/'),
(73, '33', 'Rabininsia Memorial University of Health and Allied Sciences (RMUHAS)', 'Dar es Salaam', 'Private University', 'Provisional Licence', 'https://www.rabininsiamemorialhospital.co.tz/'),
(74, '34', 'University of Medical Sciences and Technology (UMST)', 'Dar es Salaam', 'Private University', 'Provisional Licence', 'https://umst.org/'),
(75, '35', 'Islamic University of East Africa (IUEA)', 'Dar es Salaam', 'Private University', 'Provisional Licence', 'https://iuea.ac.ug/'),
(76, '36', 'Dar es Salaam University College of Education (DUCE)', 'Dar es Salaam', 'Public University College', 'Accredited and Chartered', 'https://udsm.ac.tz/duce'),
(77, '37', 'Mkwawa University College of Education (MUCE)', 'Iringa', 'Public University College', 'Accredited and Chartered', 'https://muce.udsm.ac.tz/'),
(78, '38', 'Mzumbe University – Dar es Salaam Campus College (MU – Dar es Salaam Campus College)', 'Dar es Salaam', 'Public University College', 'Accredited', 'https://dcc.mzumbe.ac.tz/'),
(79, '39', 'Mzumbe University – Mbeya Campus College (MU – Mbeya Campus College)', 'Mbeya', 'Public University College', 'Accredited', 'https://drps.mzumbe.ac.tz/index.php/mcc'),
(80, '40', 'Mbeya College of Health and Allied Sciences (MCHAS)', 'Mbeya', 'Public University College', 'Accredited', 'http://dev.udsm.ac.tz/mbeya-college-health-and-allied-sciences'),
(81, '41', 'Mbeya University of Science and Technology – Rukwa Campus College (MUST – RC)', 'Rukwa', 'Public University College', 'Accredited', 'https://www.must.ac.tz/'),
(82, '42', 'Sokoine University of Agriculture – Mizengo Pinda Campus College (SUA – MPC)', 'Katavi', 'Public University College', 'Accredited', 'https://www.mizengopinda.sua.ac.tz/'),
(83, '43', 'Kilimanjaro Christian Medical University College (KCMUCo)', 'Moshi', 'Private University College', 'Accredited and Chartered', 'https://kcmuco.ac.tz/'),
(84, '44', 'Stefano Moshi Memorial University College (SMMUCo)', 'Moshi', 'Private University College', 'Certificate of Full Registration and Chartered', 'https://www.smmuco.ac.tz/'),
(85, '45', 'Archbishop Mihayo University College of Tabora (AMUCTA)', 'Tabora', 'Private University College', 'Accredited', 'https://www.amucta.ac.tz/'),
(86, '46', 'Jordan University College (JUCo)', 'Morogoro', 'Private University College', 'Accredited', 'https://www.juco.ac.tz/'),
(87, '47', 'St. Francis University College of Health and Allied Sciences (SFUCHAS)', 'Morogoro', 'Private University College', 'Certificate of Full Registration', 'https://sfuchas.ac.tz/'),
(88, '48', 'Stella Maris Mtwara University College (STeMMUCo)', 'Mtwara', 'Private University College', 'Accredited', 'https://stemmuco.ac.tz/'),
(89, '49', 'Marian University College (MARUCo)', 'Bagamoyo', 'Private University College', 'Accredited', 'https://maruco.ac.tz/'),
(90, '50', 'St. Joseph University College of Health and Allied Sciences (SJCHAS)', 'Dar es Salaam', 'Private University College', 'Accredited', 'https://sjchs.sjuit.ac.tz/'),
(91, '51', 'Institute of Marine Sciences (IMS)', 'Zanzibar', 'Public University Campus, Centre and Institute', 'As per status of the Mother University', 'https://www.udsm.ac.tz/web/index.php/institutes/ims'),
(92, '52', 'Kizumbi Institute of Cooperative Business Education (KICoB)', 'Shinyanga', 'Public University Campus, Centre and Institute', 'As per status of the Mother University', 'https://kicob.mocu.ac.tz/'),
(93, '53', 'St. Augustine University of Tanzania, Dar es Salaam Centre', 'Dar es Salaam', 'Private University Campus, Centre and Institute', 'As per status of the Mother University', 'https://sautdarcentre.ac.tz/'),
(94, '54', 'Stefano Moshi Memorial University College, Mwika Centre', 'Moshi', 'Private University Campus, Centre and Institute', 'As per status of the Mother University', 'https://www.smmuco.ac.tz/'),
(95, '55', 'St. Augustine University of Tanzania, Arusha Centre', 'Arusha', 'Private University Campus, Centre and Institute', 'As per status of the Mother University', 'https://sautarusha.ac.tz/'),
(96, '56', 'Hubert Kairuki Memorial University (HK)', 'Dar es Salaam', 'Private', 'Accredited and Chartered', 'https://www.hkmu.ac.tz/');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `check` varchar(20) NOT NULL DEFAULT 'New',
  `phone` text NOT NULL,
  `profile_image` varchar(255) DEFAULT 'initial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `check`, `phone`, `profile_image`) VALUES
(20, 'Miles Morales', 'Miles12@gmail.com', '70ccd9007338d6d81dd3b6271621b9cf9a97ea00', '2025-03-01 11:55:37', 'Old', '0789123987', 'uploads/user_20_1740841956.png'),
(21, 'Benin Benjamin', 'ben10@gmail.com', '70ccd9007338d6d81dd3b6271621b9cf9a97ea00', '2025-03-11 09:36:22', 'Old', '0786143254', 'uploads/user_21_1742790332.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accreditation`
--
ALTER TABLE `accreditation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_university` (`uni_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `university_name` (`university_name`);

--
-- Indexes for table `search_history`
--
ALTER TABLE `search_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `student_results`
--
ALTER TABLE `student_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_university_name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accreditation`
--
ALTER TABLE `accreditation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `search_history`
--
ALTER TABLE `search_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `security_questions`
--
ALTER TABLE `security_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `student_results`
--
ALTER TABLE `student_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_subjects`
--
ALTER TABLE `student_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accreditation`
--
ALTER TABLE `accreditation`
  ADD CONSTRAINT `fk_university` FOREIGN KEY (`uni_id`) REFERENCES `universities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`university_name`) REFERENCES `universities` (`name`);

--
-- Constraints for table `search_history`
--
ALTER TABLE `search_history`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `security_questions`
--
ALTER TABLE `security_questions`
  ADD CONSTRAINT `security_questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_results`
--
ALTER TABLE `student_results`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_subjects`
--
ALTER TABLE `student_subjects`
  ADD CONSTRAINT `student_subjects_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student_results` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
