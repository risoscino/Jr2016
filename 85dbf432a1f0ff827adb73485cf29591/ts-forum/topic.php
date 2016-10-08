<?php
session_start();
include 'database.php';
include 'header.php';
include 'class-ts-query.php';

$ts_query = new TSQuery( $conn );
$user_id = (int)$_SESSION['user_id'];
$posts = $ts_query->get_post_by_id( $_GET['id'] );
$topics = $ts_query->get_topics_by_id( $_GET['id'] );
$current_vote = $ts_query->get_vote( $_GET['id'], $user_id );
$totals = $ts_query->get_total_vote( $_GET['id'] );

if ( $topics->num_rows == 0 ) {
		echo 'There are no topics in this category yet.';
} else {
	while ( $row = $topics->fetch_assoc() ) {
		echo'<table class="table table-bordered">';
		?>
			<thead>
				<input type="hidden" id="topic" name="topic_id" value="<?php echo $_GET['id']; ?>">
				<tr>
					<td colspan='4'><?php echo $row['topic_subject'];?>
						<button class="vote <?php echo $current_vote == 1 ? "active" : "" ?>" type="submit" value="1"> 
							<img src="up_button_2.png" height="35">
						</button> 
						<span id="total"><?php echo $totals['total_vote'];?> </span>
						<button class="vote <?php echo $current_vote == -1 ? "active" : "" ?>" type="submit" value="-1"> 
							<img src="down_button.png" height="35"/>
						</button>
						Users Voted: <span id="total_voters"><?php if ( isset($totals['users_voted']) ) { echo $totals['users_voted']; } else { echo "No Votes"; } ?> </span>
					</td>
				</tr>
			</thead>
			<script>
			$('button.vote').click(
				function(){
				var topic = $('#topic').val();
				$.ajax({
					url: "vote-ajax-endpoint.php",
					type: "POST",
					dataType: 'json',
					data:{"vote" : this.value,
						  "topic_id" : topic
						 },
					success: function(data){
						console.log(data);
						$('button.vote.active').removeClass('active');
						$('button.vote[value='+data.votes+']').addClass('active');
						$('#total').text(data.total_vote);
						$('#total_voters').text(data.users_voted);
				}
			});
		});
			</script>
			<?php
		if ( $posts->num_rows > 0 ) {
			while ( $post = $posts->fetch_assoc() ) {
				echo '<tr>';
					echo '<td class="rightpart">';
					echo $post['user_name'] . "</br>" . date( 'm-d-Y', strtotime( $post['post_date'] ) );
					echo '</td>';
				echo '<td class="leftpart">';
					echo $post['post_content'];
				echo '</td>';
				echo '</tr>';
			}
		}
		?>
		</table>
		<h3> Reply to This Topic <h3>
		<form method="post" action='reply.php?id=<?php echo $_GET['id']; ?>'>
			<textarea name="reply-content" required></textarea>
			<input class="btn btn-primary" type="submit" value="Submit reply" />
		</form>
		<?php
		
	}
}