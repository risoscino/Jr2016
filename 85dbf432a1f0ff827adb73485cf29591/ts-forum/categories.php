<?php
include 'database.php';
include 'header.php';
include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );
$result = $ts_query->get_category_by_id( $_GET['id'] );
if ( !$result ) {
	echo 'The category could not be displayed, please try again later.' . mysqli_error();
} else {
	if ( $result->num_rows == 0 ) {
		echo 'This category does not exist.';
	} else {
		//display category data
		while( $row = $result->fetch_assoc() ) {
			echo '<h2>Topics in ′' . $row['cat_name'] . '′ category</h2>';
		}
		$result = $ts_query->get_topics_by_cat_id( $_GET['id'] );
		if( !$result ) {
			echo 'The topics could not be displayed, please try again later.';
		} else {
			if ( $result->num_rows == 0 ) {
				echo 'There are no topics in this category yet. Add a topic <a href=create_topic.php>here!</a>';
			} else {
				echo '<table class="table table-bordered">
					  <tr>
						<th>Topic</th>
						<th>Created On</th>
						<th>Votes</th>
					  </tr>';
				while ( $row = $result->fetch_assoc() ) {
					echo '<tr>';
						echo '<td class="leftpart">';
							echo '<h3><a href="topic.php?id=' . $row['topic_id'] . '">' . $row['topic_subject'] . '</a><h3>';
						echo '</td>';
						echo '<td class="right">';
							echo date( 'm-d-Y', strtotime( $row['topic_date'] ) );
						echo '</td>';
						echo '<td class="right">';
							$votes = $ts_query->get_total_vote( $row['topic_id'] );
							if ( isset( $votes['total_vote'] ) ) {
								echo $votes['total_vote'];
							} else {
								echo 0;
							}
						echo '</td>';
					echo '</tr>';
				}
			}
		}
	}
}
 
include 'footer.php';
?>