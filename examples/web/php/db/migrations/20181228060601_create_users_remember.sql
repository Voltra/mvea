-- migrate:up
CREATE TABLE user_remember(
  id int PRIMARY KEY AUTO_INCREMENT,
  user_id int,
  remember_id varchar(255),
  remember_hash varchar(255),
  created_at timestamp DEFAULT current_timestamp,
  updated_at timestamp,
  FOREIGN KEY(user_id) REFERENCES users(id)
);

-- migrate:down
DROP TABLE user_remember;

