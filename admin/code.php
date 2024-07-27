
<?php $i = $_GET['i']; ?>









<form action="" method="POST">
<p><span style="font-size:24px;">Modal Marquee Edit</span></p>
<input type="Hidden" name="id" value="<?php echo $i; ?>"/>
<p>Time in Bangla<br />
<select name="Status">
<option value="Active">
  Active
</option>
<option value="Block">
  Block
</option>
</select>
<!-- <input name="Time" required="" style="width:200px;" type="text" value=""/><br />
News In Bangla<br />
<textarea name="News" required="" style="height:150px;" type="text" value=""></textarea><br />
<br /> -->
<input style="width:150px; height:40px" type="submit" name="up" value="Change" /></p>
</form>

<?php
if (isset($_POST['up']))
{
  include('db.php');

    $Status = $_POST['Status'];

         $s= mysqli_query($con,"update users Set Status='$Status' where id='$id' ");
    header("location:users.php");
}
    ?>
