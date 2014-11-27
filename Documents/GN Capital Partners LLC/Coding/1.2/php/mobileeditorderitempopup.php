<?php
	session_start();
	
	include "connect.php";
	
	$loc_id = $_SESSION['loc_id'];
	$rest_id = $_SESSION['rest_id'];
	$order_id = $_SESSION['order_id'];
	$order_item_id = $_GET['order_item_id'];
	
	if($loc_id && $rest_id && $order_item_id)
	{
        
        $query_get_item = "
        	SELECT * FROM Customer_Order_Item 
        	WHERE (order_id='$order_id' AND order_item_id='$order_item_id')
        	";
		$result_get_item = mysqli_query($dbc, $query_get_item);

        if (@mysqli_num_rows($result_get_item) > 0)//if Query is successfull 
        { // A match was made.
            
            $item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC);
			$item_id = $item_row['item_id'];
			$name = $item_row['item_name'];
			
			$query_get_menu_item = "
				SELECT * FROM Menu_Item 
				WHERE (rest_id='$rest_id' AND item_id='$item_id')
				";
			$result_get_menu_item = mysqli_query($dbc, $query_get_menu_item);
			$menu_item_row = mysqli_fetch_array($result_get_menu_item, MYSQLI_ASSOC);
			
			$subcat_id = $menu_item_row['subcat_id'];
			$query_get_subcat = "
				SELECT * FROM Menu_Subcategory 
				WHERE (rest_id='$rest_id' AND subcat_id='$subcat_id')
				";
			$result_get_subcat = mysqli_query($dbc, $query_get_subcat);
			
			
			if (@mysqli_num_rows($result_get_subcat) > 0)
			{
				$subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC);
				
				if($menu_item_row['allergen'])
				{
					$allergen = "
						<br>
						allergen: ".$menu_item_row['allergen']."
						";
				}
				else
				{
					$allergen = "";
				}
				
				$outputtext .= "
					<center>
					<form id='itemForm' onSubmit='updateItemInOrder(".'"'.$order_item_id.'"'.");return false;' method='post' class='item_order_form' autocomplete='off'>
						<table id='itemtable'>
							<tr>
								<td colspan='5'>".$menu_item_row['descript'].$allergen."</td>
							</tr>
							<tr>
								<th class='firstcol'><br>Quanity</th>
								<th align='left'>
									<br>
									<input type='hidden' value='::I'/>
									<input type='hidden' value='::Q'/>
									<input type='number' id='quanity' value='".$item_row['quantity']."' min='0'/>
								</th>
							</tr>
							<tr>
								<th class='firstcol'><br>Size</th>
								<td>
									<table id='itemtable'>
									<tr>
					";
				
				$i = 1;
				while($i <= 5)
				{
					$price_num = 'price_'.$i;
					if($menu_item_row[$price_num]!=0.00)
					{
						$size_num = 'size_'.$i;
						$price="$".number_format($menu_item_row[$price_num], 2, '.', '');
						if($i==$item_row['size_number'])
						{
							$outputtext .= "
									<td width='20%'>
										<input type='hidden' value='::S$i'/>
										<input type='radio' name='size' id='$size_num' checked>".$subcat_row[$size_num]."<br>$price
									</td>
									";
						}
						else
						{
							$outputtext .= "
									<td width='20%'>
										<input type='hidden' value='::S$i'/>
										<input type='radio' name='size' id='$size_num'>".$subcat_row[$size_num]."<br>$price
									</td>
									";
						}
					}
					$i++;
				}
				
				$outputtext .= "
									</td>
								</tr>
							</table>
						</td>
					</tr>
					";
				
				$query_get_group = "
					SELECT * FROM Customer_Order_Item_Option_Group 
					WHERE (order_id='$order_id' AND order_item_id='$order_item_id') 
					ORDER BY group_order
					";
				$result_get_group = mysqli_query($dbc, $query_get_group);
				
				while($group_row = mysqli_fetch_array($result_get_group, MYSQLI_ASSOC))
				{
					$i = 0;
					$group_id = $group_row['group_id'];
					
					$outputtext .= "
						<table id='itemtable'>
							<tr>
								<th class='firstcol'>
									<input type='hidden' value='::G'/>
									<input type='hidden' value='".$group_id."'/>
									".$group_row['group_name']."
								</th>
						";
					
					$query_get_option = "
						SELECT * FROM Customer_Order_Item_Option 
						WHERE (order_id='$order_id' AND order_item_id='$order_item_id' AND group_id='$group_id') 
						ORDER BY option_order
						";
					$result_get_option = mysqli_query($dbc, $query_get_option);
					
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
						
						if($group_row['group_type'] == 'quantity')
						{
							$outputtext .= "value='".$option_row['option_quantity']."'";
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
						</table>
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
							<table id='itemtable'>
								<tr>
									<th class='firstcol'>Extra Notes</th>
									<td>
										<input type='hidden' value='::N'/>
										<textarea id='notes' form='orderitem' rows='4' cols='25' placeholder='Ex: Please leave the dressing on the side.'>".$item_row['notes']."</textarea>
									</td>
								</tr>
								<tr>
									<td colspan='2' class='submit'>
										<input type='submit' id='stylebutton' value='Update Item' />
									</td>
								</tr>
							</table>
						</form>
						<br>
						<br>
						</center>
					</div>
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
				<center>
					No Item
				</center>
			</div>
			";
    }
	
	echo $outputtext;
	
    /// var_dump($error);
    mysqli_close($dbc);
?>