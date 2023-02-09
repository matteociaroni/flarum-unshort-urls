import app from "flarum/admin/app";

app.initializers.add("matteociaroni/flarum-unshort-urls", () => {
	app.extensionData
		.for("matteociaroni-unshort-urls")

		.registerSetting({
			setting: "matteociaroni-unshort-urls.domains",
			type: "textarea",
			label: app.translator.trans("matteociaroni-unshort-urls.admin.settings.domains_label"),
			help: app.translator.trans("matteociaroni-unshort-urls.admin.settings.domains_help"),
		})
});
