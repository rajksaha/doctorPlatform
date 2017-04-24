CREATE TABLE IF NOT EXISTS `follow_up_result` (
  `resultID` int(11) NOT NULL AUTO_INCREMENT,
  `followUpID` int(11) NOT NULL,
  `data` text NOT NULL,
  `entryDate` date NOT NULL,
  PRIMARY KEY (`resultID`)
);