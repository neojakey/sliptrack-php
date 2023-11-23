$(document).ready(function() {
	$('.yodlee-launcher').click(function(event) {
		event.preventDefault();
		this.blur(); // Manually remove focus from clicked link.
		getFastLinkToken();
	  });
})

function getFastLinkToken() {
	$.ajax(
	{
		url: "get_fastlink_token.asp",
		success: function(response) {
			launchFastlink(response.token);
		},
		error: function(response) {
			console.log(response);
			// TODO handle failures correctly
		}
	});
}

function launchFastlink(accessToken) {
	
	// JQuery modal button
	var errorMsg = "There was a problem launching the linking utility";
	
	var fastLinkModal = $('#fastlink-modal').modal({
		escapeClose: false,
  		clickClose: false,
		showClose: false
	});
	$('#fastlink-modal > a').click(function() {
		closeModal(true);
	});
	if(accessToken != "error") {
		var fastlinkIFrame = window.fastlink.open({
			fastLinkURL: yodleeFastLinkUrl,
			accessToken: "Bearer " + accessToken,
			params: {
				configName : 'Aggregation'
              },
			onSuccess: function (data) {
					console.log(data);
			},
			onError: function (data) {
					console.log(data);
					handleError(data);
			},
			onClose: function (data) {
				console.log("exit has been called");
				console.log(data);
				closeModal(true);
			},
			onEvent: function (data) {
					console.log(data);
			}
		}, 'fastlink-modal');
		// Add event listener to explicitly close FastLink when modal closes
		$('#fastlink-close').click(function() {
			closeModal();
		});
	} else {
		$('<p>', {
			text: errorMsg,
			id: 'fastlink-error'
		 }).appendTo(fastLinkModal);
	}
}

function handleClose(data) {
	closeModal(true);
}

function closeModal(refreshAccounts) {
	$('#fastlink-modal > div').remove();
	$.modal.close();
	if(refreshAccounts) {
		refreshExternalAccounts();
	}
}

function handleError(fastLinkData) {
	if(fastLinkData.code == "N104") {
		// Cookies disabled
		$("#fastlink-modal").append("<P>Cookies are disabled on your browser. Please enable them to continue");
	}
	if(fastLinkData.hasOwnProperty('code') && (fastLinkData.code.includes("N") || fastLinkData.code.includes("E") || fastLinkData.code == "undefined")) {
		// Application launch failure
		var closeButton = $('<input/>').attr({
			type: "button",
			id: "errorClose",
			value: 'Close',
			class: "close-modal"
		}).on("click", function() {
			closeModal(false);
		});
		$("#fastlink-modal").append(closeButton);
	}

	if(fastLinkData.hasOwnProperty('code') && fastLinkData.code != undefined) {
		if(fastLinkData.code.includes("N") || fastLinkData.code == "undefined" || fastLinkData.code.includes("E")) {
			reportFastLinkError(fastLinkData);
		}
	}
}

function reportFastLinkError(fastLinkData) {
	$.ajax({
		url: "report_fastlink_failure.asp",
		type: "POST",
		data: {
			"code": fastLinkData.code,
			"title": fastLinkData.title,
			"message": fastLinkData.message,
			"action": fastLinkData.action,
			"fnToCall": fastLinkData.fnToCall
		},
		error: function(response) {
			console.log(response);
		}
	});
}

function refreshExternalAccounts() {
	$('.yodlee-launcher').addClass('disabled');
	$('.yodlee-launcher').removeAttr('href');
	$('.yodlee-launcher > a').removeAttr('href');
	createLoadingRow();
	$.ajax(
	{
		url: "update_external_accounts.asp",
		success: function(data) {
			console.log(data);
			populateProviders(data);
			populateUnsupportedAccounts();
			createDropDownListeners();
			createDeleteProviderListeners();
			$('.yodlee-launcher').removeClass('disabled');
			$('.yodlee-launcher').attr({
				"href": "#fastlink-modal"
			});
			$('.yodlee-launcher > a').attr({
				"href": "javascript:void(0);"
			});
		}
	});
}

