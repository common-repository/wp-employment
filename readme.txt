=== Plugin Name ===
Contributors: ahuisinga
Tags: employment, jobs, management
Requires at least: 3.4
Tested up to: 3.5.1
Stable tag: 0.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: huisinga.ws/donate/

Integrates a simple system to list job openings, display them on a sleek and organized page, and accept applications via pre-formatted email messages.

== Description ==
Integrates a simple system to list job openings, display them on a sleek and organized page, and accept applications via pre-formatted email messages.

Uses the short code `[WPEM]` to render the job listings with the proper tags defined in the settings panel. Uses the short code `[EMAPPLY]` to render the job application form for the selected job opening.

Note: When creating job listings, be sure to fill out the job meta data (contact email, wage, resume, etc)! Without some of them, you may experience errors.

== Dependencies ==
1. Active Wordpress installation (tested on 3.5+).
2. Uses PHPMailer to format and mail the received applications.

== Installation ==
1. Upload the `wp-employment.zip` file to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Adjust settings under `Settings -> Job Openings`
4. Create some job listings under `Employment` custom post type
  1. Be sure to give the post a tag of the company that this listing is for (valid tags are defined in Settings).
  2. Also be sure to check the Meta information under the post body. These are settings unique to the job listing post type.
5. Create a new WP page, and use the short code `[WPEM]` in post content to pull in active listings and format them automatically.
6. Create another new WP page, and use the short code `[EMAPPLY]` to let viewers apply for positions that are listed.

== Screenshots ==
1. Manages job listings for multiple companies on a single page.
2. Detailed job information and application option for each listing.
3. Highly customizable application form for each position.
4. Configurable details for all job listings.

== Changelog ==
= 0.3.2 =
1. Fixed compatibility issues with some of the CSS styling used.
2. If a resume is selected but not uploaded, the user is notified on submission.
3. Fixed possible issue where application isn't able to pull the job id correctly.

= 0.3.1 =
1. Fixed styling for the confirmation box on application submission.
2. Fixed issue where applications wouldn't submit properly if the URL contained a space (%20).
3. Added a few more checks to the submission process.

= 0.3 =
1. Bootstrap is no longer required. All necessary styles are now built into the plugin.

= 0.2 =
1. Added placeholders to the settings menu to help show users what content to enter.
2. Let users choose the page title and URL of their applications page
3. Display notice after installation reminding admins to create pages with necessary short codes
4. Several bug fixes and interface enhancements

= 0.1 =
Initial Version.