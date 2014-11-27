<?php
	include "connect.php";
	
	$loc_id = $_SESSION['loc_id'];
	$rest_id = $_SESSION['rest_id'];
	
	if($loc_id && $rest_id)
	{
        $outputtext .= "
			<div id='top'>
				<table id='topbartable'>
					<tr>
						<td valign='middle' align='center' style='font-size:35px;'>
							Edit ".$_SESSION['name']." Menu
						</td>
					</tr>
				</table>
			</div>
		
			<div id='main'>
				<script>
					window.onload=loadMenuDiv;
				</script>
				<center>
			";
        
        $query_get_category = "SELECT * FROM Menu_Category WHERE (loc_id='$loc_id' AND rest_id='$rest_id') ORDER BY category_order";
   
        $result_get_category = mysqli_query($dbc, $query_get_category);
        if(!$result_get_category)
		{//If the QUery Failed 
            $outputtext .= 'Query Failed ';
        }

        if (@mysqli_num_rows($result_get_category) > 0)//if Query is successfull 
        { // A match was made.
            
            $outputtext .= "
				<table id='menutable' class='sorted_table'>
					<colgroup>
						<col width='100%'>
						<col span='5' nowrap>
					</colgroup>
					";
            
            while($category_row = mysqli_fetch_array($result_get_category, MYSQLI_ASSOC))
            {
            	$category = $category_row['category'];
            	$query_get_item = "SELECT * FROM Menu_Item WHERE (loc_id='$loc_id' AND rest_id='$rest_id' AND category='$category') ORDER BY item_order";
            	$result_get_item = mysqli_query($dbc, $query_get_item);
            	
            	
            	if (@mysqli_num_rows($result_get_item) > 0)
            	{
            		$outputtext .= "
            			<tr>
							<th class='firstcol'>$category</th>
							<th>".$category_row['size_1']."</th>
							<th>".$category_row['size_2']."</th>
							<th>".$category_row['size_3']."</th>
							<th>".$category_row['size_4']."</th>
							<th>".$category_row['size_5']."</th>
						</tr>
            			";
            		
            		while($item_row = mysqli_fetch_array($result_get_item, MYSQLI_ASSOC))
            		{
            			$item_id = $item_row['item_id'];
            			$name = $item_row['name'];
            			
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
            			
            			if($item_row['price_1']){$price_1="$".number_format($item_row['price_1'], 2, '.', '');}
            			if($item_row['price_2']){$price_2="$".number_format($item_row['price_2'], 2, '.', '');}
            			if($item_row['price_3']){$price_3="$".number_format($item_row['price_3'], 2, '.', '');}
            			if($item_row['price_4']){$price_4="$".number_format($item_row['price_4'], 2, '.', '');}
            			if($item_row['price_5']){$price_5="$".number_format($item_row['price_5'], 2, '.', '');}
            			
            			
            			$outputtext .= "
            				<tr>
            					<td class='firstcol'>
            						<a href='javascript:popupItem(".'"'.$loc_id.'","'.$rest_id.'","'.$item_id.'","'.$name.'"'.")'>$name</a>
            						<br>
            						".$item_row['descript']."
            						$allergen
            					</td>
            					<td>$price_1</td>
            					<td>$price_2</td>
            					<td>$price_3</td>
            					<td>$price_4</td>
            					<td>$price_5</td>
            				</tr>
            				";
            		}
            		
            		$outputtext .= "
            			<tr><td><br></td></tr>
            			<tr><td><br></td></tr>
            			";
            		
            	}
            }
        }
		else
		{ 
            $outputtext .= "No Restaurant Menu In Database";
        }
        
        $outputtext .= "
					</table>
            		<br>
            		<br>
				</center>
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
	
	echo $outputtext;
	
    /// var_dump($error);
    mysqli_close($dbc);
?>