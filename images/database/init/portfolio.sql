CREATE DATABASE portfolio;

\c portfolio

CREATE TABLE projects (
    id SERIAL PRIMARY KEY,
    title TEXT,
    body TEXT,
    href TEXT,
    image TEXT
);

INSERT INTO projects (id, title, body, href, image)
VALUES (DEFAULT, 'Art Portfolio', 'Made with <b>HTML</b>, <b>CSS</b> and <b>JS</b>', 'https://art.matthijsverheijen.com', '/images/projects/art.png');

INSERT INTO projects (id, title, body, href, image)
VALUES (DEFAULT, 'Snake Clone', 'Made with <b>P5</b> (Xbox controller support)', 'https://snake.matthijsverheijen.com', '/images/projects/snake.png');

INSERT INTO projects (id, title, body, href, image)
VALUES (DEFAULT, 'Live Chat', 'Made with <b>Express </b> and <b>Socket.io</b>', 'https://chat.matthijsverheijen.com', '/images/projects/chat.png');

INSERT INTO projects (id, title, body, href, image)
VALUES (DEFAULT, 'Sum Genaratort', 'Made with <b>PHP</b>', 'https://sum-genarator.matthijsverheijen.com/', '/images/projects/sum.png');

INSERT INTO projects (id, title, body, href, image)
VALUES (DEFAULT, 'Staff management system', 'Made with <b>PHP</b> and <b>Laravel</b>', 'https://laravel.matthijsverheijen.com', '/images/me.jpg');

