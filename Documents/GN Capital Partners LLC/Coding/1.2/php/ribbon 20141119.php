<?php
	
$outputtext .= "
	<div id='ribbon_container' class='ribbon_container'>
		<div id='ribbon' class='ribbon'></div>
			<div id='ribbon_content' class='ribbon_content'>
				<div id='ribbon_phrase_outer_div' class='ribbon_phrase_outer_div'>
					<div id='ribbon_phrase_inner_div' class='ribbon_phrase_inner_div'>
						ORDER NOW AND<br/>EAT LIKE A LOCAL!
					</div>
				</div>
				<div id='ribbon_search_outer_div' class='ribbon_search_outer_div'>
					<div id='ribbon_search_inner_div' class='ribbon_search_inner_div'>
						<div id='current_address' class='solid_block_div'>
							<div class='solid_block_title content'>
								Find Food Near You
							</div>
							<div class='solid_block_body content'>
							<form id='current_address_form' onSubmit='submitForm(this,".'"setcurrentaddress"'.");return false;' method='post' class='login_form'>

								<table id='registrationtable'>
						
								<tr id='delivery_preference_test'>
								<th colspan='1' class='optional'><input type='radio' id='delivery_preference1' name='delivery_preference' value='delivery' onchange='' placeholder='Delivery' size='30' ";
						
								if($_SESSION['delivery_preference']=='delivery'||!$_SESSION['delivery_preference'])
								{
									$outputtext .= "checked";
								}
						
								$outputtext .= " /><label for='delivery_preference1'>Delivery</label></th>
								<th colspan='1' class='optional'><input type='radio' id='delivery_preference2' name='delivery_preference' value='pickup' onchange='' placeholder='Pick Up' size='30' ";
						
								if($_SESSION['delivery_preference']=='pickup')
								{
									$outputtext .= "checked";
								}
						
								$outputtext .= " /><label for='delivery_preference2'>Pick Up</label></th>
								</tr>
						
								<tr>
								<th colspan='2' class='optional' id='current_on_beach_test'><input type='checkbox' id='current_on_beach' name='current_on_beach' onchange='testOnBeach(this)' placeholder='On The Beach' size='30' ".$_SESSION['del_on_beach']." /><label for='current_on_beach'>On The Beach</label></th>
								</tr>

								<tr>
								<th colspan='2' class='test' id='current_address_test'><input type='text' id='current_address' name='current_address' onchange='testAddress(this)' onkeyup='testAddress(this)' placeholder='Street Address' value='".$_SESSION['del_address']."' size='30' /><div></div></th>
								</tr>

								<tr>
								<th colspan='2' class='test' id='current_city_test'><input type='text' id='current_city' name='current_city' onchange='testCity(this)' onkeyup='testCity(this)' placeholder='City' value='".$_SESSION['del_city']."' size='30' /><div></div></th>
								</tr>

								<tr>
								<th colspan='2' class='test' id='current_state_test'>
					";

				$states = statesList();
				$stateletters = $_SESSION['del_state'];

				if($stateletters)
				{
					$statename = $states[$stateletters];
				}
				else
				{
					$statename = "Select Your State...";
				}

				$outputtext .= "<select type='text' id='current_state' name='current_state' onchange='testState(this)' >";
				$outputtext .= "<option selected='selected' value='".$stateletters."'>".$statename."</option>";

				$outputtext .= "</option>";

				foreach($states as $key=>$value)
				{
					$outputtext .= "<option value='".$key."'>".$value."</option>";
				}

				$outputtext .= "</select><div></div>";

				$outputtext .= "
								</th>
								</tr>

								<tr>
								<th colspan='2' class='test' id='current_zip_test'><input type='text' id='current_zip' name='current_zip' onchange='testZipCode(this)' onkeyup='testZipCode(this)' placeholder='Zip Code' value='".$_SESSION['del_zip']."' size='30' /><div></div></th>
								</tr>

								<tr>
								<th colspan='2' class='center'><input type='submit' id='stylebutton' value='Enter Address' title='Enter Address' disabled /></th>
								</tr>
						
								<tr>
								<th colspan='2' class='center regular'>view restaurants in <a onclick='changeURLVariable(".'"loc_id=ac_area"'.",{".'"reload"'.":true});'>AC area</a></th>
								</tr>

								</table>

							</form>
							</div>
						</div>
						<script>
							testForm('#current_address_form');
							checkForm('#current_address_form');
							$(function() {
								$( '#delivery_preference_test' ).buttonset();
							});
						</script>
					</div>
				</div>
			</div>
		</div>
	</div>
	";

?>