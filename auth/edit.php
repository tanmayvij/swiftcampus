<?php
include '../rss/config/config.php';
page_protect();
get_head();
if(isset($_SESSION['username']) && isset($_POST['submit']))
{
	/** Check whether new domain already exists **/
	$query_verifydom = mysqli_query($link, "SELECT `instance_id` FROM `instances` WHERE `domain` = '$_POST[domain]' AND `instance_id` != '$_POST[instance_id]'");
	if(mysqli_affected_rows($link) > 0)
	{
		echo "Sorry, this domain is already in use. If you own this domain, please contact us and we'll sort this issue for you.";
	}
	else
	{
		/** Get name of old domain to rename from old dir name to new **/
		$query_getolddom = mysqli_query($link, "SELECT `domain` FROM `instances` WHERE `instance_id` = '$_POST[instance_id]' AND `user_id` = '$_SESSION[username]';");
		list($old_dom) = mysqli_fetch_array($query_getolddom);
		$schoolname = mysqli_real_escape_string($link, $_POST['schoolname']);
		/** Perform the edit **/
		$edit_updateq = mysqli_query($link, "UPDATE `instances` SET `school_name` = '$schoolname', `domain` = '$_POST[domain]' WHERE `instance_id` = '$_POST[instance_id]' AND `user_id` = '$_SESSION[username]'");
		if(mysqli_affected_rows($link) == 0)
		{
			echo "<h3>No changes made.</h3>";
		}
		else
		{
			echo "<h3>Changes successfully completed.</h3>";
			rename("../instances/" . $old_dom, "../instances/" . $_POST['domain']);
		}
	}
}
else
{

	if(!empty($_GET['id']) && isset($_GET['id']))
	{
		$id = $_GET['id'];
		/** Display current record **/
		$query_edit = mysqli_query($link, "SELECT `school_name`, `domain` FROM `instances` WHERE `instance_id` = '$id' AND `user_id` = '$_SESSION[username]'");
		if(mysqli_num_rows($query_edit) == 0)
		{
			echo "Invalid ID";
		}
		else
		{
			list($school_name, $domain) = mysqli_fetch_row($query_edit);
	?>
			<style> td { padding-bottom: 10px; } </style>
			<h3>Making Edits for Instance <?php echo $id ?></h3><br> 
			<form action="" method="post">
			<table>
			<tr><td>Edit School Name:</td><td><input type="text" value="<?php echo $school_name; ?>" size="64" name="schoolname"></td></tr>
			<tr><td>Edit Domain:</td><td><input type="text" value="<?php echo $domain; ?>" size="64" name="domain"></td></tr>
			<input type="hidden" name="instance_id" value="<?php echo $id ?>">
			</table>
			<input type="submit" name="submit">
			</form>
	<?php
		}
	}
}
?>