
/*CREATE TABLE albums_create (id INTEGER PRIMARY KEY AUTOINCREMENT, artist VARCHAR(100) NOT NULL, title VARCHAR(100) NOT NULL);
*/
INSERT INTO albums_create (artist, title) VALUES ('The Military Wives', 'In My Dreams');
INSERT INTO albums_create (artist, title) VALUES ('Adele', '21');
INSERT INTO albums_create (artist, title) VALUES ('Lana Del Rey', 'Born To Die');
INSERT INTO albums_create (artist, title) VALUES ('Gotye', 'Making Mirrors');


INSERT INTO "albums_create" ("artist", "title")
VALUES
  ("Avril Lavigne", "The Best Damn Thing"),
  ("Rihanna", "Good Girl Gone Bad"),
  ("Bruno Mars", "Unorthodox Jukebox"),
  ("P!nk", "The Truth About Love"),
  ("David Bowie", "The Next Day"),
  ("The Script", "#3"),
  ("Ellie Goulding", "Halcyon"),
  ("Bruno Mars", "Doo-Wops & Hooligans"),
  ("Calvin Harris", "18 Months"),
  ("Rihanna", "Loud"),
  ("One Direction", "Take Me Home"),
  ("Maroon 5", "Overexposed"),
  ("Linkin Park", "Meteora"),
  ("Bon Jovi", "What About Now"),
  ("Taylor Swift", "Red"),
  ("David Guetta", "Nothing But the Beat Ultimate"),
  ("David Bowie", "Best of Bowie"),
  ("Rihanna", "Unapologetic"),
  ("Jimi Hendrix", "People, Hell & Angels"),
  ("Ed Sheeran", "+"),
  ("One Direction", "Up All Night"),
  ("Fun.", "Some Nights"),
  ("Justin Bieber", "Believe Acoustic"),
  ("Justin Timberlake", "Justified"),
  ("Passenger", "All the Little Lights"),
  ("The Killers", "Battle Born"),
  ("Michael Bublé", "To Be Loved"),
  ("Daughter", "If You Leave"),
  ("Eminem", "Curtain Call"),
  ("Rita Ora", "Ora"),
  ("Train", "California 37"),
  ("Jessie Ware", "Devotion"),
  ("Bruno Mars", "Unorthodox Jukebox");

/*
 ALTER TABLE albums_create ADD 'imagepath' VARCHAR(255);
 ALTER TABLE albums_create ADD 'songpath' VARCHAR(255);


 ALTER TABLE albums_create ADD 'song' TEXT; */

  /* DROP TABLE albums; */
