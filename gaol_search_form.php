<?php
use lyttelton_gaol\fields;

//Tab layout from https://codepen.io/oknoblich/pen/tfjFl?editors=1100
?>
<!--GAOL SEARCH/BROWSE SECTION-->
<div id="gaol-search">

	<input id="tab-find-person" class="gaol-search-tab" type="radio" name="tabs" checked>
	<label for="tab-find-person" class="gaol-search-tab-label">Find a person</label>

	<input id="tab-find-by-keyword" class="gaol-search-tab" type="radio" name="tabs">
	<label for="tab-find-by-keyword" class="gaol-search-tab-label">Search by keyword</label>

	<section id="find-person" class="gaol-search-section">
		<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
			<div class="form-row">
				<div class="form-group col-md-6">
					<!--first name-->
					<label for="firstName"><?php echo fields\bio::NAME['desc']?></label>
					<input type="text" class="form-control" id="firstName" name="<?php echo fields\bio::NAME['id'] ?>" placeholder="Marry">
				</div>
				<!--last name-->
				<div class="form-group col-md-6">
					<label for="lastName"><?php echo fields\bio::SURNAME['desc'] ?></label>
					<input type="text" class="form-control" id="lastName" name="<?php echo fields\bio::SURNAME['id'] ?>" placeholder="Ann">
				</div>
			</div>
			<div class="form-row">
				<!--country-->
				<div class="form-group col-md-8">
					<label for="countryOfBirth"><?php echo fields\bio::COUNTRY_OF_BIRTH['desc'] ?></label>
					<input type="text" class="form-control" id="countryOfBirth" name="<?php echo fields\bio::COUNTRY_OF_BIRTH['id'] ?>" placeholder="England">
				</div>
				<!--trade-->
				<div class="form-group col-md-4">
					<label for="trade"><?php echo fields\bio::TRADE['desc'] ?></label>
					<select id="trade" class="form-control" name="<?php echo fields\bio::TRADE['id'] ?>">
						<option disabled selected value="">Select...</option>
						<?php
							foreach (get_all_meta_values(fields\bio::TRADE['id'], true) as $trade){
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
		<p>
			Bacon ipsum dolor sit amet landjaeger sausage brisket, jerky drumstick fatback boudin ball tip
			turducken. Pork belly meatball t-bone bresaola tail filet mignon kevin turkey ribeye shank flank
			doner cow kielbasa shankle. Pig swine chicken hamburger, tenderloin turkey rump ball tip sirloin
			frankfurter meatloaf boudin brisket ham hock. Hamburger venison brisket tri-tip andouille pork
			belly ball tip short ribs biltong meatball chuck. Pork chop ribeye tail short ribs, beef
			hamburger meatball kielbasa rump corned beef porchetta landjaeger flank. Doner rump frankfurter
			meatball meatloaf, cow kevin pork pork loin venison fatback spare ribs salami beef ribs.
		</p>
	</section>
</div>
