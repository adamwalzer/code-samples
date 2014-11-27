<?php
	include "connect.php";
	
	$loc_id = $_GET['loc_id'];
	$rest_id = $_GET['rest_id'];
	$item_id = $_GET['item_id'];
	
	if($loc_id && $rest_id && $item_id)
	{
        
        $query_get_item = "
        	SELECT * FROM Menu_Item 
        	WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND item_id='$item_id')
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
				WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND subcat_id='$subcat_id')
				";
			$result_get_subcat = mysqli_query($dbc, $query_get_subcat);
			
			
			if (@mysqli_num_rows($result_get_subcat) > 0)
			{
				$subcat_row = mysqli_fetch_array($result_get_subcat, MYSQLI_ASSOC);
				
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
					<form id='itemForm' action='javascript:addItemToOrder(".'"'.$loc_id.'","'.$rest_id.'","'.$item_id.'"'.")' method='post' class='item_order_form' autocomplete='off'>
						<table id='itemtable'>
							<colgroup>
							</colgroup>
							<tr>
								<td colspan='5'>".$item_row['descript'].$allergen."</td>
							</tr>
							<tr>
								<th align='left'><br>Quanity</th>
								<th align='left'>
									<br>
									<input type='hidden' value='::I'/>
									<input type='hidden' value='::Q'/>
									<input type='number' id='quanity' value='1' min='0'/>
								</th>
							</tr>
							<tr>
								<th align='left'><br>Size</th>
								<td>
									<table id='itemtable'>
									<tr>
					";
				
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
				
			/*
				if($item_row['price_1']!=0.00)
				{
					$price_1="$".number_format($item_row['price_1'], 2, '.', '');
					$outputtext .= "
							<td width='20%'>
								<input type='hidden' value='::S1'/>
								<input type='radio' name='size' id='size_1' checked>".$subcat_row['size_1']."<br>$price_1
							</td>
							";
				}
				
				if($item_row['price_2']!=0.00)
				{
					$price_2="$".number_format($item_row['price_2'], 2, '.', '');
					$outputtext .= "
							<td width='20%'>
								<input type='hidden' value='::S2'/>
								<input type='radio' name='size' id='size_2'>".$subcat_row['size_2']."<br>$price_2
							</td>
							";
				}
				
				if($item_row['price_3']!=0.00)
				{
					$price_3="$".number_format($item_row['price_3'], 2, '.', '');
					$outputtext .= "
							<td width='20%'>
								<input type='hidden' value='::S3'/>
								<input type='radio' name='size' id='size_3'>".$subcat_row['size_3']."<br>$price_3
							</td>
							";
				}
				
				if($item_row['price_4']!=0.00)
				{
					$price_4="$".number_format($item_row['price_4'], 2, '.', '');
					$outputtext .= "
							<td width='20%'>
								<input type='hidden' value='::S4'/>
								<input type='radio' name='size' id='size_4'>".$subcat_row['size_4']."<br>$price_4
							</td>
							";
				}
				
				if($item_row['price_5']!=0.00)
				{
					$price_5="$".number_format($item_row['price_5'], 2, '.', '');
					$outputtext .= "
							<td width='20%'>
								<input type='hidden' value='::S5'/>
								<input type='radio' name='size' id='size_5'>".$subcat_row['size_5']."<br>$price_5
							</td>
							";
				}
*/
				
				/*
				if($item_row['price_1']){$price_1="$".number_format($item_row['price_1'], 2, '.', '');}
				if($item_row['price_2']){$price_2="$".number_format($item_row['price_2'], 2, '.', '');}
				if($item_row['price_3']){$price_3="$".number_format($item_row['price_3'], 2, '.', '');}
				if($item_row['price_4']){$price_4="$".number_format($item_row['price_4'], 2, '.', '');}
				if($item_row['price_5']){$price_5="$".number_format($item_row['price_5'], 2, '.', '');}
				*/

				/*				
				$outputtext .= "
							<td><input type='radio' name='size' id='size_1' checked='checked' />".$subcat_row['size_1']."</td>
							<td><input type='radio' name='size' id='size_2' />".$subcat_row['size_2']."</td>
							<td><input type='radio' name='size' id='size_3' />".$subcat_row['size_3']."</td>
							<td><input type='radio' name='size' id='size_4' />".$subcat_row['size_4']."</td>
							<td><input type='radio' name='size' id='size_5' />".$subcat_row['size_5']."</td>
						</tr>
					";
				*/
				
				$outputtext .= "
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th align='left'>Extra Notes</th>
						<td colspan='5'>
							<input type='hidden' value='::N'/>
							<textarea id='notes' form='orderitem' rows='4' cols='50' placeholder='Ex: Please leave the dressing on the side.'></textarea>
						</td>
					</tr>
					<tr>
						<td colspan='2' class='submit'><input type='submit' id='stylebutton' value='Add To Order' /></td>
					</tr>
					";
					
				$outputtext .= "
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