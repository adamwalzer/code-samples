<?php

session_start();

include "connect.php";

$loc_id = $_SESSION['loc_id'];
$rest_id = $_SESSION['rest_id'];

/*
$outputtext .= "

	<table class='table table-striped table-bordered sorted_table'>
		<colgroup>
			<col span='1' style='width:20px'>
		</colgroup>
		  <tr>
		  	<td><i class='icon-move'></i></td>
		  	<td colspan='2'>
		  		Category 1
				<ol class='category'>
					<li>
						<i class='icon-move-list'></i>
						Subcat 1
						<table>
							<colgroup>
								<col span='1' style='width:20px'>
							</colgroup>
							<tr>
								<td>
									<i class='icon-move'></i>
								</td>
								<td>
									Item 1
								</td>
							</tr>
							<tr>
								<td>
									<i class='icon-move'></i>
								</td>
								<td>
									Item 2
								</td>
							</tr>
						</table>
					</li>
					<li>
						<i class='icon-move-list'></i>
						Subcat 2
					</li>
				</ol>
		 	</td>
		  </tr>
		  <tr>
		  	<td><i class='icon-move'></i></td>
		  	<td colspan='2'>
		  		Category 2
				<ol class='category'>
					<li>
						<i class='icon-move-list'></i>
						Subcat 3
					</li>
					<li>
						<i class='icon-move-list'></i>
						Subcat 4
					</li>
				</ol>
		 	</td>
		  </tr>
	</table>
	
	
	
	
	
	<ol class='category'>
		<li>
			<i class='icon-move'></i>
			Lunch
			<table class='table table-striped table-bordered sorted_table'>
			  <thead>
				<tr>
				  <th>Lunch Specials</th>
				  <th>B Column</th>
				</tr>
			  </thead>
			  <tbody>
				<tr>
					<td>Special 1</td>
					<td>B Item 1</td>
				  </tr>
				  <tr>
					<td>Special 2</td>
					<td>B Item 2</td>
				  </tr>
				  <tr>
					<td>A Item 3</td>
					<td>B Item 3</td>
				  </tr>
				  <tr>
					<td>A Item 4</td>
					<td>B Item 4</td>
				  </tr>
				  <tr>
					<td>A Item 5</td>
					<td>B Item 5</td>
				  </tr>
				  <tr>
					<td>A Item 6</td>
					<td>B Item 6</td>
				  </tr>
			  </tbody>
			</table>
			<br>
			<table class='table table-striped table-bordered sorted_table'>
			  <thead>
				<tr>
				  <th>Sushi</th>
				  <th>B Column</th>
				</tr>
			  </thead>
			  <tbody>
				<tr>
					<td>California Roll</td>
					<td>B Item 1</td>
				  </tr>
				  <tr>
					<td>Ell and Avocado Roll</td>
					<td>B Item 2</td>
				  </tr>
				  <tr>
					<td>A Item 3</td>
					<td>B Item 3</td>
				  </tr>
				  <tr>
					<td>A Item 4</td>
					<td>B Item 4</td>
				  </tr>
				  <tr>
					<td>A Item 5</td>
					<td>B Item 5</td>
				  </tr>
				  <tr>
					<td>A Item 6</td>
					<td>B Item 6</td>
				  </tr>
			  </tbody>
			</table>
			<br>
		</li>
		<li>
			<i class='icon-move'></i>
			Dinner
			<table class='table table-striped table-bordered sorted_table'>
			  <thead>
				<tr>
				  <th>Entres</th>
				  <th>B Column</th>
				</tr>
			  </thead>
			  <tbody>
				<tr>
					<td>Lasagna</td>
					<td>B Item 1</td>
				  </tr>
				  <tr>
					<td>Tacos</td>
					<td>B Item 2</td>
				  </tr>
				  <tr>
					<td>A Item 3</td>
					<td>B Item 3</td>
				  </tr>
				  <tr>
					<td>A Item 4</td>
					<td>B Item 4</td>
				  </tr>
				  <tr>
					<td>A Item 5</td>
					<td>B Item 5</td>
				  </tr>
				  <tr>
					<td>A Item 6</td>
					<td>B Item 6</td>
				  </tr>
			  </tbody>
			</table>
			<br>
		</li>
	</ol>
	
	
	<div class='span4'>
		<h3>Sortable Rows</h3>
		<table class='table table-striped table-bordered sorted_table'>
		  <thead>
			<tr>
			  <th>A Column</th>
			  <th>B Column</th>
			</tr>
		  </thead>
		  <tbody>
			<tr>
				<td>A Item 1</td>
				<td>B Item 1</td>
			  </tr>
			  <tr>
				<td>A Item 2</td>
				<td>B Item 2</td>
			  </tr>
			  <tr>
				<td>A Item 3</td>
				<td>B Item 3</td>
			  </tr>
			  <tr>
				<td>A Item 4</td>
				<td>B Item 4</td>
			  </tr>
			  <tr>
				<td>A Item 5</td>
				<td>B Item 5</td>
			  </tr>
			  <tr>
				<td>A Item 6</td>
				<td>B Item 6</td>
			  </tr>
		  </tbody>
		</table>
	  </div>";
*/

