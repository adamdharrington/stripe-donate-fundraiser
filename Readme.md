# Stripe Donate Fundraiser

This plugin was developed with love by Adam Harrington to help Uplift.ie 
handle donations and fundraising actions.

## Dependencies

__Pods__: This plugin is designed to work with Pods custom post types plugin. 
Sample configuration and page templates are included in the samples directory.

__Bootstrap__: This plugin includes a stripped down version of Bootstrap to facilitate quick development.
So few actual styles are used that removing Bootstrap entirely should be possible in 
future versions. The Bootstrap Modal JS is also included.

__Composer__: Composer is used to autoload PHP dependencies, these could easily be removed.

__Stripe-PHP__: Stripe has PHP bindings. Version 2.3 was used in this plugin.

__jQuery__: Obviously, included via CDN.

__jQuery.Validation__: Used to validate fields, CDN.

__jQuery.Payment: Used to give slick formatting and logic in payment fields. CDN too.

## Setup

The installation is the easiest part, just add to plugins directory and activate via the plugins menu.

Next is configuration, go to `Settings > Stripe Donate` to set the test API keys for the plugin, and
some defaults while in there.

If Pods is not installed, read up on that and make sure it suits your needs before installing. 
- Go to `Pods Admin > Components` and enable Pages and Migrate.
- In Migrate, import the import.json file by pasting it in and clicking the button.

Two sample page templates are included One for a Donation page that will live at \/contribute by default 
and the other for each donation or fundraising page. Move these to your theme directory to use, however, 
be advised that they are not designed to work with all themes and will definitely require some modification.

To use the default donation page, simply set up your first donation page, then create a new page 
for the desired url slug. You don't need to add any content, just choose the "Donate Page" template and you're
away.

That's it for version 1.0.0

## Version

This is version 1.0.0 and is not for general release, I make no guarantees that all code is great or even
injection-proof.

## Roadmap

Future versions will come with nicer code and features as they present themselves as important. I envision a 
version 1.0.1 with bug fixes really soon.

v1.2 will likely include some shortcode functionality to make it easier to use these pages. This will also 
include added fields, options and activation/deactivation hooks for DB actions.

## Contribute

All contributions welcome, just don't fork this and leave my name in it ;)



