CREATE TABLE users (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL UNIQUE,
    email varchar(255),
    email_verified_at timestamp DEFAULT CURRENT_TIMESTAMP,
    password varchar(255) NOT NULL,
    usertype varchar(20) DEFAULT 'User' CHECK (Usertype IN ('Admin', 'Artist', 'User')),
    remember_token varchar(100),
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp DEFAULT CURRENT_TIMESTAMP,
    gender varchar(20) CHECK (Gender IN ('Woman', 'Man', 'Other')),
    year int,
    blocked boolean default 0,
    profile_pic varchar(300) DEFAULT 'https://isobarscience.com/wp-content/uploads/2020/09/default-profile-picture1.jpg',
    bio varchar(500) DEFAULT 'Music lover | Exploring new sounds | Creating vibes | Always tuned in to the beat | Passionate about rhythm and melody | Let’s share the soundtrack of life!',
    country varchar(50),
    PRIMARY KEY (id)
);

CREATE TABLE sessions (
	id varchar(255) NOT NULL,
	user_id bigint(20),
	ip_address varchar(45),
	user_agent text,
	payload longtext,
	last_activity int(11),
	PRIMARY KEY (id)
);

CREATE TABLE password_reset_tokens (
	email varchar(255),
    token varchar(255),
    created timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (email)
);

CREATE TABLE jobs (
	id bigint(20) NOT NULL AUTO_INCREMENT,
    queue varchar(255),
    payload longtext,
    attempts tinyint(3),
    reserved_at int(10),
    available_at int(10),
    created_at int(10),
    PRIMARY KEY (id)
);

CREATE TABLE job_batches (
		id varchar(255),
        name varchar(255),
        total_jobs int(11),
        pending_jobs int(11),
        failed_jobs int(11),
        options mediumtext,
        cancelled_at int(11),
        created_at int(11),
        finished_at int(11),
		PRIMARY KEY (id)
);

CREATE TABLE failed_jobs (
		id bigint(20) NOT NULL AUTO_INCREMENT,
        uuid varchar(255),
        connection text,
        queue text,
        payload longtext,
        exception longtext,
        failed_at timestamp DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id)
);

CREATE TABLE cache (
	`key` varchar(255),
    value mediumtext,
    expiration int(11),
    PRIMARY KEY (`key`)
);

CREATE TABLE cache_locks (
	`key` varchar(255),
    owner varchar(255),
    expiration int(11),
    PRIMARY KEY (`key`)
);

CREATE TABLE artists (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    name varchar(30) NOT NULL,
    registered boolean DEFAULT 0,
    artist_pic varchar(400) DEFAULT 'https://www.onlinelogomaker.com/blog/wp-content/uploads/2017/06/music-logo-design.jpg',
    user_id bigint(20),
    description varchar(1000) DEFAULT '',
    info varchar(1000) DEFAULT '',
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE albums (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    name varchar (100) NOT NULL,
    artist_id bigint(20) NOT NULL,
    cover varchar(300),
    release_year int,
    average_rating int,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (artist_id) REFERENCES artists(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE songs (
	id bigint(20) NOT NULL AUTO_INCREMENT,
    album_id bigint(20) NOT NULL,
    name varchar (255),
    number int,
    length varchar (5),
    PRIMARY KEY (id),
    FOREIGN KEY (album_id) REFERENCES albums(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE reviews (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    album_id bigint(20) NOT NULL,
    title varchar(100),
    text varchar(4000) DEFAULT "At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.",
    rating float NOT NULL CHECK (rating BETWEEN 1 AND 100),
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (album_id) REFERENCES albums(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE lists (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    user_id bigint(20) NOT NULL,
    name varchar(100),
    list_pic varchar(500) DEFAULT 'https://static.vecteezy.com/system/resources/previews/049/624/353/non_2x/party-playlist-icon-design-vector.jpg',
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE lists_elements (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    list_id bigint(20) NOT NULL,
    album_id bigint(20) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (list_id) REFERENCES lists(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (album_id) REFERENCES albums(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE followings (
	id bigint(20) NOT NULL AUTO_INCREMENT,
    follower_id bigint(20) NOT NULL,
    following_id bigint(20) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (follower_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (following_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE genres (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    name varchar(50) NOT NULL,
    album_id bigint(20) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (album_id) REFERENCES albums(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE messages (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    sender_id bigint(20) NOT NULL,
    receiver_id bigint(20) NOT NULL,
    text varchar(500) DEFAULT 'hey',
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (sender_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
);