if($loc_id && $rest_id)
{
	$query_get_highcat = "
		SELECT MAX(cat_id) AS highcat 
		FROM Menu_Category 
		WHERE (loc_id='$loc_id' AND rest_id='$rest_id')
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
		WHERE (loc_id='$loc_id' AND rest_id='$rest_id')
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
		WHERE (loc_id='$loc_id' AND rest_id='$rest_id')
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
		</script>
		";
	
	$outputtext .= "
		<div id='main'>
			Create menu by dragging categories, subcategories, and items below.
			<ol class='category'>
		";
	
	$query_get_category = "
		SELECT * FROM Menu_Category 
		WHERE (loc_id='$loc_id' AND rest_id='$rest_id') 
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
				WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND cat_id='$cat_id') 
				ORDER BY subcat_order
				";
			$result_get_subcat = mysqli_query($dbc, $query_get_subcat);
			
			if (@mysqli_num_rows($result_get_subcat) > 0)
			{
				$outputtext .= "
					<li>
						<i class='icon-move-category'></i>
						<table id='menutable'>
							<tr>
								<td class='firstcol'>
									<input type='hidden' id='cat_id' placeholder='cat_id' value='::$cat_id' />
									<input type='text' id='categoryname' placeholder='Category' value='$category' />
								</td>
							</tr>
							<tr>
								<td class='allcol'>
									<input type='text' id='categorynote' placeholder='Category Note' class='description' value='".$cat_row['note']."' />
								</td>
							</tr>
						</table>
						<i onClick='var li = this.parentNode; var ol = li.parentNode; deletecategory(li,ol)' class='icon-cancel'></i>
						<ol class='subcategory'>
					";
				
				while($subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC))
				{
					$subcat_id = $subcat_row['subcat_id'];
					$subcategory = $subcat_row['name'];
					$query_get_item = "
						SELECT * FROM Menu_Item 
						WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND subcat_id='$subcat_id') 
						ORDER BY item_order
						";
					$result_get_item = mysqli_query($dbc, $query_get_item);
			
			
					if (@mysqli_num_rows($result_get_item) > 0)
					{
						$outputtext .= "
							<li>
								<i class='icon-move-subcategory'></i>
								<table id='menutable'>
									<colgroup>
										<col width='100%'>
										<col span='5' nowrap>
									</colgroup>
									<tr>
										<td class='firstcol'>
											<input type='hidden' id='subcat_id' placeholder='subcat_id' value=';;$subcat_id' />
											<input type='text' id='subcategoryname' placeholder='Subcategory' value='$subcategory' />
										</td>
										<td><input type='text' id='size_1' class='size' placeholder='Size 1' value='".$subcat_row['size_1']."' /></td>
										<td><input type='text' id='size_2' class='size' placeholder='Size 2' value='".$subcat_row['size_2']."' /></td>
										<td><input type='text' id='size_3' class='size' placeholder='Size 3' value='".$subcat_row['size_3']."' /></td>
										<td><input type='text' id='size_4' class='size' placeholder='Size 4' value='".$subcat_row['size_4']."' /></td>
										<td><input type='text' id='size_5' class='size' placeholder='Size 5' value='".$subcat_row['size_5']."' /></td>
									</tr>
									<tr>
										<td class='allcol' colspan='6'>
											<input type='text' id='subcategorynote' placeholder='Subcategory Note' class='description' value='".$subcat_row['note']."' />
										</td>
									</tr>
								</table>
								<i onClick='var li = this.parentNode; var ol = li.parentNode; deletesubcategory(li,ol)' class='icon-cancel'></i>
								<ol class='item'>
							";
				
						while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
						{
							$item_id = $item_row['item_id'];
							$item = $item_row['name'];
					
							if($item_row['allergen'])
							{
								$allergen = "
									allergen: ".$item_row['allergen']."
									";
							}
							else
							{
								$allergen = "";
							}
					
							if($item_row['price_1']){$price_1=number_format($item_row['price_1'], 2, '.', '');}else{$price_1="";}
							if($item_row['price_2']){$price_2=number_format($item_row['price_2'], 2, '.', '');}else{$price_2="";}
							if($item_row['price_3']){$price_3=number_format($item_row['price_3'], 2, '.', '');}else{$price_3="";}
							if($item_row['price_4']){$price_4=number_format($item_row['price_4'], 2, '.', '');}else{$price_4="";}
							if($item_row['price_5']){$price_5=number_format($item_row['price_5'], 2, '.', '');}else{$price_5="";}
					
					
							$outputtext .= "
								<li>
									<i class='icon-move-item'></i>
									<table id='menutable'>
										<colgroup>
											<col width='100%'>
											<col span='5' nowrap>
										</colgroup>
										<tr>
											<td class='firstcol'>
												<input type='hidden' id='item_id' placeholder='item_id' value=',,$item_id' />
												<input type='text' id='item' placeholder='Item' value='$item' />
											</td>
											<td><input type='text' id='price_1' class='size' placeholder='Price 1' value='$price_1' /></td>
											<td><input type='text' id='price_2' class='size' placeholder='Price 2' value='$price_2' /></td>
											<td><input type='text' id='price_3' class='size' placeholder='Price 3' value='$price_3' /></td>
											<td><input type='text' id='price_4' class='size' placeholder='Price 4' value='$price_4' /></td>
											<td><input type='text' id='price_5' class='size' placeholder='Price 5' value='$price_5' /></td>
										</tr>
										<tr>
											<td class='allcol' colspan='6'>
												<input type='text' id='item_desc' placeholder='Item Description' class='description' value='".$item_row['descript']."' />
												<br>
												<input type='text' id='alergen' placeholder='alergen' value='$alergen' />
											</td>
										</tr>
									</table>
									<i onClick='var li = this.parentNode; var ol = li.parentNode; deleteitem(li,ol)' class='icon-cancel'></i>
								</li>
								";
						}
				
						$outputtext .= "
								</ol>
							</li>
							";
				
					}
				}
				
				$outputtext .= "
						</ol>
					</li>
					";
			}
		}
	}
	else
	{		
		$outputtext .= "
			<li>
				<i class='icon-move-category'></i>
				<table id='menutable'>
					<tr>
						<td class='firstcol'>
							<input type='hidden' id='cat_id' placeholder='cat_id' value='::$highcat' />
							<input type='text' id='categoryname' placeholder='Category' value='' />
						</td>
					</tr>
					<tr>
						<td class='allcol'>
							<input type='text' id='categorynote' placeholder='Category Note' class='description' value='' />
						</td>
					</tr>
				</table>
				<i onClick='var li = this.parentNode; var ol = li.parentNode; deletecategory(li,ol)' class='icon-cancel'></i>
				<ol class='subcategory'>
					<li>
						<i class='icon-move-subcategory'></i>
						<table id='menutable'>
							<colgroup>
								<col width='100%'>
								<col span='5' nowrap>
							</colgroup>
							<tr>
								<td class='firstcol'>
									<input type='hidden' id='subcat_id' placeholder='subcat_id' value=';;$highsubcat' />
									<input type='text' id='subcategoryname' placeholder='Subcategory' value='$subcategory' />
								</td>
								<td><input type='text' id='size_1' class='size' placeholder='Size 1' value='' /></td>
								<td><input type='text' id='size_2' class='size' placeholder='Size 2' value='' /></td>
								<td><input type='text' id='size_3' class='size' placeholder='Size 3' value='' /></td>
								<td><input type='text' id='size_4' class='size' placeholder='Size 4' value='' /></td>
								<td><input type='text' id='size_5' class='size' placeholder='Size 5' value='' /></td>
							</tr>
							<tr>
								<td class='allcol' colspan='6'>
									<input type='text' id='subcategorynote' placeholder='Subcategory Note' class='description' value='' />
								</td>
							</tr>
						</table>
						<i onClick='var li = this.parentNode; var ol = li.parentNode; deletesubcategory(li,ol)' class='icon-cancel'></i>
						<ol class='item'>
							<li>
								<i class='icon-move-item'></i>
								<table id='menutable'>
									<colgroup>
										<col width='100%'>
										<col span='5' nowrap>
									</colgroup>
									<tr>
										<td class='firstcol'>
											<input type='hidden' id='item_id' placeholder='item_id' value=',,$highitem' />
											<input type='text' id='item' placeholder='Item' value='$item' />
										</td>
										<td><input type='text' id='price_1' class='size' placeholder='Price 1' value='' /></td>
										<td><input type='text' id='price_2' class='size' placeholder='Price 2' value='' /></td>
										<td><input type='text' id='price_3' class='size' placeholder='Price 3' value='' /></td>
										<td><input type='text' id='price_4' class='size' placeholder='Price 4' value='' /></td>
										<td><input type='text' id='price_5' class='size' placeholder='Price 5' value='' /></td>
									</tr>
									<tr>
										<td class='allcol' colspan='6'>
											<input type='text' id='item_desc' placeholder='Item Description' class='description' value='' />
											<br>
											<input type='text' id='alergen' placeholder='alergen' value='' />
										</td>
									</tr>
								</table>
								<i onClick='var li = this.parentNode; var ol = li.parentNode; deleteitem(li,ol)' class='icon-cancel'></i>
							</li>
						</ol>
					</li>
				</ol>
			</li>
			";
	}
	
	
	$outputtext .= "
			</ol>
		</div>
		";
}
else
{
	$outputtext .= "
		<div id='main'>
			<center>
				No Restaurant ID
			</center>
		</div>
		";
}

