SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;



INSERT INTO `document` (`id`, `title`, `body`, `created_at`, `updated_at`) VALUES
(2, 'title test', 'body test', '2012-08-06 09:58:02', '2012-08-06 09:58:02');


INSERT INTO `location` (`id`, `title`) VALUES
(1, 'Tente'),
(2, 'Chambre');

INSERT INTO `meal` (`id`, `type`, `date`) VALUES
(1, 2, '2013-07-12'),
(2, 0, '2013-07-13'),
(3, 1, '2013-07-13'),
(4, 2, '2013-07-13'),
(5, 0, '2013-07-14'),
(6, 1, '2013-07-14'),
(7, 2, '2013-07-14');

INSERT INTO `user` (`id`, `location_id`, `firstname`, `lastname`, `email`, `affiliation`, `description`, `answered`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nicolas', 'Behier', 'nbd@gmail.com', 'Membre de la famille', 'Ma Description', 1, '2012-08-06 15:30:08', '2012-08-10 07:05:02'),
(2, 2, 'Simon, VÃ©ro et Rose', 'Assani', 'simon@gmail.com', 'Polytech Tours', '', 2, '2012-08-07 07:11:40', '2012-08-10 18:39:35'),
(3, 1, 'Flavien et Guyguy', 'Lenourichel', 'flavien@gmail.com', 'Erasmus, Lycee', '', 1, '2012-08-07 07:11:40', '2012-08-10 18:39:47'),
(4, 1, 'Camille', 'Ratia', 'camille@gmail.com', 'Tours', '', 1, '2012-08-08 09:04:03', '2012-08-10 19:02:07');

INSERT INTO `usermeal` (`user_id`, `meal_id`, `number`) VALUES
(1, 1, 2),
(1, 2, 2),
(1, 3, 2),
(1, 4, 2),
(1, 5, 2),
(1, 6, 2),
(2, 3, 2),
(2, 4, 2),
(3, 3, 2),
(3, 4, 2),
(3, 5, 2);
SET FOREIGN_KEY_CHECKS=1;
