# Deploy Drupal to Kubernetes

> Like the previous documentation on pushing to a Docker registry, this guide assumes you're going to run the Drupal for Kubernetes example in the Pi Dramble Kubernetes cluster. If you are going to run it in a different cluster, look at the Drupal K8s manifests in the Pi Dramble codebase for an example Deployment and associated Kubernetes objects.

TODO: Instructions for building ARM version of this image.

TODO: Instructions for overriding the following in `config.yml`:

    # Override these for Drupal for Kubernetes container usage.
    drupal_docker_image: registry.pidramble.test/geerlingguy/drupal-for-kubernetes:latest
    drupal_files_dir: /var/www/html/web/sites/default/files
    drupal_download_if_not_present: 'false'

Then run the following (if you have `kubectl` installed on your local machine):

    export KUBECONFIG=~/.kube/config-dramble-pi (or `-vagrant` for local)
    # Get the 'drupal-' pod ID with `kubectl get pods -n drupal`
    kubectl exec -n drupal drupal-[pod ID] -- bash -c 'vendor/bin/drush site:install minimal --db-url="mysql://drupal:$DRUPAL_DATABASE_PASSWORD@$DRUPAL_DATABASE_HOST/drupal" --site-name="Drupal Example Site for Kubernetes" --existing-config -y'

Drupal should then be installed, and you have your new site running in Kubernetes, yay!
