DELETE FROM `blends`;
DELETE FROM `accesses`;
DELETE FROM `users`;

INSERT INTO `blends` (`legacy_id`, `fileName`, `fileGoogleId`, `password`, `uploaderIp`, `questionLink`, `valid`, `fileSize`, `date`, `adminComment`, `deleted`, `owner`, `id`) VALUES
(NULL, 'Test.blend', '1qKPoB581V32cLDiDP5P2uO71o0VLmbna', '', 'ip 1', 'https://blender.stackexchange.com/q/24886', 0, NULL, NOW(), '', 0, 'DEdzry8A7J', '07W3q7wD'),
(NULL, 'Test 2.blend', '1qKPoB581V32cLDiDP5P2uO71o0VLmbna', '', 'ip 1', 'https://blender.stackexchange.com/q/24886', 0, NULL, NOW(), '', 0, NULL, 'b723tg78'),
(NULL, 'Test 3.blend', '1qKPoB581V32cLDiDP5P2uO71o0VLmbna', '', 'ip 1', 'https://blender.stackexchange.com/q/24886', 0, NULL, NOW(), '', 0, NULL, 'qq6wert5'),
(NULL, 'Test 4.blend', '1qKPoB581V32cLDiDP5P2uO71o0VLmbna', '', 'ip 1', 'https://blender.stackexchange.com/q/24886', 0, NULL, NOW(), '', 0, NULL, 'i32oh503'),
(NULL, 'Test 5.blend', '1qKPoB581V32cLDiDP5P2uO71o0VLmbna', '', 'ip 1', 'https://blender.stackexchange.com/q/24886', 0, NULL, NOW(), '', 0, NULL, 'Os4bHoI3');

INSERT INTO `accesses` (`accept`, `fileId`, `type`, `ip`, `val`, `date`, `id`, `message`) VALUES
(0, '07W3q7wD', 'view', 'ip 1', '', '2018-07-05 12:12:30', 0, NULL),
(0, '07W3q7wD', 'view', 'ip 1', '', '2018-07-13 06:35:01', 1, NULL),
(0, '07W3q7wD', 'view', 'ip 2', '', '2018-07-13 06:35:01', 2, NULL),
(0, '07W3q7wD', 'view', 'ip 3', '', '2018-07-13 06:35:01', 3, NULL),

(0, '07W3q7wD', 'download', 'ip 1', '', '2018-07-05 12:12:30', 4, NULL),
(0, '07W3q7wD', 'download', 'ip 1', '', '2018-07-13 06:35:01', 5, NULL),
(0, '07W3q7wD', 'download', 'ip 2', '', '2018-07-13 06:35:01', 6, NULL),
(0, '07W3q7wD', 'download', 'ip 3', '', '2018-07-13 06:35:01', 7, NULL),

(0, 'b723tg78', 'download', 'ip 1', '', '2018-07-05 12:12:30', 8, NULL),
(0, 'b723tg78', 'download', 'ip 1', '', '2018-07-13 06:35:01', 9, NULL),
(0, 'b723tg78', 'download', 'ip 3', '', '2018-07-13 06:35:01', 10, NULL);

INSERT INTO `users` (`id`,`stackId`,`role`,`email`,`username`,`password`) VALUES
--- TODO move stack id the .env
('DEdzry8A7J','63102',0,'john.doe@blend-exchange.com
','John Doe',null)
