COMMUNITYCONNECT
PHP Applied Project – Part 1

Student: D-andrea Graham
Student ID: 2304203

ABOUT THE APPLICATION
CommunityConnect allows participants to register for community
activities, select scheduled dates and skills, review their details
and save registrations to CSV files.

ADMINISTRATOR LOGIN
Username: Community
Password: comproject_2026

Select Admin Login to access the registration report.
Select Admin Logout to remove administrator access.
End Session clears both participant and administrator session data.

These credentials are supplied for local assessment purposes.

REGISTRATION WORKFLOW
1. Enter participant details on the registration page.
2. Select an activity and one of its available dates.
3. Select at least one interest or skill.
4. Review the registration.
5. Select Confirm and Save.
6. Log in as administrator to view saved registrations.
7. Select End Session when finished.

MAIN FILES
index.php
Redirects visitors to the registration page.

common.php
Contains session setup, arrays, shared functions, navigation,
administrator access checks and CSV saving.

registration.php
Collects and validates participant information.

preferences.php
Collects activity, date and skills.
Remembers the activity using a cookie.
Allows selected session skills to be cleared.

confirmation.php
Displays the registration summary and processes saving.

records.php
Displays saved registrations after administrator authentication.

admin_config.php
Contains the administrator username and password hash.

admin_login.php
Processes administrator login.

admin_logout.php
Removes administrator access from the session.

finish.php
Clears and destroys the session.

style.css
Controls colours, layout, spacing and background presentation.

image.png
Background image used by the stylesheet.

.htaccess
Blocks direct browser access to CSV files and administrator
configuration, and disables directory listings.
Apache must permit these .htaccess settings for them to apply.

DATA FILES
registrations.csv
Contains earlier registration records, if included.

registrations_extended.csv
Stores new registrations, including contact preference,
volunteering experience and availability.
Created automatically when the first new registration is saved.

The administrator report reads both files.
Older records display Not specified for fields not collected earlier.

ACTIVITY DATES
Each activity has its own scheduled dates in the $activityDates
array in common.php.

The Preferences page displays dates for the selected activity.
Past dates are excluded, and PHP validates the activity/date pairing.

TESTING BEFORE SUBMISSION
Check required field, email, telephone and age validation.
Check that entered values remain available after validation errors.
Check that each activity displays its own scheduled dates.
Confirm that at least one skill must be selected.
Confirm that participant details reach the summary page.
Save a registration and verify it in the administrator report.
Clear session skills and verify other participant details remain.
End the session and verify protected information is unavailable.
Check that records.php requires administrator login.
Check that opening a CSV FILE directly returns Forbidden.

