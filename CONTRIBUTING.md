Contributing
============

Step 1: Setup your Environment
------------------------------

### Install the Software Stack

Before working on the project, setup the environment by following the [Machine Set Up][@machine-set-up] guide.

### Setup Git Flow

Install Git Flow and initialise it as instructed in the [Git Branching][@git-branching] wiki page.

Step 2: Work on your Feature
----------------------------

### Create a Topic Branch

Developers will create a new ``feature`` branch from the ``develop`` branch every time they start a new Story, RFC or
defect. The feature ID should match the ID of the work item in [JIRA][@jira] for tracking purposes.

    $ git feature flow start VM-8103-global-phpcs-configuration

### Work on your Feature

Work on the code as much as you want and commit as much as you want; but keep
in mind the following:

* Follow [PSR-2][@psr-2] and [ZendFramework2][@zf2-cs] coding standards (which is a superset of PSR-2). To enforce these
  standards apply the ``php-cs-fixer`` for all the changed PHP files:

        $ ./php-cs-fixer fix mot-common-web-module/src/DvsaCommon/Http/HttpStatus.php

* Do atomic and logically separate commits (use the power of ``git rebase`` to
  have a clean and logical history);

* Never fix coding standards in some existing code as it makes the code review
  more difficult;

Step 3: Submit your Feature
---------------------------

### Rebase your Feature

Before submitting your feature, update your branch:

    $ git checkout develop
    $ git fetch origin
    $ git merge origin/develop
    $ git checkout feature/VM-8103-global-phpcs-configuration
    $ git rebase develop


When doing the ``rebase`` command, you might have to fix merge conflicts.
``git status`` will show you the *unmerged* files. Resolve all the conflicts,
then continue the rebase:

    $ git add ... # add resolved files
    $ git rebase --continue

Check that all tests still pass and push your branch remotely:

    $ git push --force origin feature/VM-8103-global-phpcs-configuration

### Have the Feature Reviewed

Before we declare a feature to be complete which involves the feature being merged back into the ``develop`` branch it
MUST be reviewed. This review must involve a technical review with another developer before begin merged into
``develop``. Business review with the product owner will be done at the end of the sprint during the show and tell in the demo
environment.

### Declare the Feature Complete

When we declare the feature to be finished the `feature` branch will be deleted. All code from the feature branch will
already have been merged back into ``develop`` ready to start the next Story.

[@git-branching]:   https://wiki.i-env.net/display/CPMS/Git+Branching
[@homebrew-php]:    https://github.com/homebrew/homebrew-php
[@jira]:            https://jira.i-env.net/]
[@machine-set-up]:  https://wiki.i-env.net/display/MP/Machine+Set+Up
[@psr-2]:           http://www.php-fig.org/psr/psr-2/
[@zf2-cs]:          https://github.com/zendframework/zf2/wiki/Coding-Standards