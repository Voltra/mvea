-- migrate:up
START TRANSACTION;
	DELETE FROM users WHERE username = "admin"; /*remove it if it exists*/

	INSERT INTO users(username, password)
	VALUES("admin", "$2y$12$xqSfcw43hruudKU22/YtB.sFzPnNXeC44.b1aZE/YNnGFRYuqyBBy");
	/*
	password is:
	admin
	*/

	SELECT @id := last_insert_id();
	INSERT INTO admins(user_id) VALUES(@id);
COMMIT;

-- migrate:down
DELETE FROM users WHERE username = "admin";
