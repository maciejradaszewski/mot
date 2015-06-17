-- VM-10224
-- repopulating a list of transition statuses.
-- list defined by Helen Jacquest, James Body and Paul Bryant

--
-- ALREADY IN PRODUCTION
--

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

TRUNCATE `transition_status`;

INSERT INTO `transition_status` (`id`,`name`,`code`,`mot1_legacy_id`,`created_by`,`created_on`,`last_updated_by`,`last_updated_on`,`version`,`batch_number`) VALUES (1,'Not Started','NS',NULL,2,'2015-06-02 16:13:35.686405',NULL,NULL,1,1);
INSERT INTO `transition_status` (`id`,`name`,`code`,`mot1_legacy_id`,`created_by`,`created_on`,`last_updated_by`,`last_updated_on`,`version`,`batch_number`) VALUES (2,'Submitted','SUB',NULL,2,'2015-06-02 16:13:35.696716',NULL,NULL,1,1);
INSERT INTO `transition_status` (`id`,`name`,`code`,`mot1_legacy_id`,`created_by`,`created_on`,`last_updated_by`,`last_updated_on`,`version`,`batch_number`) VALUES (3,'One time password assigned','OTPA',NULL,1,'2015-06-02 16:25:05.973678',NULL,NULL,1,1);
INSERT INTO `transition_status` (`id`,`name`,`code`,`mot1_legacy_id`,`created_by`,`created_on`,`last_updated_by`,`last_updated_on`,`version`,`batch_number`) VALUES (4,'Restricted','REST',NULL,1,'2015-06-02 16:25:05.974055',NULL,NULL,1,1);
INSERT INTO `transition_status` (`id`,`name`,`code`,`mot1_legacy_id`,`created_by`,`created_on`,`last_updated_by`,`last_updated_on`,`version`,`batch_number`) VALUES (5,'Full functionality','FULL',NULL,1,'2015-06-02 16:25:05.974448',NULL,NULL,1,1);
INSERT INTO `transition_status` (`id`,`name`,`code`,`mot1_legacy_id`,`created_by`,`created_on`,`last_updated_by`,`last_updated_on`,`version`,`batch_number`) VALUES (6,'Not to be transitioned','NOT',NULL,1,'2015-06-02 16:25:05.974879',NULL,NULL,1,1);
INSERT INTO `transition_status` (`id`,`name`,`code`,`mot1_legacy_id`,`created_by`,`created_on`,`last_updated_by`,`last_updated_on`,`version`,`batch_number`) VALUES (0,'Unknown','UNKN',NULL,2,'2015-06-02 16:13:35.698127',NULL,NULL,1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
