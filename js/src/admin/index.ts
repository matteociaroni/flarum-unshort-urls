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
		.registerSetting({
			setting: "matteociaroni-unshort-urls.timeout",
			type: "int",
			label: app.translator.trans("matteociaroni-unshort-urls.admin.settings.timeout_label"),
			help: app.translator.trans("matteociaroni-unshort-urls.admin.settings.timeout_help"),
		})
		.registerSetting({
			setting: "matteociaroni-unshort-urls.max_iterations",
			type: "int",
			label: app.translator.trans("matteociaroni-unshort-urls.admin.settings.max_iterations_label"),
			help: app.translator.trans("matteociaroni-unshort-urls.admin.settings.max_iterations_help"),
		})
});
