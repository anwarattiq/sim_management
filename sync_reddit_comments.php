<?php
	include('admin_elements/admin_header.php');
	$module = 'reddit_comments';
	$module_caption = 'Comment';
	$tbl_name = $tbl_prefix.$module;
	$error_message = ''; $success_message = '';
	############################################

	/**
	**************************************
	 @@@ GET ALL VARIABLES ADD/UPDATE @@@
	**************************************
	**/
	if ($action=="sync_$module"){
		$my_subreddit		= $mysqli->real_escape_string(stripslashes($_REQUEST['my_subreddit']));
		$permalink			= $mysqli->real_escape_string(stripslashes($_REQUEST['permalink']));
		$post_id				= $mysqli->real_escape_string(stripslashes($_REQUEST['post_id']));

	} else {
		$my_subreddit		= '';
		$permalink			= '';
		$post_id				= '';
	}

	/////////////////////////////////////////////
?>
<div id="contentwrapper">
	<div class="main_content">
		<?php include('admin_elements/breadcrumb.php');?>
		<?php include('admin_elements/errors.php');?>

		<form method="post" id="frm<?php echo $module;?>" name="frm<?php echo $module;?>" action="sync_<?php echo $module;?>.php">
		<input type="hidden" name="action" id="action" value="sync_<?php echo $module;?>" />
		<input type="hidden" name="post_id" id="post_id" value="post_id" />

		<div class="row">
			<div class="col-sm-6 col-md-6">
			<h3 class="heading">COMMENTS from REDDIT</h3>

				<div class="formSep">
					<div class="row">
						<div class="col-sm-12 col-md-12">
							<?php

							/**
							**************************************
						   @@@ Sync @@@
							**************************************
							**/
							if ($action=="sync_$module" && !empty($permalink)){

									$permalink					= rtrim($permalink, '/');
									// echo $permalink; echo '<br />';

										//GET COMMENTS
										// $json = (array) json_decode(file_get_contents("https://www.reddit.com/r/dubai/comments/5nbjlw/the_end_of_the_story.json?sort=new"));
										$json = (array) json_decode(file_get_contents("https://www.reddit.com".$permalink.".json?sort=new"));
										// echo $permalink; echo '<br />';
										$response = $json['1']->data;
										$comments = $response->children;

										foreach ($comments as $object){
											$replies_json = $object->data->replies;
											$parent = $object->data->body;
											// print_r($parent);

											$parent_id 		= $object->data->parent_id;
											$comment_id 	= $object->data->id;
											$body 				= $object->data->body;
											$body_html 		= $object->data->body_html;
											$author 			= $object->data->author;
											$ups 					= $object->data->ups;
											$downs 				= $object->data->downs;
											$subreddit_id = $object->data->subreddit_id;
											$link_id 			= $object->data->link_id;
											$permalink 		= $object->data->permalink;
											$created_utc 	= $object->data->created_utc;

											// echo $parent_id;	echo '<br />';
											// echo $comment_id; echo '<br />';
											// echo $body; 			echo '<br />';
											// echo $author; 		echo '<br />';
											// echo $ups; 				echo '<br />';
											// echo $downs; 			echo '<br />';
											// echo $subreddit_id; echo '<br />';
											// echo $link_id; 		echo '<br />';
											// echo $permalink; 	echo '<br />';
											// echo $created_utc;echo '<br />';

											$body 	= $mysqli->real_escape_string(stripslashes($body));
											if (!empty($body_html)){
												$body 	= $mysqli->real_escape_string(stripslashes($body_html));
											}

											// echo "INSERT INTO `$tbl_name`(post_id, parent_id, comment_id, body, author,ups, downs, subreddit_id, link_id, permalink, created_utc) VALUES ('".$post_id."', '".$parent_id."', '".$comment_id."', '".$body."', '".$author."', '".$ups."', '".$downs."', '".$subreddit_id."', '".$link_id."', '".$permalink."', '".$created_utc."')"; echo '<br />';

											$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(post_id, parent_id, comment_id, body, author,ups, downs, subreddit_id, link_id, permalink, created_utc) VALUES ('".$post_id."', '".$parent_id."', '".$comment_id."', '".$body."', '".$author."', '".$ups."', '".$downs."', '".$subreddit_id."', '".$link_id."', '".$permalink."', '".$created_utc."')");

											if ($insert_row){
												echo '<i class="splashy-check"></i> '. $body; echo ' <a href="https://www.reddit.com'.$permalink.'" target="_blank">&#128065;</a> <br /><br /><br />';

											} else {
												echo '<i class="splashy-error_small"></i> '. $body; echo ' <a href="https://www.reddit.com'.$permalink.'" target="_blank">&#128065;</a> <br /><br /><br />';

											}

											//*******************************
											// GRAB IF NESTED COMMENTS EXISTS
											//*******************************
											if ($replies_json){

												$nested_replies = $replies_json;
												$nested_replies = $nested_replies;

												foreach ($nested_replies as $reply){
													if ($reply!='Listing'){

														foreach ($reply as $reply_details){
															if ($reply_details!='' && $reply_details!='all_ads'){

																foreach ($reply_details as $child){

																		// echo '<strong>Child:</strong> ';
																		// print_r($child->data);

																		$parent_id 		= $child->data->parent_id;
								                    $comment_id 	= $child->data->id;
								                    $body 				= $child->data->body;
								                    $body_html 		= $child->data->body_html;
								                    $author 			= $child->data->author;
								                    $ups 					= $child->data->ups;
								                    $downs 				= $child->data->downs;
								                    $subreddit_id = $child->data->subreddit_id;
								                    $link_id 			= $child->data->link_id;
								                    $permalink 		= $child->data->permalink;
								                    $created_utc 	= $child->data->created_utc;

																		// echo $parent_id;	echo '<br />';
								                    // echo $comment_id; echo '<br />';
								                    // echo $body; 			echo '<br />';
								                    // echo $author; 		echo '<br />';
								                    // echo $ups; 				echo '<br />';
								                    // echo $downs; 			echo '<br />';
								                    // echo $subreddit_id; echo '<br />';
								                    // echo $link_id; 		echo '<br />';
								                    // echo $permalink; 	echo '<br />';
								                    // echo $created_utc;echo '<br />';

																		$body 	= $mysqli->real_escape_string(stripslashes($body));
																		if (!empty($body_html)){
																			$body 	= $mysqli->real_escape_string(stripslashes($body_html));
																		}

																		// echo "INSERT INTO `$tbl_name`(post_id, parent_id, comment_id, body, author,ups, downs, subreddit_id, link_id, permalink, created_utc) VALUES ('".$post_id."', '".$parent_id."', '".$comment_id."', '".$body."', '".$author."', '".$ups."', '".$downs."', '".$subreddit_id."', '".$link_id."', '".$permalink."', '".$created_utc."')"; echo '<br />';

																		$insert_row = $mysqli->query("INSERT INTO `$tbl_name`(post_id, parent_id, comment_id, body, author,ups, downs, subreddit_id, link_id, permalink, created_utc) VALUES ('".$post_id."', '".$parent_id."', '".$comment_id."', '".$body."', '".$author."', '".$ups."', '".$downs."', '".$subreddit_id."', '".$link_id."', '".$permalink."', '".$created_utc."')");

																		if ($insert_row){
																			echo '<i class="splashy-check"></i> '. $body; echo ' <a href="https://www.reddit.com'.$permalink.'" target="_blank">&#128065;</a> <br /><br /><br />';

																		} else {
																			echo '<i class="splashy-error_small"></i> '. $body; echo ' <a href="https://www.reddit.com'.$permalink.'" target="_blank">&#128065;</a> <br /><br /><br />';

																		}

																}//foreach
															}
														}//foreach
													}
												}//foreach
											}//endif
										}//foreach
							}//endif
							?>
						</div>
					</div>
				</div>

			</div>

			<div class="col-sm-6 col-md-6">

				<div class="row">
					<div class="col-sm-12 col-md-12">
						<!-- <h3>Standard search operators</h3> <a href="https://www.reddit.com/dev/api/" target="_blank">https://www.reddit.com/dev/api/</a><br /><br /> -->


						<div class="formSep">

							<div class="row">
								<div class="col-sm-12 col-md-12">
									<label>Permalink </label>
										<input name="permalink" id="permalink" value="<?php echo $permalink;?>" class="form-control" type="text">
									<span class="help-block">/r/dubai/comments/7sm8or/anyone_know_where_you_can_eat_plantain_bolon/</span>
								</div>
							</div>

						</div>

						<div class="form-actions">
							<button class="btn btn-gebo" type="submit">Sync <?php echo $module_caption;?> [LIVE]</button>
						</div>

					</div>
				</div>
			</div>

		</div>
		</form>
	</div>
	<!--END CONTENT-->
</div>
</div>
<?php include('admin_elements/sidebar.php');?>
<?php include('admin_elements/admin_footer.php');?>
