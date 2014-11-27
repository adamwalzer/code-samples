<?php

session_start();

include "connect.php";

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];

$outputtext .= "
	<div id='main_right' class='col span_3_of_4'>
	";

if($loc_id && $rest_id)
{
	$query_get_updated = "
		SELECT MAX(updated) AS updated 
		FROM Menu_Category 
		WHERE (rest_id='$rest_id')
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
	
	$query_get_highcat = "
		SELECT MAX(cat_id) AS highcat 
		FROM Menu_Category 
		WHERE (rest_id='$rest_id' AND updated='$updated')
		";
	$result_get_highcat = mysqli_query($dbc, $query_get_highcat);
	$highcat_row = mysqli_fetch_array($result_get_highcat, MYSQLI_ASSOC);
	if($highcat_row['highcat'])
	{
		$highcat = $highcat_row['highcat'];
	}
	else
	{
		$highcat = 1;
	}
	
	$query_get_highsubcat = "
		SELECT MAX(subcat_id) AS highsubcat 
		FROM Menu_Subcategory 
		WHERE (rest_id='$rest_id' AND updated='$updated')
		";
	$result_get_highsubcat = mysqli_query($dbc, $query_get_highsubcat);
	$highsubcat_row = mysqli_fetch_array($result_get_highsubcat, MYSQLI_ASSOC);
	if($highsubcat_row['highsubcat'])
	{
		$highsubcat = $highsubcat_row['highsubcat'];
	}
	else
	{
		$highsubcat = 1;
	}
	
	$query_get_highitem = "
		SELECT MAX(item_id) AS highitem 
		FROM Menu_Item 
		WHERE (rest_id='$rest_id' AND updated='$updated')
		";
	$result_get_highitem = mysqli_query($dbc, $query_get_highitem);
	$highitem_row = mysqli_fetch_array($result_get_highitem, MYSQLI_ASSOC);
	if($highitem_row['highitem'])
	{
		$highitem = $highitem_row['highitem'];
	}
	else
	{
		$highitem = 1;
	}
	
	$outputtext .= "
		<script>
			var highcat = $highcat;
			var highsubcat = $highsubcat;
			var highitem = $highitem;
			window.onbeforeunload = function() {
				return 'You may have unsaved changes!';
			}
		</script>
		";
	
	$outputtext .= "
		<div id='main'>
			Create menu by dragging categories, subcategories, and items below.
			<ol class='category'>
		";
	
	$query_get_category = "
		SELECT * FROM Menu_Category 
		WHERE (rest_id='$rest_id' AND updated='$updated') 
		ORDER BY cat_order
		";

	$result_get_category = mysqli_query($dbc, $query_get_category);
	if(!$result_get_category)
	{//If the QUery Failed 
		$outputtext .= 'Query Failed ';
	}

	if (@mysqli_num_rows($result_get_category) > 0)//if Query is successfull 
	{ // A match was made.
		
		while($cat_row = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC))
		{
			$cat_id = $cat_row['cat_id'];
			$category = $cat_row['name'];
			$query_get_subcat = "
				SELECT * FROM Menu_Subcategory 
				WHERE (rest_id='$rest_id' AND cat_id='$cat_id' AND updated='$updated') 
				ORDER BY subcat_order
				";
			$result_get_subcat = mysqli_query($dbc, $query_get_subcat);
			
			if (@mysqli_num_rows($result_get_subcat) > 0)
			{
				$outputtext .= '
					<li>
						<i class="icon-move-category"></i>
						<table id="menutable">
							<tr>
								<td class="firstcol">
									<input type="hidden" id="cat_id" placeholder="cat_id" value="::'.$cat_id.'" />
									<input type="text" id="categoryname" placeholder="Category" value="'.htmlentities($category, ENT_QUOTES).'" />
								</td>
							</tr>
							<tr>
								<td class="allcol">
									<input type="text" id="categorynote" placeholder="Category Note" class="description" value="'.htmlentities($cat_row['note'], ENT_QUOTES).'" />
								</td>
							</tr>
						</table>
						<i onClick="var li = this.parentNode; var ol = li.parentNode; deletecategory(li,ol)" class="icon-cancel"></i>
						<ol class="subcategory">
					';
				
				while($subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC))
				{
					$subcat_id = $subcat_row['subcat_id'];
					$subcategory = $subcat_row['name'];
					$query_get_item = "
						SELECT * FROM Menu_Item 
						WHERE (rest_id='$rest_id' AND subcat_id='$subcat_id' AND updated='$updated') 
						ORDER BY item_order
						";
					$result_get_item = mysqli_query($dbc, $query_get_item);
			
			
					if (@mysqli_num_rows($result_get_item) > 0)
					{
						$outputtext .= '
							<li>
								<i class="icon-move-subcategory"></i>
								<table id="menutable">
									<colgroup>
										<col width="100%">
										<col span="5" nowrap>
									</colgroup>
									<tr>
										<td class="firstcol">
											<input type="hidden" id="subcat_id" placeholder="subcat_id" value=";;'.$subcat_id.'" />
											<input type="text" id="subcategoryname" placeholder="Subcategory" value="'.htmlentities($subcategory, ENT_QUOTES).'" />
										</td>
										<td><input type="text" id="size_1" class="size" placeholder="Size 1" value="'.$subcat_row['size_1'].'" /></td>
										<td><input type="text" id="size_2" class="size" placeholder="Size 2" value="'.$subcat_row['size_2'].'" /></td>
										<td><input type="text" id="size_3" class="size" placeholder="Size 3" value="'.$subcat_row['size_3'].'" /></td>
										<td><input type="text" id="size_4" class="size" placeholder="Size 4" value="'.$subcat_row['size_4'].'" /></td>
										<td><input type="text" id="size_5" class="size" placeholder="Size 5" value="'.$subcat_row['size_5'].'" /></td>
									</tr>
									<tr>
										<td class="allcol" colspan="6">
											<input type="text" id="subcategorynote" placeholder="Subcategory Note" class="description" value="'.htmlentities($subcat_row['note'], ENT_QUOTES).'" />
										</td>
									</tr>
								</table>
								<i onClick="var li = this.parentNode; var ol = li.parentNode; deletesubcategory(li,ol)" class="icon-cancel"></i>
								<ol class="item">
							';
				
						while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
						{
							$item_id = $item_row['item_id'];
							$item = $item_row['name'];
					
							if($item_row['price_1']){$price_1=number_format($item_row['price_1'], 2, '.', '');}else{$price_1="";}
							if($item_row['price_2']){$price_2=number_format($item_row['price_2'], 2, '.', '');}else{$price_2="";}
							if($item_row['price_3']){$price_3=number_format($item_row['price_3'], 2, '.', '');}else{$price_3="";}
							if($item_row['price_4']){$price_4=number_format($item_row['price_4'], 2, '.', '');}else{$price_4="";}
							if($item_row['price_5']){$price_5=number_format($item_row['price_5'], 2, '.', '');}else{$price_5="";}
					
					
							$outputtext .= '
								<li>
									<i class="icon-move-item"></i>
									<table id="menutable">
										<colgroup>
											<col width="100%">
											<col span="5" nowrap>
										</colgroup>
										<tr>
											<td class="firstcol">
												<input type="hidden" id="item_id" placeholder="item_id" value=",,'.$item_id.'" />
												<input type="text" id="item" placeholder="Item" value="'.htmlentities($item, ENT_QUOTES).'" />
											</td>
											<td><input type="text" id="price_1" class="size" placeholder="Price 1" value="'.$price_1.'" /></td>
											<td><input type="text" id="price_2" class="size" placeholder="Price 2" value="'.$price_2.'" /></td>
											<td><input type="text" id="price_3" class="size" placeholder="Price 3" value="'.$price_3.'" /></td>
											<td><input type="text" id="price_4" class="size" placeholder="Price 4" value="'.$price_4.'" /></td>
											<td><input type="text" id="price_5" class="size" placeholder="Price 5" value="'.$price_5.'" /></td>
										</tr>
										<tr>
											<td class="allcol" colspan="6">
												<input type="text" id="item_desc" placeholder="Item Description" class="description" value="'.htmlentities($item_row['descript'], ENT_QUOTES).'" />
												<br>
												<input type="text" id="allergen" placeholder="allergen" value="'.htmlentities($item_row['allergen'], ENT_QUOTES).'" />
											</td>
										</tr>
									</table>
									<i onClick="var li = this.parentNode; var ol = li.parentNode; deleteitem(li,ol)" class="icon-cancel"></i>
								</li>
								';
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
								<i class="icon-move-subcategory"></i>
								<table id="menutable">
									<colgroup>
										<col width="100%">
										<col span="5" nowrap>
									</colgroup>
									<tr>
										<td class="firstcol">
											<input type="hidden" id="subcat_id" placeholder="subcat_id" value=";;'.$highsubcat.'" />
											<input type="text" id="subcategoryname" placeholder="Subcategory" value="" />
										</td>
										<td><input type="text" id="size_1" class="size" placeholder="Size 1" value="" /></td>
										<td><input type="text" id="size_2" class="size" placeholder="Size 2" value="" /></td>
										<td><input type="text" id="size_3" class="size" placeholder="Size 3" value="" /></td>
										<td><input type="text" id="size_4" class="size" placeholder="Size 4" value="" /></td>
										<td><input type="text" id="size_5" class="size" placeholder="Size 5" value="" /></td>
									</tr>
									<tr>
										<td class="allcol" colspan="6">
											<input type="text" id="subcategorynote" placeholder="Subcategory Note" class="description" value="" />
										</td>
									</tr>
								</table>
								<i onClick="var li = this.parentNode; var ol = li.parentNode; deletesubcategory(li,ol)" class="icon-cancel"></i>
								<ol class="item">
									<li>
										<i class="icon-move-item"></i>
										<table id="menutable">
											<colgroup>
												<col width="100%">
												<col span="5" nowrap>
											</colgroup>
											<tr>
												<td class="firstcol">
													<input type="hidden" id="item_id" placeholder="item_id" value=",,'.$highitem.'" />
													<input type="text" id="item" placeholder="Item" value="" />
												</td>
												<td><input type="text" id="price_1" class="size" placeholder="Price 1" value="" /></td>
												<td><input type="text" id="price_2" class="size" placeholder="Price 2" value="" /></td>
												<td><input type="text" id="price_3" class="size" placeholder="Price 3" value="" /></td>
												<td><input type="text" id="price_4" class="size" placeholder="Price 4" value="" /></td>
												<td><input type="text" id="price_5" class="size" placeholder="Price 5" value="" /></td>
											</tr>
											<tr>
												<td class="allcol" colspan="6">
													<input type="text" id="item_desc" placeholder="Item Description" class="description" value="" />
													<br>
													<input type="text" id="allergen" placeholder="allergen" value="" />
												</td>
											</tr>
										</table>
										<i onClick="var li = this.parentNode; var ol = li.parentNode; deleteitem(li,ol)" class="icon-cancel"></i>
									</li>
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
		}
	}
	else
	{		
		$outputtext .= '
			<li>
				<i class="icon-move-category"></i>
				<table id="menutable">
					<tr>
						<td class="firstcol">
							<input type="hidden" id="cat_id" placeholder="cat_id" value="::'.$highcat.'" />
							<input type="text" id="categoryname" placeholder="Category" value="" />
						</td>
					</tr>
					<tr>
						<td class="allcol">
							<input type="text" id="categorynote" placeholder="Category Note" class="description" value="" />
						</td>
					</tr>
				</table>
				<i onClick="var li = this.parentNode; var ol = li.parentNode; deletecategory(li,ol)" class="icon-cancel"></i>
				<ol class="subcategory">
					<li>
						<i class="icon-move-subcategory"></i>
						<table id="menutable">
							<colgroup>
								<col width="100%">
								<col span="5" nowrap>
							</colgroup>
							<tr>
								<td class="firstcol">
									<input type="hidden" id="subcat_id" placeholder="subcat_id" value=";;'.$highsubcat.'" />
									<input type="text" id="subcategoryname" placeholder="Subcategory" value="'.$subcategory.'" />
								</td>
								<td><input type="text" id="size_1" class="size" placeholder="Size 1" value="" /></td>
								<td><input type="text" id="size_2" class="size" placeholder="Size 2" value="" /></td>
								<td><input type="text" id="size_3" class="size" placeholder="Size 3" value="" /></td>
								<td><input type="text" id="size_4" class="size" placeholder="Size 4" value="" /></td>
								<td><input type="text" id="size_5" class="size" placeholder="Size 5" value="" /></td>
							</tr>
							<tr>
								<td class="allcol" colspan="6">
									<input type="text" id="subcategorynote" placeholder="Subcategory Note" class="description" value="" />
								</td>
							</tr>
						</table>
						<i onClick="var li = this.parentNode; var ol = li.parentNode; deletesubcategory(li,ol)" class="icon-cancel"></i>
						<ol class="item">
							<li>
								<i class="icon-move-item"></i>
								<table id="menutable">
									<colgroup>
										<col width="100%">
										<col span="5" nowrap>
									</colgroup>
									<tr>
										<td class="firstcol">
											<input type="hidden" id="item_id" placeholder="item_id" value=",,'.$highitem.'" />
											<input type="text" id="item" placeholder="Item" value="'.$item.'" />
										</td>
										<td><input type="text" id="price_1" class="size" placeholder="Price 1" value="" /></td>
										<td><input type="text" id="price_2" class="size" placeholder="Price 2" value="" /></td>
										<td><input type="text" id="price_3" class="size" placeholder="Price 3" value="" /></td>
										<td><input type="text" id="price_4" class="size" placeholder="Price 4" value="" /></td>
										<td><input type="text" id="price_5" class="size" placeholder="Price 5" value="" /></td>
									</tr>
									<tr>
										<td class="allcol" colspan="6">
											<input type="text" id="item_desc" placeholder="Item Description" class="description" value="" />
											<br>
											<input type="text" id="allergen" placeholder="allergen" value="" />
										</td>
									</tr>
								</table>
								<i onClick="var li = this.parentNode; var ol = li.parentNode; deleteitem(li,ol)" class="icon-cancel"></i>
							</li>
						</ol>
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

$outputtext .= "
	</div>
	";

/// var_dump($error);
mysqli_close($dbc);

?>