# Start a new Drupal Project

Part of the purpose of this project is to outline the exact steps required to build _your own_ Drupal site which is deployable inside Kubernetes, so the following section outlines every step used to initialize the codebase and prep it for a container environment:

  1. Build the basic Drupal codebase in a new directory using Composer with the [`drupal/recommended-project`](https://www.drupal.org/docs/develop/using-composer/using-composer-to-install-drupal-and-manage-dependencies#s-using-drupalrecommended-project) template:

     ```
     composer -n --prefer-dist create-project drupal/recommended-project:^8 my-drupal-site
     ```

  1. Install Drush, since it will be helpful for managing your Drupal project:

     ```
     cd my-drupal-site
     composer require drush/drush
     ```

  1. Create a [`.gitignore`](../.gitignore) file to exclude any sensitive files or files that are managed by Composer from being added to your project's Git repository.

  1. Initialize a git repository to track all future changes in the codebase:

     ```
     git init
     git add -A
     git commit -m "Initial commit of my new Drupal site."
     ```

  1. Create a [`Dockerfile`](../Dockerfile) and [`docker-compose.yml`](../docker-compose.yml) to build a Docker image to run the site both locally and in production—using the exact same Docker container!
    1. If using the Dockerfile from this project, comment out the three `COPY` lines under "Copy other required configuration into the container." section. Also comment out the `COPY` line that copies the `scripts/` directory.
  1. Build the docker image from the Dockerfile:

     ```
     docker build -t geerlingguy/drupal-for-kubernetes .
     ```

  1. Modify the `docker-compose.yml` by commenting out the `volumes` section under the `drupal` service (since for now, we're testing the production container build, not the local development environment).
  1. Verify you can run the site in the container image locally:

     ```
     docker-compose up -d
     ```

     (Wait for the environment to come up—you can monitor the logs with `docker-compose logs -f`).

  1. Once the container is running, you need to install Drupal. You can either access http://localhost/ and install using the UI, or install via Drush:

     ```
     docker-compose exec drupal bash -c 'drush site:install minimal --db-url="mysql://drupal:$DRUPAL_DATABASE_PASSWORD@$DRUPAL_DATABASE_HOST/drupal" --site-name="My Drupal Site" -y'
     ```

  1. Visit http://localhost/ in your browser, and login as `admin` using the password Drush printed in the 'Installation complete' message.
  1. Once you're satisfied things are working, commit the Docker configurations:

     ```
     git add -A
     git commit -m "Add Docker configuration for my Drupal site."
     ```
