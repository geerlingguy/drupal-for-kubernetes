# Configure the site and make it reproducible

Once you have a basic Drupal site running in a Docker container, you should start building it in a way that is maintainable and easily deployable.

For Drupal 8+, that means using _configuration_ to synchronize changes between a local development environment and other environments.

Before we can start doing this work, we need to make sure we have our local environment set up to allow us to work in the Docker environment, but also have our codebase changes reflected locally (outside of the running Docker container), so we can commit any changes to our Git codebase.

## Local Development with `docker-compose`

The first step is to modify the `docker-compose.yml` file so it has a `volume` which shares the local codebase into the Drupal container:

  1. If you already have the Docker environment running from the previous guide, make sure it is completely removed using `docker-compose down -v`.
  1. Since we'll be using the local codebase to drive the Drupal site (instead of the Drupal codebase only available inside the Drupal container), we need to install Composer dependencies locally:

     ```
     composer install
     ```

     This will also install any 'dev dependencies' (things like Behat, Coder, etc. which can be useful in development but should not be present in a production environment).
  1. Make sure the volume mount for the `drupal` service is configured as it is in this project's codebase (see [docker-compose.yml](../docker-compose.yml)), so it will share the local codebase into the Drupal container.
  1. Start the local development environment:

     ```
     docker-compose up -d
     ```

  1. Install Drupal using the same Drush command used in the previous guide, [Starting a new Drupal Project](starting-new-project.md).

## Adding a module and exporting Drupal's configuration

To make this website easy to manage (both when installing locally for development, and deploying to production), we take advantage of Drupal's built-in configuration management system.

The first, simple thing to do is to add a module, export the full site configuration, and then modify the installation command so when you install Drupal fresh (or reinstall it, or install it in a new environment), it will have the exact same configuration every time—the knowledge that you can reset your Drupal installation to a known good state gives you freedom to try new things and experiment without serious repercussions.

So let's do something I do for all my Drupal sites: install the [Admin Toolbar](https://www.drupal.org/project/admin_toolbar) module, and make sure it is included in the site configuration:

  1. Use Composer to download the module and place it in the project `composer.json`:

     ```
     composer require drupal/admin_toolbar
     ```

  1. Log into the Drupal site locally (it should be running and using your local development codebase after you brought up the environment with Docker Compose).
  1. Go to the Extend page (`/admin/modules`) and install the Admin Toolbar and Admin Toolbar Extra Tools modules (and also any modules they require).
  1. Now run the following command to tell Drupal to dump its current configuration into your 'config sync' directory (which happens to be configured in `[project root]/config/sync` by default):

     ```
     docker-compose exec drupal bash -c 'vendor/bin/drush config:export -y'
     ```

  1. If you look inside the `config/sync` folder you should now see lots of YAML files (ending with `.yml`), containing all the details of your Drupal site's configuration.
  1. Now is where this gets interesting; let's say you then make some other changes to your site configuration, but you want to ditch them because you weren't happy with them. For example, go to the Basic site settings page (`/admin/config/system/site-information`) and change the Slogan to "The best website ever" and save it.
  1. You can now _reinstall_ the site from scratch, but using the configuration you previously exported (so it will have the Admin Toolbar module and everything else, but not this silly slogan you don't like), by adding the flag `--existing-config` to the install command:

     ```
     docker-compose exec drupal bash -c 'vendor/bin/drush site:install minimal --db-url="mysql://drupal:$DRUPAL_DB_PASSWORD@$DRUPAL_DB_HOST/drupal" --site-name="My Drupal Site" --existing-config -y'
     ```

  1. A minute or so later, if you log into the site using the `admin` user and the new password Drush prints to the command line, you'll see that the Slogan was reverted to being blank, as it was before you changed it.
  1. At this point, if the site's configuration is in a state that you should like, you should commit the code to Git so the configuration is tracked in your code repository—this will allow you to compare different configuration changes and choose which ones to deploy or not deploy in the future:

     ```
     git add -A
     git commit -m "Add the initial configuration export."
     ```
