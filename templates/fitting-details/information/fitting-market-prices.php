<?php
$fittingArray = \WordPress\Plugin\EveOnlineFittingManager\Helper\EftHelper::getFittingArrayFromEftData($eftFitting);
$marketPrices = \WordPress\Plugin\EveOnlineFittingManager\Helper\MarketdataHelper::getInstance()->getMarketPricesFromFittingArray($fittingArray);

if($marketPrices !== false) {
	?>
	<div class="fitting-market-price price-jita">
		<h4>
			<?php echo \__('Estimated Prices', 'eve-online-fitting-manager'); ?>
		</h4>

		<div class="bs-callout bs-callout-info">
			<p class="small">
				<?php echo \__('These are only estimated market prices and can vary from ingame prices depending on the shiptype and market you are looking at. Especially capital ships that are not traded at the Jita market can be miles off.', ''); ?>
			</p>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
			<div class="table-responsive">
				Jita Buy Order Price<br>
				<table class="table table-condensed table-fitting-marketdata">
					<tr>
						<th><?php echo \__('Ship:', 'eve-online-fitting-manager'); ?></th>
						<td><?php echo $marketPrices['ship']['jitaBuyPrice']; ?></td>
					</tr>
					<tr>
						<th><?php echo \__('Fitting:', 'eve-online-fitting-manager'); ?></th>
						<td><?php echo $marketPrices['fitting']['jitaBuyPrice']; ?></td>
					</tr>
					<tr>
						<th><?php echo \__('Total:', 'eve-online-fitting-manager'); ?></th>
						<td><?php echo $marketPrices['total']['jitaBuyPrice']; ?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
			<div class="table-responsive">
				Jita Sell Order Price<br>
				<table class="table table-condensed table-fitting-marketdata">
					<tr>
						<th><?php echo \__('Ship:', 'eve-online-fitting-manager'); ?></th>
						<td><?php echo $marketPrices['ship']['jitaSellPrice']; ?></td>
					</tr>
					<tr>
						<th><?php echo \__('Fitting:', 'eve-online-fitting-manager'); ?></th>
						<td><?php echo $marketPrices['fitting']['jitaSellPrice']; ?></td>
					</tr>
					<tr>
						<th><?php echo \__('Total:', 'eve-online-fitting-manager'); ?></th>
						<td><?php echo $marketPrices['total']['jitaSellPrice']; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<?php
} // END if($marketPrices !== false)
