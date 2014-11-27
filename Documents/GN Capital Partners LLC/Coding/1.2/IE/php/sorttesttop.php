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
		";
}
else
{
	$outputtext .= "
		<div id='top'>
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