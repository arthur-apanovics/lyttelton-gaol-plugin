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

    <!-- FIND PERSON -->
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
            <input type="hidden" name="search_by" value="person" />
            <button type="submit" class="btn btn-primary">Search</button>
		</form>
	</section>

    <!-- FIND BY OFFENCE -->
	<section id="find-by-offence" class="gaol-search-section">
            <div id="offence-list">
		        <?php
		        $all_people_all_con = [];
		        foreach (get_all_meta_values('convictions', true) as $all)
			        $all_people_all_con[] = unserialize($all);

		        $unique = [];
		        foreach ($all_people_all_con as $all_con) {
			        foreach ($all_con as $con) {
			            $offence_value = $con[conviction::OFFENCE['id']];
				        if (!in_array($offence_value, $unique) && !preg_match('/(\(.*\)|and)/', $offence_value)) {
					        $unique[] = $offence_value;
				        }
			        }
		        }
                ?>
                <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                    <fieldset>
                        <legend>Choose convictions</legend>
                        <div class="row">
				            <?php
				            natcasesort($unique);
				            $col1    = $col2 = '';
				            $half    = count($unique) / 2;
				            $current = 0;
				            foreach ($unique as $key => $value) {
					            $str = '<div class="form-group form-check">
                                    <input type="checkbox" id="conviction_' . $key . '" class="form-check-input" 
                                        name="' . conviction::OFFENCE['id'] . '[]" value="' . $value . '" />
                                    <label class="form-check-label" for="conviction_' . $key . '">' . $value . '</label>
                                  </div>';

					            if ($current < $half)
					                $col1 .= $str;
					            else
					                $col2 .= $str;

					            $current++;
				            }
				            ?>
                            <div class="col-sm">
                                <?php echo $col1; ?>
                            </div>
                            <div class="col-sm">
		                        <?php echo $col2; ?>
                            </div>
                        </div>
                    </fieldset>

                    <div id="search-mode-wrapper" class="float-right">
                        <label for="search-mode">Search mode</label>
                        <select name="search-mode" id="search-mode">
                            <option value="AND" selected>AND</option>
                            <option value="OR">OR</option>
                        </select>
                    </div>
                    <input type="hidden" name="post_type" value="convict" />
                    <input type="hidden" name="search_by" value="conviction" />
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
        </div>
	</section>
    <hr>
</div>
