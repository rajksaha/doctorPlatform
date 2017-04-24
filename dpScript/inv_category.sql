ALTER TABLE  `inv` ADD  `categoryID` INT NOT NULL AFTER  `id`

CREATE TABLE IF NOT EXISTS `inv_category` (
  `invCategoryID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  PRIMARY KEY (`invCategoryID`)
);

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menuID` int(11) NOT NULL,
  `menuURL` varchar(255) NOT NULL,
  `defaultName` varchar(255) NOT NULL,
  `isPopUp` int(11) NOT NULL,
  `functionName` varchar(100) NOT NULL,
  `inPrescription` tinyint(1) NOT NULL,
  `displayOrder` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

INSERT INTO `menu` (`id`, `menuID`, `menuURL`, `defaultName`, `isPopUp`, `functionName`, `inPrescription`, `displayOrder`) VALUES
(11, 11, '#/oldPrescription', 'Old Prescription', 0, '', 1, 7),
(17, 17, '#/pastHistory', 'History', 1, 'history', 1, 1),
(19, 19, '#/invReport', 'Inv Reports', 0, '', 1, 3),
(20, 20, '#/followUpChart', 'Follow Up Chart', 0, '', 1, 4),
(21, 21, '#/drugAdvisor', 'Drug Advisor', 0, '', 1, 5),
(23, 23, '#/history?histpryType=MH', 'MH', 1, '', 2, 0),
(24, 24, '#/history?histpryType=OBS', 'OBS', 1, '', 2, 0),
(25, 25, '#/history?histpryType=RISK', 'RISK', 1, '', 2, 0),
(26, 26, '#/history?histpryType=ALLERGY', 'ALLERGY', 1, '', 2, 0),
(27, 27, '#/history?histpryType=HABBIT', 'HABBIT', 1, '', 2, 0),
(28, 28, '#/vital', 'O.E', 0, '', 0, 12),
(29, 29, '#inv', 'inv', 0, '', 0, 13);