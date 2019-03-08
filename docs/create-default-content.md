# Create some default content for testing and development

Another thing I do on most of my sites (but not all!) is create a few nodes, users, and sometimes other bits of content that I store in my codebase for testing purposes. Sometimes I even use this 'default content' on my production site—but often it's just stored in the codebase for local testing, so I don't have to create some specialized nodes to work on a certain feature or design element every time I reinstall Drupal.

Having default content for local development also helps prevent me from having to always be downloading my production database to do local development—this is an anti-pattern for many reasons:

  - Getting production databases means developers need to have access to retrieve the production database, or a copy of it from somewhere
  - Production databases can be massive and take a long time to import or dump
  - Production databases may contain PII or other sensitive data (e.g. user password hashes) and should always be scrubbed when used outside of production (developers often forget to do this!).

Besides all that, having some 'reference content' you can use in things like Behat tests, feature demos, or for other standard purposes is usually very helpful.

And if you use Drupal, there's a module built just for this purpose: [Default Content](https://www.drupal.org/project/default_content).

The module lets you create some nodes, users, taxonomy terms, blocks, etc., then export them to JSON files using a Drush command.

Currently the module only imports the exported entities when you enable the module, but in the future it should also allow importing individual bits of content (e.g. when you deploy an update), so it could conceivably be used to deploy _content_ into other environments just as easily as you can deploy _configuration_ into other environments!

Anyways, let's get started with Default Content:

  1. Require the Default Content module:

     ```
     composer require drupal/default_content
     ```

  1. Log into your local Drupal site and install the module on the Extend page (you may be asked to install a couple other modules it requires, too).
  1. At this point, you should create a user to whom the new content should belong:
    1. I generally recommend creating a user _separate from the 'user 1' account_ because content owned by user 1 can sometimes do strange things after reinstalling Drupal.
    1. Make sure this new user has privileges to create the types of content you need to have in your default set of content.
  1. Log in as the user you just created, then create some nodes, taxonomy terms, blocks, or whatever you need to create to populate the important bits of your site (e.g. for a news site, post an About page, a few Articles, and a block with company information in the footer).
  1. Create a custom module to store your site's default content:
    1. Create a module directory (e.g. `mysite_default_content`) inside `web/modules/custom`.
    1. Create a module `.info.yml` file (e.g. `mysite_default_content.info.yml`) inside the module directory (see [guide here](https://www.drupal.org/docs/8/creating-custom-modules/let-drupal-8-know-about-your-module-with-an-infoyml-file).
  1. Now on the command line, use the Default Content module's Drush command to export the entities you just created:

     ```
     # For a node (1 is the node ID of the node to export):
     drush dcer node 1 --folder=modules/custom/mysite_default_content/content/

     # For a user (2 is the user ID of the user to export):
     drush dcer user 2 --folder=modules/custom/mysite_default_content/content/

     # For a block (1 is the block ID of the custom block to export):
     drush dcer block_content 1 --folder=modules/custom/mysite_default_content/content/
     ```

  1. Next up, you want to _delete_ all the users, nodes, and blocks you just exported, then enable your new Default Content module. This should _re_-create all the users, nodes, and blocks you just exported.
  1. Once you're satisfied things worked correctly, go ahead and export the site configuration so the Default Content module and your site's default content module are enabled:

     ```
     docker-compose exec drupal bash -c 'drush config:export -y'
     ```

  1. Reinstall Drupal from scratch, and you _should_ see that not only is the Drupal site back the same way you had configured it, there are also entities that are recreated after an install which should be helpful for local development and theming.

> Eagle-eyed readers may notice a bit of a conundrum: what if you want this default content to be installed _locally_, but don't want it installed in _production_? Well, that's a little bit outside the scope of this document, but I'll give you a hint: check out the [Configuration Split](https://www.drupal.org/project/config_split) module.
