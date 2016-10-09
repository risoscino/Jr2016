<?php
include 'database.php';
include 'header.php';

include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );
$result = $ts_query->display_categories();
if ( !$result ) {
	echo 'The categories could not be displayed, please try again later.';
} else {
	if( $result->num_rows == 0 ) {
		echo 'No categories defined yet.';
	} else {
		//prepare the table
		echo '<table class="table table-bordered">
				<tr>
					<th>Category</th>
					<th>Last topic</th>
				</tr>'; 
		while( $row = mysqli_fetch_assoc( $result ) ) {
			$recent_topic = $ts_query->get_most_recent_topic( $row['cat_id'] );
				echo '<tr>';
					echo '<td class="leftpart">';
						echo '<h3><a href="categories.php?id=' . $row['cat_id'] .'">' . $row['cat_name'] . '</a></h3>' . $row['cat_description'];
					echo '</td>';
						echo '<td class="right">';
							echo '<a href="topic.php?id=' . $recent_topic['topic_id'] . '">' . $recent_topic['topic_subject'] . '</a>';
						echo '</td>';
					}
				}
			echo '</tr>';
		}
include 'footer.php';
?>