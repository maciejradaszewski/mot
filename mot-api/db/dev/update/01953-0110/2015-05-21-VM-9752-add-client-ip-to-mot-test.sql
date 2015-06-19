-- VM-9752
--
-- Client IP is saved as a variable length string to cope with IPv4 and IPv6 addresses.
--
-- Please refer to this page for details regarding the chosen size.
--   http://stackoverflow.com/questions/166132/maximum-length-of-the-textual-representation-of-an-ipv6-address
ALTER TABLE `mot_test`
  ADD COLUMN `client_ip` varchar(45) DEFAULT "0.0.0.0"  NULL;
