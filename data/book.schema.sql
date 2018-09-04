/* CREATE TABLE book (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   author varchar(100) NOT NULL,
   title varchar(100) NOT NULL
);

INSERT INTO book (author, title) VALUES ('Dennis Ritchie', 'C Programming');
INSERT INTO book (author, title) VALUES ('James gosling', 'Java Programming');
INSERT INTO book (author, title) VALUES ('Rasmus Lerdorf', 'Programming PHP');
*/
ALTER TABLE `book` ADD `imagepath` VARCHAR(255) /*NOT NULL AFTER 'imagepath'*/;
