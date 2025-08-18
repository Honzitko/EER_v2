# Easy Event Registration

Changelog
-------
2.0 - 01.01.2024

- Update: Bumped version number

1.3.3 - 11.11.2020

- New: Confirm button styles
- New: Disable notes for events

1.3.2 - 05.05.2020

- New: Custom styles
- Update: ES translations
- Fix: Payment note display

1.3.0 - 17.01.2020

- New: Support for checkbox discount
- Update: PDF ticket generation moved to separated module

1.2.9 - 14.10.2019

- New: Added support for Form Builder
- New: Added difference price to ticket
- New: Added ticket code to confirmation email
- Fix: Bug with not sending more than one confirmation email
- Fix: Icons at frontend

1.2.8 - 10.10.2019

- New: Option to change ticket in sold tickets
- New: Option to update partner in sold tickets
- New: Tickets title in payments
- Update: Font Awesome change to Wordpress Dashicons
- Fix: Event duplication

1.2.7 - 23.07.2019

- New: Option to send emails via wp_mail function
- New: New level tag in confirmation email
- New: Copy tag on click
- Fix: Zloty currency symbol

1.2.6 - 30.06.2019

- Fix: Db update

1.2.5 - 30.06.2019

- Fix: Fixed bug with changing html class

1.2.4 - 30.06.2019

- New: Sold tickets confirmation times
- New: ES translations
- Fix: Solo ticket levels bug
- Fix: Required control

1.2.3 - 01.06.2019

- New: Email tags
- New: Required class in sale form
- New: Support for discount info
- New: Events duplication with tickets
- New: Scroll to thank you text in sale form
- New: Scroll error in sale form

1.2.2 - 08.05.2019

- New: Custom checkbox added to email template
- New: Food title and table title
- New: Payment confirmation email tags
- Update: Show 0 instead of empty string in payments
- Fix: Payment JS update

1.2.1 - 22.04.2019

- New: Levels and max per order in Add over limit
- New: Total price tag for ticket confirmation
- Update: Option to change order data
- Update: Related tickets moved to separated module
- Fix: Admin texts
- Fix: Design bugs
- Fix: Icons in admin
- Fix: Default pairing bug
- Fix: Floating price

1.2.0 - 13.03.2019

- Update: Option to change order data
- Update: Support for levels and max per order to Add over limit
- Fix: Admin texts
- Fix: Design bug
- Fix: Admin icons
- Fix: Default pairing mode

1.2.0 - 13.03.2019

- New: Option to change sold ticket level
- New: TinyMCE button
- New: Option to disable partner check
- New: Note and food required option
- Update: Textarea resize enabled
- Update: Tickets with levels availability
- Fix: Level selection and saving for solo tickets
- Fix: Add over limit validation check
- Fix: Solo tickets with level recount
- Fix: Admin texts
- Fix: Selected tickets design bug

1.1.9 - 24.02.2019

- Fix: Bug in emails with tickets list

1.1.8 - 24.02.2019

- New: Custom checkbox and textarea
- Update: Text cleanup
- Fix: Sending unset data

1.1.6 - 26.01.2019

- New: Tickets pairing mode
- New: Bcc email
- New: Form confirm button text and note description settings
- Update: Payment table
- Fix: Few small bug fixes and improvements

1.1.5 - 21.12.2018

- New: cs-CZ translation
- New: New table buttons like Copy emails, Excel export
- Update: Datatables libs
- Update: Text Send order changed to Submit

1.1.4 - 12.11.2018

- New: Added ticket codes to payments
- New: Remove order forever
- New: Bcc email support
- New: Support for new PDF generation module
- Update: Notes are working much better
- Update: New edit pages for events and tickets
- Fix: Lots of small fixed and updates

1.1.3 - 1.11.2018

- Fix: Fixed ticket summary generation

1.1.2 - 31.10.2018

- New: Admin can remove ticket
- Update: Icons on frontend

1.1.1 - 30.10.2018

- New: Tests
- New: Registration spinner
- New: Selected event saved for admin
- New: Over limit page
- New: Disable tickets
- Update: Tickets order
- Fix: Backslash bug fix
- Fix: Admin styles
- Fix: Solo tickets show / hide
- Fix: Lots of small bug fixes

## Running tests

Run `composer install` to install the development dependencies. After that execute
`vendor/bin/phpunit` from the repository root. The configuration file `phpunit.xml`
is provided in the project.

## Debugging

Enable detailed plugin logging by defining the `EER_DEBUG` constant as `true`. Log
messages are written to `eer-debug.log` in the plugin root directory.

You can verify the logger independently by running the demo script:

```
php tests/debug_demo.php
```

The script writes a sample entry to `tests/debug.log` so you can confirm that
debugging is working.
