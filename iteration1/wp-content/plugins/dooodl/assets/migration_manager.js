(function($){

	var total = 0,
		remaining = 0,
		$progressBar = $('.fjs-dooodl-progress-bar'),
		$progressLabel = $('.fjs-dooodl-progress-label'),
		$totalCount = $('.fjs-dooodl-total-count'),
		$remainingCount = $('.fjs-dooodl-remaining-count'),
		$progressPanel = $('.fjs-dooodl-progress'),
		$successPanel = $('.fjs-dooodl-success'),
		$databaseUpgradePanel = $('.fjs-dooodl-database-upgrade-step'),
		preventNavigation = true;

	function init() {
		$(document).ready(function(){
			startCancelNavigation();
			getDooodlStats(startUpgrade);
		});
	}

	function startCancelNavigation() {
		$(window).on('beforeunload', function() {
	        if (preventNavigation) {
	            return DooodlAdminLabels.confirmNavigation;
	        }
	    }); 
	}				

	function getDooodlStats(callback){
		var data = {'action': 'dooodl-get-stats'};

		$.get(ajaxurl, data, function(response) {
			total = response.count;
			remaining = response.count;

			showUpgradeProgress();

			if (typeof response.migration_complete !== 'undefined' && response.migration_complete == true) {
				updateDatabase();
			} else {
				callback();
			}					
		});
	}
	
	function showUpgradeProgress(){
		var progress = 0;

		if (remaining !== 0) {
			progress = Math.round((total - remaining) / total * 1000) / 10;	
		} else {
			progress = 100;
		}

		$remainingCount.text(remaining);
		$totalCount.text(total);					
		$progressBar.css('width', progress + '%');
		$progressLabel.text(progress);
	}

	function startUpgrade() {
		doUpdateBatch();
	}

	function doUpdateBatch() {
		var data = {'action': 'dooodl-batch-update'};

		$.get(ajaxurl, data, function(response) {
			remaining = response.count;
			
			showUpgradeProgress();

			if(typeof response.migration_complete !== 'undefined' && response.migration_complete === true) {
				updateDatabase();
			} else {
				onBatchComplete();
			}						
		});
	}

	function updateDatabase() {
		var data = {'action': 'dooodl-database-update'};

		$databaseUpgradePanel.slideDown(null, null, function(){
			$.get(ajaxurl, data, function(response){

				if(response.success === true) {
					onUpgradeComplete();
				} else {
					if (typeof console !== 'undefined' && typeof console.log !== 'undefined') {
						console.log(response);
						console.log('---- message from the plugin developer, Ronny Welter --------');
						console.log('If you see this something went wrong. Please get in touch with me via https://wordpress.org/support/plugin/dooodl');
						console.log('Sorry to make you go through this. I\'m pretty sure your database and site are okay. I will help you out asap.');
						alert('Something went wrong during the upgrade of the database. Please check the browser console for information');
					} else {
						alert('Something went wrong during the upgrade of the database. Please get in touch with the plugin developer via the WordPress support forums. https://wordpress.org/support/plugin/dooodl');
					}							
				}
			});
		});
	}

	function onBatchComplete() {
		if (remaining === 0) {
			updateDatabase();
		} else {
			doUpdateBatch();
		}
	}

	function onUpgradeComplete() {

		//remove the migration link. It is no longer necessary
		$("a[href='admin.php?page=dooodl-migration']").remove();

		$progressPanel.slideUp(null,null, function(){
			$successPanel.slideDown(null, null, function(){
				preventNavigation = false;
			});
		});
	}

	init();

}(jQuery));
