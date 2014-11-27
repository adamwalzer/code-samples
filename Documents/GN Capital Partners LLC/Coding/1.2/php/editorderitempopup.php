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
						allergen: ".htmlspecialchars($menu_item_row['allergen'], ENT_QUOTES)."
						";
				}
				else
				{
					$allergen = "";
				}
				
				$outputtext .= "
					<center>
					<form id='itemForm' onSubmit='submitInput(this,".'"updateiteminorder'.($_GET['review_order']=='true'?'&review_order=true':'').'&order_item_id='.$order_item_id.'"'.");return false;' method='post' class='item_order_form' autocomplete='off'>
						<div class='section group row'> 
							<div class='col span_2_of_2'>".$item_row['descript'].$allergen."<br/></div>
						</div>
						<div class='section group row'>
							<div class='col span_1_of_5'><h3>Quanity</h3></div>
							<div class='col span_4_of_5'>
								<div class='col span_1_of_4'>
									<h3>
										<input type='hidden' value='::I'/>
										<input type='hidden' value='::Q'/>
										<input type='number' id='quanity' value='".$item_row['quantity']."' step='1' min='1'/>
									</h3>
								</div>
							</div>
						</div>
						";
				
				$has_prices = 0;	
				for($i=1;$i<=5;$i++)
				{
					$price_num = 'price_'.$i;
					if($menu_item_row[$price_num]!=0.00)
					{
						$has_prices++;
					}
				}
				
				if($has_prices)
				{
					$outputtext .= "
								<div class='section group row'>
									<div class='col span_1_of_5'><h3>Size</h3></div>
									<div class='col span_4_of_5'>
										<div id='sizes' class='section group'>
											<div class='section group'>
						";
				}
				
				$i = 1;
				while($i <= 5)
				{
					$price_num = 'price_'.$i;
					if($menu_item_row[$price_num]!=0.00)
					{
						$size_num = 'size_'.$i;
						$price="$".number_format($menu_item_row[$price_num], 2, '.', '');
						if($i==5)
						{
							$outputtext .= "
								</div>
								<div class='section group'>
								";
						}
						
						$outputtext .= "
								<div class='col span_1_of_4'>
									<input type='hidden' value='::S$i'/>
									<input type='radio' name='size' id='$size_num' ".($i==$item_row['size_number']?"checked ":"")."/><label for='$size_num'>";
						
						if($subcat_row[$size_num])
						{
							$outputtext .= $subcat_row[$size_num]."<br>";
						}
						
						$outputtext .= $price."</label>
								</div>
								";
					}
					$i++;
				}
				
				if($has_prices)
				{
					$outputtext .= "
										</div>
									</div>
								</div>
							</div>
						";
				}
				
				$query_get_group = "SELECT * FROM Customer_Order_Item_Option_Group WHERE (order_id='$order_id' AND order_item_id='$order_item_id') ORDER BY group_order ASC";
				$result_get_group = mysqli_query($dbc, $query_get_group);
				
				while($group_row = mysqli_fetch_array($result_get_group, MYSQLI_ASSOC))
				{
					$i = 0;
					$group_id = $group_row['group_id'];
					
					$outputtext .= "
						<div class='section group row'>
							<div class='col span_1_of_5'>
									<input type='hidden' value='::G'/>
									<input type='hidden' value='".$group_id."'/>
									<h3>".$group_row['group_name']."</h3>
							</div>
							<div class='col span_4_of_5'>
								<div id='group_".$group_id."' class='section group'>
									<div class='col span_1_of_2 span_1_to_2_of_2'>
						";
					
					$query_get_option = "SELECT * FROM Customer_Order_Item_Option WHERE (order_id='$order_id' AND order_item_id='$order_item_id' AND group_id='$group_id') ORDER BY option_order ASC";
					$result_get_option = mysqli_query($dbc, $query_get_option);
					
					while($option_row = mysqli_fetch_array($result_get_option, MYSQLI_ASSOC))
					{
						$option_id = $option_row['option_id'];
						
						$outputtext .= "
							<div class='col span_1_of_2'>
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
								<label for='option_$option_id'>
									".$option_row['option_name']."
									<br>
									".$option_row['option_cost']."
								</label>
							</div>
							";
							
						if($i % 2)
						{
							$outputtext .= "
								</div>
								<div class='col span_1_of_2 span_1_to_2_of_2'>
								";
						}
							
						$i++;
					}
					
					$outputtext .= "
								</div>
							</div>
							";
							
					if($group_row['group_type'] == 'quantity')
					{
						$outputtext .= "
							<div class='col span_2_of_2'>
								<td colspan='4' align='center' id='quantity_test'></td>
							</div>
							";
					}
							
					$outputtext .= "
						</div>
						</div>
						<script>
							$(function() {
								$( '#group_".$group_id."' ).buttonset();
							});
						</script>
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
							<div class='section group row'>
								<div class='col span_1_of_5'><h3>Extra Notes</h3></div>
								<div class='col span_4_of_5'>
									<input type='hidden' value='::N'/>
									<textarea id='notes' form='orderitem' rows='4' cols='50' placeholder='Ex: Please leave the dressing on the side.'>".htmlspecialchars($item_row['notes'], ENT_QUOTES)."</textarea>
								</div>
							</div>
							<div class='section group row'>
								<div class='col span_2_of_2'>
									<input type='submit' id='stylebutton' value='Add To Order' />
								</div>
							</div>
						</form>
						</center>
						<script>
							$(function() {
								$( '#sizes' ).buttonset();
							});
						</script>
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
				<form action='javascript:popup[".'"item"'."].dialog(".'"close"'.");' method='post' class='login_form'>
					<center>
						<table id='itemtable'>
							<tr>
								<td>This item was not found in this order.</td>
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
    
    $outputtext = "
		<action type='popup' name='edit_order_item' title='Edit ".htmlspecialchars($menu_item_row['name'], ENT_QUOTES)."'>
			<text>
				".$outputtext."
			</text>
		</action>
		";
	
    /// var_dump($error);
    mysqli_close($dbc);
?>