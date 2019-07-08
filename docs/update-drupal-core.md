# Update Drupal core

Unless you're perpetually building new Drupal sites and deleting your old one after a day, you will need to upgrade Drupal from time to time, whether for new features or security or bug fixes.

Since we built the Drupal site's codebase using Composer, we should also use Composer to upgrade Drupal and any other dependencies (e.g. modules, base themes, etc.).

  1. Follow the official [Composer template for Drupal projects 'Updating Drupal Core'](https://github.com/drupal-composer/drupal-project#updating-drupal-core) directions, run:
     ```
     composer update drupal/core webflo/drupal-core-require-dev "symfony/*" --with-dependencies
     ```
  1. After core and related dependencies have been updated, make sure you run database updates on your local site, e.g. by accessing `/update.php` or running:
     ```
     drush updb -y
     ```
  1. Export site configuration in case any configuration settings have been added or changed as a result of the update, with:
     ```
     drush config:export -y
     ```
  1. Check in any changes that have been exported to the project's git repository, then you'll need to:
     1. Build a new version of the Drupal site container image and push it to your container registry (see [Push the Drupal image to a Docker registry](push-to-registry.md)). I'd recommend tagging the image with a tag corresponding to the git hash, e.g. `geerlingguy/drupal-for-kubernetes:607f358`
     2. Update the `drupal_docker_image` setting in `config.yml` to point to the new image tag and deploy the change using Ansible as noted in [Deploy Drupal to Kubernetes](deploy-drupal-kubernetes.md).
