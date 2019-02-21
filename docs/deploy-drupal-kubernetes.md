# Deploy Drupal to Kubernetes

> Like the previous documentation on pushing to a Docker registry, this guide assumes you're going to run the Drupal for Kubernetes example in the Pi Dramble Kubernetes cluster. If you are going to run it in a different cluster, look at the Drupal K8s manifests in the Pi Dramble codebase for an example Deployment and associated Kubernetes objects.

## Initial Deployment

### Update the cluster `config.yml`

Before the new Drupal image can be run in the Kubernetes cluster, you need to tell the Drupal deployment in the cluster to use it. Additionally, there are two settings that are configured by default for the `geerlingguy/drupal` Docker image, which need to be changed so they work correctly with our project's Composer-based structure.

In your `config.yml` for the Raspberry Pi Dramble, make sure you override the following three variables:

    # Override these for Drupal for Kubernetes container usage.
    drupal_docker_image: registry.pidramble.test/geerlingguy/drupal-for-kubernetes:latest
    drupal_files_dir: /var/www/html/web/sites/default/files
    drupal_download_if_not_present: 'false'

> If you're deploying to ARM and tagged the Docker image as such, make sure to use `arm` instead of `latest`!

Then, run the Raspberry Pi Dramble's `main.yml` Ansible playbook to make sure the updated Drupal manifest is deployed

    # Run from within the raspberry-pi-dramble repo root.
    ansible-playbook -i inventory main.yml

### Check on deployment status

Assuming you have already [installed `kubectl`](https://kubernetes.io/docs/tasks/tools/install-kubectl/), run the following command to get the status of the rollout:

    kubectl rollout status -n drupal deployment drupal

Once it's complete, Kubernetes should report:

    deployment "drupal" successfully rolled out

If there are any issues with the deployment, you can usually see what went wrong with the command:

    kubectl describe -n drupal deployment drupal

Look specifically under the `Events` listing for clues.

### Install Drupal

Once the new container image has been deployed, you can either install Drupal using the GUI (visit `http://cluster.pidramble.test/`), or using Drush via `kubectl`:

    # Use `-vagrant` instead of `-pi` if running on the local cluster.
    export KUBECONFIG=~/.kube/config-dramble-pi
    
    DRUPAL_POD=$(kubectl get pods -n drupal -o name --no-headers=true -o custom-columns=":metadata.name" | grep drupal | head -n 1)
    kubectl exec -n drupal $DRUPAL_POD -- bash -c 'vendor/bin/drush site:install minimal --db-url="mysql://drupal:$DRUPAL_DATABASE_PASSWORD@$DRUPAL_DATABASE_HOST/drupal" --site-name="Drupal Example Site for Kubernetes" --existing-config -y'

Drupal should then be installed, and you have your new site running in Kubernetes at http://cluster.pidramble.test/, yay!

## Ongoing Deployments

If you need to _update_ Drupal and/or the codebase, perform all the earlier steps under Initial Deployment (except what's under "Install Drupal"!), and then perform common post-code-update tasks, like:

    # Use `-vagrant` instead of `-pi` if running on the local cluster.
    export KUBECONFIG=~/.kube/config-dramble-pi
    
    DRUPAL_POD=$(kubectl get pods -n drupal -o name --no-headers=true -o custom-columns=":metadata.name" | grep drupal | head -n 1)
    kubectl exec -n drupal $DRUPAL_POD -- bash -c 'vendor/bin/drush updatedb -y'
    kubectl exec -n drupal $DRUPAL_POD -- bash -c 'vendor/bin/drush config:import'
    kubectl exec -n drupal $DRUPAL_POD -- bash -c 'vendor/bin/drush cache:rebuild'

Note that Kubernetes does not pull new container images by default unless the container's `tag` has changed. So for ongoing deployments/updates, you should probably do something like tag the image with a release version (e.g. `1.2.3`), or a Git commit hash corresponding to the site codebase's commit at the time of the deployment (e.g. `bbbb8a1`).
