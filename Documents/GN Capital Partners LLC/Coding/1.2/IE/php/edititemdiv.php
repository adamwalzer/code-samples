<?php

session_start();

include "connect.php";

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];
$item_id = $_GET['item_id'];

$query_get_item = "
	SELECT * FROM Menu_Item 
	WHERE (rest_id='$rest_id' AND item_id='$item_id') 
	LIMIT 1
	";
$result_get_item = mysqli_query($dbc, $query_get_item);
$item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC);

$subcat_id = $item_row['subcat_id'];

$query_get_subcat = "
	SELECT * FROM Menu_Subcategory 
	WHERE (rest_id='$rest_id' AND subcat_id='$subcat_id') 
	LIMIT 1
	";
$result_get_subcat = mysqli_query($dbc, $query_get_subcat);
$subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC);

if($loc_id && $rest_id)
{
	$query_get_updated = "
		SELECT MAX(updated) AS updated 
		FROM Menu_Item_Option_Group 
		WHERE (rest_id='$rest_id' AND item_id='$item_id')
		";
	$result_get_updated = mysqli_query($dbc, $query_get_updated);
	$updated_row = mysqli_fetch_array($result_get_updated, MYSQLI_ASSOC);
	if($updated_row['updated'])
	{
		$updated = $updated_row['updated'];
	}
	else
	{
		$updated = '0000-00-00 00:00:00';
	}
	
	$query_get_highgroup = "
		SELECT MAX(group_id) AS highgroup 
		FROM Menu_Item_Option_Group 
		WHERE (rest_id='$rest_id' AND item_id='$item_id' AND updated='$updated')
		";
	$result_get_highgroup = mysqli_query($dbc, $query_get_highgroup);
	$highgroup_row = mysqli_fetch_array($result_get_highgroup, MYSQLI_ASSOC);
	if($highgroup_row['highgroup'])
	{
		$highgroup = $highgroup_row['highgroup'];
	}
	else
	{
		$highgroup = 1;
	}
	
	$query_get_highoption = "
		SELECT MAX(option_id) AS highoption 
		FROM Menu_Item_Option 
		WHERE (rest_id='$rest_id' AND item_id='$item_id' AND updated='$updated')
		";
	$result_get_highoption = mysqli_query($dbc, $query_get_highoption);
	$highoption_row = mysqli_fetch_array($result_get_highoption, MYSQLI_ASSOC);
	if($highoption_row['highoption'])
	{
		$highoption = $highoption_row['highoption'];
	}
	else
	{
		$highoption = 1;
	}
	
	$outputtext .= '
		<script>
			var highgroup = '.$highgroup.';
			var highoption = '.$highoption.';
			window.onbeforeunload = function() {
				return "You may have unsaved changes!";
			}
		</script>
		';
		
	$outputtext .= '
		<div id="main">
			<table id="menutable">
				<colgroup>
					<col width="100%">
					<col span="5" nowrap>
				</colgroup>
				<tr>
					<td class="firstcol">'.$subcat_row['name'].'</td>
					<td colspan=5>'.$subcat_row['note'].'</td>
				</tr>
				<tr>
					<td class="firstcol">&nbsp;</td>
					<td>'.$subcat_row['size_1'].'</td>
					<td>'.$subcat_row['size_2'].'</td>
					<td>'.$subcat_row['size_3'].'</td>
					<td>'.$subcat_row['size_4'].'</td>
					<td>'.$subcat_row['size_5'].'</td>
				</tr>
				<tr>
					<td class="firstcol">
						<input type="hidden" id="item_id" placeholder="item_id" value="::'.$item_row['item_id'].'" />
						<input type="text" id="item" placeholder="Item" value="'.$item_row['name'].'" />
					</td>
					<td><input type="text" id="price_1" class="size" placeholder="Price 1" value="'.$item_row['price_1'].'" /></td>
					<td><input type="text" id="price_2" class="size" placeholder="Price 2" value="'.$item_row['price_2'].'" /></td>
					<td><input type="text" id="price_3" class="size" placeholder="Price 3" value="'.$item_row['price_3'].'" /></td>
					<td><input type="text" id="price_4" class="size" placeholder="Price 4" value="'.$item_row['price_4'].'" /></td>
					<td><input type="text" id="price_5" class="size" placeholder="Price 5" value="'.$item_row['price_5'].'" /></td>
				</tr>
				<tr>
					<td class="allcol" colspan="6">
						<input type="text" id="item_desc" placeholder="Item Description" class="description" value="'.$item_row['descript'].'" />
						<br>
						<input type="text" id="allergen" placeholder="allergen" value="'.$item_row['allergen'].'" />
					</td>
				</tr>
			</table>
			<br>
			Create options by dragging groups and options below.
			<ol class="group">
		';
	
	
	$query_get_group = "
		SELECT * FROM Menu_Item_Option_Group 
		WHERE (rest_id='$rest_id' AND item_id='$item_id' AND updated='$updated') 
		ORDER BY group_order
		";

	$result_get_group = mysqli_query($dbc, $query_get_group);
	if(!$result_get_group)
	{//If the QUery Failed 
		$outputtext .= 'Query Failed ';
	}

	if (@mysqli_num_rows($result_get_group) > 0)//if Query is successfull 
	{ // A match was made.
				
		while($group_row = mysqli_fetch_array($result_get_group, MYSQLI_ASSOC))
		{
			$group_id = $group_row['group_id'];
			$group_name = $group_row['group_name'];
			$group_quantity = $group_row['group_quantity'];
			$query_get_option = "
				SELECT * FROM Menu_Item_Option 
				WHERE (rest_id='$rest_id' AND item_id='$item_id' AND group_id='$group_id' AND updated='$updated') 
				ORDER BY option_order
				";
			$result_get_option = mysqli_query($dbc, $query_get_option);
	
	
			if (@mysqli_num_rows($result_get_option) > 0)
			{
				$outputtext .= '
					<li>
						<i class="icon-move-group"></i>
						<table id="menutable">
							<tr>
								<td class="firstcol" width="25%">
									<input type="hidden" id="group_id" placeholder="group_id" value=";;'.$group_id.'" />
									<input type="text" id="groupname" placeholder="Group Name" value="'.$group_name.'" />
								</td>
								<td width="25%"><input type="radio" name="grouptype'.$group_id.'" id="checkbox" ';
								
				if($group_row['group_type']=='checkbox')
				{
					$outputtext .= 'checked';
				}
								
				$outputtext .= '>Check Boxes</td>
								<td width="25%"><input type="radio" name="grouptype'.$group_id.'" id="radio" ';
								
				if($group_row['group_type']=='radio')
				{
					$outputtext .= 'checked';
				}
								
				$outputtext .= '>Radio Buttons</td>
								<td width="19%"><input type="radio" name="grouptype'.$group_id.'" id="quantity" ';
								
				if($group_row['group_type']=='quantity')
				{
					$outputtext .= 'checked';
				}
								
				$outputtext .= '>Quantities</td>
								<td width="6%"><input type="number" id="quanitynumber" placeholder="12" value="'.$group_quantity.'" /></td>
							</tr>
						</table>
						<i onClick="var li = this.parentNode; var ol = li.parentNode; deletegroup(li,ol)" class="icon-cancel"></i>
						<ol class="option">
					';
		
				while($option_row = mysqli_fetch_array($result_get_option, MYSQLI_ASSOC))
				{
					$option_id = $option_row['option_id'];
					$option_name = $option_row['option_name'];
					$option_cost = $option_row['option_cost'];
			
					$outputtext .= '
						<li>
							<i class="icon-move-option"></i>
							<table id="menutable">
								<tr>
									<td class="firstcol">
										<input type="hidden" id="option_id" placeholder="option_id" value=",,'.$option_id.'" />
										<input type="text" id="option" placeholder="Option" value="'.$option_name.'" />
									</td>
									<td><input type="text" id="option_cost" placeholder="Price" value="'.$option_cost.'" /></td>
									<td><input type="checkbox" id="option_checked" '.$option_row['checked'].' />Check by default</td>
								</tr>
							</table>
							<i onClick="var li = this.parentNode; var ol = li.parentNode; deleteoption(li,ol)" class="icon-cancel"></i>
						</li>
						';
				}
		
				$outputtext .= '
						</ol>
					</li>
					';
		
			}
		}
		
		$outputtext .= '
				</ol>
			</li>
			';
	}
	else
	{		
		$outputtext .= '
			<li>
				<i class="icon-move-group"></i>
				<table id="menutable">
					<tr>
						<td class="firstcol" width="25%">
							<input type="hidden" id="group_id" placeholder="group_id" value=";;'.$highgroup.'" />
							<input type="text" id="groupname" placeholder="Group Name" value="" />
						</td>
						<td width="25%"><input type="radio" name="grouptype'.$highgroup.'" id="checkbox" checked >Check Boxes</td>
						<td width="25%"><input type="radio" name="grouptype'.$highgroup.'" id="radio" >Radio Buttons</td>
						<td width="19%"><input type="radio" name="grouptype'.$highgroup.'" id="quantity" >Quantities</td>
						<td width="6%"><input type="number" id="quanitynumber" placeholder="12" ></td>
					</tr>
				</table>
				<i onClick="var li = this.parentNode; var ol = li.parentNode; deletegroup(li,ol)" class="icon-cancel"></i>
				<ol class="option">
					<li>
						<i class="icon-move-option"></i>
						<table id="menutable">
							<tr>
								<td class="firstcol">
									<input type="hidden" id="option_id" placeholder="option_id" value=",,'.$highoption.'" />
									<input type="text" id="option" placeholder="Option" value="" />
								</td>
								<td><input type="text" id="option_cost" placeholder="Price" value="" /></td>
								<td><input type="checkbox" id="option_checked" />Check by default</td>
							</tr>
						</table>
						<i onClick="var li = this.parentNode; var ol = li.parentNode; deleteoption(li,ol)" class="icon-cancel"></i>
					</li>
				</ol>
			</li>
			';
	}
	
	
	$outputtext .= '
			</ol>
		</div>
		';
}
else
{
	$outputtext .= '
		<div id="main">
			<center>
				No Restaurant ID
			</center>
		</div>
		';
}

/// var_dump($error);
mysqli_close($dbc);

?>