CREATE DATABASE IF NOT EXISTS `DB_and_Web`;

DROP TABLE IF EXISTS `associations`;
CREATE TABLE IF NOT EXISTS `associations` (
  `association_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `website` varchar(100) DEFAULT NULL,
  `timestamp_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`association_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

DROP TABLE IF EXISTS `committees`;
CREATE TABLE IF NOT EXISTS `committees` (
  `committee_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `timestamp_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`committee_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `student_nr` int(6) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `timestamp_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `memberships`;
CREATE TABLE IF NOT EXISTS `memberships` (
  `membership_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_started` date DEFAULT NULL,
  `date_ended` date DEFAULT NULL,
  `member_id` int(11) NOT NULL,
  `period_id` int(11) DEFAULT NULL,
  `committee_id` int(11) DEFAULT NULL,
  `association_id` int(11) DEFAULT NULL,
  `successor_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`membership_id`),
  KEY `member_id` (`member_id`),
  KEY `period_id` (`period_id`),
  KEY `committee_id` (`committee_id`),
  KEY `association_id` (`association_id`),
  KEY `successor_id` (`successor_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `office_periods`;
CREATE TABLE IF NOT EXISTS `office_periods` (
  `period_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_starts` date NOT NULL,
  `date_ends` date NOT NULL,
  PRIMARY KEY (`period_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `successors`;
CREATE TABLE IF NOT EXISTS `successors` (
  `successor_id` int(11) NOT NULL AUTO_INCREMENT,
  `reason` varchar(100) NOT NULL,
  `member_id` int(11) NOT NULL,
  PRIMARY KEY (`successor_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password_hash` varchar(200) NOT NULL,
  `user_role` varchar(45) NOT NULL,
  `timestamp_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `users` (`username`, `password_hash`, `user_role`) VALUES
('h.peter', '$2y$10$vbOUqOgYGNQGh9xa6oe9IeIsVm/ZZUbguBc4Vvrda5A8vFYmp9vp2', 'Employee'),
('m.scholz', '$2y$10$meOwgrk/XwHebBsdFtl21uxRqFUYo9fFwhcv0qhpoqzzqeMVYiLUC', 'Admin');

INSERT INTO `associations` (`association_id`, `name`, `website`) VALUES
(1, 'Elektrotechnik und Informationstechnik', 'https://www.tu-chemnitz.de/stud/fs/et-it/'),
(2, 'Human- und Sozialwissenschaften', 'https://www.tu-chemnitz.de/projekt/fsrhsw/'),
(3, 'Maschinenbau', 'https://www.tu-chemnitz.de/fsrmb/'),
(4, 'Chemie', 'https://www.tu-chemnitz.de/fsr-chemie/'),
(5, 'Informatik', 'https://www.tu-chemnitz.de/fsrif/'),
(6, 'Mathematik', 'https://www.tu-chemnitz.de/mathematik/fsrmathe/'),
(7, 'Wirtschaftswissenschaften', 'https://www.tu-chemnitz.de/wirtschaft/fsr/'),
(8, 'Physik', 'https://www.tu-chemnitz.de/fsphysik/'),
(9, 'Philosophische FakultÃ¤t', 'https://www.tu-chemnitz.de/projekt/fsrphil/');

INSERT INTO `committees` (`committee_id`, `name`, `description`) VALUES
(1, 'StuRa', 'Der Student_innenrat (StuRa) ist laut sÃ¤chsischem Hochschulfreiheitsgesetz (Â§24 (3) SÃ¤chsHSFG) ein Organ der Student_innenschaft.'),
(2, 'FSR', 'Ipsum dupsum.');

INSERT INTO `office_periods` (`date_starts`, `date_ends`) VALUES
('2010-01-01', '2010-12-31'),
('2011-01-01', '2011-12-31'),
('2012-01-01', '2012-12-31'),
('2013-01-01', '2013-12-31'),
('2014-01-01', '2014-12-31'),
('2015-01-01', '2015-12-31'),
('2016-01-01', '2016-12-31');

INSERT INTO `members` (`fname`, `lname`, `student_nr`, `email`) VALUES
('Max', 'Scholz', 417462, 'max.scholz@s2015.tu-chemnitz.de');

-- Insert 100 dummy entries for members
insert into members (fname, lname, email, student_nr) values ('Carol', 'Fuller', 'cfuller0@jiathis.com', 879118);
insert into members (fname, lname, email, student_nr) values ('Rebecca', 'Wood', 'rwood1@xing.com', 916371);
insert into members (fname, lname, email, student_nr) values ('Craig', 'Martin', 'cmartin2@cpanel.net', 705285);
insert into members (fname, lname, email, student_nr) values ('Phillip', 'Berry', 'pberry3@hostgator.com', 523324);
insert into members (fname, lname, email, student_nr) values ('Jane', 'Scott', 'jscott4@nationalgeographic.com', 718015);
insert into members (fname, lname, email, student_nr) values ('Arthur', 'Greene', 'agreene5@shop-pro.jp', 640775);
insert into members (fname, lname, email, student_nr) values ('Ralph', 'King', 'rking6@alibaba.com', 461476);
insert into members (fname, lname, email, student_nr) values ('Nicholas', 'Carter', 'ncarter7@phoca.cz', 804456);
insert into members (fname, lname, email, student_nr) values ('Annie', 'Washington', 'awashington8@diigo.com', 464383);
insert into members (fname, lname, email, student_nr) values ('Charles', 'Carroll', 'ccarroll9@constantcontact.com', 470328);
insert into members (fname, lname, email, student_nr) values ('Andrea', 'Wallace', 'awallacea@zimbio.com', 219301);
insert into members (fname, lname, email, student_nr) values ('Linda', 'Crawford', 'lcrawfordb@jalbum.net', 909553);
insert into members (fname, lname, email, student_nr) values ('Beverly', 'Moore', 'bmoorec@java.com', 894745);
insert into members (fname, lname, email, student_nr) values ('David', 'Wells', 'dwellsd@imdb.com', 144644);
insert into members (fname, lname, email, student_nr) values ('Anna', 'Holmes', 'aholmese@shutterfly.com', 575690);
insert into members (fname, lname, email, student_nr) values ('Sandra', 'Romero', 'sromerof@skype.com', 966918);
insert into members (fname, lname, email, student_nr) values ('Sharon', 'Stone', 'sstoneg@china.com.cn', 260510);
insert into members (fname, lname, email, student_nr) values ('Beverly', 'Ramirez', 'bramirezh@mayoclinic.com', 132117);
insert into members (fname, lname, email, student_nr) values ('Robert', 'Reid', 'rreidi@skype.com', 623083);
insert into members (fname, lname, email, student_nr) values ('Stephen', 'Collins', 'scollinsj@rambler.ru', 106619);
insert into members (fname, lname, email, student_nr) values ('Kathleen', 'Barnes', 'kbarnesk@networksolutions.com', 317643);
insert into members (fname, lname, email, student_nr) values ('Samuel', 'Wright', 'swrightl@comsenz.com', 531936);
insert into members (fname, lname, email, student_nr) values ('Jennifer', 'Alexander', 'jalexanderm@npr.org', 937650);
insert into members (fname, lname, email, student_nr) values ('Carlos', 'Wallace', 'cwallacen@answers.com', 620304);
insert into members (fname, lname, email, student_nr) values ('Paul', 'Nguyen', 'pnguyeno@360.cn', 703188);
insert into members (fname, lname, email, student_nr) values ('Carolyn', 'Butler', 'cbutlerp@usnews.com', 395178);
insert into members (fname, lname, email, student_nr) values ('Betty', 'Fernandez', 'bfernandezq@barnesandnoble.com', 366538);
insert into members (fname, lname, email, student_nr) values ('Shirley', 'Carr', 'scarrr@cpanel.net', 126094);
insert into members (fname, lname, email, student_nr) values ('Willie', 'Bowman', 'wbowmans@ycombinator.com', 200600);
insert into members (fname, lname, email, student_nr) values ('Shawn', 'Fowler', 'sfowlert@wisc.edu', 674026);
insert into members (fname, lname, email, student_nr) values ('Michael', 'Hicks', 'mhicksu@domainmarket.com', 622964);
insert into members (fname, lname, email, student_nr) values ('Henry', 'Cole', 'hcolev@prweb.com', 704326);
insert into members (fname, lname, email, student_nr) values ('Roger', 'Rose', 'rrosew@t.co', 787601);
insert into members (fname, lname, email, student_nr) values ('Raymond', 'Hernandez', 'rhernandezx@yellowpages.com', 885519);
insert into members (fname, lname, email, student_nr) values ('Ryan', 'Burns', 'rburnsy@plala.or.jp', 770610);
insert into members (fname, lname, email, student_nr) values ('Catherine', 'Scott', 'cscottz@home.pl', 897773);
insert into members (fname, lname, email, student_nr) values ('Bruce', 'Thompson', 'bthompson10@nyu.edu', 381780);
insert into members (fname, lname, email, student_nr) values ('Timothy', 'Burns', 'tburns11@imageshack.us', 827279);
insert into members (fname, lname, email, student_nr) values ('Michael', 'Hicks', 'mhicks12@scientificamerican.com', 825982);
insert into members (fname, lname, email, student_nr) values ('Jessica', 'Dunn', 'jdunn13@simplemachines.org', 172567);
insert into members (fname, lname, email, student_nr) values ('Angela', 'Owens', 'aowens14@whitehouse.gov', 538532);
insert into members (fname, lname, email, student_nr) values ('Denise', 'Wells', 'dwells15@wikispaces.com', 400766);
insert into members (fname, lname, email, student_nr) values ('Kevin', 'Snyder', 'ksnyder16@marriott.com', 458677);
insert into members (fname, lname, email, student_nr) values ('Ryan', 'Ortiz', 'rortiz17@lulu.com', 904527);
insert into members (fname, lname, email, student_nr) values ('Barbara', 'West', 'bwest18@answers.com', 356128);
insert into members (fname, lname, email, student_nr) values ('David', 'Bell', 'dbell19@ameblo.jp', 801679);
insert into members (fname, lname, email, student_nr) values ('Lillian', 'Nguyen', 'lnguyen1a@usgs.gov', 511204);
insert into members (fname, lname, email, student_nr) values ('Peter', 'Wheeler', 'pwheeler1b@a8.net', 106252);
insert into members (fname, lname, email, student_nr) values ('George', 'Frazier', 'gfrazier1c@state.tx.us', 779801);
insert into members (fname, lname, email, student_nr) values ('Joan', 'Bennett', 'jbennett1d@infoseek.co.jp', 124266);
insert into members (fname, lname, email, student_nr) values ('Charles', 'Wells', 'cwells1e@columbia.edu', 789766);
insert into members (fname, lname, email, student_nr) values ('Carlos', 'Fisher', 'cfisher1f@statcounter.com', 298664);
insert into members (fname, lname, email, student_nr) values ('Carlos', 'Taylor', 'ctaylor1g@ca.gov', 187054);
insert into members (fname, lname, email, student_nr) values ('Wayne', 'Hawkins', 'whawkins1h@51.la', 968335);
insert into members (fname, lname, email, student_nr) values ('Lillian', 'Perez', 'lperez1i@geocities.jp', 569022);
insert into members (fname, lname, email, student_nr) values ('Patrick', 'Davis', 'pdavis1j@yellowbook.com', 565176);
insert into members (fname, lname, email, student_nr) values ('Robert', 'Sullivan', 'rsullivan1k@spiegel.de', 383023);
insert into members (fname, lname, email, student_nr) values ('Daniel', 'Butler', 'dbutler1l@amazon.com', 399309);
insert into members (fname, lname, email, student_nr) values ('Jason', 'Lawrence', 'jlawrence1m@time.com', 986759);
insert into members (fname, lname, email, student_nr) values ('Terry', 'Price', 'tprice1n@forbes.com', 762056);
insert into members (fname, lname, email, student_nr) values ('Eric', 'Nichols', 'enichols1o@fc2.com', 797605);
insert into members (fname, lname, email, student_nr) values ('Johnny', 'Jenkins', 'jjenkins1p@google.de', 604040);
insert into members (fname, lname, email, student_nr) values ('Lillian', 'Evans', 'levans1q@netscape.com', 113538);
insert into members (fname, lname, email, student_nr) values ('Barbara', 'Berry', 'bberry1r@51.la', 490613);
insert into members (fname, lname, email, student_nr) values ('Helen', 'Diaz', 'hdiaz1s@360.cn', 156457);
insert into members (fname, lname, email, student_nr) values ('Maria', 'Vasquez', 'mvasquez1t@fastcompany.com', 788234);
insert into members (fname, lname, email, student_nr) values ('Keith', 'Edwards', 'kedwards1u@sakura.ne.jp', 295320);
insert into members (fname, lname, email, student_nr) values ('Jane', 'Peterson', 'jpeterson1v@amazon.co.jp', 707840);
insert into members (fname, lname, email, student_nr) values ('Laura', 'Watkins', 'lwatkins1w@about.com', 464070);
insert into members (fname, lname, email, student_nr) values ('Jack', 'Moreno', 'jmoreno1x@squidoo.com', 961291);
insert into members (fname, lname, email, student_nr) values ('Eric', 'Parker', 'eparker1y@google.es', 967004);
insert into members (fname, lname, email, student_nr) values ('Beverly', 'Ferguson', 'bferguson1z@icio.us', 712717);
insert into members (fname, lname, email, student_nr) values ('Jack', 'Cole', 'jcole20@statcounter.com', 487208);
insert into members (fname, lname, email, student_nr) values ('Clarence', 'Wagner', 'cwagner21@businessinsider.com', 519626);
insert into members (fname, lname, email, student_nr) values ('Gerald', 'Wheeler', 'gwheeler22@yellowpages.com', 408959);
insert into members (fname, lname, email, student_nr) values ('Albert', 'Greene', 'agreene23@amazon.co.jp', 699649);
insert into members (fname, lname, email, student_nr) values ('Judith', 'Torres', 'jtorres24@weebly.com', 915782);
insert into members (fname, lname, email, student_nr) values ('Amanda', 'Sims', 'asims25@yellowbook.com', 676462);
insert into members (fname, lname, email, student_nr) values ('Elizabeth', 'Jordan', 'ejordan26@cdc.gov', 566078);
insert into members (fname, lname, email, student_nr) values ('Jesse', 'Harris', 'jharris27@studiopress.com', 985882);
insert into members (fname, lname, email, student_nr) values ('Harry', 'Castillo', 'hcastillo28@51.la', 655593);
insert into members (fname, lname, email, student_nr) values ('Donald', 'Gonzales', 'dgonzales29@privacy.gov.au', 611218);
insert into members (fname, lname, email, student_nr) values ('Virginia', 'Berry', 'vberry2a@accuweather.com', 480040);
insert into members (fname, lname, email, student_nr) values ('Jane', 'Martin', 'jmartin2b@tiny.cc', 906059);
insert into members (fname, lname, email, student_nr) values ('Ruth', 'Oliver', 'roliver2c@ibm.com', 876369);
insert into members (fname, lname, email, student_nr) values ('Carolyn', 'Torres', 'ctorres2d@a8.net', 468102);
insert into members (fname, lname, email, student_nr) values ('Heather', 'Perkins', 'hperkins2e@sciencedirect.com', 714387);
insert into members (fname, lname, email, student_nr) values ('Stephen', 'Harrison', 'sharrison2f@bandcamp.com', 315371);
insert into members (fname, lname, email, student_nr) values ('Shirley', 'Roberts', 'sroberts2g@histats.com', 200726);
insert into members (fname, lname, email, student_nr) values ('Steven', 'Simpson', 'ssimpson2h@hubpages.com', 682059);
insert into members (fname, lname, email, student_nr) values ('Nicole', 'Patterson', 'npatterson2i@twitpic.com', 349727);
insert into members (fname, lname, email, student_nr) values ('Peter', 'Gardner', 'pgardner2j@shareasale.com', 564842);
insert into members (fname, lname, email, student_nr) values ('Paul', 'Harrison', 'pharrison2k@mayoclinic.com', 566364);
insert into members (fname, lname, email, student_nr) values ('Louis', 'Moore', 'lmoore2l@timesonline.co.uk', 125874);
insert into members (fname, lname, email, student_nr) values ('John', 'Lewis', 'jlewis2m@bravesites.com', 502907);
insert into members (fname, lname, email, student_nr) values ('Shawn', 'Garrett', 'sgarrett2n@umn.edu', 313681);
insert into members (fname, lname, email, student_nr) values ('Shawn', 'Nichols', 'snichols2o@xrea.com', 577265);
insert into members (fname, lname, email, student_nr) values ('Larry', 'Hart', 'lhart2p@newsvine.com', 108472);
insert into members (fname, lname, email, student_nr) values ('Wanda', 'Nelson', 'wnelson2q@narod.ru', 775508);
insert into members (fname, lname, email, student_nr) values ('Heather', 'Bell', 'hbell2r@joomla.org', 113724);