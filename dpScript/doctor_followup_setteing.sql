CREATE TABLE IF NOT EXISTS `doctor_followup_setteing` (
  `followUpSerttingID` int(11) NOT NULL AUTO_INCREMENT,
  `doctorID` int(11) NOT NULL,
  `invID` int(11) NOT NULL,
  PRIMARY KEY (`followUpSerttingID`)
);
