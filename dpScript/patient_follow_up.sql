CREATE TABLE IF NOT EXISTS `patient_follow_up` (
  `patientFollowUpID` int(11) NOT NULL AUTO_INCREMENT,
  `patientID` int(11) NOT NULL,
  `doctorID` int(11) NOT NULL,
  `invID` int(11) NOT NULL,
  PRIMARY KEY (`patientFollowUpID`)
);