<div class="fitting-market-price price-jita">
	<script type="text/javascript">
	var eftData = '<?php echo \urlencode($eftFitting); ?>';
	</script>

	<h4>
		<?php echo \__('Estimated Prices', 'eve-online-fitting-manager'); ?>
	</h4>

	<div class="bs-callout bs-callout-info">
		<p class="small">
			<?php echo \__('These are only estimated market prices and can vary from ingame prices depending on the shiptype and market you are looking at. Especially capital ships that are not traded at the Jita market can be miles off.', 'eve-online-fitting-manager'); ?>
		</p>
	</div>

	<div class="row">
		<div class="col-sm-6 col-md-6 col-lg-12 col-xl-6">
			<div class="table-responsive">
				Jita Buy Order Price<br>
				<table class="table table-condensed table-fitting-marketdata">
					<tr>
						<th><?php echo \__('Ship:', 'eve-online-fitting-manager'); ?></th>
						<td class="eve-market-price eve-market-ship-buy"><span class="loaderImage"></span></td>
					</tr>
					<tr>
						<th><?php echo \__('Fitting:', 'eve-online-fitting-manager'); ?></th>
						<td class="eve-market-price eve-market-fitting-buy"><span class="loaderImage"></span></td>
					</tr>
					<tr>
						<th><?php echo \__('Total:', 'eve-online-fitting-manager'); ?></th>
						<td class="eve-market-price eve-market-total-buy"><span class="loaderImage"></span></td>
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
						<td class="eve-market-price eve-market-ship-sell"><span class="loaderImage"></span></td>
					</tr>
					<tr>
						<th><?php echo \__('Fitting:', 'eve-online-fitting-manager'); ?></th>
						<td class="eve-market-price eve-market-fitting-sell"><span class="loaderImage"></span></td>
					</tr>
					<tr>
						<th><?php echo \__('Total:', 'eve-online-fitting-manager'); ?></th>
						<td class="eve-market-price eve-market-total-sell"><span class="loaderImage"></span></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
