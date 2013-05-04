INSERT INTO groups (id, title) VALUES (1, 'Administrators');
INSERT INTO groups (id, title) VALUES (2, 'Punters');
INSERT INTO users (id, username, password, `group`) VALUES (-1, 'SYSTEM', sha1(''), 1);
INSERT INTO permissions (id, `key`, description) VALUES (1, 'SUPERUSER', 'Has every permission');
INSERT INTO privileges_g(`group`, `permission`) VALUES (1, 1);
INSERT INTO settings(`key`, value) VALUES
	('emailFrom', 'lps Admin'), 
	('siteTitle', 'lps Untitled Site'), 
	('timezone', 'Europe/London'), 
	('moneyFormatString', '&pound;%.2n'), 
	('siteDescription', 'Untitled site'),
	('cookieDomain', ''),
	('maintenanceMode', 1),
	('lanMode', 0),
	('masterConnectionAvailable', 0),
	('masterConnectionUrl', ''),
	('baseUrl', ''),
	('copyright', ''),
	('theme', 'airdale'),
	('globalAnnouncement', null),
	('newsFeature', 0),
	('galleryFeature', 1),
	('mailerAddress', 'nobody@example.com'),
	('defaultEmailSubject', 'Email from LPS'),
	('currency', 'GBP'),
	('paypalEmail', 'youremail@example.com'),
	('paypalCommission', '3.4%;.2'),
	('avatarMaxWidth', '80'), 
	('avatarMaxHeight', '80');
INSERT INTO venues (name) VALUES ('My First Venue');
