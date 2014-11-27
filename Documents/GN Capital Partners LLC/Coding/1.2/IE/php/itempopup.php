<?php
	include "connect.php";
	
	$loc_id = $_GET['loc_id'];
	$rest_id = $_GET['rest_id'];
	$item_id = $_GET['item_id'];
	
	$day_num = date("w");
	$time = date("H:i:s", time());

	$query_get_open = "
		SELECT * FROM Hours 
		WHERE ( rest_id='$rest_id' 
		AND ( ( day_num='$day_num' 
			AND open<='$time' 
			AND ( close>'$time' 
				OR ( close_day_num>'$day_num' 
				OR ( day_num='6' 
					AND close_day_num='0' 
					) 
				) 
			) ) 
			OR ( ( day_num<'$day_num' 
				OR ( day_num='6' 
					AND close_day_num='0' 
					) 
				) AND ( close_day_num='$day_num' 
					AND close>'$time' 
					) 
				) 
			) 
			)
		";
	$result_get_open = mysqli_query($dbc, $query_get_open);
	
	if(@mysqli_num_rows($result_get_open) != 0)
	{
        $query_get_item = "
        	SELECT * FROM Menu_Item 
        	WHERE (rest_id='$rest_id' AND item_id='$item_id')
        	";
   
        $result_get_item = mysqli_query($dbc, $query_get_item);
        if(!$result_get_item)
		{//If the QUery Failed 
            $outputtext .= 'Query Failed ';
        }

        if (@mysqli_num_rows($result_get_item) > 0)//if Query is successfull 
        { // A match was made.
            
            $item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC);
			$name = $item_row['name'];
			
			$subcat_id = $item_row['subcat_id'];
			$query_get_subcat = "
				SELECT * FROM Menu_Subcategory 
				WHERE (rest_id='$rest_id' AND subcat_id='$subcat_id')
				";
			$result_get_subcat = mysqli_query($dbc, $query_get_subcat);
			$subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC);
			
			$cat_id = $subcat_row['cat_id'];
			$query_get_disable = "
				SELECT * FROM Menu_Category_Disable_Hours 
				WHERE ( rest_id='$rest_id' 
					AND cat_id='$cat_id' 
					AND ( ( day_num='$day_num' 
						AND start<='$time' 
						AND ( stop>'$time' 
							OR ( stop_day_num>'$day_num' 
								OR ( day_num='6' 
									AND stop_day_num='0' 
									) 
								) 
							) 
							) OR ( ( day_num<'$day_num' 
								OR ( day_num='6' 
									AND stop_day_num='0' 
									) 
									) AND ( stop_day_num='$day_num' 
										AND stop>'$time' 
										) 
									) 
						) 
						)
				";
			$result_get_disable = mysqli_query($dbc, $query_get_disable);
			
			//$outputtext .= $day_num." ";
			//$outputtext .= $time;
			if(@mysqli_num_rows($result_get_disable) > 0)
			{
				$outputtext .= "
					<div id='main'>
						<form action='javascript:closeItemPopupDialog();' method='post' class='login_form'>
							<center>
								<table id='menutable'>
									<tr>
										<td>This item cannot be ordered at this time.</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td class='submit'><input type='submit' id='stylebutton' value='Thanks' title='Thanks' /></td>
									</tr>
								</table>
							</center>
						</form>
					</div>
					";
			}
			elseif (@mysqli_num_rows($result_get_subcat) > 0)
			{
				if($item_row['allergen'])
				{
					$allergen = "
						<br>
						allergen: ".$item_row['allergen']."
						";
				}
				else
				{
					$allergen = "";
				}
				
				$outputtext .= "
					<center>
					<form id='itemForm' onSubmit='addItemToOrder(this,".'"'.$loc_id.'"'.",".'"'.$rest_id.'"'.",".'"'.$item_id.'"'.");return false;' method='post' class='item_order_form' autocomplete='off'>
						<table id='itemtable'>
							<tr>
								<td colspan='5'>".$item_row['descript'].$allergen."</td>
							</tr>
							<tr>
								<th class='firstcol'><br>Quanity</th>
								<th align='left'>
									<br>
									<input type='hidden' value='::I'/>
									<input type='hidden' value='::Q'/>
									<input type='number' id='quanity' value='1' step='1' min='0'/>
								</th>
							</tr>
							";
				
				$has_prices = 0;	
				for($i=1;$i<=5;$i++)
				{
					$price_num = 'price_'.$i;
					if($item_row[$price_num]!=0.00)
					{
						$has_prices++;
					}
				}
				
				if($has_prices)
				{
					$outputtext .= "
								<tr>
									<th class='firstcol'><br>Size</th>
									<td>
										<table id='itemtable'>
										<tr>
						";
				}
				
				$i = 1;
				while($i <= 5)
				{
					$price_num = 'price_'.$i;
					if($item_row[$price_num]!=0.00)
					{
						$size_num = 'size_'.$i;
						$price="$".number_format($item_row[$price_num], 2, '.', '');
						if($i==1)
						{
							$outputtext .= "
									<td width='20%'>
										<input type='hidden' value='::S$i'/>
										<input type='radio' name='size' id='$size_num' checked />".$subcat_row[$size_num]."<br>$price
									</td>
									";
						}
						else
						{
							$outputtext .= "
									<td width='20%'>
										<input type='hidden' value='::S$i'/>
										<input type='radio' name='size' id='$size_num' />".$subcat_row[$size_num]."<br>$price
									</td>
									";
						}
					}
					$i++;
				}
				
				if($has_prices)
				{
					$outputtext .= "
									</tr>
								</table>
							</td>
						</tr>
						";
				}
				
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
				
				$query_get_group = "
					SELECT * FROM Menu_Item_Option_Group 
					WHERE (rest_id='$rest_id' AND item_id='$item_id' AND updated='$updated') 
					ORDER BY group_order
					";
				$result_get_group = mysqli_query($dbc, $query_get_group);
				
				while($group_row = mysqli_fetch_array($result_get_group, MYSQLI_ASSOC))
				{
					$i = 0;
					$group_id = $group_row['group_id'];
					
					$outputtext .= "
						<tr>
						<td colspan='5'>
						<table id='itemtable'>
							<tr>
								<th class='firstcol'>
									<input type='hidden' value='::G'/>
									<input type='hidden' value='".$group_id."'/>
									".$group_row['group_name']."
								</th>
						";
					
					$query_get_option = "
						SELECT * FROM Menu_Item_Option 
						WHERE (rest_id='$rest_id' AND item_id='$item_id' AND group_id='$group_id' AND updated='$updated') 
						ORDER BY option_order
						";
					$result_get_option = mysqli_query($dbc, $query_get_option);
					
					$x=1;
					while($option_row = mysqli_fetch_array($result_get_option, MYSQLI_ASSOC))
					{
						if($i == 3)
						{
							$outputtext .= "
								</tr>
								<tr>
									<td>&nbsp;</td>
								";
							$i = 0;
						}
						
						$option_id = $option_row['option_id'];
						
						$outputtext .= "
							<td>
								<input type='hidden' value='::O'/>
								<input type='hidden' value='".$option_id."'/>
								<input type='";
								
						if($group_row['group_type'] == 'quantity')
						{
							$outputtext .= "number";
						}
						else
						{
							$outputtext .= $group_row['group_type'];
						}
						
						$outputtext .= "' min='0' step='1' name='group_$group_id' id='option_$option_id' ";
						
						
						if($x==1 && $group_row['group_type']=='radio')
						{
							$outputtext .= 'checked';
							$x++;
						}
						else
						{
							$outputtext .= $option_row['checked'];
						}
						
						$outputtext .= " >
								".$option_row['option_name']."
								<br>
								".$option_row['option_cost']."
							</td>
							";
							
						$i++;
					}
					
					$outputtext .= "
							</tr>
							";
							
					if($group_row['group_type'] == 'quantity')
					{
						$outputtext .= "
							<tr>
								<td colspan='4' align='center' id='quantity_test'></td>
							</tr>
							";
					}
							
					$outputtext .= "
						</table>
						</td>
						</tr>
						";
						
					if($group_row['group_type'] == 'quantity')
					{
						$outputtext .= "
							<script>
								$('#itemForm').keyup(function(){
									testFormQuantity('itemForm',".$group_row['group_quantity'].");
								});
								$('#itemForm').change(function(){
									testFormQuantity('itemForm',".$group_row['group_quantity'].");
								});
								testFormQuantity('itemForm',".$group_row['group_quantity'].");
							</script>
							";
					}
				}
				
				
				
				$outputtext .= "
								<tr>
									<th class='firstcol'>Extra Notes</th>
									<td>
										<input type='hidden' value='::N'/>
										<textarea id='notes' form='orderitem' rows='4' cols='50' placeholder='Ex: Please leave the dressing on the side.'></textarea>
									</td>
								</tr>
								<tr>
									<td colspan='2' class='submit'>
										<input type='submit' id='stylebutton' value='Add To Order' />
									</td>
								</tr>
							</table>
						</form>
						<br>
						<br>
						</center>
					";
				
			}
        }
		else
		{ 
            $outputtext .= "No Item In Database";
        }
    }
	else
	{
		$outputtext .= "
			<div id='main'>
				<form action='javascript:closeItemPopupDialog();' method='post' class='login_form'>
					<center>
						<table id='itemtable'>
							<tr>
								<td>This item cannot be ordered at this time because the restaurant is closed.</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td class='submit'><input type='submit' id='stylebutton' value='Thanks' title='Thanks' /></td>
							</tr>
						</table>
					</center>
				</form>
			</div>
			";
    }
	
	echo $outputtext;
	
    /// var_dump($error);
    mysqli_close($dbc);
?>