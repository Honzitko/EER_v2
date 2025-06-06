(function () {
	var $events = {};
	tinymce.PluginManager.add("eer_mce_button", function (editor, url) {
		jQuery.post(ajaxurl, {"action": "eer_tinymce_load_events"}, function (response) {
			$events = response;
		});
		editor.addButton("eer_mce_button", {
			text: "Events",
			icon: "icon dashicons-tickets-alt",
			classes: "eer-tinymce-button",
			title: "Insert Event Sale Shortcode",
			onclick: function () {
				editor.windowManager.open({
					title: "Insert Event Sale Shortcode",
					classes: "bg-show-more",
					body: [
						{
							type: "listbox",
							name: "eerEventID",
							label: "Choose Event",
							values: $events
						}
					],
					onsubmit: function (e) {
						var eer_shortcode_name = "eer_event_sale";
						var eer_shortcode = " event=\"" + e.data.eerEventID + "\"";
						editor.insertContent("[" + eer_shortcode_name + eer_shortcode + "]");
					}
				});
			}
		});
	});
})();
