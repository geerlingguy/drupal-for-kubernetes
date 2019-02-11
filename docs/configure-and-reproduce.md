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

TODO:

  - Composer require toolbar module.
  - Drush cex command to export config.
  - Modify install command so it uses existing config (link to issue about using minimal base profile).
  - Commit to git.
