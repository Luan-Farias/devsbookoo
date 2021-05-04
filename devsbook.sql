/**
 * Creating Users Table
 */

CREATE TABLE users (
	id SERIAL PRIMARY KEY,
  	email VARCHAR(128) NOT NULL,
  	password VARCHAR(256) NOT NULL,
    name VARCHAR(128) NOT NULL,
    birthdate DATE NOT NULL,
    city VARCHAR(128) DEFAULT NULL,
    work VARCHAR(128) DEFAULT NULL,
    avatar VARCHAR(128) DEFAULT 'avatar.jpg',
    cover VARCHAR(128) DEFAULT 'cover.jpg',
    token VARCHAR(256) DEFAULT NULL
);



/**
 * Creating Posts Table
 */

CREATE TABLE posts (
	id SERIAL PRIMARY KEY,
  	id_user INTEGER REFERENCES users(id) NOT NULL,
  	type VARCHAR(16) NOT NULL,
  	body TEXT NOT NULL,
  	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/**
 * Creating User Relations Table
 */

CREATE TABLE user_relations (
	id SERIAL PRIMARY KEY,
  	user_from INTEGER REFERENCES users(id) NOT NULL,
  	user_to INTEGER REFERENCES users(id) NOT NULL
);


/**
 * Creating Posts Likes Table
 */

CREATE TABLE post_likes (
	id SERIAL PRIMARY KEY,
  	id_user INTEGER REFERENCES users(id) NOT NULL,
  	id_post INTEGER REFERENCES posts(id) NOT NULL,
  	created_at TIMESTAMP DEFAUlT CURRENT_TIMESTAMP
);


/**
 * Creating Posts Comments Table
 */

CREATE TABLE post_comments (
	id SERIAL PRIMARY KEY,
  	id_user INTEGER REFERENCES users(id) NOT NULL,
  	id_post INTEGER REFERENCES posts(id) NOT NULL,
  	body TEXT NOT NULL,
  	created_at TIMESTAMP DEFAUlT CURRENT_TIMESTAMP
);