function populateProviders(providerData) {
	var providers = providerData.data;
	var timezoneOffset = providerData.timezoneOffset;
	$("#integration-table").find("tr:gt(0)").remove();
	var table = $("#integration-table");
	if(providers.length == 0) {
		var providerRows = $("<tr>").attr({
			class: "h30"
		}).append($("<td>").attr({
			class: "fb tac",
			id: "loading-row",
			colspan: "5"
		}).text("No accounts have been linked"));

		table.append(providerRows);
	} else {
		for(provider of providers) {
			var applicantProvider = $("<tr>");

			var expandImage = $("<i>").attr({
				title: "Expand",
				class: "fa fa-caret-right",
				id: "icon-" + provider.IntegrationId
			});

			var iconLink = $("<a>").attr("href", "javascript:void(0);");

			if('IconUrl' in provider && provider.IconUrl != null){
				// Use the provided icon
				iconLink.append($("<img>").attr({
					src: provider.IconUrl,
					class: "bank-icons",
					title: "Click to show assciated records",
					alt: provider.ProviderName
				}));
			} else {
				iconLink.attr({
					class: "bank-icon"
				});
				iconLink.text = $("<i>").attr({
					class: "fa fa-university",
					"aria-hidden": "true"
				}).text("Click to show assciated records");
			}

			applicantProvider
				.append($("<td>").attr({
					class: "hand wb-all",
					id: "folder-" + provider.IntegrationId,
					"data-providerid": provider.ProviderId
				})
				.append($("<ul>").attr({
					class: "exp-col-list"
				})
				.append($("<li>").append(expandImage))
				.append($("<li>").append(iconLink))));

				applicantProvider
				.append(createProviderNameColumn(provider))
				.append(createProviderIdColumn(provider))
				.append(createProviderCreateTimeColumn(provider, timezoneOffset))
				.append(createProviderActionColumn(provider));
			
			table.append(applicantProvider).append(createProviderCollapseRow(provider));
		}
	}
}

function populateUnsupportedAccounts() {
	$.ajax(
		{
			url: "get_unsupported_accounts.asp",
			success: function(data) {
				var providersLinked = data.providersLinked;
				if(providersLinked > 0) {
					$('#unsupported-container').removeClass("hidden");
					$('#unsupported-container-h').removeClass("hidden");
				}
				$("#unsupported-table").find("tr:gt(0)").remove();
				var table = $("#unsupported-table");
				if(providersLinked == 0 && data.externalAccounts.length == 0) {
					var providerRows = $("<tr>").attr({
						class: "h30"
					}).append($("<td>").attr({
						class: "fb tac",
						id: "loading-row",
						colspan: "3"
					}).text("No accounts have been linked"));
					table.append(providerRows);
				} else if (providersLinked > 0 && data.externalAccounts == 0) {
					var providerRows = $("<tr>").attr({
						class: "h30"
					}).append($("<td>").attr({
						class: "fb tac",
						id: "loading-row",
						colspan: "3"
					}).text("All of your accounts are supported!"));
					table.append(providerRows);
				} else {
					data.externalAccounts.forEach(function(account, index) {
						var row = $("<tr>")
							.append($("<td>").text(account.accountName))
							.append($("<td>").text(account.accountNumber))
							.append($("<td>").text(account.providerName));
						table.append(row);
					})
				}
			}
		});
}

function createLoadingRow() {
    $("#integration-table").find("tr:gt(0)").remove();
	var table = $("#integration-table");

    table.append($("<tr>").attr({
        class: "h30"
    }).append($("<td>").attr({
        class: "fb tac",
        id: "loading-row",
        colspan: "5"
    }).append($("<img>").attr({
        src: "/Images/icons/ajax-loader.gif"
	}))));
	
	$("#unsupported-table").find("tr:gt(0)").remove();
	var unsupportedTable = $("#unsupported-table");
	unsupportedTable.append($("<tr>").attr({
        class: "h30"
    }).append($("<td>").attr({
        class: "fb tac",
        id: "loading-row",
        colspan: "3"
    }).append($("<img>").attr({
        src: "/Images/icons/ajax-loader.gif"
	}))));
}

function createProviderNameColumn(provider) {
	var column = $("<td>");

	var link = $("<a>").attr({
		href: "javascript:void(0);",
		id: "link-" + provider.ProviderId
	})
	.data("providerid", provider.ProviderId)
	.append(provider.ProviderName);

	return column.append(link);
}

function createProviderIdColumn(provider) {
	var column = $("<td>").text(provider.ProviderId);

	return column;
}

function createProviderCreateTimeColumn(provider, offset) {
	var date = new Date(provider.CreateDate).addHours(offset);
	date = (date.getMonth() + 1) + '/' + date.getDate() + '/' +  date.getFullYear();
	var column = $("<td>").text(date);

	return column;
}

function createProviderActionColumn(provider) {
	var deleteImage = $("<img>").attr({
		src: "/images/icons/cross.png",
		alt: ""
	});
	var column = $("<td>");

	var actionList = $("<ul>").attr({
		class: "data-grid-icons"
	});
	var deleteAction = $("<li>").append($("<a>").attr({
		href: "javascript:void(0);",
		class: "delete-provider",
		title: "Delete",
		"data-providerid": provider.ProviderId
	})
	.append(deleteImage));

	actionList.append(deleteAction);

	return column.append(actionList);
}

function createProviderCollapseRow(provider) {
	return $("<tr>").attr({
		id: "folder-child-" + provider.IntegrationId,
		class: "collapsed"
	}).append($("<td>").attr({
		colspan: "5",
		id: "details-" + provider.IntegrationId
	}).text("&nbsp;"));
}

Date.prototype.addHours = function(h) {
	this.setTime(this.getTime() + (h*60*60*1000));
	return this;
}
