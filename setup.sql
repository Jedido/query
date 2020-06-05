-- Jed Chen
-- CSE 154 AG
-- This sets up 4 tables used in the Query game.

CREATE DATABASE query;
USE query;
DROP TABLE IF EXISTS Names;
DROP TABLE IF EXISTS Hints;
DROP TABLE IF EXISTS Password1;
DROP TABLE IF EXISTS TableQuery;

CREATE TABLE Names(name varchar(20), size varchar(60));
CREATE TABLE Hints(id int, required int, content varchar(80), letters varchar(3));
CREATE TABLE Password1(n int, phrase varchar(50));
CREATE TABLE TableQuery(front varchar(8), mid varchar(8), back varchar(8));

-- Names of the tables, and their "size" (which really isn't size)
INSERT INTO Names(name, size)
VALUES ('Hints', '15'),
       ('Names', '8'),
       ('Password1', 'begingame'),
       ('TableFake', 'Last Puzzle part 1 hint: read diagonally'),
       ('TableQuery', 'Last Puzzle Table'),
       ('TableY', 'None'),
       ('TableZ', 'None'),
       ('The LIMIT hint (1/2)', '1st word of first content sorted by letters, then by required.');

-- The main chunk of the table data, with an id, required, content, and letters column
INSERT INTO Hints(id, required, content, letters)
VALUES (1, 0, 'Rule 1: No need to look at source. Query for hints!', 'lo'),
       (2, 0, 'Rule 2: Don\'t put spaces in the boxes. They won\'t be read.', 'wo'),
       (3, 1, 'Hint for WHERE: nth character of each phrase, in order.', 'rd'),
       (4, 1, 'How many required=1 can you find? That ID is wrong!', 'er'),
       (5, 1, 'IDs 6 through 9 are hints to the SELECT password. ', 'so'),
       (6, 1, 'The first word of the "wrong" ID.', 'up'),
       (7, 1, 'wrong IDth word of id=3.', 'hel'),
       (8, 1, 'right IDs added up.', 'rld'),
       (9, 1, 'separate with underscore.', 'ejo'),
       (10, -1, 'there are a lot of entries with id 10.', 'nic'),
       (10, 1, 'you probably won\'t be able to see them all.', 'bw'),
       (10, 0, 'It might be worth selecting different columns when you can (hint for ORDER BY).', 'ell'),
       (10, 0, 'Congratulations! Are you also looking at letters?', 'ino'),
       (10, 0, 'over here you\'ve made it! The other tables may have more stuff.', 'do'),
       (10, 0, 'Last Puzzle part 1 hint: ordered by mid (you might not be here yet).', 'ne');

-- A smaller table that has a number and varchar
INSERT INTO Password1(n, phrase)
VALUES (3, 'HEllo'),
       (5, 'my naMe Is'),
       (10, 'HelLowORld'),
       (10, 'The LIMIT hint (2/2): don\'t look at required=1.');

-- The final puzzle table
INSERT INTO TableQuery
VALUES ('password', 'thetable', 'front'),
       ('frontis', 'and', 'password'),
       ('front', 'greater', 'toback'),
       ('mid', 'less', 'tofront'),
       ('thetable', 'isequal', 'thanback'),
       ('midis', 'notequal', 'tenth'),
       ('backis', 'outof', 'hints'),
       ('twelve', 'morethan', 'toentry');
