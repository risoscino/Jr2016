<?php 
include 'database.php';
date_default_timezone_set( 'UTC' );
class TSQuery {
	var $db_conn;
	public function __construct( $conn ) {
		$this->db_conn = $conn;
	}
	function add_user ( $user_name, $user_pass, $user_email, $user_level ) {
		$user_level = 0;
		$now = date('Y-m-d');
		$stmt = $this->db_conn->prepare("INSERT INTO users (user_name, user_pass, user_email ,user_date, user_level) VALUES (?, ?, ?, ?, ?)");
		$stmt->bind_param( 'ssssi', $user_name, $user_pass, $user_email, $now, $user_level );
		$stmt->execute();
		if ( $stmt == true) {
			echo 'Successfully registered. You can now <a href="signin.php">sign in</a> and start posting!';
		} else {
		echo 'Something went wrong while registering. Please try again later.';
			echo mysql_error();
		}
	}
	function get_user_email ( $user_email ) {
		$stmt = $this->db_conn->prepare("SELECT user_email, user_id FROM users WHERE user_email = ?");
		$stmt->bind_param( 's', $user_email );
		$stmt->execute();
		$result = $stmt->get_result();
		if ( $result->num_rows == 0 ) {
			return NULL;
		} else {
			$row = $result->fetch_assoc();
			return $row;
		}
	}
	function reset_password ( $code, $user_id ) {
		$stmt = $this->db_conn->prepare("UPDATE users SET pass_reset = ? WHERE user_id = ?");
		$stmt->bind_param( 'ss', $code, $user_id );
		$stmt->execute();
		return $stmt;
	}
	function sign_in ( $user_name, $user_pass ) {
		$sign_in = "SELECT user_id, user_name, user_level
				FROM users
				WHERE user_name = '" . mysqli_real_escape_string( $this->db_conn, $user_name ) . "'
				AND user_pass = '" . $user_pass . "'";
			$result = $this->db_conn->query( $sign_in );
		if ( $result == true ) {
			return $result;
		} else {
			return false;
		}
	}
	function add_category ( $cat_name, $cat_description ) {
		$stmt = $this->db_conn->prepare( "INSERT INTO categories(cat_name, cat_description) VALUES (?, ?)" );
		$stmt->bind_param( 'ss', $cat_name, $cat_description );
		$stmt->execute();
		return $stmt;
	}
	function create_user_table () {
		$create_user_table = "CREATE TABLE users (
		user_id     INT(8) NOT NULL AUTO_INCREMENT,
		user_name   VARCHAR(30) NOT NULL,
		user_pass   VARCHAR(255) NOT NULL,
		user_email  VARCHAR(255) NOT NULL,
		user_date   DATETIME NOT NULL,
		user_level  INT(8) NOT NULL,
		pass_reset int(11) DEFAULT NULL,
		UNIQUE INDEX user_name_unique (user_name),
		PRIMARY KEY (user_id)
	)";
		if ( $this->db_conn->query( $create_user_table ) === TRUE ) {
			echo "Table users created successfully <br />";
		} else {
			echo "Error creating users table: " . $this->db_conn->error;
		}
	}
	function display_categories () {
		$stmt = $this->db_conn->prepare("SELECT cat_id, cat_name, cat_description FROM categories" );
		$stmt->execute();
		return $stmt->get_result();
	}
	function get_category_by_id ( $id ) {
		$stmt = $this->db_conn->prepare( "SELECT cat_id, cat_name, cat_description FROM categories WHERE cat_id = ?" );
		$stmt->bind_param( 's', $id );
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}
	function create_posts_table () {
		$create_posts_table = "CREATE TABLE posts (
			post_id INT(8) NOT NULL AUTO_INCREMENT,
			post_content TEXT NOT NULL,
			post_date DATETIME NOT NULL,
			post_topic INT(8) NOT NULL,
			post_by INT(8) NOT NULL,
			PRIMARY KEY (post_id)
		)";
		if ( $this->db_conn->query( $create_posts_table) === TRUE) {
			echo "Table posts created successfully <br />";
		} else {
			echo "Error creating posts table: " . $this->db_conn->error;
		}
	}
	function create_categories_table () {
		$create_categories_table = "CREATE TABLE categories (
			cat_id INT(8) NOT NULL AUTO_INCREMENT,
			cat_name VARCHAR(255) NOT NULL,
			cat_description VARCHAR(255) NOT NULL,
			UNIQUE INDEX cat_name_unique (cat_name),
			PRIMARY KEY (cat_id)
		)";
		if ( $this->db_conn->query( $create_categories_table ) === TRUE) {
			echo "Table categories created successfully <br />";
		} else {
			echo "Error creating categories table: " . $this->db_conn->error;
		}
	}
	function get_user_by_id ( $user_id ) {
		$stmt = $this->db_conn->prepare( "SELECT * FROM users WHERE user_id = ?" );
		$stmt->bind_param( 's', $user_id );
		$stmt->execute();
		$result = $stmt->get_result();
		if ( $result->num_rows == 0 ) {
			return NULL;
		} else {
			$row = $result->fetch_assoc();
			return $row;
		}
	}
	function update_password ( $new_pass, $user_id ) {
		$user_id = (int)$user_id;
		$stmt = $this->db_conn->prepare( "UPDATE users SET user_pass = ? WHERE user_id = ?" );
		$stmt->bind_param( 'si', $new_pass, $user_id );
		$stmt->execute();
		return $stmt;
	}
	function update_pass_reset ( $user_id ) {
		$stmt = $this->db_conn->prepare( "UPDATE users SET pass_reset = 0 WHERE user_id = ?" );
		$stmt->bind_param( 's', $user_id );
		$stmt->execute();
		return $stmt;
	}
	function create_votes_table () {
		$create_votes_table = "CREATE TABLE votes (
			id int NOT NULL AUTO_INCREMENT,
			topic_id int(11) NOT NULL DEFAULT 0,
			vote int(11) DEFAULT NULL,
			user_id int(11) NOT NULL DEFAULT 0,
			UNIQUE KEY(user_id, topic_id),
			PRIMARY KEY (id),
			CONSTRAINT votes_ibfk_1 FOREIGN KEY (user_id) REFERENCES users (user_id),
			CONSTRAINT votes_ibfk_2 FOREIGN KEY (topic_id) REFERENCES topics (topic_id)
		)";
	if ( $this->db_conn->query($create_votes_table ) === TRUE) {
			echo "Table votes created successfully <br />";
		} else {
			echo "Error creating votes table: " . $this->db_conn->error;
		}
	}
	function create_topics_table () {
		$create_topics_table = "CREATE TABLE topics (
			topic_id INT(8) NOT NULL AUTO_INCREMENT,
			topic_subject VARCHAR(255) NOT NULL,
			topic_date DATETIME NOT NULL,
			topic_cat INT(8) NOT NULL,
			topic_by INT(8) NOT NULL,
			PRIMARY KEY (topic_id)
		)";
	if ( $this->db_conn->query($create_topics_table ) === TRUE) {
			echo "Table topics created successfully <br />";
		} else {
			echo "Error creating topics table: " . $this->db_conn->error;
		}
	}
	function get_topics_by_cat_id ( $id ) {
 		$stmt = $this->db_conn->prepare( "SELECT topic_id, topic_subject, topic_date, topic_cat FROM topics WHERE topic_cat = ? " );
		$stmt->bind_param( 's', $id );
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}
	function begin_work () {
		$begin_work = "BEGIN WORK;";
		return $this->db_conn->query( $begin_work );
	}
	function add_topic ( $topic_subject, $topic_cat, $topic_by ) {
		$now = date('Y-m-d');
		$stmt = $this->db_conn->prepare("INSERT INTO topics(topic_subject, topic_date, topic_cat, topic_by) VALUES(?,?,?,?)");
		$stmt->bind_param( 'ssss', $topic_subject, $now, $topic_cat, $topic_by );
		$stmt->execute();
		if ( $stmt == true) {
			return $stmt;
		} else {
			echo 'Something went wrong while adding Topic. Please try again later.';
			//echo mysql_error()
		}
	}
	function roll_back () {
		$roll_back = "ROLLBACK";
		return $this->db_conn->query( $roll_back );
	}
	function add_post ( $post_content, $topic_id, $user_id ) {
		$now = date('Y-m-d');
		$stmt = $this->db_conn->prepare("INSERT INTO posts(post_content, post_date, post_topic, post_by) VALUES(?,?,?,?)");
		$stmt->bind_param( 'sssi', $post_content, $now, $topic_id, $user_id);
		$stmt->execute();
		if ( $stmt == true ) {
			return $stmt;
		} else {
			echo 'Something went wrong while adding Post. Please try again later.';
			//echo mysql_error()
		}
	}
	function get_post_by_id ( $id ) {
	$stmt = $this->db_conn->prepare("SELECT posts.post_topic, posts.post_content, posts.post_date,
									 posts.post_by, users.user_id, users.user_name 
									FROM posts
									LEFT JOIN users
									ON posts.post_by = users.user_id
									WHERE posts.post_topic = ?");
	$stmt->bind_param( 's', $id );
	$stmt->execute();
	$result = $stmt->get_result();
	return $result;
	}
	function get_topics_by_id ( $id ) {
		$stmt = $this->db_conn->prepare( "SELECT topic_id, topic_subject, topic_date, topic_cat FROM topics WHERE topic_id = ? " );
		$stmt->bind_param( 's', $id );
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}
	function commit () {
		$commit = "COMMIT";
		return $this->db_conn->query( $commit );
	}
	function get_most_recent_topic ( $topic_cat ) {
		$stmt = $this->db_conn->prepare( "SELECT topic_subject, topic_date, topic_id, MAX(topic_id) FROM topics WHERE topic_cat = ?" );
		$stmt->bind_param( 'i', $topic_cat);
		$stmt->execute();
		$result = $stmt->get_result();
		$result = $result->fetch_assoc();
		return $result;
	}
	function add_reply ( $reply_content, $id, $user_id ) {
		$now = date('Y-m-d');
		$stmt = $this->db_conn->prepare( "INSERT INTO posts(post_content, post_date, post_topic, post_by) VALUES ( ?, ?, ?, ?)" );
		$stmt->bind_param( 'ssss', $reply_content, $now, $id, $user_id );
		$stmt->execute();
		return $stmt;
	}
	function add_vote ( $topic_id, $vote, $user_id ) {
		$now = date('Y-m-d');
		$stmt = $this->db_conn->prepare( "INSERT INTO votes(topic_id, vote, user_id) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE vote = IF(VALUES(vote) = vote, 0, VALUES(vote)) " );
		$stmt->bind_param( 'iii', $topic_id, $vote, $user_id );
		$stmt->execute();
		return $this->get_vote( $topic_id, $user_id );
	}
	function get_vote ( $topic_id, $user_id ) {
		$stmt = $this->db_conn->prepare( " SELECT vote FROM votes WHERE topic_id = ? AND user_id = ?" );
		$stmt->bind_param( 'ii', $topic_id, $user_id );
		$stmt->execute();
		$result = $stmt->get_result();
		if ( $result->num_rows == 0 ) {
			return NULL;
		} else {
			$row = $result->fetch_assoc();
			return $row['vote'];
		}
	}
	function get_total_vote ( $topic_id ) {
		$stmt = $this->db_conn->prepare("SELECT SUM(vote) AS total_vote, COUNT(vote) as users_voted FROM votes WHERE vote <> 0 AND topic_id = ?");
		$stmt->bind_param( 'i', $topic_id );
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		return array_map('intval', $row);
	}
}