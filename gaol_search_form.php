<?php
use lyttelton_gaol\fields\bio;
use lyttelton_gaol\fields\conviction;

//Tab layout from https://codepen.io/oknoblich/pen/tfjFl?editors=1100
?>
<!--GAOL SEARCH/BROWSE SECTION-->
<div id="gaol-search">

	<input id="tab-find-person" class="gaol-search-tab" type="radio" name="tabs" checked>
	<label for="tab-find-person" class="gaol-search-tab-label">Find a person</label>

	<input id="tab-find-by-conviction" class="gaol-search-tab" type="radio" name="tabs">
	<label for="tab-find-by-conviction" class="gaol-search-tab-label">Search by conviction</label>

	<section id="find-person" class="gaol-search-section">
		<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
			<div class="form-row">
				<div class="form-group col-md-6">
					<!--first name-->
					<label for="firstName"><?php echo bio::NAME['desc']?></label>
					<input type="text" class="form-control" id="firstName" name="<?php echo bio::NAME['id'] ?>" placeholder="Marry">
				</div>
				<!--last name-->
				<div class="form-group col-md-6">
					<label for="lastName"><?php echo bio::SURNAME['desc'] ?></label>
					<input type="text" class="form-control" id="lastName" name="<?php echo bio::SURNAME['id'] ?>" placeholder="Ann">
				</div>
			</div>
			<div class="form-row">
				<!--country-->
				<div class="form-group col-md-8">
					<label for="countryOfBirth"><?php echo bio::COUNTRY_OF_BIRTH['desc'] ?></label>
					<input type="text" class="form-control" id="countryOfBirth" name="<?php echo bio::COUNTRY_OF_BIRTH['id'] ?>" placeholder="England">
				</div>
				<!--trade-->
				<div class="form-group col-md-4">
					<label for="trade"><?php echo bio::TRADE['desc'] ?></label>
					<select id="trade" class="form-control" name="<?php echo bio::TRADE['id'] ?>">
						<option disabled selected value="">Select...</option>
						<?php
							foreach (get_all_meta_values(bio::TRADE['id'], true) as $trade){
								echo "<option value='$trade'>$trade</option>";
							}
						?>
					</select>
				</div>
			</div>
			<input type="hidden" name="post_type" value="convict" />
			<button type="submit" class="btn btn-primary">Search</button>
		</form>
	</section>

	<section id="find-by-keyword" class="gaol-search-section">
        <div class="form-group col-md-4">
            <label for="offence"><?php echo conviction::OFFENCE['desc'] ?></label>
            <select id="offence" class="form-control" name="<?php echo conviction::OFFENCE['id'] ?>">
                <option disabled selected value="">Select...</option>
				<?php
                    $all_con = [];
                    foreach (get_all_meta_values('convictions', true) as $con)
                        $all_con[] = unserialize($con);

                    //TODO GROUP CONVICTIONS FROM $ALL_CON

                    foreach ($all_con as $conviction){
                        echo "<option value='$conviction'>$conviction</option>";
				}
				?>
            </select>
        </div>
	</section>
</div>
