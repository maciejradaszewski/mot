SQL scripts
===========

Please ensure one of following 4 types of tags is applied to the end of the filename of each SQL script in this folder:

- `MIGRATION` - Modifies data to unblock the data migration processes.
- `STORY` - Default one, but please ADD this.
- `NOT-FOR-PRODUCTION` - Adds or updates test data. Used only for the development environment.
- `PRODUCTION-APPLIED` - Records changes that were made directly to production environment that need reflected into development environments.

Any scripts added to the CURRENT folder that are required for non-dev environments must also be copied into the release folder and added in the correct order to the upgrade runner script.

Examples
--------

- **Migration**: `2015-06-03-VM-10224-transition_status-update-MIGRATION.sql`
- **Story**: `2015-06-10-VM-10350-Transition-User-Profile-Edit-STORY.sql`
- **Not For Production**: `2015-06-12-VM-10375-remove-tester-active-check-NOT-FOR-PRODUCTION.sql`
- **Production Applied**: `2015-07-16-VM-1337-delete-all-prod-data-PRODUCTION-APPLIED.sql`