/// var_dump($error);
mysqli_close($dbc);

/*
$outputtext .= "
	<ol class='category'>
		<li>
			<i class='icon-move-category'></i>
			Lunch
			<ol class='subcategory'>
				<li>
					<i class='icon-move-subcategory'></i>
					Lunch Specials
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							Special 1
						</li>
						<li>
							<i class='icon-move-item'></i>
							Special 2
						</li>
					</ol>
				</li>
				<li>
					<i class='icon-move-subcategory'></i>
					Sushi
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							California Roll
						</li>
						<li>
							<i class='icon-move-item'></i>
							Ell and Avocado Roll
						</li>
					</ol>
				</li>
			</ol>
		</li>
		<li>
			<i class='icon-move-category'></i>
			Dinner
			<ol class='subcategory'>
				<li>
					<i class='icon-move-subcategory'></i>
					Entres
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							Speghetti
						</li>
						<li>
							<i class='icon-move-item'></i>
							Lasagna
						</li>
					</ol>
				</li>
				<li>
					<i class='icon-move-subcategory'></i>
					Sushi
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							Dragon Roll
						</li>
						<li>
							<i class='icon-move-item'></i>
							Spider Roll
						</li>
					</ol>
				</li>
			</ol>
		</li>
		<li>
			<i class='icon-move-category'></i>
			Dinner
			<ol class='subcategory'>
				<li>
					<i class='icon-move-subcategory'></i>
					Entres
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							Speghetti
						</li>
						<li>
							<i class='icon-move-item'></i>
							Lasagna
						</li>
					</ol>
				</li>
				<li>
					<i class='icon-move-subcategory'></i>
					Sushi
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							Dragon Roll
						</li>
						<li>
							<i class='icon-move-item'></i>
							Spider Roll
						</li>
					</ol>
				</li>
			</ol>
		</li>
		<li>
			<i class='icon-move-category'></i>
			Dinner
			<ol class='subcategory'>
				<li>
					<i class='icon-move-subcategory'></i>
					Entres
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							Speghetti
						</li>
						<li>
							<i class='icon-move-item'></i>
							Lasagna
						</li>
					</ol>
				</li>
				<li>
					<i class='icon-move-subcategory'></i>
					Sushi
					<ol class='item'>
						<li>
							<i class='icon-move-item'></i>
							Dragon Roll
						</li>
						<li>
							<i class='icon-move-item'></i>
							Spider Roll
						</li>
					</ol>
				</li>
			</ol>
		</li>
	</ol>
	
	<div id='serialize_output'>
	
	</div>
	";
*/

/*
$outputtext .= "
	<br>
	<input type='text' size='30' >
	<br>
	<br>
	
	<ol class='category'>
  <li><i class='icon-move-category'></i><b>First</b></li>
  <li><i class='icon-move-category'></i><b>Second</b></li>
  <li><i class='icon-move-category'></i><b>Third</b></li>
</ol>

	<pre id='serialize_output'>
	
	</pre>
	
	";
*/

?>